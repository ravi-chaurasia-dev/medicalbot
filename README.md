# MediAI

MediAI is a production-ready foundation for an AI-powered healthcare assistant built with PHP 8.3+, MySQL 8, Bootstrap 5, JavaScript, and modern secure coding practices.

This repository contains the architecture and application shell only. Business features will be implemented in a future phase.

## Tech Stack

- PHP 8.3+
- MySQL 8
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- AJAX-ready architecture
- Composer
- PDO
- PHPMailer

## Project Structure

```text
medicalbot/
├── app/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   ├── HomeController.php
│   │   └── ...
│   ├── Core/
│   │   ├── App.php
│   │   ├── BaseController.php
│   │   ├── BaseModel.php
│   │   ├── Config.php
│   │   ├── CSRF.php
│   │   ├── Database.php
│   │   ├── ErrorHandler.php
│   │   ├── Flash.php
│   │   ├── Logger.php
│   │   ├── Router.php
│   │   ├── SessionManager.php
│   │   └── ViewRenderer.php
│   ├── Helpers/
│   │   └── helpers.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   └── Models/
├── bootstrap/
│   └── app.php
├── config/
│   ├── app.php
│   ├── database.php
│   └── routes.php
├── database/
│   └── schema.sql
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── ...
│   ├── uploads/
│   └── index.php
├── resources/
│   └── views/
│       ├── auth/
│       ├── dashboard/
│       ├── home/
│       └── layouts/
├── storage/
│   └── logs/
├── .env.example
├── .gitignore
├── composer.json
├── README.md
└── vendor/
```

## Setup Instructions

1. Install PHP 8.3 and Composer.
2. Create a MySQL 8 database named `mediai`.
3. Clone the project and run:

```bash
composer install
```

4. Copy the environment file:

```bash
cp .env.example .env
```

5. Update the database and app settings in `.env`.
6. Create the database schema:

```bash
mysql -u root -p < database/schema.sql
```

7. Start the local PHP server:

```bash
php -S localhost:8000 public/index.php
```

8. Open `http://localhost:8000` in your browser.

## Environment Variables

The application loads values from `.env` via `vlucas/phpdotenv` and exposes them with the helper `env()` function.

## Routing

Routes are defined in `config/routes.php` and resolved by `App\Core\Router`.

## Security Measures

- CSRF token generation and validation
- Session-based auth
- Secure cookie configuration
- Prepared statements via PDO
- Error logging and protection against debug leakage in production
- Input sanitization and output escaping support

## UI Architecture

The UI includes:

- Responsive top navbar
- Dashboard sidebar
- Clean healthcare look and feel
- Login layout
- Footer
- Dark mode toggle
- Loading animations

## Notes

This is the foundational architecture phase and does not include business logic, patient features, or AI workflows yet.
