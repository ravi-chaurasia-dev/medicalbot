MediAI Deployment Guide
======================

This document describes production-ready deployment steps for MediAI.

Prerequisites
- PHP 8.3
- Composer
- MySQL/MariaDB
- Apache or Nginx
- GD extension (for image optimization)

1. Environment
- Copy `.env.example` to `.env` and set values (DB_*, APP_ENV=production, APP_DEBUG=false).
- Ensure `APP_KEY` is set to a secure random value.

2. Install dependencies and optimize
```
composer install --no-dev --optimize-autoloader
```

3. Database
- Create the database and run migrations:
```
php scripts/migrate.php
```

4. Web server
- For Apache, use `deploy/apache/mediai.conf` as a starting point.
- Ensure the document root points to `public/` and TLS is configured.

5. File permissions
- Ensure `storage/` and `public/uploads/` are writable by the web server user.

6. Backups
- Use `scripts/backup.sh` for scheduled DB dumps.

7. Monitoring & Logs
- Logs are written to `storage/logs/app.log` by default.
