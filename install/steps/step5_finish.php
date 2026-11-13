<?php

$successMessage = "";
$errorMessages  = [];

$dbConfig     = $installerConfig['database'] ?? [];
$adminConfig  = $installerConfig['admin'] ?? [];
$fileConfig   = $installerConfig['config_file'] ?? [];
$secConfig    = $installerConfig['security'] ?? [];

$host      = $_SESSION['db_host'] ?? '';
$port      = $_SESSION['db_port'] ?? 3306;
$db_name   = $_SESSION['db_name'] ?? '';
$user      = $_SESSION['db_user'] ?? '';
$password  = $_SESSION['db_pass'] ?? '';
$site_name = $_SESSION['site_name'] ?? '';
$site_url  = $_SESSION['site_url'] ?? '';

// Validar que tengamos la información mínima
if (empty($host) || empty($db_name) || empty($user) || empty($site_name) || empty($site_url)) {
  $errorMessages[] = "Faltan datos de configuración en la sesión. Por favor regresa a los pasos anteriores y completa la información.";
} else {
  try {
    // 1. Generar claves de seguridad criptográficas
    $secret_key = bin2hex(random_bytes(16)); // 32 caracteres hex
    $secret_iv  = bin2hex(random_bytes(8));  // 16 caracteres hex

    // 2. Generar el archivo de configuración (.php o .env)
    $tplPath  = $fileConfig['template'] ?? (__DIR__ . '/../config.tpl');
    $destPath = $fileConfig['destination'] ?? (dirname(__DIR__) . '/config.php');

    if (!file_exists($tplPath)) {
      throw new Exception("No se encontró la plantilla de configuración en: {$tplPath}");
    }

    $templateContent = file_get_contents($tplPath);
    $placeholders = [
      '<DB_HOST>'     => escapeConfigValue($host),
      '<DB_PORT>'     => escapeConfigValue((string)$port),
      '<DB_NAME>'     => escapeConfigValue($db_name),
      '<DB_USER>'     => escapeConfigValue($user),
      '<DB_PASSWORD>' => escapeConfigValue($password),
      '<SITE_NAME>'   => escapeConfigValue($site_name),
      '<SITE_URL>'    => escapeConfigValue($site_url),
      '<SECRET_KEY>'  => escapeConfigValue($secret_key),
      '<SECRET_IV>'   => escapeConfigValue($secret_iv),
    ];

    $finalConfigContent = strtr($templateContent, $placeholders);

    if (@file_put_contents($destPath, $finalConfigContent) === false) {
      throw new Exception("No se pudo escribir el archivo de configuración en '{$destPath}'. Verifica los permisos de escritura del directorio.");
    }

    // 3. Conectar a la base de datos
    $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => true,
    ]);

    // 4. Ejecutar el script SQL
    $sqlFile = $dbConfig['sql_file'] ?? (__DIR__ . '/db.sql');
    if (file_exists($sqlFile)) {
      $sql = file_get_contents($sqlFile);
      if (!empty(trim($sql))) {
        $pdo->exec($sql);
      }
    }

    // 5. Crear usuario administrador
    if (!empty($adminConfig['create_admin'])) {
      $adminEmail = $_SESSION['admin_email'] ?? '';
      $adminUser  = $_SESSION['admin_user'] ?? '';
      $adminPass  = $_SESSION['admin_pass'] ?? '';

      if (empty($adminEmail) || empty($adminUser) || empty($adminPass)) {
        throw new Exception("Faltan las credenciales del usuario administrador.");
      }

      $hashMode = $adminConfig['password_hash'] ?? 'password_hash';
      if ($hashMode === 'encryption_class' && class_exists('Encryption')) {
        $finalPassword = Encryption::encrypt($adminPass, $secret_key, $secret_iv);
      } else {
        $finalPassword = password_hash($adminPass, PASSWORD_DEFAULT);
      }

      $table     = $adminConfig['table'] ?? 'users';
      $cols      = $adminConfig['columns'] ?? [];
      $userCol   = $cols['username'] ?? 'user_name';
      $emailCol  = $cols['email'] ?? 'user_email';
      $passCol   = $cols['password'] ?? 'user_password';
      $roleCol   = $cols['role'] ?? 'user_role';
      $statusCol = $cols['status'] ?? 'user_status';

      $defaultRole   = $adminConfig['default_role'] ?? 0;
      $defaultStatus = $adminConfig['default_status'] ?? 1;

      $insertSql = "INSERT INTO `{$table}` (`{$userCol}`, `{$emailCol}`, `{$passCol}`, `{$roleCol}`, `{$statusCol}`) 
                    VALUES (:username, :email, :password, :role, :status)";
      
      $stmt = $pdo->prepare($insertSql);
      $stmt->bindValue(':username', $adminUser, PDO::PARAM_STR);
      $stmt->bindValue(':email', $adminEmail, PDO::PARAM_STR);
      $stmt->bindValue(':password', $finalPassword, PDO::PARAM_STR);
      $stmt->bindValue(':role', $defaultRole, PDO::PARAM_INT);
      $stmt->bindValue(':status', $defaultStatus, PDO::PARAM_INT);
      $stmt->execute();
    }

    // 6. Archivo lock
    $lockFile = $secConfig['lock_file'] ?? (__DIR__ . '/install.lock');
    @file_put_contents($lockFile, "Instalado el: " . date('Y-m-d H:i:s') . "\n");

    session_unset();

    $successMessage = "¡Felicidades! La instalación se ha completado exitosamente.";
  } catch (PDOException $e) {
    $errorMessages[] = "Error de base de datos durante la instalación: " . $e->getMessage();
  } catch (Exception $e) {
    $errorMessages[] = $e->getMessage();
  }
}

include 'includes/header.php';
?>

<div>
  <h2 class="step-heading">Resultado de la Instalación</h2>
  <p class="step-subheading">Comprobación del proceso de despliegue y configuración.</p>

  <?php if (!empty($errorMessages)): ?>
    <div class="installer-alert installer-alert-danger">
      <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
      <div>
        <div class="fw-bold mb-1">Se encontraron errores durante la instalación:</div>
        <ul class="mb-0 ps-3">
          <?php foreach ($errorMessages as $message): ?>
            <li><?= htmlspecialchars($message); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="mt-4">
      <a href="index.php?step=4" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Regresar y corregir datos</span>
      </a>
    </div>
  <?php else: ?>
    <div class="installer-alert installer-alert-success mb-4 p-4">
      <i class="bi bi-check-circle-fill text-success fs-1 mt-1"></i>
      <div>
        <h4 class="h5 fw-bold mb-1 text-success"><?= htmlspecialchars($successMessage); ?></h4>
        <p class="mb-0 text-secondary">La base de datos y los archivos de configuración han sido establecidos correctamente.</p>
      </div>
    </div>

    <div class="installer-alert installer-alert-warning mb-4">
      <i class="bi bi-shield-lock-fill fs-5 mt-1"></i>
      <div>
        <div class="fw-bold mb-1">Recomendación de seguridad:</div>
        <span>Se ha generado el archivo de protección <code>install.lock</code>. Para mayor seguridad en entornos de producción, te sugerimos eliminar o renombrar la carpeta <code>/install</code>.</span>
      </div>
    </div>

    <div class="mt-4 d-flex justify-content-end">
      <a href="../" class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2">
        <span>Ir a la Aplicación</span>
        <i class="bi bi-box-arrow-up-right"></i>
      </a>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
