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
    public function insertCitaServicio(int $citaId, int $servicioId, int $empleadoId, int $duracionM, string $fechaInicio, string $fechaFin, int $precio)
    {
        $query = "INSERT INTO citas_servicios
            (cita_id, servicio_id, empleado_id, duracionM,fechaInicio, fechaFin, precio)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $arrData = [
            $citaId,
            $servicioId,
            $empleadoId,
            $duracionM,
            $fechaInicio,
            $fechaFin,
            $precio
        ];
        return $this->insert($query, $arrData);
    }


    public function getCitasDisEmpleado(int $empleadoId, string $fechaInicio, string $fechaFin)
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
    }


    public function selectCitas()
    {
        $sql = "SELECT 
            c.id,
            cl.nombre AS cliente,
            c.fechaInicio AS start,
            c.fechaFin AS end,
            c.total,
            c.status,
            c.notas,
            s.nombre AS servicio,
            e.nombre AS empleado,
            cs.duracionM
          FROM citas c
          INNER JOIN clientes cl ON c.cliente_id = cl.id
          INNER JOIN citas_servicios cs ON c.id = cs.cita_id
          INNER JOIN servicios s ON cs.servicio_id = s.id
          INNER JOIN empleados e ON cs.empleado_id = e.id
          ORDER BY c.id, cs.id";
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
