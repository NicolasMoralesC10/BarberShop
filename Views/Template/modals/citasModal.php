<!-- Modal -->
<div class="modal fade" id="modalCita" tabindex="-1" aria-labelledby="modalCitaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-light" id="modalCitaLabel">Detalles de la Cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- 1. Cabecera con cliente y horario -->
        <div class="d-flex justify-content-between mb-3">
          <div>
            <strong>Cliente:</strong> <span id="mc-cliente"></span><br>
            <strong>Fecha:</strong> <span class="me-4" id="mc-fecha"></span>
            <strong>Hora:</strong> <span id="mc-hora"></span>
          </div>
          <div>
            <span class="badge" id="mc-status"></span>
          </div>
        </div>

        <!-- 2. Servicios y empleados asignados -->
        <div class="mb-3">
          <h6>Servicios</h6>
          <ul class="list-group" id="mc-servicios">
          </ul>
        </div>

        <!-- 3. Notas -->
        <div class="mb-3">
          <h6>Notas</h6>
          <textarea id="mc-notas" class="form-control" rows="3"></textarea>
        </div>

        <!-- 4. Totales y cobro -->
        <div class="mb-3 d-flex justify-content-end align-items-center">
          <strong class="me-2">Total:</strong>
          <span id="mc-total" class="fs-5 text-success"></span>
        </div>
      </div>

      <div class="modal-footer">
        <!-- Acciones -->
        <button type="button" class="btn btn-primary" id="btn-cancelar">Cancelar</button>
        <button type="button" class="btn btn-info" id="btn-reprogramar">Reprogramar</button>
        <button type="button" class="btn bg-gradient-dark" id="btn-guardar">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Cita -->
<div
  class="modal fade"
  id="modalCrearCita"
  tabindex="-1"
  aria-labelledby="modalCrearCitaLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formCrearCita">
        <div class="modal-header bg-gradient-dark">
          <h5 class="modal-title text-light" id="modalCrearCitaLabel">Nueva Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Cliente -->
          <div class="mb-3">
            <label for="selectCliente" class="form-label">Cliente</label>
            <select id="selectCliente" placeholder="Buscar cliente..." required></select>
          </div>

          <!-- Fecha y Hora de Inicio -->
          <div class="mb-3">
            <label for="inputFechaHora" class="form-label">Fecha y Hora</label>
            <input
              type="text"
              id="inputFechaHora"
              class="form-control"
              placeholder="Selecciona fecha y hora"
              required />
          </div>

          <!-- Servicios Dinámicos -->
          <div class="mb-3">
            <div id="serviciosContainer"></div>
            <button type="button" class="btn btn-sm bg-gradient-dark text-light mt-2" id="btnAgregarServicio">
              + Agregar Servicio
            </button>
          </div>

          <!-- Total -->
          <div class="d-flex justify-content-end align-items-center">
            <strong class="me-2">Total:</strong>
            <span id="spanTotal" class="fs-5 text-success">$0</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cita</button>
        </div>
      </form>
    </div>
  </div>
</div>