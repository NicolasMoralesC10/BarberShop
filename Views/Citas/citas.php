<?php header_admin($data);
getModal('citasModal', $data);
?>
<?php aside_admin($data); ?>
<?php nav_admin($data); ?>
<div class="row">
  <div class="col-lg-12">
    <button type="button" class="btn btn-lg bg-gradient-dark shadow-dark" style="margin-left: 5px;" id="btnAgregarCita">
      <i class="material-symbols-rounded">Person_Add</i>
    </button>
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <h6 class="text-white text-capitalize ps-3">Calendario <?= $data['page_title'] ?></h6>
        </div>
      </div>
      <div class="card-body px-1 pb-2 p-3">
        <div class="p-3" id="calendarioCitas"></div>
      </div>
    </div>
  </div>
</div>


<?php footer_admin($data); ?>