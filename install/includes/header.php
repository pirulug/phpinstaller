<?php
if (!isset($installerConfig)) {
  $installerConfig = getInstallerConfig();
}
$appName    = $installerConfig['app']['name'] ?? 'PHPInstaller';
$appVersion = $installerConfig['app']['version'] ?? '1.0';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($appName) ?> - Asistente de Instalación</title>
  
  <!-- Script preventivo anti-parpadeo de tema (Dark/Light) -->
  <script>
    (function () {
      const savedTheme = localStorage.getItem('installer_theme');
      if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
      } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    })();
  </script>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="assets/css/bootstrapicons.css" />
  <!-- Custom Modern Theme -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <div class="installer-wrapper">
    <!-- Barra superior con Switch de Tema (Dark / Light) -->
    <div class="installer-topbar">
      <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Cambiar tema">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
        <span id="themeLabel">Modo Oscuro</span>
      </button>
    </div>

    <!-- Encabezado Principal -->
    <header class="installer-header">
      <div class="installer-title">
        <i class="bi bi-box-seam-fill text-primary"></i>
        <span><?= htmlspecialchars($appName) ?></span>
      </div>
      <p class="installer-subtitle">Asistente de Instalación v<?= htmlspecialchars($appVersion) ?></p>
    </header>

    <!-- Tarjeta Principal del Instalador -->
    <div class="installer-card">
      <div class="installer-card-body">
        <div class="row">
          <!-- Menú Lateral de Pasos (Stepper) -->
          <div class="col-md-3 installer-steps-sidebar">
            <ul class="installer-steps-nav">
              <li class="nav-item">
                <a href="?step=1" class="nav-link <?= $currentStep === 1 ? 'active' : '' ?>">
                  <i class="bi bi-house-door"></i>
                  <span>1. Bienvenido</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="?step=2" class="nav-link <?= $currentStep === 2 ? 'active' : '' ?>">
                  <i class="bi bi-clipboard-check"></i>
                  <span>2. Requisitos</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="?step=3" class="nav-link <?= $currentStep === 3 ? 'active' : '' ?>">
                  <i class="bi bi-database"></i>
                  <span>3. Base de Datos</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="?step=4" class="nav-link <?= $currentStep === 4 ? 'active' : '' ?>">
                  <i class="bi bi-sliders"></i>
                  <span>4. Configuración</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="?step=5" class="nav-link <?= $currentStep === 5 ? 'active' : '' ?>">
                  <i class="bi bi-check2-circle"></i>
                  <span>5. Finalizar</span>
                </a>
              </li>
            </ul>
          </div>

          <!-- Contenido del Paso Actual -->
          <div class="col-md-9 ps-md-4">
