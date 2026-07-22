@echo off
set PHP_EXE=C:\Users\Jozef\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe
cd /d C:\DEMO-GOB_BACKEND
"%PHP_EXE%" -S 127.0.0.1:8081 -t public public\index.php
