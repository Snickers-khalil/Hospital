@echo off
REM ============================================================
REM  MediCore - database setup for Windows (XAMPP)
REM  1. Start MySQL in the XAMPP Control Panel first.
REM  2. Double-click this file.
REM ============================================================
setlocal

set "MYSQL=C:\xampp\mysql\bin\mysql.exe"
if not exist "%MYSQL%" (
    echo [ERROR] MySQL not found at %MYSQL%
    echo Edit this file and set MYSQL to your XAMPP mysql.exe path.
    pause
    exit /b 1
)

set "SQL=%~dp0hms.sql"
if not exist "%SQL%" (
    echo [ERROR] hms.sql not found next to this script.
    pause
    exit /b 1
)

echo Creating database "hms" ...
"%MYSQL%" -u root -e "DROP DATABASE IF EXISTS hms; CREATE DATABASE hms CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
if errorlevel 1 (
    echo [ERROR] Could not connect to MySQL. Is MySQL started in XAMPP?
    pause
    exit /b 1
)

echo Importing hms.sql (this can take a minute) ...
"%MYSQL%" -u root --default-character-set=utf8mb4 hms < "%SQL%"
if errorlevel 1 (
    echo [ERROR] Import failed.
    pause
    exit /b 1
)

echo.
echo Done. Database "hms" is ready.
echo Open http://localhost/Hospital/ in your browser
echo (use the folder name you cloned into C:\xampp\htdocs).
pause
