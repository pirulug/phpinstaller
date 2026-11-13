<?php

$dbConfig      = $installerConfig['database'] ?? [];
$allowCreateDb = $dbConfig['allow_create_db'] ?? true;

$defaultHost = $_SESSION['db_host'] ?? ($dbConfig['default_host'] ?? 'localhost');
$defaultPort = $_SESSION['db_port'] ?? ($dbConfig['default_port'] ?? 3306);
$defaultName = $_SESSION['db_name'] ?? ($dbConfig['default_name'] ?? 'mi_proyecto_db');
$defaultUser = $_SESSION['db_user'] ?? ($dbConfig['default_user'] ?? 'root');
$defaultPass = $_SESSION['db_pass'] ?? ($dbConfig['default_pass'] ?? '');

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db_host   = trim($_POST['db_host'] ?? 'localhost');
  $db_port   = (int)($_POST['db_port'] ?? 3306);
  $db_name   = trim($_POST['db_name'] ?? '');
  $db_user   = trim($_POST['db_user'] ?? '');
  $db_pass   = $_POST['db_pass'] ?? '';
  $create_db = !empty($_POST['create_db']);

  if (empty($db_host) || empty($db_name) || empty($db_user)) {
    $errorMessage = 'Por favor completa todos los campos requeridos.';
  } else {
    try {
      $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
      $pdo = @new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
      ]);

      $_SESSION['db_host'] = $db_host;
      $_SESSION['db_port'] = $db_port;
      $_SESSION['db_name'] = $db_name;
      $_SESSION['db_user'] = $db_user;
      $_SESSION['db_pass'] = $db_pass;

      header('Location: index.php?step=4');
      exit;
    } catch (PDOException $e) {
      if ($e->getCode() == 1049 && $create_db) {
        try {
          $serverDsn = "mysql:host={$db_host};port={$db_port};charset=utf8mb4";
          $serverPdo = new PDO($serverDsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
          ]);

          $sanitizedDbName = preg_replace('/[^a-zA-Z0-9_]/', '', $db_name);
          $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$sanitizedDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

          $_SESSION['db_host'] = $db_host;
          $_SESSION['db_port'] = $db_port;
          $_SESSION['db_name'] = $sanitizedDbName;
          $_SESSION['db_user'] = $db_user;
          $_SESSION['db_pass'] = $db_pass;

          header('Location: index.php?step=4');
          exit;
        } catch (PDOException $createEx) {
          $errorMessage = "No se pudo crear automáticamente la base de datos '{$db_name}'. Verifica que el usuario tenga privilegios o créala manualmente en phpMyAdmin. Error: " . $createEx->getMessage();
        }
      } elseif ($e->getCode() == 1049) {
        $errorMessage = "La base de datos '{$db_name}' no existe. Puedes marcar la casilla de abajo para crearla automáticamente o verificar el nombre.";
      } elseif ($e->getCode() == 1045) {
        $errorMessage = "Acceso denegado: El usuario o la contraseña de la base de datos son incorrectos.";
      } elseif ($e->getCode() == 2002) {
        $errorMessage = "No se puede establecer conexión con el servidor MySQL en '{$db_host}:{$db_port}'. Asegúrate de que MySQL esté iniciado.";
      } else {
        $errorMessage = "Error de conexión: " . $e->getMessage();
      }
    }
  }
}

include 'includes/header.php';
?>

<div>
  <h2 class="step-heading">Configuración de la Base de Datos</h2>
  <p class="step-subheading">Ingresa los parámetros de conexión a tu servidor MySQL o MariaDB.</p>

  <?php if (!empty($errorMessage)): ?>
    <div class="installer-alert installer-alert-danger">
      <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
      <div><?= htmlspecialchars($errorMessage); ?></div>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="row">
      <div class="col-md-8 mb-3">
        <label class="form-label">Servidor / Host:</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-hdd-network"></i></span>
          <input class="form-control" type="text" name="db_host" value="<?= htmlspecialchars($defaultHost); ?>" required>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Puerto:</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-hash"></i></span>
          <input class="form-control" type="number" name="db_port" value="<?= htmlspecialchars((string)$defaultPort); ?>" required>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Nombre de la Base de Datos:</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-database"></i></span>
        <input class="form-control" type="text" name="db_name" value="<?= htmlspecialchars($defaultName); ?>" required>
      </div>
      <?php if ($allowCreateDb): ?>
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" name="create_db" id="create_db" value="1" checked>
          <label class="form-check-label text-muted small" for="create_db">
            Crear la base de datos automáticamente si aún no existe
          </label>
        </div>
      <?php endif; ?>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Usuario de la Base de Datos:</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input class="form-control" type="text" name="db_user" value="<?= htmlspecialchars($defaultUser); ?>" required>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <label class="form-label">Contraseña:</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-key"></i></span>
          <input class="form-control" type="password" name="db_pass" value="<?= htmlspecialchars($defaultPass); ?>" placeholder="(Dejar vacío si no tiene)">
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <a href="index.php?step=2" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Anterior</span>
      </a>
      <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <span>Siguiente</span>
        <i class="bi bi-arrow-right"></i>
      </button>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
