<?php

namespace App;

/**
 * A2IDPass — Argon2id Password Hashing & Encryption Utility
 * 
 * Written by Darren Nay - 4/5/2026
 * This PHP object was documented using Claude AI - DN
 *
 * Implements a two-layer password security strategy:
 *
 *   1. HASHING  — The raw cleartext password is run through Argon2id
 *                 (via PHP's password_hash()), producing a one-way hash
 *                 that is intentionally slow and memory-hard to brute-force.
 *
 *   2. ENCRYPTION — The resulting hash is then symmetrically encrypted with
 *                   AES-256-CBC using a secret "pepper" key sourced from
 *                   application config. This means that even a full database
 *                   dump is useless to an attacker who does not also have the
 *                   pepper stored separately (e.g. in an environment variable
 *                   or secrets manager).
 *
 * Terminology used throughout this class:
 *   - cleartext  : The raw password as typed by the user.
 *   - hash       : The Argon2id digest of the cleartext password.
 *   - cyphertext : The AES-256-CBC encrypted form of the hash, stored in the DB.
 *   - pepper     : A secret application-level key mixed in at encryption time.
 *                  Unlike a salt (which is random per-password and stored in the
 *                  hash itself), a pepper is a single shared secret kept outside
 *                  the database.
 *
 * Typical write/read cycle:
 *   Store  : cleartext → hash() → encrypt() → stored cyphertext
 *   Verify : stored cyphertext → decrypt() → hash, then password_verify(cleartext, hash)
 *
 * @see https://www.php.net/manual/en/function.password-hash.php
 * @see https://www.php.net/manual/en/function.openssl-encrypt.php
 * @see https://en.wikipedia.org/wiki/Argon2
 */
class A2IDPass
{

    /**
     * Produces an Argon2id hash of a cleartext password.
     *
     * Argon2id is the recommended variant of the Argon2 family (winner of the
     * 2015 Password Hashing Competition). It is resistant to both side-channel
     * attacks (Argon2i) and GPU-based brute-force attacks (Argon2d).
     *
     * The parameters below follow the OWASP / RFC 9106 recommended minimums:
     *   - memory_cost : 64 MB of RAM required per hash attempt, making
     *                   large-scale parallel cracking expensive.
     *   - time_cost   : Number of iterations (passes) over memory. Higher
     *                   values increase computation time linearly.
     *   - threads     : Degree of parallelism. Set to 1 to avoid giving
     *                   attackers a benefit from multi-threaded hardware.
     *
     * PHP's password_hash() automatically generates and embeds a unique
     * cryptographically random salt into the returned hash string, so no
     * manual salt management is needed.
     *
     * @param  string $cleartext  The raw plaintext password to hash.
     * @return string             The Argon2id hash string (includes algo, params,
     *                            salt, and digest — safe to store as-is).
     *
     * @see https://en.wikipedia.org/wiki/Argon2#Recommended_minimum_parameters
     */
    public static function hash($cleartext) {
        $pass_algo = PASSWORD_ARGON2ID;
        $pass_options = array(        // https://en.wikipedia.org/wiki/Argon2#Recommended_minimum_parameters
            'memory_cost' => 65536,   // default PASSWORD_ARGON2_DEFAULT_MEMORY_COST == 65536 KiB (64MB)
            'time_cost' => 4,         // default PASSWORD_ARGON2_DEFAULT_TIME_COST == 4
            'threads' => 1            // default PASSWORD_ARGON2_DEFAULT_THREADS == 1
        );
        return password_hash($cleartext, $pass_algo, $pass_options);
    }

