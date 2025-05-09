<!-- Modal -->
<div class="modal fade" id="crearEmpleadoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h1 class="modal-title text-light fs-6 text-bold" id="exampleModalLabel">Añadir Empleado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearEmpleado" method="POST">
                    <input type="hidden" name="txtIdEmpleado" id="txtIdEmpleado" value="0" data-ignore-clear>
                    <div class="row">
                        <div class="mb-3 col-4" id="nombreZone">
                            <label for="txtNombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>
                        <div class="mb-3 col-4" id="passwordZone">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="text" class="form-control" id="txtPassword" name="txtPassword">
                        </div>

                        <div class="mb-3 col-4" id="telefonoZone">
                            <label for="txtTelefono" class="form-label">Telefono</label>
                            <input type="number" class="form-control" id="txtTelefono" name="txtTelefono">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="txtSalario" class="form-label">Salario</label>
                            <input type="text" class="form-control" id="txtSalario" name="txtSalario">
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
                        <div class="col-6"  id="fechaZone">
                            <label for="txtFechaContratacion" class="form-label">Fecha de Contratacion</label>
                            <input type="text" class="form-control" id="txtFechaContratacion" name="txtFechaContratacion">
                        </div>
                        <div class="mb-3 col-6" id="userStatusZone">
                            <label for="genero" class="form-label">Estado</label>
                            <select class="form-control" name="txtEstado" id="txtEstado">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer bg-gradient-dark">
                <button type="button" class="btn btn-secondary mb-0" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" id="btnCrearEmpleado" class="btn btn-primary mb-0">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>