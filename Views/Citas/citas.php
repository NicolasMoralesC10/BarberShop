<?php header_admin($data);
getModal('citasModal', $data);
?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="ms-3">
    <h3 class="mb-0 h4 font-weight-bolder text-light"><?= $data['page_title'] ?></h3>
    <p class="mb-4">
      Programa, gestiona y controla las citas de forma clara y ordenada.
    </p>
  </div>
  <div class="col-lg-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark d-flex justify-content-between align-items-center shadow-dark border-radius-lg pt-3 pb-3 px-3">
          <h6 class="text-white text-capitalize ps-3">Calendario <?= $data['page_title'] ?></h6>
          <button type="button" class="btn btn-lg bg-gradient-primary shadow-dark mt-0 mb-0" id="btnAgregarCita" style="margin: none;">
            <span class="material-symbols-rounded text-3xl" translate="no">calendar_add_on</span>
          </button>
        </div>
      </div>
      <div class="card-body px-1 pb-2 p-3">
        <div class="p-3" id="calendarioCitas"></div>
      </div>
    </div>
  </div>
</div>


<?php footer_admin($data); ?>