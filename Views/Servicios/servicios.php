<?php header_admin($data);
getModal('serviciosModal', $data); ?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="col-lg-12">
    <button type="button" class="btn btn-lg bg-gradient-dark shadow-dark" style="margin-left: 5px;" id="btnAgregar">
      <i class="material-symbols-rounded">AddSelf_Care</i>
    </button>
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <h6 class="text-white text-capitalize ps-3"><?= $data['page_title'] ?></h6>
        </div>
      </div>
      <div class="card-body px-1 pb-2">
        <div class="contenedor">
          <div class="row ps-5 pe-5" style="gap: 11.1%;" id="cards_servicios">

          </div>
        </div>
        <!-- <div class="table-responsive p-3">
          <table id="tbl_clientes" class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nombre</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Telefono</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Estado</th>
                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7"></th>
              </tr>
            </thead>

            <tbody class="text-center">
            </tbody>
          </table>
        </div>
      </div> -->
      </div>
    </div>
  </div>

  <?php footer_admin($data); ?>