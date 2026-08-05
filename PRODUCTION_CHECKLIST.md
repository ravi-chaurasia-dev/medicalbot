Production Checklist
====================

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] Set `SESSION_SECURE=true` and configure TLS
- [ ] Set a strong `APP_KEY`
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run database migrations (`php scripts/migrate.php`)
- [ ] Configure Apache/Nginx to serve `public/`
- [ ] Configure log rotation for `storage/logs`
- [ ] Configure backups (`scripts/backup.sh`)
- [ ] Verify uploads folder permissions
- [ ] Run route and form validation scripts (see `tools/`)
