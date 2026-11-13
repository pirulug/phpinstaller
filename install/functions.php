<?php

/**
 * Obtiene la configuración del instalador.
 *
 * @return array
 */
function getInstallerConfig(): array {
  static $config = null;
  if ($config === null) {
    $configFile = __DIR__ . '/installer_config.php';
    if (file_exists($configFile)) {
      $config = require $configFile;
    } else {
      $config = [];
    }
  }
  return $config;
}

/**
 * Genera una cadena aleatoria criptográficamente segura.
 *
 * @param string $tipo 'mixto', 'numeros', 'letras'
 * @param bool $mayusculas
 * @param int $longitud
 * @param bool $incluirEspeciales
 * @return string
 */
function generarCadenaAleatoria(string $tipo = 'mixto', bool $mayusculas = false, int $longitud = 16, bool $incluirEspeciales = false): string {
  $numeros          = '0123456789';
  $letrasMinusculas = 'abcdefghijklmnopqrstuvwxyz';
  $especiales       = '!@#$%^&*()-_=+[]{}|;:,.<>?';

  if ($tipo === 'numeros') {
    $caracteres = $numeros;
  } elseif ($tipo === 'letras') {
    $caracteres = $letrasMinusculas;
  } else {
    $caracteres = $numeros . $letrasMinusculas;
  }

  if ($mayusculas) {
    $caracteres .= strtoupper($caracteres);
  }

  if ($incluirEspeciales) {
    $caracteres .= $especiales;
  }

  $maxIndex = strlen($caracteres) - 1;
  $cadena   = '';

  for ($i = 0; $i < $longitud; $i++) {
    try {
      $cadena .= $caracteres[random_int(0, $maxIndex)];
    } catch (\Throwable $e) {
      $cadena .= $caracteres[rand(0, $maxIndex)];
    }
  }

  return $cadena;
}

/**
 * Detecta inteligentemente la URL base donde está corriendo la aplicación.
 *
 * @return string
 */
function detectBaseUrl(): string {
  $isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
  );

  $protocol = $isHttps ? 'https://' : 'http://';
  $host     = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');

  // Detectar la ruta base eliminando la subcarpeta /install
  $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
  // Normalizar separadores
  $scriptDir = str_replace('\\', '/', $scriptDir);
  // Si termina en /install, quitarlo
  $basePath  = preg_replace('#/install$#', '', $scriptDir);
  $basePath  = rtrim($basePath, '/');

  return rtrim($protocol . $host . $basePath, '/');
}

/**
 * Comprueba si la aplicación ya se encuentra instalada.
 *
 * @param array $config
 * @return bool
 */
function isAlreadyInstalled(array $config): bool {
  $lockFile = $config['security']['lock_file'] ?? (__DIR__ . '/install.lock');
  $destFile = $config['config_file']['destination'] ?? (dirname(__DIR__) . '/config.php');

  return file_exists($lockFile) || file_exists($destFile);
}

/**
 * Escapa un valor para ser incrustado de manera segura en un archivo PHP o ENV.
 *
 * @param string $value
 * @return string
 */
function escapeConfigValue(string $value): string {
  return addcslashes($value, "\\'\"\$\n\r\t");
}
