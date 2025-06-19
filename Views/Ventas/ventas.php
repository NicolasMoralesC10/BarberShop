<?php header_admin($data);
getModal('ventasModal', $data);
getModal('detallesVentaModal', $data);
?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="ms-3 mb-5">
    <h3 class="mb-0 h4 font-weight-bolder text-light"><?= $data['page_title'] ?></h3>
    <p class="mb-4">
      Administra y registra las ventas de productos de forma rápida y organizada.
    </p>
  </div>
  <div class="col-lg-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark d-flex justify-content-between align-items-center shadow-dark border-radius-lg pt-3 pb-3 px-3">
          <h6 class="text-white text-capitalize m-0">Registros <?= $data['page_title'] ?></h6>
          <button type="button" class="btn btn-lg bg-gradient-primary shadow-dark mt-0 mb-0" id="btnAgregarVenta" style="margin: none;">
            <span class="material-symbols-rounded" translate="no">new_window</span>
          </button>
        </div>
      </div>
      <div class="card-body px-1 pb-2">
        <div class="table-responsive p-3">
          <table id="tbl_ventas" class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Fecha</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Cliente</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Empleado</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Método de pago</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Total</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Observaciones</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Estado</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Acciones</th>
              </tr>
            </thead>

            <tbody class="text-center">
              <!-- Los datos de la tabla van aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php footer_admin($data); ?>