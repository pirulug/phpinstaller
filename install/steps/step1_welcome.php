<?php
include 'includes/header.php';

$appDesc = $installerConfig['app']['description'] ?? 'Este asistente le guiará a través del proceso de instalación y configuración de su sistema.';
?>

<div>
  <h2 class="step-heading">Bienvenido al asistente de instalación</h2>
  <p class="step-subheading"><?= htmlspecialchars($appDesc) ?></p>

  <div class="installer-alert installer-alert-info">
    <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
    <div>
      <div class="fw-bold mb-1">El asistente realizará las siguientes acciones:</div>
      <ul class="mb-0 ps-3">
        <li>Verificar la compatibilidad del servidor y permisos de archivos.</li>
        <li>Conectar y preparar la base de datos (con opción de crearla automáticamente).</li>
        <li>Importar las tablas iniciales del sistema.</li>
        <li>Configurar las credenciales de la cuenta administradora.</li>
        <li>Generar el archivo de configuración final de manera segura.</li>
      </ul>
    </div>
  </div>

  <div class="mt-4 pt-3 d-flex justify-content-end">
    <a href="index.php?step=2" class="btn btn-primary d-inline-flex align-items-center gap-2">
      <span>Comenzar</span>
      <i class="bi bi-arrow-right"></i>
    </a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
