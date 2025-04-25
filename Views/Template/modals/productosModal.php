<!-- Modal -->
<div class="modal fade" id="crearProductoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h1 class="modal-title text-light fs-6 text-bold" id="exampleModalLabel">Crear Producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmCrearProducto" method="POST">
                    <input type="hidden" name="txtIdProducto" id="txtIdProducto" value="0" data-ignore-clear>
                    <div class="row">
                        <div class="mb-3 col-4" id="nombreZone">
                            <label for="txtNombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>
                        <div class="mb-3" id="descripcionZone">
                            <label for="txtDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="3"></textarea>
                        </div>

                        <div class="mb-3 col-4" id="precioZone">
                            <label for="txtPrecio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="txtPrecio" name="txtPrecio">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6" id="stockZone">
                            <label for="txtStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="txtStock" name="txtStock">
                        </div>

                        <div class="mb-3 col-6" id="stockMinZone">
                            <label for="txtStockMin" class="form-label">Stock mínimo</label>
                            <input type="number" class="form-control" id="txtStockMin" name="txtStockMin">
                        </div>
                        <div class="mb-3 col-6" id="productStatusZone">
                            <label for="genero" class="form-label">Estado</label>
                            <select class="form-control" name="txtEstado" id="txtEstado">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
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