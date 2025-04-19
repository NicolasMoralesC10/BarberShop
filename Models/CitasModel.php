<?php

class CitasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
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
}
