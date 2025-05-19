<!-- Modal -->
<div class="modal fade" id="modalVenta" tabindex="-1" aria-labelledby="modalVentaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-light text-bold fs-6" id="modalVentaLabel">Detalles de la Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- 1. Cabecera con cliente y horario -->
        <div class="d-flex justify-content-between mb-3">
          <div>
            <strong>Cliente:</strong> <span id="mc-cliente"></span><br>
            <strong>Empleado:</strong> <span id="mc-empleado"></span><br>
            <strong>Fecha:</strong> <span class="me-4" id="mc-fecha"></span>
          </div>
          <div>
            <span class="badge" id="mc-status"></span>
          </div>
        </div>

        <!-- 2. Servicios y empleados asignados -->
        <div class="mb-3">
          <h6>Productos</h6>
          <ul class="list-group" id="mc-productos">
          </ul>
        </div>

        <!-- 3. Notas -->


        <!-- 4. Totales y cobro -->
        <div class="mb-3 d-flex justify-content-end align-items-center">
          <strong class="me-2">Total:</strong>
          <span id="mc-total" class="fs-5 text-success"></span>
        </div>
      </div>

      <div class="modal-footer">
        <!-- Acciones -->
        <button type="button" class="btn btn-primary" id="btn-cancelar">Cancelar Venta</button>
        <button type="button" class="btn bg-gradient-dark" id="btn-guardar">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Venta -->
<div
  class="modal fade"
  id="modalCrearVenta"
  tabindex="-1"
  aria-labelledby="modalCrearVentaLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formCrearVenta">
        <div class="modal-header bg-gradient-dark">
          <h5 class="modal-title text-light text-bold fs-6" id="modalCrearVentaLabel">Nueva Venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Cliente -->
                               <input type="hidden" name="txtIdProducto" id="txtIdProducto" value="0" data-ignore-clear>
          <div class="mb-3">
            <label for="selectCliente" class="form-label">Cliente</label>
            <select id="selectCliente" placeholder="Buscar cliente..." required></select>
          </div>
          <div class="mb-3">
            <label for="selectEmpleado" class="form-label">Empleado</label>
            <select id="selectEmpleado" placeholder="Buscar empleado..." required></select>
          </div>
          <div class="mb-3">
            <label for="metodoPago" class="form-label">Método de Pago</label>
            <input
              type="text"
              id="metodoPago"
              class="form-control"
              placeholder="Digite el método de pago"
              required />
</div>
        <div class="mb-3">
          <h6>Observaciones</h6>
          <textarea id="observacionesText" class="form-control" rows="3"></textarea>
        </div>
          <!-- Servicios Dinámicos -->
          <div class="mb-3">
            <div id="productosContainer"></div>
            <button type="button" class="btn btn-sm bg-gradient-dark text-light mt-2" id="btnAgregarProducto">
              + Agregar Producto
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
          <button type="submit" class="btn btn-primary">Guardar Venta</button>
        </div>
      </form>
    </div>
  </div>
</div>