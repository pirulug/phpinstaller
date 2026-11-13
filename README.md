# PHPInstaller

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

[Versión en Español](README.es.md)

PHPInstaller is a modern, modular, and secure web installation wizard designed to integrate seamlessly into any PHP project without rebuilding an installer flow from scratch.

## Key Features

* Centralized Configuration: The entire installation behavior is managed via a single configuration file (`install/installer_config.php`).
* Multi-Format Configuration Output: Supports generating traditional `config.php` files as well as `.env` files for modern frameworks.
* Dark and Light Theme Support: Clean and responsive user interface featuring an interactive theme switcher and automatic system preference detection.
* Standardized Icon Set: Built exclusively with Bootstrap Icons.
* Enhanced Security:
  * Secure administrator password hashing using PHP native `password_hash()` (default Bcrypt).
  * Protection against PHP code injection when writing configuration files.
  * Cryptographically secure key generation via `random_bytes()` and `random_int()`.
  * Automatic `install.lock` file generation upon installation completion.
* Smart Database Handler: Verifies database connectivity and allows automatic database creation if it does not exist, with custom port support.
* Automatic Environment Detection: Detects protocol (HTTP/HTTPS), domain name, custom port, and project base path automatically.

## System Requirements

* PHP 8.0.0 or higher
* MySQL 5.7+ or MariaDB 10.3+ database server
* Required PHP extensions: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `json`, `curl`
* Write permissions on the project root directory

## Integration Guide for New Projects

Integrating the installer into a new project requires three straightforward steps:

### 1. Copy the Installer Folder

Copy the `install/` directory into the root folder of your project:

```text
my-project/
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

### 2. Configure `install/installer_config.php`

Open `install/installer_config.php` and define the settings required by your project:

```php
return [
  // Application Details
  'app' => [
    'name'        => 'My Application',
    'version'     => '1.0.0',
    'author'      => 'Developer',
    'description' => 'Application setup wizard.',
  ],

  // System Requirements
  'requirements' => [
    'php_version'    => '8.1.0',
    'extensions'     => ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl'],
    'writable_paths' => ['../'],
  ],

  // Database Settings
  'database' => [
    'default_host'    => 'localhost',
    'default_port'    => 3306,
    'default_name'    => 'my_project_db',
    'default_user'    => 'root',
    'default_pass'    => '',
    'allow_create_db' => true,
    'sql_file'        => __DIR__ . '/db.sql',
  ],

  // Initial Administrator Account
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

  // Configuration File Output
  'config_file' => [
    'type'        => 'php', // 'php' or 'env'
    'template'    => __DIR__ . '/config.tpl',
    'destination' => dirname(__DIR__) . '/config.php',
  ],

  // Security Settings
  'security' => [
    'lock_file'          => __DIR__ . '/install.lock',
    'block_if_installed' => true,
  ],
];
```

### 3. Add Schema to `install/db.sql`

Paste the SQL tables, schema structure, and default records needed by your project into `install/db.sql`.

### 4. Run the Installation Wizard

Navigate to the installer path in your browser (e.g., `http://localhost/my-project/install/`) and follow the interactive wizard steps.

## Post-Installation Security

Once the setup is finished:
1. The installer automatically generates the `install/install.lock` file to prevent accidental restarts.
2. For production environments, it is recommended to delete or restrict web access to the `install/` directory.

## License

This project is open-sourced software licensed under the MIT License.
