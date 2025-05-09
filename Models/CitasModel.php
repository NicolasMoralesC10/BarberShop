<?php

class CitasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }


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

    public function updateCita(int $citaId, int $clienteId, string $fechaInicio, string $fechaFin, ?string $notas, int $total)
    {

        $this->citaId = $citaId;
        $this->clienteId = $clienteId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->notas = $notas;
        $this->total = $total;


        $sql = "UPDATE citas SET cliente_id  = ?, fechaInicio = ?, fechaFin = ?,notas = ?, total = ? WHERE id = {$this->citaId}";
        $arrData = array(
            $clienteId,
            $fechaInicio,
            $fechaFin,
            $notas,
            $total,
        );
        return $this->update($sql, $arrData);
    }

    public function getCitasDisEmpleado(int $empleadoId, string $fechaInicio, string $fechaFin)
    {
        $this->empleadoId = $empleadoId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $query = "
          SELECT c.id, c.fechaInicio, c.fechaFin, e.nombre AS empleadoNombre
            FROM citas c
            JOIN citas_servicios cs ON cs.cita_id = c.id
            JOIN empleados e ON e.id = cs.empleado_id
           WHERE cs.empleado_id = {$this->empleadoId}
             AND c.status = 1 
             AND (
                  (cs.fechaInicio <= '{$this->fechaInicio}' AND cs.fechaFin   >= '{$this->fechaInicio}')
               OR (cs.fechaInicio <= '{$this->fechaFin}' AND cs.fechaFin   >= '{$this->fechaFin}')
               OR (cs.fechaInicio >= '{$this->fechaInicio}' AND cs.fechaFin   <= '{$this->fechaFin}')
             )
        ";
        return $this->select_all($query);
    }

    public function getCitasDisEmpleadoRepro(int $empId, string $start, string $end, int $excludeCitaId)
    {
        $this->empId = $empId;
        $this->start = $start;
        $this->end = $end;
        $this->excludeCitaId = $excludeCitaId;

        $sql = "SELECT cs.fechaInicio, cs.fechaFin, e.nombre AS empleadoNombre, cs.cita_id
            FROM citas_servicios cs
            JOIN empleados e ON cs.empleado_id = e.id
            WHERE cs.empleado_id = {$this->empId}
            AND cs.cita_id != {$this->excludeCitaId}
              AND (
                (cs.fechaInicio < '{$this->end}' AND cs.fechaFin > '{$this->start}')
                OR
                (cs.fechaInicio >= '{$this->start}' AND cs.fechaInicio < '{$this->end}')
              )";

        return $this->select_all($sql);
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
          WHERE c.status = 1 
          ORDER BY c.id, cs.id";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCitaById(int $citaId)
    {
        $this->citaId = $citaId;
        $sql = "
            SELECT 
              c.id,
              c.cliente_id,
              cl.nombre        AS cliente,
              c.fechaInicio   AS start,
              c.fechaFin      AS end,
              c.total,
              c.status,
              c.notas,
              cs.servicio_id,
              s.nombre         AS servicio,
              cs.empleado_id,
              e.nombre         AS empleado,
              cs.duracionM AS duracionM,
              cs.precio
            FROM citas c
            INNER JOIN clientes cl ON c.cliente_id = cl.id
            INNER JOIN citas_servicios cs ON c.id = cs.cita_id
            INNER JOIN servicios s     ON cs.servicio_id = s.id
            INNER JOIN empleados e     ON cs.empleado_id  = e.id
            WHERE c.id = {$this->citaId}
            ORDER BY cs.id
        ";
        return $this->select_all($sql, [$citaId]);
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
        $sql = "SELECT id, nombre, cargo FROM empleados WHERE status = 1 AND cargo != 'Administrador' AND cargo != 'Recepcionista'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function cancelarCita(int $citaId)
    {
        $this->citaId = $citaId;

        $sql = "UPDATE citas SET status = ? WHERE id = ?";
        $arrData = array(0, $this->citaId);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deleteCitaServicios(int $citaId)
    {
        $this->citaId = $citaId;
        $sql = "DELETE FROM citas_servicios WHERE cita_id = ?";
        $arrData = array($this->citaId);
        $request = $this->delete($sql, $arrData);
        return $request;
    }
}
