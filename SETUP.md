# Setup Guide (XAMPP/WAMP)

1. Install XAMPP (or WAMP/LAMP).
2. Copy this project into `htdocs/collage`.
3. Start **Apache** and **MySQL**.
4. Open phpMyAdmin and create database `neb_computer`.
5. Import `db/schema.sql`.
6. Update DB credentials in `config.php` if needed.
7. Open `http://localhost/collage`.
8. Admin panel: `http://localhost/collage/admin` (default `admin/admin123`).

## Notes

- Frontend pages work with static/localStorage data even if API is unavailable.
- APIs and admin require a valid database connection.
