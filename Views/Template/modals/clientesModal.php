<!-- Modal -->
<div class="modal fade" id="crearClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: black">
                <h1 class="modal-title fs-5" style="color: white;" id="exampleModalLabel">Crear cliente</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
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

                    <!-- <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtCargo" class="form-label">Salario</label>
                            <input type="number" class="form-control" id="txtCargo" name="txtCargo">
                        </div>

                        <div class="mb-3 col-6">
                            <label for="genero" class="form-label">Genero</label>
                            <select class="form-control" name="genero" id="genero">
                                <option value="0">Seleccione el Genero</option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                                <option value="3">Otro</option>
                            </select>
                        </div>
                    </div> -->

                    <div id="userStatusZone" class="mb-3">
                        <label for="genero" class="form-label">Estado</label>
                        <select class="form-control" name="txtEstado" id="txtEstado">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
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