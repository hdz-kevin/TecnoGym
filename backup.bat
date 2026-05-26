@echo off
chcp 65001 >nul

:: ============================================
:: LocalDevGym - Respaldo automático
:: ============================================
:: Este script es ejecutado por Windows Task Scheduler
:: para crear respaldos de la base de datos y limpiar
:: los respaldos antiguos automáticamente.
:: ============================================

cd /d "%~dp0"

echo [%date% %time%] Iniciando respaldo...

:: Crear respaldo (solo base de datos)
php artisan backup:run --only-db --no-interaction

if %ERRORLEVEL% neq 0 (
    echo [%date% %time%] ERROR: El respaldo falló.
    exit /b 1
)

echo [%date% %time%] Respaldo creado exitosamente.

:: Limpiar respaldos antiguos según la estrategia configurada
php artisan backup:clean --no-interaction

echo [%date% %time%] Limpieza de respaldos viejos completada.
echo [%date% %time%] Proceso finalizado.
