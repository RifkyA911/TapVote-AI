#!/usr/bin/env pwsh
# TapVote-AI Development Server Runner
# Usage: .\run.ps1 [dev|serve|build|migrate|seed|test]

param(
    [string]$Command = "serve"
)

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  TapVote-AI - Development Server" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Set PHP path (herd-lite)
$env:Path = "C:\Users\USER\.config\herd-lite\bin;$env:Path"

# Verify PHP
Write-Host "[*] PHP Version:" -ForegroundColor Yellow
php -v
Write-Host ""

# Auto-setup checks
if (-not (Test-Path ".env")) {
    Write-Host "[!] .env not found, creating..." -ForegroundColor Red
    Copy-Item .env.example .env
    php artisan key:generate --ansi
    Write-Host ""
}

# Database: MySQL MariaDB (tapvote_ai via XAMPP)
# Make sure XAMPP MySQL is running before starting
$mysqlStatus = & "C:\xampp\mysql\bin\mysqladmin" -u root status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "[!] XAMPP MySQL is not running! Start it from XAMPP Control Panel first." -ForegroundColor Red
    exit 1
}
Write-Host "[*] MySQL MariaDB is running" -ForegroundColor Green

if (-not (Test-Path "vendor")) {
    Write-Host "[!] Installing Composer dependencies..." -ForegroundColor Red
    composer install
    Write-Host ""
}

if (-not (Test-Path "node_modules")) {
    Write-Host "[!] Installing NPM dependencies..." -ForegroundColor Red
    npm install
    Write-Host ""
}

switch ($Command) {
    "serve" {
        Write-Host "[*] Starting Laravel server..." -ForegroundColor Green
        Write-Host "[*] App URL: http://localhost:8000" -ForegroundColor Green
        Write-Host "[*] Press Ctrl+C to stop" -ForegroundColor Yellow
        Write-Host ""
        php artisan serve
    }
    "dev" {
        Write-Host "[*] Starting dev server (Laravel + Vite)..." -ForegroundColor Green
        Write-Host "[*] App URL: http://localhost:8000" -ForegroundColor Green
        Write-Host "[*] Vite URL: http://localhost:5173" -ForegroundColor Green
        Write-Host "[*] Press Ctrl+C to stop" -ForegroundColor Yellow
        Write-Host ""
        npx concurrently --names "laravel,vite" --prefix-colors "green,blue" "php artisan serve" "npm run dev"
    }
    "build" {
        Write-Host "[*] Building frontend assets..." -ForegroundColor Green
        npm run build
    }
    "migrate" {
        Write-Host "[*] Running migrations..." -ForegroundColor Green
        php artisan migrate --ansi
    }
    "seed" {
        Write-Host "[*] Running seeders..." -ForegroundColor Green
        php artisan db:seed --ansi
    }
    "test" {
        Write-Host "[*] Running tests..." -ForegroundColor Green
        php artisan test --ansi
    }
    default {
        Write-Host "Usage: .\run.ps1 [serve|dev|build|migrate|seed|test]" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Commands:" -ForegroundColor Cyan
        Write-Host "  serve   - Start Laravel server only (default)" 
        Write-Host "  dev     - Start Laravel + Vite dev server (hot reload)"
        Write-Host "  build   - Build frontend assets for production"
        Write-Host "  migrate - Run database migrations"
        Write-Host "  seed    - Run database seeders"
        Write-Host "  test    - Run tests"
    }
}
