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

- **PHP >= 7.4**
- **Composer**
- **Node.js y npm** (para gestionar las dependencias de frontend)
- **MySQL** (o cualquier otra base de datos compatible con Laravel)
- **XAMPP** (si estás utilizando un entorno local de desarrollo)

# Configuración del Proyecto con XAMPP

## Instalación y Configuración de XAMPP

XAMPP es una herramienta que permite instalar un servidor local que incluye Apache, MySQL, PHP y Perl. Es ideal para desarrollar aplicaciones web de manera local.

### Paso 1: Descargar XAMPP

1. Dirígete al sitio oficial de XAMPP: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
2. Descarga la versión de XAMPP adecuada para tu sistema operativo:
   - **Windows**
   - **Linux**
   - **macOS**

### Paso 2: Instalación de XAMPP

1. Una vez descargado el archivo, ejecútalo y sigue las instrucciones del asistente de instalación.
2. Selecciona los componentes que deseas instalar. Los componentes recomendados son:
   - Apache
   - MySQL
   - PHP
   - phpMyAdmin (para gestionar la base de datos)

3. Completa el proceso de instalación.

### Paso 3: Iniciar XAMPP

1. Abre el panel de control de XAMPP.
2. Inicia los servicios de **Apache** y **MySQL** haciendo clic en el botón "Start" junto a cada uno.
3. Para verificar que XAMPP está funcionando correctamente, abre tu navegador y ve a `http://localhost`. Deberías ver la página de bienvenida de XAMPP.

### Paso 4: Configurar el Proyecto

1. Coloca los archivos del proyecto en la carpeta `htdocs` dentro de la carpeta de instalación de XAMPP. Por defecto, en Windows es:

2. Clona tu repositorio dentro de esta carpeta o coloca el contenido del proyecto allí.

```bash
cd C:\xampp\htdocs

# 1. Clona el repositorio de GitLab a tu máquina local:
git clone https://github.com/rgsarmiento/meseros.git meseros

# 2. Accede al directorio del proyecto
cd meseros

# Instala las dependencias de PHP con Composer
composer install

# Instala las dependencias de frontend con npm
npm install

# Copia el archivo de configuración de ejemplo y crea el archivo .env
cp .env.example .env

# Genera una nueva clave de aplicación para Laravel
php artisan key:generate

# Abre el archivo .env y configura tu base de datos en los siguientes valores
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=meseros
# DB_USERNAME=root
# DB_PASSWORD=

# Ejecuta las migraciones para crear las tablas en la base de datos
php artisan migrate --seed

# Limpiar caché de la configuración
php artisan config:clear

# Limpiar caché de rutas:
php artisan route:clear

# Limpiar caché de vistas:
php artisan view:clear

# Limpiar caché de la aplicación:
php artisan cache:clear

# Asignar permisos a las carpetas storage y bootstrap/cache:
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
