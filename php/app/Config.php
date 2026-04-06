<?php

namespace App;

/**
 * Config - A lightweight wrapper for reading JSON-based configuration files.
 * 
 * Written by Darren Nay - 4/5/2026
 * This PHP object was documented using Claude AI - DN
 *
 * Loads a JSON file from disk on instantiation and exposes its values
 * through a simple get/has API. Nested keys can be accessed using dot
 * notation, so a config structure like:
 *
 *   { "database": { "host": "localhost" } }
 *
 * ...can be read as:
 *
 *   $config->get('database.host'); // "localhost"
 *
 * Values can also be accessed as magic properties for top-level keys:
 *
 *   $config->database; // returns the full "database" array
 *
 * Usage:
 *   $config = new Config('/path/to/config.json');
 *   $host   = $config->get('database.host', '127.0.0.1');
 */
class Config
{
    /**
     * The decoded configuration data, keyed by config section/name.
     *
     * Populated from the JSON file during construction and never modified
     * afterwards, making this class effectively immutable after instantiation.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    // -------------------------------------------------------------------------
    // Construction
    // -------------------------------------------------------------------------

    /**
     * Load and parse a JSON configuration file.
     *
     * Reads the file at $filePath, JSON-decodes it into an associative array,
     * and stores the result internally. All three failure modes — file missing,
     * unreadable, or malformed JSON — throw a RuntimeException so callers can
     * handle them in one place.
     *
     * @param string $filePath Absolute or relative path to the JSON config file.
     *                         Defaults to '../config.json' (one level above the
     *                         document root in a typical project layout).
     *
     * @throws \RuntimeException If the file does not exist at $filePath.
     * @throws \RuntimeException If the file exists but cannot be read (e.g. due
     *                           to filesystem permission restrictions).
     * @throws \RuntimeException If the file contents are not valid JSON.
     */
    public function __construct(string $filePath = '../config.json')
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Config file not found: {$filePath}");
        }

        $json = file_get_contents($filePath);

        if ($json === false) {
            throw new \RuntimeException("Failed to read config file: {$filePath}");
        }

        // Decode as an associative array rather than stdClass objects so that
        // every level of nesting is uniformly array-accessible.
        $decoded = json_decode($json, associative: true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                "Failed to parse config file: " . json_last_error_msg()
            );
        }

        $this->config = $decoded;
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Retrieve a configuration value by key.
     *
     * Accepts simple top-level keys ("debug") as well as dot-notated paths
     * for nested values ("database.host", "mail.smtp.port"). The key is split
     * on "." and each segment is traversed in turn; if any segment is absent
     * or the current value is not an array, $default is returned immediately.
     *
     * Examples:
     *   $config->get('app_name');              // "My App"
     *   $config->get('database.host');         // "localhost"
     *   $config->get('database.port', 3306);   // 3306 (from file, or default)
     *   $config->get('nonexistent', 'fallback'); // "fallback"
     *
     * @param string $key     Dot-notated path to the desired value.
     * @param mixed  $default Value to return when the key does not exist.
     *                        Defaults to null.
     *
     * @return mixed The config value at $key, or $default if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $keys  = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            // Stop traversal as soon as we reach a dead end.
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Check whether a configuration key exists.
     *
     * Supports the same dot-notated paths as get(). Returns true even when
     * the stored value is null, false, or an empty string — existence is
     * determined solely by the presence of the key, not by its truthiness.
     *
     * Examples:
     *   $config->has('database.host');   // true
     *   $config->has('nonexistent.key'); // false
     *
     * @param string $key Dot-notated path to check.
     *
     * @return bool True if the key is present in the config, false otherwise.
     */
    public function has(string $key): bool
    {
        // A unique sentinel object is used as the default so that a stored
        // value of null is not mistaken for "key not found".
        return $this->get($key, $__sentinel = new \stdClass()) !== $__sentinel;
    }

    /**
     * Return the entire configuration as a plain associative array.
     *
     * Useful for debugging, serialisation, or passing the full config to a
     * component that expects a raw array rather than this wrapper object.
     *
     * @return array<string, mixed> The raw, decoded configuration data.
     */
    public function all(): array
    {
        return $this->config;
    }

    // -------------------------------------------------------------------------
    // Magic accessors
    // -------------------------------------------------------------------------

    /**
     * Allow top-level config keys to be read as object properties.
     *
     * Enables the shorthand syntax $config->key in place of $config->get('key')
     * for top-level keys only. Dot notation is not supported here; use get()
     * for nested access.
     *
     * Example:
     *   $config->database; // equivalent to $config->get('database')
     *
     * @param string $key The top-level config key to look up.
     *
     * @return mixed The config value, or null if the key does not exist.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Allow top-level config keys to be checked with isset().
     *
     * Enables the shorthand syntax isset($config->key) in place of
     * $config->has('key') for top-level keys only.
     *
     * Example:
     *   isset($config->database); // equivalent to $config->has('database')
     *
     * @param string $key The top-level config key to check.
     *
     * @return bool True if the key exists, false otherwise.
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }
}