            
            </div>
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    © ITBOY , made with <span class="text-danger"><i class="tf-icons mdi mdi-heart"></i></span>
                    <a target="_blank" class="footer-link fw-medium"></a>
                  </div>

                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/back/vendor/js/core.js -->
    <script src="../assets/back/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/back/vendor/libs/popper/popper.js"></script>
    <script src="../assets/back/vendor/js/bootstrap.js"></script>
    <script src="../assets/back/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/back/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/back/vendor/libs/hammer/hammer.js"></script>
    <script src="../assets/back/vendor/libs/i18n/i18n.js"></script>
    <script src="../assets/back/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="../assets/back/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/back/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../assets/back/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="../assets/back/vendor/libs/select2/select2.js"></script>
    <script src="../assets/back/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="../assets/back/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <!-- Main JS -->
    <script src="../assets/back/js/main.js"></script>

    <!-- Page JS -->


    <script src="../assets/back/js/forms-selects.js"></script>
    <script src="../assets/back/vendor/libs/quill/katex.js"></script>
    <script src="../assets/back/vendor/libs/quill/quill.js"></script>
    <link rel="stylesheet" href="../assets/back/vendor/libs/toastr/toastr.js" />

    <script src="../assets/back/js/forms-editors.js"></script>

    <script src="../assets/back/js/tables-datatables-basic.js"></script>
    <script>
        $(document).ready(function() {
          // Tampilkan toast
          $('#toast-container').show();

          // Atur timeout untuk menutup toast setelah 2 detik (2000 ms)
          setTimeout(function() {
            $('#toast-container').fadeOut('slow', function() {
              $(this).remove();
            });
          }, 2000);
        });
      </script>
  </body>
</html>
