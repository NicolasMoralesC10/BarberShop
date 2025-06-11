<?php header_admin($data);
getModal('serviciosModal', $data); ?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="ms-3 mb-5">
    <h3 class="mb-0 h4 font-weight-bolder text-light"><?= $data['page_title'] ?></h3>
    <p class="mb-4">
      Gestiona tus servicios: registralos, modifica su precio y ajusta su tiempo estimado de duración.
    </p>
  </div>
  <div class="col-lg-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark d-flex justify-content-between align-items-center shadow-dark border-radius-lg pt-3 pb-3 px-3">
          <h6 class="text-white text-capitalize m-0"><?= $data['page_title'] ?></h6>
          <button type="button" class="btn btn-lg bg-gradient-primary shadow-dark mt-0 mb-0" id="btnAgregar" style="margin: none;">
            <i class="material-symbols-rounded" translate="no">AddSelf_Care</i>
          </button>
        </div>
      </div>
      <div class="card-body px-1 pb-2">
        <div class="contenedor">
          <div class="row ps-5 pe-5 pb-4" style="gap: 4.2%; justify-content: space-around" id="cards_servicios">

          </div>
        </div>
      </div>
    </div>
  </div>

  <?php footer_admin($data); ?>