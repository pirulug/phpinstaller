# PHPInstaller

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

[English Version](README.md)

PHPInstaller es un asistente web de instalación modular, seguro y listo para producción, diseñado para integrarse en cualquier proyecto PHP nuevo sin necesidad de programar un flujo de instalación desde cero.

## Características Principales

* Configuracion Centralizada: Toda la logica del instalador se administra desde un unico archivo (`install/installer_config.php`).
* Soporte Multi-formato: Genera tanto archivos clasicos `config.php` como archivos de entorno `.env` para frameworks modernos.
* Interfaz con Soporte Dark y Light: Diseno limpio y responsivo con selector de tema integrado y deteccion automatica de preferencias del sistema.
* Iconografia Estandarizada: Basado exclusivamente en Bootstrap Icons.
* Seguridad Reforzada:
  * Almacenamiento seguro de contraseñas de administrador mediante `password_hash()` (algoritmo predeterminado Bcrypt).
  * Proteccion contra inyeccion de codigo PHP al generar archivos de configuracion.
  * Generacion de claves criptograficas mediante `random_bytes()` y `random_int()`.
  * Generacion automatica del archivo `install.lock` al finalizar la instalacion.
* Gestor de Base de Datos Inteligente: Permite verificar la conexion y crear la base de datos de forma automatica si aun no existe, con soporte para puertos personalizados.
* Deteccion Automatica del Entorno: Identifica automaticamente protocolo (HTTP/HTTPS), dominio, puerto y subdirectorio de instalacion.

## Requisitos del Sistema

* PHP 8.0.0 o superior
* Servidor de base de datos MySQL 5.7+ o MariaDB 10.3+
* Extensiones de PHP: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `json`, `curl`
* Permisos de escritura en el directorio raiz del proyecto

## Integracion en Proyectos Nuevos

Configurar el instalador en un nuevo proyecto requiere unicamente tres pasos:

### 1. Copiar la carpeta del instalador

Copia la carpeta `install/` dentro del directorio raiz de tu proyecto:

```text
mi-proyecto/
├── install/
│   ├── assets/
│   ├── includes/
│   ├── steps/
│   ├── config.tpl
│   ├── env.tpl
│   ├── db.sql
│   ├── installer_config.php
│   └── index.php
├── index.php
└── ...
```

### 2. Configurar `install/installer_config.php`

Abre `install/installer_config.php` y adapta los parametros a las necesidades de tu sistema:

```php
return [
  // Informacion del Proyecto
  'app' => [
    'name'        => 'Mi Nuevo Sistema',
    'version'     => '1.0.0',
    'author'      => 'Desarrollador',
    'description' => 'Asistente de configuracion del sistema.',
  ],

  // Requisitos del Servidor
  'requirements' => [
    'php_version'    => '8.1.0',
    'extensions'     => ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl'],
    'writable_paths' => ['../'],
  ],

  // Base de Datos
  'database' => [
    'default_host'    => 'localhost',
    'default_port'    => 3306,
    'default_name'    => 'mi_sistema_db',
    'default_user'    => 'root',
    'default_pass'    => '',
    'allow_create_db' => true,
    'sql_file'        => __DIR__ . '/db.sql',
  ],

  // Administrador Inicial
  'admin' => [
    'create_admin'   => true,
    'table'          => 'users',
    'columns'        => [
      'username' => 'user_name',
      'email'    => 'user_email',
      'password' => 'user_password',
      'role'     => 'user_role',
      'status'   => 'user_status',
    ],
    'default_role'   => 0,
    'default_status' => 1,
    'password_hash'  => 'password_hash',
  ],

  // Archivo de Configuracion Final
  'config_file' => [
    'type'        => 'php', // 'php' o 'env'
    'template'    => __DIR__ . '/config.tpl',
    'destination' => dirname(__DIR__) . '/config.php',
  ],

  // Seguridad
  'security' => [
    'lock_file'          => __DIR__ . '/install.lock',
    'block_if_installed' => true,
  ],
];
```

### 3. Cargar el esquema en `install/db.sql`

Coloca en el archivo `install/db.sql` las sentencias SQL (tablas, indices, datos iniciales) requeridas por tu sistema.

### 4. Ejecutar el asistente

Ingresa desde tu navegador a la ruta del instalador (por ejemplo, `http://localhost/mi-proyecto/install/`) y completa los pasos del asistente visual.

## Medidas de Seguridad Post-Instalacion

Una vez finalizada la instalacion:
1. El sistema genera automaticamente el archivo `install/install.lock`.
2. Para entornos de produccion, se recomienda eliminar o restringir el acceso web a la carpeta `install/`.

## Licencia

Este proyecto esta publicado bajo los terminos de la Licencia MIT.
