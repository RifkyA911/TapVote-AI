@echo off
title TapVote-AI Server
echo ==========================================
echo   TapVote-AI - Starting Development Server
echo ==========================================
echo.

:: Set PHP path (herd-lite)
set "PATH=C:\Users\USER\.config\herd-lite\bin;%PATH%"

:: Check PHP version
echo [*] PHP Version:
php -v
echo.

:: Check if .env exists
if not exist ".env" (
    echo [!] .env not found, copying from .env.example...
    copy .env.example .env
    php artisan key:generate --ansi
    echo.
)

:: Database: MySQL MariaDB (tapvote_ai via XAMPP)
:: Make sure XAMPP MySQL is running before starting

:: Check if vendor exists
if not exist "vendor" (
    echo [!] Installing Composer dependencies...
    composer install
    echo.
)

:: Check if node_modules exists
if not exist "node_modules" (
    echo [!] Installing NPM dependencies...
    npm install
    echo.
)

echo [*] Starting Laravel development server...
echo [*] App URL: http://localhost:8000
echo [*] Press Ctrl+C to stop
echo.

php artisan serve
