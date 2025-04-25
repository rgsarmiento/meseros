# Aplicación de Toma de Órdenes

## Descripción

Esta es una aplicación de gestión de pedidos diseñada para restaurantes. Permite crear y gestionar órdenes de manera eficiente, registrar detalles de productos, gestionar mesas, y llevar un control del estado de las órdenes. La aplicación está desarrollada en **PHP (Laravel)** para el backend, utiliza **MySQL** como base de datos y puede integrarse con una interfaz frontend en **JavaScript** para notificaciones y actualizaciones en tiempo real.

## Características

- **Gestión de mesas**: Crear, actualizar, y cambiar el estado de las mesas (libre, ocupada, reservada, en limpieza).
- **Gestión de órdenes**: Crear nuevas órdenes, agregar productos, calcular totales, y registrar observaciones.
- **Actualización del estado de las órdenes**: Actualizar el estado de las órdenes (pendiente, preparando, terminado, procesado).
- **Notificaciones en tiempo real**: Notificaciones sobre la actualización de órdenes utilizando tecnologías de frontend (por ejemplo, **SweetAlert**).
- **Autenticación de usuarios**: Sistema de usuarios para la gestión de órdenes y asignación de mesas.
- **Interfaz de carrito**: Los usuarios pueden agregar productos al carrito y procesar el pedido en tiempo real.
  
## Requisitos Previos

Antes de instalar y ejecutar la aplicación, asegúrate de tener instalado lo siguiente:

- **Git** [https://git-scm.com/downloads](https://git-scm.com/downloads)
- **Composer** [https://getcomposer.org/](https://getcomposer.org/)
- **Node.js y npm** (para gestionar las dependencias de frontend) [https://nodejs.org/es](https://nodejs.org/es)
- **LARAGON** (si estás utilizando un entorno local)

# Configuración del Proyecto con LARAGON

## Instalación y Configuración de LARAGON

LARAGON es una herramienta que permite instalar un servidor local que incluye Apache, MySQL, PHP y Perl. Es ideal para desarrollar aplicaciones web de manera local.

### Paso 1: Descargar LARAGON

1. Dirígete al sitio de LARAGON: [https://github.com/leokhoa/laragon/releases/tag/6.0.0](https://github.com/leokhoa/laragon/releases/tag/6.0.0)
2. Descarga la versión completa (Full):
   - **laragon-wamp.exe**

### Paso 2: Instalación de LARAGON

1. Una vez descargado el archivo, ejecútalo, selecciona el idioma y sigue las instrucciones del asistente de instalación.

### Paso 3: Iniciar LARAGON

1. Abre el panel de control de LARAGON.
2. Inicia los servicios de **Apache** y **MySQL** haciendo clic en el botón "Iniciar Todo".
3. Para verificar que LARAGON está funcionando correctamente, abre tu navegador y ve a `http://localhost`. Deberías ver la página de bienvenida de LARAGON.

### Paso 4: Cómo agregar una nueva versión de PHP en Laragon

1. Descarga el **.zip** de la versión de **PHP 8.2 (8.2.28)** desde el sitio oficial de PHP para Windows:
    - URL de descarga:: [https://windows.php.net/download](https://windows.php.net/download)

2. Extraer los archivos PHP en Laragon
    - Una vez descargado el archivo comprimido (por ejemplo, **php-8.2.28-nts-Win32-vs16-x64.zip**), extrae su contenido.
    - Copia la carpeta **php-8.2.28-nts-Win32-vs16-x64** en el directorio donde Laragon mantiene las versiones de PHP. La ruta predeterminada es: **C:\laragon\bin\php**

3. Configurar Laragon para usar la nueva versión de PHP
    - Abre Laragon.
    - Click izquierdo en Laragon, para abrir el menú.
    - Ve a "PHP" y luego selecciona la opción "Version".
    - En el menú de versiones, deberías ver la opción para seleccionar PHP 8.2.28 (si todo está configurado correctamente).
    - Recargar Apache

### Paso 5: Pasos para crear una base de datos desde Laragon

1. Crear una base de datos **"meseros"** Elige el "Collation", (utf8mb4_spanish_ci)
 
### Paso 6: Configurar el Proyecto

1. Arbrir la terminal de Laragon. Por defecto, se inicia en:

```bash
cd C:\Laragon\www
```

2. Clona el repositorio de GitLab a tu máquina local:
```bash
git clone https://github.com/rgsarmiento/meseros.git meseros
```
3. Accede al directorio del proyecto
```bash
cd meseros
```
4. Instala las dependencias de PHP con Composer
```bash
composer install
```
5. Instala las dependencias de frontend con npm
```bash
npm install
```
6. Copia el archivo de configuración de ejemplo y crea el archivo .env
```bash
cp .env.example .env
```
7. Genera una nueva clave de aplicación para Laravel
```bash
php artisan key:generate
```
8. Abre el archivo .env y configura tu base de datos en los siguientes valores
```bash
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=meseros
# DB_USERNAME=root
# DB_PASSWORD=
```
9. Ejecuta las migraciones para crear las tablas en la base de datos:
```bash
php artisan migrate --seed
```

10. Limpia caché de la configuración, caché de rutas, view:clear, cache:clear
```bash
php artisan optimize:clear  
```

11. Asignar permisos a las carpetas storage y bootstrap/cache:
```bash
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```
12. Compila el frontend:
```bash
npm run build
```

# Configuración del Proyecto Meseros para Acceso en Red

## Paso 1: Obtener la Dirección IP Local

Para permitir que otros dispositivos en la red accedan a tu aplicación, primero necesitas obtener la dirección IP local de tu equipo.

1. Abre una terminal y ejecuta el siguiente comando:

    ```bash
    ipconfig
    ```

2. En los resultados, busca la **Dirección IPv4** que debería ser algo como `192.168.1.100`. Esta será la IP que otros dispositivos usarán para acceder a la aplicación.

## Paso 2: Configurar Apache en Laragon

1. Abre el archivo de configuración de hosts virtuales de Apache en tu instalación de Laragon. Puedes encontrarlo en:

    ```bash
    C:\laragon\bin\apache\httpd-2.4.54-win64-VS16\conf
    ```

2. Edita el archivo `httpd.conf` para agregar una entrada para tu proyecto:
    - Busca la linea **# Virtual hosts** y debajo pegas lo siguiente:
    ```apache
    <VirtualHost *:80>
    DocumentRoot "C:/laragon/www/meseros/public"
    ServerName meseros.local
    <Directory "C:/laragon/www/meseros/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
    ```

## Paso 3: Configurar el Firewall de Windows

Asegúrate de que el firewall de Windows permita el acceso a Apache. Sigue estos pasos:

1. Ve al **Panel de control** y selecciona **Sistema y seguridad** > **Firewall de Windows Defender** > **Permitir una aplicación o característica a través del Firewall de Windows**.
2. Busca **Apache HTTP Server** en la lista y asegúrate de que esté marcado para **Privado** y **Público**.
3. Si no lo encuentras, haz clic en **Permitir otra aplicación...** y selecciona Apache manualmente desde `C:/xampp/apache/bin/httpd.exe`.

## Paso 4: Reiniciar Apache