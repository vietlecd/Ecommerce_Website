<?php
if (defined('ADMIN_LAYOUT_FOOTER_RENDERED')) {
    return;
}
define('ADMIN_LAYOUT_FOOTER_RENDERED', true);
?>
          </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="https://tabler.io" target="_blank" rel="noopener noreferrer">Built with Tabler</a></li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-secondary">
                &copy; <?php echo date('Y'); ?> V.AShoes Admin Panel
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
  </body>
</html>
