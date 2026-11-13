# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-11-13

### Added
* Centralized installer configuration file (`install/installer_config.php`) to configure new projects without editing core logic.
* Multi-format environment output supporting `.env` (`install/env.tpl`) alongside classic `config.php`.
* Native Dark and Light theme support in `install/assets/css/style.css` with automatic OS preference detection and manual toggle saved in `localStorage`.
* Anti-FOUC inline theme script in header to prevent white screen flash.
* Automatic database creation capability with interactive checkbox during connection setup.
* Support for custom MySQL/MariaDB ports (`db_port`).
* Intelligent base URL auto-detection (`detectBaseUrl`) supporting HTTPS, custom ports, and subdirectories.
* Post-installation lock file mechanism (`install/install.lock`).
* Step navigation with Previous/Next buttons and session state persistence.
* Bilingual independent documentation: `README.md` (English) and `README.es.md` (Spanish).

### Changed
* Standardized icon library entirely to Bootstrap Icons (`bi bi-*`).
* Refactored installation wizard steps (`step1` to `step5`) into decoupled, modular components.
* Upgraded secret key generator to use cryptographically secure functions (`random_bytes` and `random_int`).

### Security
* Replaced reversible encryption for admin passwords with industry-standard `password_hash()` (Bcrypt).
* Added sanitization and escaping for configuration values to prevent PHP code injection in generated config files.
* Added automatic installer execution lockout when already installed.

### Fixed
* Fixed typo in navigation header ("Bienvenido", "Configuracion").
* Fixed incorrect validation error message for site name in final step.
* Fixed duplicated session variable assignment in step 4.

### Removed
* Removed FontAwesome stylesheet and related font assets.
* Removed deprecated legacy `app.css` bundle in favor of optimized `style.css`.
