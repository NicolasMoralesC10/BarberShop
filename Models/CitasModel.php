<?php

class CitasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    // Inserta la cita y devuelve el ID generado
    public function insertCita(int $clienteId, string $fechaInicio, string $fechaFin, ?string $notas, int $total)
    {
        // No verificamos duplicados en este caso
        $query = "INSERT INTO citas
            (cliente_id, fechaInicio, fechaFin, notas, total, status)
            VALUES (?, ?, ?, ?, ?, 1)";
        $arrData = [
            $clienteId,
            $fechaInicio,
            $fechaFin,
            $notas,
            $total
        ];
        return $this->insert($query, $arrData);
    }

    // Inserta un servicio asociado a la cita
    public function insertCitaServicio(int $citaId, int $servicioId, int $empleadoId, int $duracionM, int $precio) { 
        $query = "INSERT INTO citas_servicios
            (cita_id, servicio_id, empleado_id, duracionM, precio)
            VALUES (?, ?, ?, ?, ?)";
        $arrData = [
            $citaId,
            $servicioId,
            $empleadoId,
            $duracionM,
            $precio
        ];
        return $this->insert($query, $arrData);
    }

    public function selectCitas()
    {
        $sql = "SELECT c.id, cl.nombre AS cliente,
            DATE_FORMAT(c.fechaInicio, '%Y-%m-%dT%H:%i:%s') AS start,
            DATE_FORMAT(
                DATE_ADD(c.fechaInicio, INTERVAL SUM(cs.duracionM) MINUTE),
                '%Y-%m-%dT%H:%i:%s'
            ) AS end,
            c.status,
            SUM(cs.precio) AS total,
            GROUP_CONCAT(DISTINCT s.nombre)   AS servicios,
            GROUP_CONCAT(DISTINCT e.nombre)   AS empleados,
            GROUP_CONCAT(DISTINCT cs.duracionM ORDER BY cs.servicio_id) AS duraciones
            FROM citas c
            JOIN clientes cl   ON cl.id = c.cliente_id
            JOIN citas_servicios cs ON cs.cita_id    = c.id
            JOIN servicios s   ON s.id   = cs.servicio_id
            JOIN empleados e   ON e.id   = cs.empleado_id 
            WHERE c.status = 1
            GROUP BY c.id, cl.nombre, c.fechaInicio, c.status ;
            ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectClientes()
    {
        $sql = "SELECT id, nombre, telefono FROM clientes WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectServicios()
    {
        $sql = "SELECT * FROM servicios WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEmpleados()
    {
        $sql = "SELECT id, nombre, cargo FROM empleados WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }
}
