<?php
session_start();

require_once __DIR__ . '/libs/Encryption.php';
require_once __DIR__ . '/functions.php';

$installerConfig = getInstallerConfig();
$encryption      = new Encryption();

// Bloqueo de seguridad si la aplicación ya está instalada
if (!empty($installerConfig['security']['block_if_installed']) && isAlreadyInstalled($installerConfig)) {
  header('Location: ../');
  exit;
}

$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
if ($currentStep < 1 || $currentStep > 5) {
  $currentStep = 1;
}

switch ($currentStep) {
  case 1:
    include __DIR__ . '/steps/step1_welcome.php';
    break;
  case 2:
    include __DIR__ . '/steps/step2_requirements.php';
    break;
  case 3:
    include __DIR__ . '/steps/step3_database.php';
    break;
  case 4:
    include __DIR__ . '/steps/step4_siteconfig.php';
    break;
  case 5:
    include __DIR__ . '/steps/step5_finish.php';
    break;
  default:
    include __DIR__ . '/steps/step1_welcome.php';
    break;
}
