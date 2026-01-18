@echo off
chcp 65001 >nul
title WhatsApp Bot Control Panel
color 0A

:mainmenu
cls
echo ========================================
echo    WHATSAPP BOT CONTROL PANEL
echo ========================================
echo.
cd /d "D:\laragon\www\kosan-app3\app\Services\WhatsAppBot"
echo 📊 CURRENT STATUS:
pm2 list whatsapp-bot | findstr "whatsapp-bot"
echo.
echo ========================================
echo.
echo [1] 🚀 START Bot
echo [2] ⏹️  STOP Bot  
echo [3] 🔄 RESTART Bot
echo [4] 📊 REFRESH Status
echo [5] 📝 VIEW Logs (Tail)
echo [6] 🧹 Maintenance Menu
echo [0] ❌ EXIT
echo.
set /p choice="Pilihan (0-6): "

if "%choice%"=="1" goto startbot
if "%choice%"=="2" goto stopbot
if "%choice%"=="3" goto restartbot
if "%choice%"=="4" goto mainmenu
if "%choice%"=="5" goto viewlogs
if "%choice%"=="6" goto maintenance
if "%choice%"=="0" goto exit
goto mainmenu

:startbot
echo Starting bot...
pm2 start whatsapp-bot.js --name "whatsapp-bot" --time
timeout /t 2 /nobreak > nul
goto mainmenu

:stopbot
echo Stopping bot...
pm2 stop whatsapp-bot
timeout /t 2 /nobreak > nul
goto mainmenu

:restartbot
echo Restarting bot...
pm2 restart whatsapp-bot
timeout /t 2 /nobreak > nul
goto mainmenu

:viewlogs
cls
echo ========================================
echo    📝 WHATSAPP BOT LOGS (Press Q to quit)
echo ========================================
echo.
pm2 logs whatsapp-bot --lines 30
echo.
echo Press any key to continue...
pause >nul
goto mainmenu

:maintenance
cls
echo ========================================
echo         🧹 MAINTENANCE MENU
echo ========================================
echo.
echo [1] Clear Logs
echo [2] Backup Auth Files
echo [3] Restore Auth Files
echo [4] Check Disk Space
echo [5] Force Kill All
echo [9] Back to Main Menu
echo.
set /p mchoice="Choice: "

if "%mchoice%"=="1" goto clearlogs
if "%mchoice%"=="2" goto backupauth
if "%mchoice%"=="3" goto restoreauth
if "%mchoice%"=="4" goto diskspace
if "%mchoice%"=="5" goto forcekill
if "%mchoice%"=="9" goto mainmenu
goto maintenance

:clearlogs
pm2 flush whatsapp-bot
echo Logs cleared!
timeout /t 1 /nobreak > nul
goto maintenance

:backupauth
echo Backing up auth files...
xcopy /E /I "auth_info" "backup_auth_%date:~10,4%%date:~4,2%%date:~7,2%\" >nul
echo Backup created!
timeout /t 1 /nobreak > nul
goto maintenance

:diskspace
echo Disk space in bot folder:
dir "D:\laragon\www\kosan-app3\app\Services\WhatsAppBot" | find "File(s)"
pause
goto maintenance

:forcekill
echo Force killing...
taskkill /F /IM node.exe /T 2>nul
taskkill /F /IM pm2.exe /T 2>nul
echo Done!
pause
goto mainmenu

:exit
echo Closing control panel...
timeout /t 1 /nobreak > nul
pause
exit