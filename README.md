# PHP_Laravel12_Google_2_Factor_Authentication

## Overview

This project demonstrates a complete implementation of Google Two-Factor Authentication (2FA) in Laravel 12. It includes QR code generation, recovery codes, and a secure login verification flow. The goal of this project is to show how 2FA can be integrated into a Laravel application to enhance account security.

This implementation is suitable for learning, interviews, and real-world applications with further customization.

## Features

* Google Authenticator integration
* QR code generation for easy 2FA setup
* Recovery code generation and management
* Secure two-step login verification flow
* Enable and disable 2FA functionality
* Session-based 2FA verification handling
* User-friendly Blade-based interface

## Prerequisites

* PHP 8.1 or higher
* Composer
* Laravel 12
* MySQL or compatible database
* Node.js and NPM (for frontend assets)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/laravel-12-google-2fa.git
cd laravel-12-google-2fa
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Required Packages

```bash
composer require pragmarx/google2fa-laravel
composer require bacon/bacon-qr-code
```

### 4. Install Authentication Scaffolding (Optional)

Laravel Breeze is recommended for basic authentication scaffolding.

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### 5. Configure Environment

Create and configure the environment file.

```bash
cp .env.example .env
```

Update database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_2fa
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Run Migrations

```bash
php artisan migrate
```

## Project Structure

```
laravel-2fa/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   └── RegisteredUserController.php
│   │   └── TwoFactorController.php
│   └── Models/
│       └── User.php
├── resources/views/
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── two-factor-setup.blade.php
│   │   ├── two-factor-verify.blade.php
│   │   └── two-factor-recovery.blade.php
│   └── dashboard.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
└── README.md
```

## Database Changes

The users table includes additional columns for 2FA support:

* `google2fa_secret` (string, nullable)
* `google2fa_enabled` (boolean, default false)
* `recovery_codes` (text, nullable, stored as JSON)

## User Model Configuration

The User model includes helper methods for managing 2FA:

```php
$user->generateRecoveryCodes();
$user->verifyTwoFactorCode($code);
$user->verifyRecoveryCode($code);
$user->getQRCodeUrl();
```

## Application Flow

### Registration and Login

* Register a new account using `/register`
* Login using `/login`
* Access the dashboard after successful authentication

### Enabling Two-Factor Authentication

* Navigate to the dashboard
* Click on "Enable Two-Factor Authentication"
* Scan the QR code using Google Authenticator
* Enter the generated 6-digit code
* Save the provided recovery codes

### Login with Two-Factor Authentication

* Login with email and password
* Redirected to 2FA verification page
* Enter the 6-digit code from Google Authenticator
* Successfully redirected to the dashboard

### Recovery Codes

* Use recovery codes if the authenticator app is unavailable
* Each recovery code is single-use
* New recovery codes can be generated from the dashboard

### Disabling Two-Factor Authentication

* Navigate to the dashboard
* Select "Disable 2FA"
* Confirm with password
* Two-factor authentication is disabled

## Routes and Endpoints

| Method | URL                           | Description                 |
| ------ | ----------------------------- | --------------------------- |
| GET    | /two-factor/setup             | Show 2FA setup page         |
| POST   | /two-factor/enable            | Enable 2FA                  |
| GET    | /two-factor/verify            | Show verification form      |
| POST   | /two-factor/verify            | Verify authentication code  |
| GET    | /two-factor/recovery          | Show recovery codes         |
| POST   | /two-factor/recovery/generate | Generate new recovery codes |
| POST   | /two-factor/disable           | Disable 2FA                 |

## Security Considerations

* Google secrets are encrypted before storage
* Recovery codes are one-time use
* Password confirmation required for disabling 2FA
* Session-based verification flow
* Time-window tolerance for code verification

## Testing

### Create a Test User

```bash
php artisan tinker
```

```php
$user = new App\Models\User;
$user->name = 'Test User';
$user->email = 'test@example.com';
$user->password = bcrypt('password');
$user->save();
```

## Screenshot
### Login Page
<img width="719" height="649" alt="image" src="https://github.com/user-attachments/assets/789356fd-a1a1-4119-bffd-2ecc78f696e3" />

### Dashboard Page
<img width="1903" height="615" alt="image" src="https://github.com/user-attachments/assets/27bc93de-8174-4fcb-b76d-f4071b25ca9f" />

### Setup Two-Factor Authentication
<img width="875" height="942" alt="image" src="https://github.com/user-attachments/assets/90ec6d4f-6b66-4f86-beed-0bdef7650211" />

### Test Scenarios

* Enable 2FA
* Login with authenticator code
* Login with recovery code
* Disable 2FA

## Troubleshooting

### QR Code Not Scanning

* Ensure `bacon/bacon-qr-code` is installed
* Check PHP GD extension
* Use manual secret entry if needed

### Invalid Authentication Codes

* Verify system time synchronization
* Try previous or next code

### Common Fixes

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan migrate:fresh
```

## Deployment Notes

* Set `APP_ENV=production`
* Set `APP_DEBUG=false`
* Use HTTPS in production
* Configure secure session driver
* Enable rate limiting on login routes

## Dependencies

* pragmarx/google2fa-laravel
* bacon/bacon-qr-code
* laravel/breeze (optional)
* laravel/framework

## Future Enhancements

* Email-based 2FA fallback
* SMS verification option
* Multiple device support
* Admin management for 2FA
* Audit logs for authentication events

## License

This project is open-source and licensed under the MIT License.

## Notes

This project is intended for educational and demonstration purposes and can be extended for production use with additional security and monitoring layers.
