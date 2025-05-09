<!-- Modal -->
<div class="modal fade" id="modalCita" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalCitaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-light text-bold" style="font-size:1.1rem" id="modalCitaLabel">Detalles de la Cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <input type="number" id="intIdCita" class="form-control" hidden />
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


        <div class="mb-3">
          <h6>Servicios</h6>
          <ul class="list-group" id="mc-servicios">
          </ul>
        </div>


        <div class="mb-3">
          <h6>Notas</h6>
          <textarea id="mcNotas" class="form-control" rows="3"></textarea>
        </div>


        <div class="mb-3 d-flex justify-content-end align-items-center">
          <strong class="me-2">Total:</strong>
          <span id="mc-total" class="fs-5 text-bold text-dark"></span>
        </div>
      </div>

      <div class="modal-footer bg-gradient-dark">
        <!-- Acciones -->
        <button type="button" class="btn btn-primary mb-0" id="btn-cancelar">Cancelar Cita</button>
        <button type="button" class="btn btn-info mb-0" id="btn-reprogramar">Reprogramar</button>
        <button type="button" class="btn bg-gradient-dark mb-0" id="btn-guardar">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Cita -->
<div
  class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
  id="modalCrearCita"
  tabindex="-1"
  aria-labelledby="modalCrearCitaLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formCrearCita">
        <div class="modal-header bg-gradient-dark">
          <h5 class="modal-title text-light text-bold" style="font-size:1.1rem" id="modalCrearCitaLabel">Añadir Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="number" id="intIdCita" class="form-control" hidden />
          <div class="mb-3">
            <label for="selectCliente" class="form-label">Cliente</label>
            <select id="selectCliente" class="form-select" required>
              <option value="" disabled selected>Busca el cliente…</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="inputFechaHora" class="form-label">Fecha y Hora</label>
            <input
              type="text"
              id="inputFechaHora"
              class="form-control"
              placeholder="Selecciona fecha y hora"
              required />
          </div>

          <div class="mb-3">
            <div id="serviciosContainer" class="d-flex flex-wrap align-items-center gap-2"></div>
            <button type="button" class="btn btn-sm bg-gradient-dark text-light mt-3" id="btnAgregarServicio">
              + Agregar Servicio
            </button>
          </div>

          <div class="d-flex justify-content-end align-items-center">
            <strong class="me-2">Total:</strong>
            <span id="spanTotal" class="fs-5 text-bold text-dark">$0</span>
          </div>

        </div>
        <div class="modal-footer bg-gradient-dark">
          <button type="button" class="btn btn-secondary mb-0" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary mb-0">Guardar Cita</button>
        </div>
      </form>
    </div>
  </div>
</div>