    /**
     * Symmetrically encrypts a string (typically an Argon2id hash) using AES-256-CBC.
     *
     * This layer adds "peppering": a secret key sourced from application config
     * (auth.pepper) is used as the AES encryption key. Because the pepper lives
     * outside the database, an attacker who obtains the database cannot decrypt
     * the stored hashes without also compromising the application config / secrets.
     *
     * A fresh, cryptographically random IV (Initialisation Vector) is generated
     * for every call, ensuring that encrypting the same input twice produces
     * different cyphertexts (semantic security). The IV is not secret and is
     * stored alongside the ciphertext, separated by a pipe character:
     *
     *   <base64(encrypted)>|<base64(iv)>
     *
     * If no pepper is configured, an empty string is used as the key — this still
     * provides the storage format but offers no additional cryptographic protection.
     *
     * @param  string $cleartext  The plaintext string to encrypt (usually an Argon2id hash).
     * @return string             A pipe-delimited string: base64(ciphertext)|base64(iv).
     *
     * @see https://www.php.net/manual/en/function.openssl-encrypt.php
     */
    public static function encrypt($cleartext) {
        // Use a pepper if set
        $key = (!empty($GLOBALS['config']) && $GLOBALS['config']->has('auth.pepper')) ? 
            $GLOBALS['config']->get('auth.pepper') :'';

        // Encrypt the cleartext password
        $length = openssl_cipher_iv_length('AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($length);
        $encrypted = openssl_encrypt($cleartext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        $cyphertext = base64_encode($encrypted).'|'.base64_encode($iv);

        return $cyphertext;
    }

    /**
     * Decrypts a cyphertext string previously produced by encrypt().
     *
     * Reverses the AES-256-CBC encryption by:
     *   1. Splitting the stored value on '|' to recover the ciphertext and IV.
     *   2. Base64-decoding the IV.
     *   3. Decrypting using the same pepper key sourced from application config.
     *
     * The returned value will be the original Argon2id hash string, which can
     * then be passed to password_verify() together with the user-supplied
     * cleartext password.
     *
     * @param  string $input  A pipe-delimited string in the format produced by encrypt():
     *                        base64(ciphertext)|base64(iv).
     * @return string|false   The decrypted plaintext (an Argon2id hash string) on success,
     *                        or false if decryption fails.
     *
     * @see https://www.php.net/manual/en/function.openssl-decrypt.php
     */
    public static function decrypt($input) {
        // Use a pepper if set
        $key = (!empty($GLOBALS['config']) && $GLOBALS['config']->has('auth.pepper')) ? 
            $GLOBALS['config']->get('auth.pepper') :'';
        
        // Decrypt the password to cleartext
        list($cyphertext, $iv) = explode('|', $input);
        $iv = base64_decode($iv);
        $cleartext = openssl_decrypt($cyphertext, 'AES-256-CBC', $key, 0, $iv);

        return $cleartext;
    }

    /**
     * Prepares a password for secure storage in the users table.
     *
     * Combines both security layers in the correct order:
     *   1. hash()    — Converts the cleartext password into an Argon2id hash.
     *   2. encrypt() — Wraps that hash in AES-256-CBC encryption using the pepper.
     *
     * The returned cyphertext is what should be persisted to the database.
     * The original cleartext password is never stored.
     *
     * @param  string $cleartext  The raw password supplied by the user.
     * @return string             The encrypted hash string, ready for DB insertion.
     */
    public static function write($cleartext) {
        return self::encrypt(self::hash($cleartext));
    }

    /**
     * Resolves a stored cyphertext back into its underlying Argon2id hash.
     *
     * This is a thin wrapper around decrypt() provided for semantic clarity:
     * "reading" a password record means decrypting it to retrieve the hash,
     * not recovering the original cleartext (which is impossible by design).
     *
     * The returned hash is intended for use with password_verify() or for
     * re-hashing purposes (e.g. upgrading hash parameters).
     *
     * @param  string $cyphertext  The encrypted value as stored in the users table.
     * @return string|false        The Argon2id hash string, or false on failure.
     */
    public static function read($cyphertext) {
        return self::decrypt($cyphertext);
    }

    /**
     * Verifies a user-supplied password against a stored cyphertext value.
     *
     * This is the primary method used during authentication. It:
     *   1. Calls read() to decrypt the stored cyphertext into an Argon2id hash.
     *   2. Calls password_verify() to securely compare the cleartext password
     *      against that hash using a timing-safe comparison (no timing attacks).
     *
     * Returns true only if both decryption succeeds and the password matches.
     *
     * Usage example:
     * <code>
     *   $stored = A2IDPass::write('hunter2');        // at registration
     *   $ok     = A2IDPass::verify('hunter2', $stored); // at login → true
     * </code>
     *
     * @param  string $cleartext   The raw password as entered by the user at login.
     * @param  string $cyphertext  The encrypted hash string retrieved from the users table.
     * @return bool                True if the password is correct, false otherwise.
     *
     * @see https://www.php.net/manual/en/function.password-verify.php
     */
    public static function verify($cleartext, $cyphertext) {
        return password_verify($cleartext, self::read($cyphertext));
    }
}

?>