# iWorQ Demo — User Registration Form

This project was built as a technical demo for a job interview with iWorQ. The original prompt was straightforward: create an HTML page that submits a form via Ajax to a PHP file that saves the data to a ficticious database. Rather than stopping there, I used it as an opportunity to build something closer to what a real-world implementation would actually look like.

## What It Does

At its core, this is a user registration form. It collects user input, validates it on both the client and server, hashes the password securely, and persists the data. The UI is clean and responsive, and the backend is structured more like a small application than a single script.

## Project Structure

The project is organized into three main areas: `webroot` (the publicly accessible web files), `pages` (the PHP page components outside of the webroot), `php` (the backend application code), and `db` (database schema). A `config.json` file (excluded from version control — see `config.json.example`) handles environment-specific settings.

## Technical Highlights

### PHP Front Controller / Router

Rather than exposing PHP files directly via the URL, all requests are routed through a single `index.php` entry point. This acts as a front controller and bootloader, keeping application files out of the public webroot and making the project structure more secure and maintainable.

### Custom PHP Classes

The `php/app` directory contains a couple of custom classes written specifically for this project:

- **Site Config Manager** — Reads and manages application configuration from a `config.json` file, making environment-specific settings easy to manage without hardcoding values.
- **Password Utility** — Handles Argon2id password hashing, encryption, and verification. Argon2id is the current recommended algorithm for password storage, and building this as a reusable class keeps the security logic clean and centralized.

### Composer & Autoloading

Composer is used for both dependency management and PSR-4 class autoloading. This keeps the codebase organized and avoids manual `require` chains throughout the application.

### Server-Side Validation with Laravel's Illuminate\Validation

Client-side validation alone is never enough — it can always be bypassed. To handle this properly, the form submission endpoint (`register_form_submit.php`) uses Laravel's standalone `illuminate/validation` package (installable via Composer without pulling in the full framework) to validate all incoming data server-side before anything touches the database.

### Client-Side Validation with jQuery

On the frontend, jQuery-based validation gives users immediate feedback as they fill out the form, keeping the experience smooth without requiring a round trip to the server for every mistake.

### Bootstrap UI

The interface is built with Bootstrap, giving it a clean, responsive layout that works across screen sizes without a lot of custom CSS overhead.

## Why Go Beyond the Requirements?

Honestly, the base requirement — a form that posts to PHP and saves to a fake database — can be done with a couple of files. But that wouldn't reflect how I actually approach problems. This demo is meant to show how I think about structure, security, and maintainability, even in a small project. A registration form is a good test case for that because it touches a lot of real concerns: input validation, password handling, routing, and separation of concerns.
