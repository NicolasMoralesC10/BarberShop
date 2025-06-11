<footer class="footer position-absolute bottom-2 py-2 w-100">
    <div class="container">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
                <div class="copyright text-center text-sm text-white text-lg-start">
                    © <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    made with <i class="fa fa-heart" aria-hidden="true"></i> by
                    <a href="" class="font-weight-bold text-white" target="_blank">Binary Dreamers</a>
                    for a better web.
                </div>
            </div>
          <!--   <div class="col-12 col-md-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com" class="nav-link text-white" target="_blank">Creative Tim</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/presentation" class="nav-link text-white" target="_blank">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/blog" class="nav-link text-white" target="_blank">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-white" target="_blank">License</a>
                    </li>
                </ul>
            </div> -->
        </div>
    </div>
</footer>
<script>
    let base_url = '<?= BASE_URL ?>'
  </script>
<!--   Core JS Files   -->
<script src="<?= media() ?>/vendor/sweetalert/sweetalert2.all.min.js"></script>
<script src="<?= media() ?>/js/core/popper.min.js"></script>
<script src="<?= media() ?>/js/core/bootstrap.min.js"></script>
<script src="<?= media() ?>/js/plugins/perfect-scrollbar.min.js"></script>
<script src="<?= media() ?>/js/plugins/smooth-scrollbar.min.js"></script>
<script src="<?= media() ?>/js/modules/login.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= media() ?>/js/material-dashboard.min.js?v=3.2.0"></script>
