<!-- Modal -->
<div class="modal fade" id="crearClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h1 class="modal-title text-light fs-6 text-bold" id="modalTitle">Añadir cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearCliente" method="POST">
                    <input type="hidden" name="txtIdCliente" id="txtIdCliente" value="0">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtNombre" class="form-label">Nombre(s)</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>

                        <div class="mb-3 col-6">
                            <label for="txtTelefono" class="form-label">Telefono</label>
                            <input type="number" class="form-control" id="txtTelefono" name="txtTelefono">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="txtObservaciones" class="form-label">Observacion(es)</label>
                        <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="3"></textarea>
                    </div>

                    <div id="userStatusZone" class="mb-3">
                        <label for="genero" class="form-label">Estado</label>
                        <select class="form-control" name="txtEstado" id="txtEstado">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer bg-gradient-dark">
                <button type="button" class="btn btn-secondary mb-0" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary mb-0" id="btnEnviar">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</div>