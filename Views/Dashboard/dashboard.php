<?php header_admin($data); ?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="ms-3">
    <h3 class="mb-0 h4 font-weight-bolder text-light">Dashboard</h3>
    <p class="mb-4">
      Check the sales, value and bounce rate by country.
    </p>
  </div>
</div>

<div class="row mb-4">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-2 ps-3">
        <div class="d-flex justify-content-between">
          <div>
            <p class="text-sm mb-0 text-capitalize">Ventas Generales | Hoy</p>
            <h4 class="mb-0" id="ventasHoy"></h4>
          </div>
          <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
            <i class="material-symbols-rounded opacity-10">weekend</i>
          </div>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-2 ps-3">
        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-2 ps-3">
        <div class="d-flex justify-content-between">
          <div>
            <p class="text-sm mb-0 text-capitalize">Ventas Citas | Hoy </p>
            <h4 class="mb-0" id="ventasCitasHoy"></h4>
          </div>
          <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
            <i class="material-symbols-rounded opacity-10">person</i>
          </div>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-2 ps-3">
        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-2 ps-3">
        <div class="d-flex justify-content-between">
          <div>
            <p class="text-sm mb-0 text-capitalize">Ventas Productos | Hoy </p>
            <h4 class="mb-0" id="ventasProductosHoy"></h4>
          </div>
          <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
            <i class="material-symbols-rounded opacity-10">leaderboard</i>
          </div>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-2 ps-3">
        <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card">
      <div class="card-header p-2 ps-3">
        <div class="d-flex justify-content-between">
          <div>
            <p class="text-sm mb-0 text-capitalize">Numero de Citas | Hoy </p>
            <h4 class="mb-0" id="citasHoy"></h4>
          </div>
          <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
            <i class="material-symbols-rounded opacity-10">weekend</i>
          </div>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-2 ps-3">
        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card z-index-2 h-100" style="border: none; border-radius: 1.2rem;">
      <div class="card-header pb-0 pt-3 bg-transparent">
        <h6 class="text-capitalize">Sales overview</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-arrow-up text-success"></i>
          <span class="font-weight-bold">4% more</span> in 2021
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-line-sales" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card card-carousel overflow-hidden h-100 p-0" style="border: none; border-radius: 1.2rem;">
      <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner border-radius-lg h-100">
          <div class="carousel-item h-100 active" style="background-image: url('<?= media() ?>/img/carousel-1.jpg');
      background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-dark text-center border-radius-md mb-3">
                <i class="material-symbols-rounded opacity-10">house</i>
              </div>
              <h5 class="text-white mb-1">Get started with Argon</h5>
              <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
            </div>
          </div>
          <div class="carousel-item h-100" style="background-image: url('<?= media() ?>/img/carousel-2.jpg');
      background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-dark text-center border-radius-md mb-3">
                <i class="material-symbols-rounded opacity-10">house</i>
              </div>
              <h5 class="text-white mb-1">Faster way to create web pages</h5>
              <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
            </div>
          </div>
          <div class="carousel-item h-100" style="background-image: url('<?= media() ?>/img/carousel-3.jpg');
      background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-dark text-center border-radius-md mb-3">
                <i class="material-symbols-rounded opacity-10">house</i>
              </div>
              <h5 class="text-white mb-1">Share with us your design tips!</h5>
              <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-4 col-md-6 mt-4 mb-4">
    <div class="card z-index-2 shadow-lg card-hover">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="mb-1 fw-bold">
              <i class="fas fa-calendar-week text-primary me-2"></i>
              Citas en la semana
            </h6>
            <p class="text-sm text-muted mb-0">
              <i class="fas fa-clock me-1"></i>
              Último recuento de la semana
            </p>
          </div>
          <div class="text-end">
            <span class="badge bg-success fs-6">+12%</span>
            <small class="d-block text-muted">vs semana anterior</small>
          </div>
        </div>

        <!-- Stats rápidos -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-primary fw-bold" id="total-citas">--</div>
              <small class="text-muted">Total</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-success fw-bold" id="promedio-dia">--</div>
              <small class="text-muted">Promedio/día</small>
            </div>
          </div>
        </div>

        <hr class="dark horizontal my-3">

        <!-- Día con más citas -->
        <div class="d-flex align-items-center justify-content-between">
          <span class="text-sm text-muted">Día con más citas:</span>
          <span class="badge bg-primary" id="dia-destacado">Cargando...</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6 mt-4 mb-4">
    <div class="card z-index-2 shadow-lg card-hover">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="chart-ventas" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="mb-1 fw-bold">
              <i class="fas fa-shopping-cart text-primary me-2"></i>
              Ventas de productos en la semana
            </h6>
            <p class="text-sm text-muted mb-0">
              <i class="fas fa-chart-line me-1"></i>
              Último recuento de la semana
            </p>
          </div>
          <div class="text-end">
            <span class="badge bg-success fs-6" id="porcentaje-cambio">+15%</span>
            <small class="d-block text-muted">vs semana anterior</small>
          </div>
        </div>

        <!-- Stats rápidos -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-primary fw-bold" id="total-ventas">--</div>
              <small class="text-muted">Total Ventas</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-success fw-bold" id="promedio-dia-ventas">--</div>
              <small class="text-muted">Promedio/día</small>
            </div>
          </div>
        </div>

        <!-- Stats adicionales -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h6 mb-0 text-warning fw-bold" id="monto-total">--</div>
              <small class="text-muted">Monto Total</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h6 mb-0 text-info fw-bold" id="ticket-promedio">--</div>
              <small class="text-muted">Ticket Prom.</small>
            </div>
          </div>
        </div>

        <hr class="dark horizontal my-3">

        <!-- Información destacada -->
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-sm text-muted">Día con más ventas:</span>
          <span class="badge bg-primary" id="dia-destacado-ventas">Cargando...</span>
        </div>

        <div class="d-flex align-items-center justify-content-between">
          <span class="text-sm text-muted">Método de pago top:</span>
          <span class="badge bg-success" id="metodo-top">Cargando...</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mt-4 mb-3">

    <div class="card z-index-2 shadow-lg card-hover">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="chart-ventas-generales" class="chart-canvas" height="170"></canvas>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="mb-1 fw-bold">
              <i class="fas fa-chart-bar text-primary me-2"></i>
              Ventas Generales
            </h6>
            <p class="text-sm text-muted mb-0">
              <i class="fas fa-calendar-week me-1"></i>
              Ventas + Citas de la semana
            </p>
          </div>
          <div class="text-end">
            <span class="badge bg-success fs-6" id="porcentaje-cambio-general">+0%</span>
            <small class="d-block text-muted">vs semana anterior</small>
          </div>
        </div>

        <!-- Stats rápidos -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-primary fw-bold" id="total-transacciones">--</div>
              <small class="text-muted">Total Transacciones</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h5 mb-0 text-success fw-bold" id="promedio-dia-general">--</div>
              <small class="text-muted">Promedio/día</small>
            </div>
          </div>
        </div>

        <!-- Stats adicionales -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h6 mb-0 text-warning fw-bold" id="monto-total-general">--</div>
              <small class="text-muted">Monto Total</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="h6 mb-0 text-info fw-bold" id="ticket-promedio-general">--</div>
              <small class="text-muted">Ticket Prom.</small>
            </div>
          </div>
        </div>

        <!-- Desglose por tipo -->
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-gradient-success rounded p-2 text-center text-white">
              <div class="h6 mb-0 fw-bold" id="total-ventas-desglose">--</div>
              <small>Ventas</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-gradient-info rounded p-2 text-center text-white">
              <div class="h6 mb-0 fw-bold" id="total-citas-desglose">--</div>
              <small>Citas</small>
            </div>
          </div>
        </div>

        <hr class="dark horizontal my-3">

        <!-- Información destacada -->
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-sm text-muted">Día con más actividad:</span>
          <span class="badge bg-primary" id="dia-destacado-general">Cargando...</span>
        </div>

        <div class="d-flex align-items-center justify-content-between">
          <span class="text-sm text-muted">Tipo predominante:</span>
          <span class="badge bg-success" id="tipo-predominante">Cargando...</span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php footer_admin($data); ?>