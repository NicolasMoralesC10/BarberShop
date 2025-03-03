<!-- ======= Sidebar ======= -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
      <img src="<?= media() ?>/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
      <span class="ms-1 text-sm text-dark">Creative Tim</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/dashboard.html">
          <i class="material-symbols-rounded opacity-5">dashboard</i>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/tables.html">
          <i class="material-symbols-rounded opacity-5">calendar_month</i>
          <span class="nav-link-text ms-1">Citas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/billing.html">
          <i class="material-symbols-rounded opacity-5">group</i>
          <span class="nav-link-text ms-1">Clientes</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/virtual-reality.html">
          <i class="material-symbols-rounded opacity-5">content_cut</i>
          <span class="nav-link-text ms-1">Servicios</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/rtl.html">
          <i class="material-symbols-rounded opacity-5">shopping_bag</i>
          <span class="nav-link-text ms-1">Productos</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/rtl.html">
          <i class="material-symbols-rounded opacity-5">point_of_sale</i>
          <span class="nav-link-text ms-1">Ventas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'empleados' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/empleados"">
          <i class=" material-symbols-rounded text-2xl opacity-7">person_apron</i>
          <span class="nav-link-text ms-1">Empleados</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/profile.html">
          <i class="material-symbols-rounded opacity-5">person</i>
          <span class="nav-link-text ms-1">Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/sign-in.html">
          <i class="material-symbols-rounded opacity-5">login</i>
          <span class="nav-link-text ms-1">Sign In</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/sign-up.html">
          <i class="material-symbols-rounded opacity-5">assignment</i>
          <span class="nav-link-text ms-1">Sign Up</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    <div class="mx-3">
      <a class="btn btn-outline-dark mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>

    </div>
  </div>
</aside><!-- End Sidebar-->