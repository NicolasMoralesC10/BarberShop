<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'dashboard' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/dashboard">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Pagina x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-person-x"></i>
        <span>x</span>
      </a>
    </li>

    <!-- Gestion de x -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>x</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
            <i class="bi bi-circle"></i>
            <span>x</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url() ?>/x">
            <i class="bi bi-circle"></i><span> x</span>
          </a>
        </li>
      </ul>
    </li><!-- End Gestion de x -->

    <!-- Pagina Gestion de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-journals"></i>
        <span>x</span>
      </a>
    </li>


    <!-- Pagina Gestion de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-person-x"></i>
        <span>x</span>
      </a>
    </li>



    <!-- Pagina Gestion de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-person-x"></i>
        <span>x</span>
      </a>
    </li>



    <!-- Pagina de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-people"></i>
        <span>x</span>
      </a>
    </li>



    <!-- Pagina de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-journal-plus"></i>
        <span>x</span>
      </a>
    </li>
    <!-- Pagina de x -->
    <li class="nav-item">
      <a class="nav-link <?= $data['page_name'] != 'x' ? 'collapsed' : ''  ?>" href="<?= base_url() ?>/x">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>x</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="<?= base_url() ?>/logout">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Salir</span>
      </a>
    </li><!-- End Login Page Nav -->

  </ul>

</aside><!-- End Sidebar-->