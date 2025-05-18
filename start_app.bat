@echo off

rem Aktifkan virtual environment
call flask_api\myenv\Scripts\activate.bat

rem Jalankan aplikasi Flask
python flask_api\app.py

rem Biarkan console tetap terbuka setelah selesai
pause