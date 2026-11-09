# Run MediCore on Windows (XAMPP)

## 1. Install
- XAMPP with **PHP 8.2**, installed in `C:\xampp`.
- Git for Windows.

## 2. Get the code
Open a terminal in `C:\xampp\htdocs` and run:

```
git clone https://github.com/Snickers-khalil/Hospital.git
```

The project is now in `C:\xampp\htdocs\Hospital`. The `.env`, `vendor/` and `uploads/` folders are already included, so there is nothing to build.

## 3. Database
1. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
2. Double-click `C:\xampp\htdocs\Hospital\database\setup_windows.bat`.
   It creates the `hms` database and imports `database/hms.sql`.

Or use phpMyAdmin: create a database named `hms`, then **Import** `database/hms.sql`.

## 4. Open the app
Go to http://localhost/Hospital/

The base URL and the session folder are detected automatically, so any folder name inside `htdocs` works.

## 5. After the first login
API keys and secrets were removed from the database export because the repository is public.
Enter them again in **Settings**:
- Gemini API key (AI features): Settings > AI settings.
- Email, SMS and payment keys, if you use them.

## Problems
| Problem | Fix |
|---|---|
| MySQL does not start (port 3306 in use) | Stop the other MySQL in `services.msc`, or change the port in `my.ini` and `DB_PORT` in `.env`. |
| Database connection error | In `.env`: `DB_USERNAME=root`, `DB_PASSWORD=` (empty), `DB_DATABASE=hms`. |
| Pages show "Not Found" | In `C:\xampp\apache\conf\httpd.conf`, enable `LoadModule rewrite_module` and set `AllowOverride All` for htdocs, then restart Apache. |
| Import too big in phpMyAdmin | Use `setup_windows.bat`, or raise `upload_max_filesize` and `post_max_size` in `php.ini`. |
