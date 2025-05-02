<!-- ======= Sidebar ======= -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="" target="_blank">
      <img src="<?= media() ?>/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
      <span class="ms-2 text-sm text-dark">Binary Dreamers</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'dashboard' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/dashboard">
          <span class="material-symbols-rounded opacity-5" translate="no">dashboard</span>
          <span class="nav-link-text ms-2">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'citas' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/citas">
        <span class="material-symbols-rounded opacity-5" translate="no">calendar_month</span>
          <span class="nav-link-text ms-2">Citas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'clientes' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/clientes">
          <span class="material-symbols-rounded opacity-5" translate="no">group</span>
          <span class="nav-link-text ms-2">Clientes</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'servicios' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/servicios">
          <span class="material-symbols-rounded opacity-5" translate="no">content_cut</span>
          <span class="nav-link-text ms-2">Servicios</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'productos' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/productos">
          <span class="material-symbols-rounded opacity-5" translate="no">shopping_bag</span>
          <span class="nav-link-text ms-2">Productos</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'ventas' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/ventas">
          <span class="material-symbols-rounded opacity-5" translate="no">point_of_sale</span>
          <span class="nav-link-text ms-2">Ventas</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $data['page_name'] != 'Empleados' ? 'text-dark' : 'active bg-gradient-dark text-white' ?> " href="<?= base_url() ?>/empleados">
          <span class=" material-symbols-rounded text-2xl opacity-6" translate="no">person_apron</span>
          <span class="nav-link-text ms-2">Empleados</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/profile.html">
          <span class="material-symbols-rounded opacity-5" translate="no">person</span>
          <span class="nav-link-text ms-2">Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/sign-in.html">
          <span class="material-symbols-rounded opacity-5" translate="no">login</span>
          <span class="nav-link-text ms-2">Sign In</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark" href="../pages/sign-up.html">
          <span class="material-symbols-rounded opacity-5" translate="no">assignment</span>
          <span class="nav-link-text ms-2">Sign Up</span>
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
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">