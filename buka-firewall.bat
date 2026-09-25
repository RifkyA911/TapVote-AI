@echo off
:: Batch Got Admin Rights
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"
if '%errorlevel%' NEQ '0' (
    echo Mengaktifkan izin Administrator...
    goto UACPrompt
) else ( goto gotAdmin )

:UACPrompt
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin.vbs"
    echo UAC.ShellExecute "%~s0", "", "", "runas", 1 >> "%temp%\getadmin.vbs"
    "%temp%\getadmin.vbs"
    exit /B

:gotAdmin
    if exist "%temp%\getadmin.vbs" ( del "%temp%\getadmin.vbs" )
    pushd "%CD%"
    CD /D "%~dp0"

echo ========================================================
echo   MENGIZINKAN FIREWALL PORT 80, 8000, 8080 UNTUK AKSES HP
echo ========================================================
powershell -NoProfile -Command "New-NetFirewallRule -DisplayName 'Laragon & PHP LAN Server (80, 8000, 8080)' -Direction Inbound -LocalPort 80,8000,8080 -Protocol TCP -Action Allow -Profile Any"
echo.
echo Selesai! Firewall Port 80, 8000, dan 8080 (Laragon Nginx) sudah berhasil dibuka.
echo Sekarang HP Anda sudah bisa mengakses Laragon langsung!
echo.
pause
