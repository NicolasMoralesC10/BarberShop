<!-- Modal -->
<div class="modal fade" id="crearEmpleadoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h1 class="modal-title text-light fs-5" id="exampleModalLabel">Crear usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearEmpleado" method="POST">
                    <input type="hidden" name="txtIdEmpleado" id="txtIdEmpleado" value="0">
                    <div class="row">
                        <div class="mb-3 col-4">
                            <label for="txtNombre" class="form-label">Nombre(s)</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>
                        <div class="mb-3 col-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="text" class="form-control" id="txtPassword" name="txtPassword">
                        </div>

                        <div class="mb-3 col-4">
                            <label for="txtTelefono" class="form-label">Telefono</label>
                            <input type="number" class="form-control border-radius-xs" id="txtTelefono" name="txtTelefono">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtSalario" class="form-label">Salario</label>
                            <input type="number" class="form-control" id="txtSalario" name="txtSalario">
                        </div>

                        <div class="mb-3 col-6">
                            <label for="Cargo" class="form-label">Cargo</label>
                            <select class="form-control" name="txtCargo" id="txtCargo">
                                <option value="0">Seleccione el Cargo</option>
                                <option value="Barbero">Barbero</option>
                                <option value="Recepcionista">Recepcionista</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="txtFechaContratacion" class="form-label">Fecha de Contratacion</label>
                            <input type="date" class="form-control" id="txtFechaContratacion" name="txtFechaContratacion">
                        </div>
                        <div class="col-6">
                            <div id="userStatusZone" class="mb-3">
                                <label for="genero" class="form-label">Estado</label>
                                <select class="form-control" name="txtEstado" id="txtEstado">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>