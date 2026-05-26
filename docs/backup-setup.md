# Configuración de Respaldos Automáticos

## ¿Qué se respalda?

- La base de datos SQLite (`database/database.sqlite`)
- Los respaldos se guardan como archivos `.zip` en `storage/app/private/LocalDevGym/`

## ¿Cómo se limpian los respaldos antiguos?

El paquete `spatie/laravel-backup` maneja la limpieza automáticamente con la siguiente estrategia:

| Período | Retención |
|---------|-----------|
| Últimos 7 días | Se conservan **todos** los respaldos |
| Hasta 16 días | Se conserva **1 por día** |
| Hasta 8 semanas | Se conserva **1 por semana** |
| Hasta 4 meses | Se conserva **1 por mes** |
| Hasta 2 años | Se conserva **1 por año** |
| Tamaño máximo total | **2000 MB** |

---

## Configuración en Windows (Task Scheduler)

### Paso 1: Abrir Task Scheduler

1. Presiona `Win + R`
2. Escribe `taskschd.msc` y presiona Enter

### Paso 2: Crear la tarea

1. En el panel derecho, haz clic en **"Create Basic Task..."**
2. **Nombre:** `LocalDevGym - Respaldo`
3. **Descripción:** `Respaldo automático de la base de datos del gimnasio`
4. Clic en **Next**

### Paso 3: Configurar la frecuencia

1. Selecciona **Daily** (Diario)
2. Clic en **Next**
3. Configura la hora. **Se recomienda una hora en que el gym esté cerrado** (ej: 3:00 AM o 6:00 AM)
4. Asegúrate que "Recur every: **1** days" esté seleccionado
5. Clic en **Next**

### Paso 4: Configurar la acción

1. Selecciona **Start a program**
2. Clic en **Next**
3. En **Program/script**, haz clic en "Browse..." y selecciona el archivo `backup.bat` que está en la raíz del proyecto
   - Ejemplo: `C:\Users\TuUsuario\Herd\LocalDevGym\backup.bat`
4. Clic en **Next**

### Paso 5: Finalizar

1. Marca la casilla **"Open the Properties dialog for this task when I click Finish"**
2. Clic en **Finish**
3. En la ventana de propiedades:
   - En la pestaña **General**, marca **"Run whether user is logged on or not"**
   - En la pestaña **Settings**, marca **"Run task as soon as possible after a scheduled start is missed"**
     (esto asegura que si la PC estaba apagada a las 3 AM, el respaldo se hará cuando se encienda)
4. Clic en **OK** e ingresa la contraseña de tu usuario de Windows

---

## Comandos manuales (opcional)

Si necesitas ejecutar los comandos manualmente desde la terminal:

```bash
# Crear un respaldo manual
php artisan backup:run --only-db

# Limpiar respaldos antiguos
php artisan backup:clean

# Ver la lista de respaldos existentes
php artisan backup:list
```

---

## Verificar que funciona

Después de configurar todo, puedes probar ejecutando el archivo `backup.bat` manualmente haciendo doble clic en él. Luego verifica que se creó el archivo de respaldo en:

```
storage/app/private/LocalDevGym/
```
