<!-- Modal -->
<div class="modal fade" id="crearServicioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h1 class="modal-title text-light fs-6 text-bold" id="modalTitle">Añadir Servicio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearServicio" method="POST">
                    <input type="hidden" name="txtIdServicio" id="txtIdServicio" value="0">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>

                        <div class="mb-3 col-6">
                            <label for="txtPrecio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="txtPrecio" name="txtPrecio">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="txtDuracion" class="form-label">Duracion (min)</label>
                            <input type="number" min="1" class="form-control" id="txtDuracion" name="txtDuracion">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="txtDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="1"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="txtImagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="txtImagen" name="txtImagen" accept="image/*">
                            <img id="imgPreview" src="" alt="Imagen actual" class="imgPreview">
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCerrar">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btnEnviar">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</div>