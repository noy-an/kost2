@echo off
:loop
php artisan schedule:work
timeout /t 60
goto loop
