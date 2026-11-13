          </div> <!-- col-md-9 -->
        </div> <!-- row -->
      </div> <!-- installer-card-body -->
    </div> <!-- installer-card -->

    <footer class="installer-footer">
      <p class="m-0">&copy; <?= date('Y'); ?> <?= htmlspecialchars($appName ?? 'PHPInstaller'); ?> &bull; Todos los derechos reservados.</p>
    </footer>
  </div> <!-- installer-wrapper -->

  <!-- Script de Control del Tema (Dark / Light) -->
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

      // Inicializar estado del botón
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

      // Escuchar cambios del sistema operativo si no se ha guardado una preferencia
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!localStorage.getItem('installer_theme')) {
          const newTheme = e.matches ? 'dark' : 'light';
          document.documentElement.setAttribute('data-theme', newTheme);
          updateToggleUI(newTheme);
        }
      });
    })();
  </script>
</body>

</html>
