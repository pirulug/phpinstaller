<?php

$adminConfig = $installerConfig['admin'] ?? [];
$createAdmin = $adminConfig['create_admin'] ?? true;

$detectedUrl = detectBaseUrl();
$defaultSiteName = $_SESSION['site_name'] ?? ($installerConfig['app']['name'] ?? 'Mi Aplicación');
$defaultSiteUrl  = $_SESSION['site_url'] ?? $detectedUrl;

$defaultAdminEmail = $_SESSION['admin_email'] ?? 'admin@admin.com';
$defaultAdminUser  = $_SESSION['admin_user'] ?? 'admin';
$defaultAdminPass  = $_SESSION['admin_pass'] ?? '';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_name   = trim($_POST['site_name'] ?? '');
  $site_url    = trim($_POST['site_url'] ?? '');
  $admin_email = trim($_POST['admin_email'] ?? '');
  $admin_user  = trim($_POST['admin_user'] ?? '');
  $admin_pass  = $_POST['admin_pass'] ?? '';
  $admin_pass_confirm = $_POST['admin_pass_confirm'] ?? '';

  if (empty($site_name) || empty($site_url)) {
    $errorMessage = 'Por favor completa el nombre y la URL del sitio.';
  } elseif ($createAdmin && (empty($admin_email) || empty($admin_user) || empty($admin_pass))) {
    $errorMessage = 'Por favor completa todos los campos del administrador.';
  } elseif ($createAdmin && $admin_pass !== $admin_pass_confirm) {
    $errorMessage = 'Las contraseñas del administrador no coinciden.';
  } elseif ($createAdmin && !filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
    $errorMessage = 'Por favor ingresa un correo electrónico válido para el administrador.';
  } else {
    $_SESSION['site_name']   = $site_name;
    $_SESSION['site_url']    = rtrim($site_url, '/');
    if ($createAdmin) {
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['admin_user']  = $admin_user;
      $_SESSION['admin_pass']  = $admin_pass;
    }

    header('Location: index.php?step=5');
    exit;
  }
}

include 'includes/header.php';
?>

<div>
  <h2 class="step-heading">Configuración de la Aplicación</h2>
  <p class="step-subheading">Define los parámetros del sitio web y los accesos iniciales de administración.</p>

  <?php if (!empty($errorMessage)): ?>
    <div class="installer-alert installer-alert-danger">
      <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
      <div><?= htmlspecialchars($errorMessage); ?></div>
    </div>
  <?php endif; ?>

  <form method="post">
    <!-- Parámetros Generales -->
    <div class="installer-section">
      <div class="installer-section-header">
        <i class="bi bi-globe text-primary"></i>
        <span>Datos del Sitio</span>
      </div>
      <div class="installer-section-body">
        <div class="mb-3">
          <label class="form-label">Nombre del Sitio / Proyecto:</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-window"></i></span>
            <input class="form-control" type="text" name="site_name" value="<?= htmlspecialchars($defaultSiteName); ?>" required>
          </div>
        </div>
        <div class="mb-0">
          <label class="form-label">URL Base del Sitio:</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
            <input class="form-control" type="url" name="site_url" value="<?= htmlspecialchars($defaultSiteUrl); ?>" required>
          </div>
          <small class="form-text text-muted mt-1 d-block">Detectada automáticamente. Asegúrate de que no incluya la carpeta <code>/install</code>.</small>
        </div>
      </div>
    </div>

    <!-- Parámetros del Administrador (si aplica) -->
    <?php if ($createAdmin): ?>
      <div class="installer-section">
        <div class="installer-section-header">
          <i class="bi bi-shield-lock text-primary"></i>
          <span>Cuenta del Administrador Principal</span>
        </div>
        <div class="installer-section-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Usuario Administrador:</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input class="form-control" type="text" name="admin_user" value="<?= htmlspecialchars($defaultAdminUser); ?>" required>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Correo Electrónico:</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input class="form-control" type="email" name="admin_email" value="<?= htmlspecialchars($defaultAdminEmail); ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Contraseña:</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input class="form-control" type="password" name="admin_pass" value="<?= htmlspecialchars($defaultAdminPass); ?>" required>
              </div>
            </div>
            <div class="col-md-6 mb-0">
              <label class="form-label">Confirmar Contraseña:</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                <input class="form-control" type="password" name="admin_pass_confirm" value="<?= htmlspecialchars($defaultAdminPass); ?>" required>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <a href="index.php?step=3" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        <span>Anterior</span>
      </a>
      <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <span>Finalizar Instalación</span>
        <i class="bi bi-check-circle"></i>
      </button>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
