@echo off
chcp 65001 >nul
title LocalDevGym - Instalación

echo.
echo ╔══════════════════════════════════════════╗
echo ║      LocalDevGym - Configuración         ║
echo ╚══════════════════════════════════════════╝
echo.

:: Verificar que PHP está disponible (Herd lo provee)
where php >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [ERROR] PHP no encontrado.
    echo Por favor instala Laravel Herd primero: https://herd.laravel.com
    echo.
    pause
    exit /b 1
)

echo [1/7] Copiando configuración de producción...
if not exist .env (
    copy .env.prod .env >nul
    echo       OK
) else (
    echo       .env ya existe, saltando...
)

echo [2/7] Generando clave de la aplicación...
php artisan key:generate --force --no-interaction
echo       OK

echo [3/7] Creando base de datos SQLite...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo       OK
) else (
    echo       Base de datos ya existe, saltando...
)

echo [4/7] Ejecutando migraciones...
php artisan migrate --force --no-interaction
echo       OK

echo [5/7] Creando enlace de almacenamiento...
php artisan storage:link --force --no-interaction
echo       OK

echo [6/7] Instalando dependencias PHP...
where composer >nul 2>nul
if %ERRORLEVEL% equ 0 (
    composer install --no-dev --optimize-autoloader --no-interaction
    echo       OK
) else (
    echo       Composer no encontrado, saltando...
    echo       (Las dependencias ya deben estar instaladas)
)

echo [7/7] Compilando assets...
where npm >nul 2>nul
if %ERRORLEVEL% equ 0 (
    call npm install --no-audit --no-fund
    call npm run build
    echo       OK
) else (
    echo       npm no encontrado, saltando...
    echo       (Los assets ya deben estar compilados)
)

echo.
echo ╔══════════════════════════════════════════╗
echo ║     ¡Instalación completada!            ║
echo ╠══════════════════════════════════════════╣
echo ║                                          ║
echo ║  Abre tu navegador y ve a:               ║
echo ║  http://LocalDevGym.test                    ║
echo ║                                          ║
echo ╚══════════════════════════════════════════╝
echo.

:: Crear acceso directo en el escritorio
echo ¿Deseas crear un acceso directo en el Escritorio? (S/N)
set /p crear_acceso="> "
if /i "%crear_acceso%"=="S" (
    echo Set objShell = CreateObject("WScript.Shell") > "%TEMP%\shortcut.vbs"
    echo Set lnk = objShell.CreateShortcut(objShell.SpecialFolders("Desktop") ^& "\LocalDevGym.lnk") >> "%TEMP%\shortcut.vbs"

    :: Intentar Chrome primero, luego Edge
    echo If CreateObject("Scripting.FileSystemObject").FileExists("C:\Program Files\Google\Chrome\Application\chrome.exe") Then >> "%TEMP%\shortcut.vbs"
    echo     lnk.TargetPath = "C:\Program Files\Google\Chrome\Application\chrome.exe" >> "%TEMP%\shortcut.vbs"
    echo     lnk.Arguments = "--app=http://localdevgym.test --start-maximized" >> "%TEMP%\shortcut.vbs"
    echo Else >> "%TEMP%\shortcut.vbs"
    echo     lnk.TargetPath = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" >> "%TEMP%\shortcut.vbs"
    echo     lnk.Arguments = "--app=http://localdevgym.test --start-maximized" >> "%TEMP%\shortcut.vbs"
    echo End If >> "%TEMP%\shortcut.vbs"

    echo lnk.Description = "LocalDevGym - Sistema de Gimnasio" >> "%TEMP%\shortcut.vbs"
    echo lnk.Save >> "%TEMP%\shortcut.vbs"
    cscript //nologo "%TEMP%\shortcut.vbs"
    del "%TEMP%\shortcut.vbs"
    echo Acceso directo creado en el Escritorio.
)

echo.
pause
