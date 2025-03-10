<?php header_admin($data); ?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <h6 class="text-white text-capitalize ps-3">Tabla <?= $data['page_title'] ?></h6>
        </div>
      </div>
      <div class="card-body px-2 pb-2">
        <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
        </div>

        <div class="table-responsive p-0">
          <table id="tbl_empleados" class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cargo</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contratacion</th>
              </tr>
            </thead>

            <tbody>
              <!-- Los datos de la tabla van aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php footer_admin($data); ?>