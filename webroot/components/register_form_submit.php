<?php
/**
 * register_form_submit.php
 *
 * Handles POST submissions from the Account Registration form.
 * Validates all inputs using Illuminate Validator, then inserts a new
 * user record into the database (with Argon2id-hashed password).
 *
 * On success: returns JS that updates the submit button and redirects to /login.
 * On failure: returns an HTML error panel describing what went wrong.
 */

// ── Only allow POST ───────────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo '405 Method Not Allowed';
    exit;
}

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\A2IDPass;

// ── Bootstrap Illuminate Validator ────────────────────────────────────────────
//
// ValidatorFactory requires a Translator. ArrayLoader satisfies that dependency
// without needing translation files on disk.

$loader     = new ArrayLoader();
$translator = new Translator($loader, 'en');
$factory    = new ValidatorFactory($translator);

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Returns a POST value as a trimmed string, or '' if not present.
 * Pass $trim = false for fields where whitespace is significant (e.g. passwords).
 */
function post(string $key, bool $trim = true): string {
    $value = (string) ($_POST[$key] ?? '');
    return $trim ? trim($value) : $value;
}

// ── Collect inputs ────────────────────────────────────────────────────────────

$input = [
    'fullName'        => post('fullName'),
    'phone'           => post('phone'),
    'email'           => post('email'),
    'password'        => post('password', false),        // never trim passwords
    'confirmPassword' => post('confirmPassword', false),
    'agreeTerms'      => post('agreeTerms'),
];

// ── Validation rules ──────────────────────────────────────────────────────────

$rules = [
    // Unicode letters, spaces, hyphens, apostrophes; 2–80 chars.
    'fullName' => [
        'required',
        'min:2',
        'max:80',
        'regex:/^[\p{L}\s\'\-]+$/u',
    ],

    // Accepts common North American and international formats:
    //   (555) 000-0000 | 555-000-0000 | +1 555 000 0000 | 5550000000
    'phone' => [
        'required',
        'regex:/^(\+?1[\s.\-]?)?(\(?\d{3}\)?[\s.\-]?)(\d{3}[\s.\-]?\d{4})$/',
    ],

    // RFC 5321 compliant; max 254 characters.
    'email' => [
        'required',
        'email',
        'max:254',
    ],

    // Min 8 chars with at least one uppercase, lowercase, digit, and special character.
    // Implemented as a closure to avoid Password::min(), which requires the full
    // Laravel container and throws a RuntimeException in standalone use.
    'password' => [
        'required',
        'min:8',
        static function (string $attribute, mixed $value, \Closure $fail): void {
            if (!preg_match('/[A-Z]/', $value)) {
                $fail('Password must contain at least one uppercase letter.');
            }
            if (!preg_match('/[a-z]/', $value)) {
                $fail('Password must contain at least one lowercase letter.');
            }
            if (!preg_match('/[0-9]/', $value)) {
                $fail('Password must contain at least one digit.');
            }
            if (!preg_match('/[\W_]/', $value)) {
                $fail('Password must contain at least one special character.');
            }
        },
    ],

    // Must be present and identical to the password field.
    'confirmPassword' => [
        'required',
        'same:password',
    ],

    // Checkbox must be ticked (truthy value).
    'agreeTerms' => [
        'accepted',
    ],
];

// ── Custom error messages ─────────────────────────────────────────────────────

$messages = [
    'fullName.required'        => 'Full name is required.',
    'fullName.min'             => 'Full name must be between 2 and 80 characters.',
    'fullName.max'             => 'Full name must be between 2 and 80 characters.',
    'fullName.regex'           => 'Full name may only contain letters, spaces, hyphens, and apostrophes.',

    'phone.required'           => 'Phone number is required.',
    'phone.regex'              => 'Please enter a valid phone number.',

    'email.required'           => 'Email address is required.',
    'email.email'              => 'Please enter a valid email address.',
    'email.max'                => 'Email address is too long (max 254 characters).',

    'password.required'        => 'Password is required.',
    'password.min'             => 'Password must be at least 8 characters.',

    'confirmPassword.required' => 'Please confirm your password.',
    'confirmPassword.same'     => 'Passwords do not match.',

    'agreeTerms.accepted'      => 'You must agree to the Terms of Service and Privacy Policy.',
];

// ── Run validation ────────────────────────────────────────────────────────────

$validator = $factory->make($input, $rules, $messages);

if ($validator->fails()) {
    // Pluck the first error message for each field.
    $errors = array_map(
        static fn(array $fieldErrors): string => $fieldErrors[0],
        $validator->errors()->toArray()
    );

    ?>
    <div class="reg-error-panel" role="alert">
        <div class="reg-error-header">
            <span class="reg-error-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <span class="reg-error-title">Please correct the following errors</span>
        </div>
        <ul class="reg-error-list">
            <?php foreach ($errors as $error): ?>
                <li><i class="bi bi-dot"></i><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php

    exit;
}

// ── All fields valid — create the account ─────────────────────────────────────

$dsn = 'mysql:host=' . $config->get('db.host')
     . ';dbname='    . $config->get('db.db')
     . ';charset=utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,  // use real prepared statements
];

try {
    $pdo = new PDO($dsn, $config->get('db.user'), $config->get('db.pass'), $options);

    // Reject duplicate email addresses before attempting an insert.
    $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $checkStmt->execute([$input['email']]);

    if ($checkStmt->fetch()) {

        ?>
        <div class="reg-error-panel" role="alert">
            <div class="reg-error-header">
                <span class="reg-error-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                <span class="reg-error-title">Registration failed</span>
            </div>
            <ul class="reg-error-list">
                <li><i class="bi bi-dot"></i>An account with that email address already exists.</li>
            </ul>
        </div>
        <?php

        exit;
    }

    // Insert the new user; A2IDPass::write() hashes the password with Argon2id.
    $insertStmt = $pdo->prepare('
        INSERT INTO users (email, password, phone, full_name)
        VALUES (:email, :password, :phone, :full_name)
    ');

    $insertStmt->execute([
        ':email'     => $input['email'],
        ':password'  => A2IDPass::write($input['password']),
        ':phone'     => $input['phone'],
        ':full_name' => $input['fullName'],
    ]);

    $newUserId = (int) $pdo->lastInsertId();

    // Update the submit button and redirect to login after a short delay.
    ?>
    <script>
        const btn = document.querySelector('.btn-signup');
        btn.innerHTML  = '<i class="bi bi-check-circle-fill me-2"></i>Account Created!';
        btn.style.background = '#27ae60';
        btn.disabled = true;

        setTimeout(() => window.location.replace('/login'), 3000);
    </script>
    <?php

} catch (PDOException $e) {

    // Log the full error server-side; never expose raw DB errors to the browser.
    error_log('Registration form submission error: ' . $e->getMessage());

    ?>
    <div class="reg-error-panel" role="alert">
        <div class="reg-error-header">
            <span class="reg-error-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <span class="reg-error-title">Something went wrong</span>
        </div>
        <ul class="reg-error-list">
            <li><i class="bi bi-dot"></i>We couldn't complete your registration. Please try again in a moment.</li>
        </ul>
    </div>
    <?php
}