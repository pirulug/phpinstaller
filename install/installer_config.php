<?php
/**
 * ====================================================================
 * Configuración del Asistente de Instalación (PHPInstaller)
 * ====================================================================
 * Modifica este archivo para adaptar el instalador a tu nuevo proyecto
 * sin necesidad de modificar el código interno del instalador.
 */

return [
  // ------------------------------------------------------------------
  // 1. Información General de tu Proyecto
  // ------------------------------------------------------------------
  'app' => [
    'name'        => 'Mi Proyecto PHP',
    'version'     => '1.0.0',
    'author'      => 'Tu Nombre / Empresa',
    'description' => 'Asistente de configuración e instalación del sistema.',
  ],

  // ------------------------------------------------------------------
  // 2. Requisitos del Servidor
  // ------------------------------------------------------------------
  'requirements' => [
    // Versión mínima de PHP requerida
    'php_version' => '8.0.0',

    // Extensiones de PHP que deben estar habilitadas
    'extensions' => [
      'pdo',
      'pdo_mysql',
      'mbstring',
      'openssl',
      'json',
      'curl',
    ],

    // Directorios o archivos que deben tener permisos de escritura (is_writable)
    // Las rutas son relativas a la carpeta raíz de tu proyecto
    'writable_paths' => [
      '../', // Raíz para escribir config.php o .env
    ],
  ],

  // ------------------------------------------------------------------
  // 3. Configuración de Base de Datos
  // ------------------------------------------------------------------
  'database' => [
    'default_host'    => 'localhost',
    'default_port'    => 3306,
    'default_name'    => 'mi_proyecto_db',
    'default_user'    => 'root',
    'default_pass'    => '',
    // Si la base de datos no existe, ¿permitir al usuario crearla con un clic?
    'allow_create_db' => true,
    // Archivo SQL que se ejecutará durante la instalación
    'sql_file'        => __DIR__ . '/db.sql',
  ],

  // ------------------------------------------------------------------
  // 4. Usuario Administrador Inicial
  // ------------------------------------------------------------------
  'admin' => [
    // ¿Crear un usuario administrador durante la instalación?
    'create_admin' => true,

    // Tabla en tu base de datos donde se insertará el administrador
    'table' => 'users',

    // Mapeo de nombres de columnas en tu tabla de usuarios
    'columns' => [
      'username' => 'user_name',
      'email'    => 'user_email',
      'password' => 'user_password',
      'role'     => 'user_role',
      'status'   => 'user_status',
    ],

    // Valores por defecto para role y status del usuario admin
    'default_role'   => 0, // 0 = Administrador
    'default_status' => 1, // 1 = Activo

    // Algoritmo de hash para la contraseña ('password_hash' recomendado)
    'password_hash'  => 'password_hash', // Opciones: 'password_hash', 'encryption_class'
  ],

  // ------------------------------------------------------------------
  // 5. Generación del Archivo de Configuración
  // ------------------------------------------------------------------
  'config_file' => [
    // Tipo de configuración a generar:
    // 'php' => Genera un archivo config.php usando config.tpl
    // 'env' => Genera un archivo .env usando env.tpl (ideal para Laravel, CodeIgniter, etc.)
    'type'        => 'php',
    'template'    => __DIR__ . '/config.tpl',
    'destination' => dirname(__DIR__) . '/config.php',
  ],

  // ------------------------------------------------------------------
  // 6. Seguridad y Bloqueo Post-Instalación
  // ------------------------------------------------------------------
  'security' => [
    // Nombre del archivo lock para evitar que el instalador se vuelva a ejecutar
    'lock_file' => __DIR__ . '/install.lock',
    // Si es true, redirige automáticamente al inicio si el archivo lock o config existe
    'block_if_installed' => true,
  ],
];
