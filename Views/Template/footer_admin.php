<footer class="footer py-4  ">
  <div class="container-fluid">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="copyright text-center text-sm text-muted text-lg-start">
          © <script>
            document.write(new Date().getFullYear())
          </script>,
          <i class="fa fa-heart"></i> por
          <a href="" class="font-weight-bold" target="_blank">Binary Dreamers</a>
        </div>
      </div>
   <!--    <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Binary Dreamers</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
          </li>
        </ul>
      </div> -->
    </div>
  </div>
</footer>
</div>
</main>
<!-- ======= Footer ======= -->

<!--   Core JS Files   -->
<script src="<?= media() ?>/js/core/popper.min.js"></script>
<script src="<?= media() ?>/js/core/bootstrap.min.js"></script>
<script src="<?= media() ?>/js/plugins/perfect-scrollbar.min.js"></script>
<script src="<?= media() ?>/js/plugins/smooth-scrollbar.min.js"></script>
<script src="<?= media() ?>/js/plugins/chartjs.min.js"></script>
<script src="<?= media() ?>/vendor/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= media() ?>/vendor/jquery/jquery-ui.js"></script>
<script src="<?= media() ?>/vendor/datatables/datatables.min.js"></script>
<script src="<?= media() ?>/vendor/fullcalendar/dist/index.global.min.js"></script>
<script src="<?= media() ?>/vendor/sweetalert/sweetalert2.all.min.js"></script>
<script src="<?= media() ?>/vendor/flatpickr/flatpickr.js"></script>
<script src="<?= media() ?>/vendor/tom-select/tom-select.complete.min.js"></script>


<!-- DataTables JS y Botones -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= media() ?>/js/material-dashboard.min.js?v=3.2.0"></script>

<!-- PDFMake (para exportar PDF) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script> -->

<script>
  let base_url = '<?= BASE_URL ?>'
</script>
<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>
<?php if (isset($data['script'])): ?>
  <script src="<?= media() ?>/js/modules/<?= $data['script'] ?>.js"></script>
<?php endif; ?>
</body>

</html>