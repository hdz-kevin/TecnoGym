# Crear Acceso Directo en el Escritorio

Un acceso directo permite abrir el sistema del gimnasio directamente desde el escritorio, sin necesidad de abrir el navegador y escribir la URL manualmente.

> **Nota:** La URL del sistema es `http://localdevgym.test`. Esta dirección la configura Laravel Herd automáticamente.

---

## Opción 1: Automático (recomendado)

Al ejecutar el archivo `setup.bat` durante la instalación, el sistema pregunta si deseas crear un acceso directo en el escritorio. Si seleccionas **"S"**, el acceso directo se crea automáticamente.

Si ya pasaste esa etapa y no lo creaste, puedes usar las opciones manuales a continuación.

---

## Opción 2: Acceso directo como aplicación (sin barra del navegador)

Este método abre el sistema en una ventana limpia, sin barra de direcciones ni pestañas, como si fuera una aplicación de escritorio.

### Con Google Chrome

1. Haz clic derecho en el escritorio
2. Selecciona **Nuevo → Acceso directo**
3. En la ubicación del elemento, escribe:
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --app=http://localdevgym.test --start-maximized
   ```
4. Clic en **Siguiente**
5. En el nombre del acceso directo, escribe: `LocalDevGym`
6. Clic en **Finalizar**

### Con Microsoft Edge

1. Haz clic derecho en el escritorio
2. Selecciona **Nuevo → Acceso directo**
3. En la ubicación del elemento, escribe:
   ```
   "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" --app=http://localdevgym.test --start-maximized
   ```
4. Clic en **Siguiente**
5. En el nombre del acceso directo, escribe: `LocalDevGym`
6. Clic en **Finalizar**

---

## Opción 3: Acceso directo simple (se abre en el navegador normal)

Si prefieres que se abra como una pestaña normal del navegador:

1. Abre tu navegador (Chrome, Edge, Firefox, etc.)
2. Ve a `http://localdevgym.test`
3. Haz clic en el **candado** o **icono de información** que aparece a la izquierda de la URL en la barra de direcciones
4. Arrastra ese icono hacia el **Escritorio**
5. Se creará automáticamente un acceso directo

---

## Cambiar el ícono del acceso directo (opcional)

Para que el acceso directo tenga un ícono personalizado:

1. Haz clic derecho en el acceso directo creado
2. Selecciona **Propiedades**
3. Clic en **Cambiar icono...**
4. Busca un archivo `.ico` de tu preferencia o selecciona uno de los íconos disponibles
5. Clic en **Aceptar** y luego en **Aplicar**

---

## Verificar que funciona

1. Asegúrate de que **Laravel Herd esté ejecutándose** (el ícono de Herd debe estar visible en la barra de tareas)
2. Haz doble clic en el acceso directo
3. El sistema del gimnasio debería abrirse directamente

> **Importante:** Laravel Herd debe estar corriendo para que la URL `http://localdevgym.test` funcione. Herd normalmente se inicia automáticamente al encender Windows.
