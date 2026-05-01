@echo off
cd C:\Users\DELL\Documents\reservation
:loop
php artisan gmail:sync
timeout /t 60 /nobreak
goto loop