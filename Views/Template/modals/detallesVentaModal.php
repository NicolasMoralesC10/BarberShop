<!-- Modal Detalles Venta -->
<div class="modal fade" id="modalDetallesVenta" tabindex="-1" aria-labelledby="modalDetallesVentaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-light text-bold fs-6" id="modalDetallesVentaLabel">Detalles de la Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <thead>
            <tr class="table-active">
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody id="detalleVentaBody">
            <!-- Aquí se insertan los detalles -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>