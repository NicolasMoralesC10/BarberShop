<!-- Modal -->
<div class="modal fade" id="crearServicioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: black">
                <h1 class="modal-title fs-5" style="color: white;" id="exampleModalLabel">Crear Servicio</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
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

                    <!-- <div id="userStatusZone" class="mb-3">
                        <label for="genero" class="form-label">Estado</label>
                        <select class="form-control" name="txtEstado" id="txtEstado">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div> -->
                    <div class="mb-3">
                        <label for="txtDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="txtImagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="txtImagen" name="txtImagen" accept="image/*">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCerrar">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>