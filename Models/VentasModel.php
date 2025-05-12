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
            $fechaVenta,
            $total,
            $metodoPago,
            $observaciones
        ];
        return $this->insert($query, $arrData);
    }

    // Inserta un servicio asociado a la cita
    public function insertVentaProducto(int $ventaId, int $productoId, int $cantidad, int $subtotal)
    {
        $query = "INSERT INTO ventas_productos
            (ventas_id, productos_id, cantidad, subtotal)
            VALUES (?, ?, ?, ?, ?)";
        $arrData = [
            $ventaId,
            $productoId,
            $cantidad,
            $subtotal,
        ];
        return $this->insert($query, $arrData);
    }


    /* public function getCitasDisEmpleado(int $empleadoId, string $fechaInicio, string $fechaFin)
    {
        // Sanitizar valores para incluir directamente en la cadena SQL
        $this->empleadoId = $empleadoId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;


        // Consulta similar a tu ejemplo de excepciones, adaptada a citas y empleados
        $query = "
          SELECT c.id, c.fechaInicio, c.fechaFin, e.nombre AS empleadoNombre
            FROM citas c
            JOIN citas_servicios cs ON cs.cita_id = c.id
            JOIN empleados e ON e.id = cs.empleado_id
           WHERE cs.empleado_id = {$this->empleadoId}
             AND c.status = 1  -- solo citas activas (1 = pendiente/confirmada)
             AND (
                  (c.fechaInicio <= '{$this->fechaInicio}' AND c.fechaFin   >= '{$this->fechaInicio}')
               OR (c.fechaInicio <= '{$this->fechaFin}' AND c.fechaFin   >= '{$this->fechaFin}')
               OR (c.fechaInicio >= '{$this->fechaInicio}' AND c.fechaFin   <= '{$this->fechaFin}')
             )
        ";

        // select_all devuelve un array de filas encontradas
        return $this->select_all($query);
    }

    public function EmpleadoDisponible(int $empleadoId, string $fechaInicio, string $fechaFin): bool
    {
        $conflictos = $this->getCitasDisEmpleado($empleadoId, $fechaInicio, $fechaFin);
        return empty($conflictos);
    } */


    public function selectVentas()
    {
        $sql = "SELECT 
            v.id,
            cl.nombre AS cliente,
            v.fecha_venta AS fecha,
            v.total,
            v.metodo_pago,
            v.observaciones,
            v.status,
            p.nombre AS productos,
            e.nombre AS empleado,
            vp.ventas_id,
            vp.productos_id,
            vp.cantidad
          FROM ventas v
          INNER JOIN clientes cl ON v.cliente_id = cl.id
          INNER JOIN ventas_productos vp ON v.id = vp.ventas_id
          INNER JOIN productos p ON vp.productos_id = p.id
          INNER JOIN empleados e ON v.empleado_id = e.id";
        $request = $this->select_all($sql);
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
        $sql = "SELECT precio FROM productos WHERE id = ?";
        $arrData = array($productoId);
        $request = $this->select($sql, $arrData);
        return $request;
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
        $arrData = array(0, $this->ventaId);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
