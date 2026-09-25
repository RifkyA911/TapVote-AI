@echo off
title TapVote-AI LAN Server (0.0.0.0:8000)
color 0A
echo ========================================================
echo        STARTING TAPVOTE-AI ON 0.0.0.0:8000
echo ========================================================
echo.
echo Akses dari HP Poco / Device lain di Wi-Fi yang sama:
echo   - Voter Kiosk : http://192.168.1.5:8000/voter
echo   - Live Count  : http://192.168.1.5:8000/
echo   - Admin Panel : http://192.168.1.5:8000/admin/login
echo.
echo Tekan Ctrl+C untuk menghentikan server.
echo ========================================================
echo.
"C:\laragon\bin\php\php-8.3.10-Win32-vs16-x64\php.exe" artisan serve --host=0.0.0.0 --port=8000
pause
