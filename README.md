# Laravel Passwordless Authentication

Passwordless authentication in Laravel without relying on third-party packages.

### Clone this repository
```bash
git clone https://github.com/kennyalmendral/laravel-passwordless-authentication.git

cd laravel-passwordless-authentication
```

### Install dependencies
```bash
composer install
```

### Create local environment file
```bash
cp .env.example .env
```

### Configure mail settings
We'll be using an email delivery platform called [Mailtrap](https://mailtrap.io/), as it's both free and user-friendly for email testing. If you don't have an account yet, [create one](https://mailtrap.io/register/signup) and [obtain the SMTP credentials](https://help.mailtrap.io/article/5-testing-integration), then add them to your application's `.env` file:

```git
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=YOUR_MAILTRAP_SMTP_USERNAME
MAIL_PASSWORD=YOUR_MAILTRAP_SMTP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}"
```

### Generate a new random encryption key
```bash
php artisan key:generate
```

### Run the database migrations
```bash
php artisan migrate
```

### Start the development server
```bash
php artisan serve
```

### Read the blog post
[https://kennyalmendral.com/laravel-passwordless-authentication/](https://kennyalmendral.com/laravel-passwordless-authentication/)
