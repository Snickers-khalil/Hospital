# Deploy MediCore on InfinityFree

## 1. Create the hosting
1. Sign up on infinityfree.com and create an account (free subdomain, e.g. `medicore.infinityfreeapp.com`).
2. Control Panel > **MySQL Databases**: create a database named `hms`.
   Write down: host (`sqlXXX.infinityfree.com`), database name (`if0_xxx_hms`),
   user (`if0_xxx`), and password (the vPanel password, under "Account Details").
3. Optional: Control Panel > **SSL Certificates** > free SSL, so the site works with `https://`.

## 2. Build the upload folder (on your Mac)
```bash
cd Multi-Hospital/Multi-Hospital
cp deploy/infinityfree/env.infinityfree.example deploy/infinityfree/env.production
# edit env.production: APP_URL + DB_* values from step 1
bash deploy/infinityfree/build.sh
```
Result in `Multi-Hospital/infinityfree_build/`:
- `htdocs/` the site files (with your `.env`)
- `hms_infinityfree.sql` the database without triggers

## 3. Import the database
Control Panel > MySQL Databases > **phpMyAdmin** > select `if0_xxx_hms` > **Import** >
choose `hms_infinityfree.sql` > Go.

## 4. Upload the files
Use **FileZilla** (the web file manager is too slow for 20,000 files):
- Host `ftpupload.net`, port 21, user/password from Control Panel > FTP Details.
- Upload the **content** of `infinityfree_build/htdocs/` into the remote `/htdocs/` folder
  (delete the default `index2.html` there).
- Check that the hidden file `.env` was uploaded (FileZilla: Server > Force showing hidden files).

## 5. Test
Open `APP_URL`. The login page should show "Connexion - MediCore".

## Notes
- No MySQL triggers on free hosting: the PHP code already updates stock, so nothing is lost.
- `.env`, `.sql`, `test_*.php` and `composer.*` are blocked by `.htaccess` (403).
- Removed from the upload: Chinese PDF fonts `Sun-Ext*.ttf` (over the 10 MB file limit),
  Font Awesome metadata, screenshots, docs, tests, logs, old sessions, zip files.
- If you get a 500 error, rename `.htaccess` to test, and turn on "Display errors" in
  Control Panel > Alter PHP Config.
