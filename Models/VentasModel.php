<?php

class VentasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    // Inserta la cita y devuelve el ID generado
    public function insertVenta(int $clienteId, int $empleadoId, int $total, string $metodoPago, ?string $observaciones)
    {
        $query = "INSERT INTO ventas
            (cliente_id, empleado_id, total, metodo_pago, observaciones, status)
            VALUES (?, ?, ?, ?, ?, 1)";
        $arrData = [
            $clienteId,
            $empleadoId,
            $total,
            $metodoPago,
            $observaciones
        ];
        return $this->insert($query, $arrData);
    }
    public function selectDistinctVenta()
    {
        $sql = "SELECT DISTINCT v.id FROM ventas v";
        $request = $this->select_all($sql);
        return $request;
    }
        public function selectProductosVenta($ventaId)
    {
        $this->ventaId = $ventaId;
        $sql = "SELECT p.nombre, p.id, vp.cantidad, vp.subtotal, p.precio FROM ventas_productos vp JOIN productos p ON vp.productos_id = p.id WHERE vp.ventas_id = {$this->ventaId}";
        $request = $this->select_all($sql);
        return $request;
    }
    // Inserta un servicio asociado a la cita
    public function insertVentaProducto(int $ventaId, int $productoId, int $cantidad, int $subtotal)
    {
        $query = "INSERT INTO ventas_productos
            (ventas_id, productos_id, cantidad, subtotal)
            VALUES (?, ?, ?, ?)";
        $arrData = [
            $ventaId,
            $productoId,
            $cantidad,
            $subtotal,
        ];
        return $this->insert($query, $arrData);
    }

    public function selectVenta($ventaId)
    {
        $this->ventaId = $ventaId;
        $sql = "SELECT 
            v.id,
            cl.nombre AS cliente,
            cl.id AS cliente_id,
            v.fecha_venta AS fecha,
            v.total,
            v.metodo_pago,
            v.observaciones,
            v.status,
            e.nombre AS empleado,
            e.id AS empleado_id
          FROM ventas v
          INNER JOIN clientes cl ON v.cliente_id = cl.id
          INNER JOIN empleados e ON v.empleado_id = e.id WHERE v.id = {$this->ventaId}";
        $request = $this->select($sql);
        return $request;
    }

    public function selectClientes()
    {
        $sql = "SELECT id, nombre, telefono FROM clientes WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectPrecioProducto(int $productoId)
    {
        $this->productoId = $productoId;
        $sql = "SELECT precio FROM productos WHERE id = {$this->productoId}";
        $request = $this->select($sql);
        return $request['precio'];	
    }
    public function selectProductos()
    {
        $sql = "SELECT * FROM productos WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEmpleados()
    {
        $sql = "SELECT id, nombre, cargo FROM empleados WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function cancelarVenta(int $ventaId)
    {
        $this->ventaId = $ventaId;

        $sql = "UPDATE ventas SET status = ? WHERE id = ?";
        $arrData = array(2, $this->ventaId);
        $request = $this->update($sql, $arrData);
        return $request;
    }
public function updateVenta(int $ventaId, int $clienteId, int $empleadoId, int $total, string $metodoPago, ?string $observaciones)
{
    $ventaId = intval($ventaId);
    $clienteId = intval($clienteId);
    $empleadoId = intval($empleadoId);
    $total = intval($total);
    $metodoPago = strClean($metodoPago);
    $observaciones = strClean($observaciones);

    $sql = "UPDATE ventas 
            SET cliente_id = $clienteId, empleado_id = $empleadoId, total = $total, metodo_pago = '$metodoPago', observaciones = '$observaciones'
            WHERE id = $ventaId";
    return $this->update($sql, []);
}

public function deleteVentaProductos(int $ventaId)
{
    $ventaId = intval($ventaId); 
    $sql = "DELETE FROM ventas_productos WHERE ventas_id = $ventaId";
    return $this->delete($sql);
}

// Sumar stock de un producto
public function sumarStockProducto(int $productoId, int $cantidad)
{
    $productoId = intval($productoId);
    $cantidad = intval($cantidad);
    $sql = "UPDATE productos SET stock = stock + $cantidad WHERE id = $productoId";
    return $this->update($sql, []);
}

// Restar stock de un producto
public function restarStockProducto(int $productoId, int $cantidad)
{
    $productoId = intval($productoId);
    $cantidad = intval($cantidad);
    $sql = "UPDATE productos SET stock = stock - $cantidad WHERE id = $productoId";
    return $this->update($sql, []);
}
}
