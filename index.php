<?php

if (!file_exists(__DIR__ . '/config.php') && !file_exists(__DIR__ . '/.env')) {
  header('Location: install/');
  exit;
}

if (file_exists(__DIR__ . '/config.php')) {
  require_once __DIR__ . '/config.php';
}

$siteName = defined('SITE_NAME') ? SITE_NAME : (getenv('APP_NAME') ?: 'Mi Proyecto');
$siteUrl  = defined('SITE_URL') ? SITE_URL : (getenv('APP_URL') ?: '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($siteName) ?></title>
  
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

  <link rel="stylesheet" href="install/assets/css/bootstrapicons.css">
  <link rel="stylesheet" href="install/assets/css/style.css">
</head>
<body>
  <div class="installer-wrapper text-center py-5">
    <div class="installer-topbar">
      <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Cambiar tema">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
        <span id="themeLabel">Modo Oscuro</span>
      </button>
    </div>

    <div class="installer-card p-5 mx-auto mt-3" style="max-width: 620px;">
      <div class="mb-3">
        <i class="bi bi-rocket-takeoff text-primary" style="font-size: 3.5rem;"></i>
      </div>
      <h1 class="h3 fw-bold mb-2"><?= htmlspecialchars($siteName) ?></h1>
      <p class="text-muted mb-4">¡Tu aplicación ha sido instalada y configurada exitosamente!</p>
      
      <div class="installer-alert installer-alert-success justify-content-center text-start mb-0">
        <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
        <div>El archivo de configuración y la base de datos están listos para comenzar el desarrollo.</div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const toggleBtn = document.getElementById('themeToggleBtn');
      const themeIcon = document.getElementById('themeIcon');
      const themeLabel = document.getElementById('themeLabel');

      function updateToggleUI(theme) {
        if (!themeIcon || !themeLabel) return;
        if (theme === 'dark') {
          themeIcon.className = 'bi bi-sun-fill text-warning';
          themeLabel.textContent = 'Modo Claro';
        } else {
          themeIcon.className = 'bi bi-moon-stars';
          themeLabel.textContent = 'Modo Oscuro';
        }
      }

      function getCurrentTheme() {
        return document.documentElement.getAttribute('data-theme') ||
          (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      }

      updateToggleUI(getCurrentTheme());

      if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
          const current = getCurrentTheme();
          const target = current === 'dark' ? 'light' : 'dark';
          document.documentElement.setAttribute('data-theme', target);
          localStorage.setItem('installer_theme', target);
          updateToggleUI(target);
        });
      }
    })();
  </script>
</body>
</html>
