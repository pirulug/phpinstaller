# PHPInstaller

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

PHPInstaller es una herramienta simple y eficiente para instalar y configurar proyectos PHP. Su objetivo es facilitar la creación y configuración de entornos PHP, asegurando que las dependencias y configuraciones necesarias estén en su lugar de manera automática.

## Características

- **Instalación automática de dependencias**: Configura y ejecuta Composer para gestionar las dependencias del proyecto.
- **Configuración del entorno**: Configura automáticamente los archivos `.env` según tus necesidades.
- **Soporte para múltiples versiones de PHP**: Compatible con PHP 8.0 y versiones superiores.
- **Fácil de usar**: Interfaz sencilla que guía al usuario a través de todo el proceso de instalación.

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/pirulug/phpinstaller.git
    ```

2. Navega al directorio del proyecto:

    ```bash
    cd phpinstaller
    ```

3. Ejecuta el instalador:

    ```bash
    php install.php
    ```

4. Sigue las instrucciones en pantalla para completar la instalación.

## Uso

### Instalación de un Proyecto PHP

Una vez que hayas clonado el repositorio y ejecutado el instalador, PHPInstaller se encargará de:

1. Instalar todas las dependencias de Composer.
2. Configurar el archivo `.env` basado en tus respuestas a las preguntas durante la instalación.
3. Crear las bases de datos necesarias y aplicar las migraciones si es necesario.

### Personalización

Puedes personalizar el proceso de instalación modificando el archivo `install.php` para adaptarlo a las necesidades específicas de tu proyecto.

```php
// Ejemplo de cómo modificar un paso del instalador
echo "Configurando el archivo .env...\n";
copy('.env.example', '.env');
```
