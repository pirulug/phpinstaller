<?php

$reqConfig          = $installerConfig['requirements'] ?? [];
$requiredPHPVersion = $reqConfig['php_version'] ?? '8.0.0';
$requiredExtensions = $reqConfig['extensions'] ?? ['pdo', 'pdo_mysql', 'mbstring'];
$writablePaths      = $reqConfig['writable_paths'] ?? ['../'];

$phpVersionOk     = version_compare(PHP_VERSION, $requiredPHPVersion, '>=');
$extensionResults = [];
foreach ($requiredExtensions as $ext) {
  $extensionResults[$ext] = extension_loaded($ext);
}
$allExtensionsOk = !in_array(false, $extensionResults, true);

$writableResults = [];
foreach ($writablePaths as $path) {
  $fullPath = __DIR__ . '/../' . ltrim($path, '/');
  if ($path === '../' || $path === '..') {
    $fullPath = dirname(__DIR__);
  }
  $isWritable = is_writable($fullPath);
  $writableResults[$path] = [
    'exists'   => file_exists($fullPath),
    'writable' => $isWritable,
  ];
}
$allWritableOk = true;
foreach ($writableResults as $res) {
  if (!$res['writable']) {
    $allWritableOk = false;
    break;
  }
}

$canProceed = ($phpVersionOk && $allExtensionsOk && $allWritableOk);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($canProceed) {
    header('Location: index.php?step=3');
    exit;
  } else {
    $error = 'Por favor, asegúrate de cumplir todos los requisitos antes de continuar.';
  }
}

include 'includes/header.php';
?>

<div>
  <h2 class="step-heading">Verificación de Requisitos del Sistema</h2>
  <p class="step-subheading">Comprobando compatibilidad con la versión de PHP, extensiones y permisos de escritura.</p>

  <form method="post">
    <!-- Versión de PHP -->
    <div class="installer-section">
      <div class="installer-section-header">
        <i class="bi bi-cpu text-primary"></i>
        <span>Versión de PHP</span>
      </div>
      <div class="installer-section-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span>Versión instalada: <strong><?= PHP_VERSION; ?></strong></span>
            <span class="text-muted small ms-1">(Mínima requerida: <?= htmlspecialchars($requiredPHPVersion); ?>)</span>
          </div>
          <?php if ($phpVersionOk): ?>
            <span class="badge-status badge-status-success">
              <i class="bi bi-check-circle-fill"></i>
              <span>Compatible</span>
            </span>
          <?php else: ?>
            <span class="badge-status badge-status-danger">
              <i class="bi bi-x-circle-fill"></i>
              <span>Incompatible</span>
            </span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Extensiones Requeridas -->
    <div class="installer-section">
      <div class="installer-section-header">
        <i class="bi bi-puzzle text-primary"></i>
        <span>Extensiones PHP Requeridas</span>
      </div>
      <ul class="list-group list-group-flush installer-list-group">
        <?php foreach ($extensionResults as $extension => $loaded): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="font-monospace"><?= htmlspecialchars($extension); ?></span>
            <?php if ($loaded): ?>
              <span class="badge-status badge-status-success">
                <i class="bi bi-check-lg"></i>
                <span>Habilitada</span>
              </span>
            <?php else: ?>
              <span class="badge-status badge-status-danger">
                <i class="bi bi-x-lg"></i>
                <span>Deshabilitada</span>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Permisos de Escritura -->
    <div class="installer-section">
      <div class="installer-section-header">
        <i class="bi bi-folder2-open text-primary"></i>
        <span>Permisos de Escritura en Directorios</span>
      </div>
      <ul class="list-group list-group-flush installer-list-group">
        <?php foreach ($writableResults as $path => $status): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="font-monospace"><?= htmlspecialchars($path); ?></span>
            <?php if ($status['writable']): ?>
              <span class="badge-status badge-status-success">
                <i class="bi bi-check-lg"></i>
                <span>Escribible</span>
              </span>
            <?php else: ?>
              <span class="badge-status badge-status-danger">
                <i class="bi bi-x-lg"></i>
                <span>Sin permisos</span>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <?php if (isset($error)): ?>
      <div class="installer-alert installer-alert-danger">
        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
        <div><?= htmlspecialchars($error); ?></div>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <a href="index.php?step=1" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Anterior</span>
      </a>
      <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" <?= !$canProceed ? 'disabled' : '' ?>>
        <span>Siguiente</span>
        <i class="bi bi-arrow-right"></i>
      </button>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
