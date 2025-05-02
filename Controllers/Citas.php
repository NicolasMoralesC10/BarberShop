<?php


class Citas extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
      
    }
  }
  public function Citas()
  {
    $data['page_title'] = "Citas";
    $data['page_name'] = "citas";
    $data['script'] = "citas";


    $this->views->getView($this, "citas", $data);
  }

  public function setCitas()
  {
    // Leer JSON bruto
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    // Validar datos mínimos
    if (
      empty($input['cliente_id']) || empty($input['fechaInicio']) || empty($input['servicios']) || !is_array($input['servicios'])
    ) {
      echo json_encode(['status' => false, 'msg' => 'Datos inválidos'], JSON_UNESCAPED_UNICODE);
      return;
    }

    // Sanitizar y preparar variables
    $clienteId = intval(strClean($input['cliente_id']));
    $fechaInicio = strClean($input['fechaInicio']); // "YYYY-MM-DD HH:ii:ss"
    $notas = isset($input['notas']) ? strClean($input['notas']) : null;
    /*     $status = 1; // pendiente por defecto */

    // Calcular total y minutos totales
    $total          = 0;
    $minutosTotales = 0;
    foreach ($input['servicios'] as $srv) {
      $precio = intval(strClean($srv['precio']));
      $duracion = intval(strClean($srv['duracionM']));
      $total += $precio;
      $minutosTotales += $duracion;
    }

    // Calcular fechaFin global de la cita
    $dt = new DateTime($fechaInicio);
    $dt->modify("+{$minutosTotales} minutes");
    $fechaFin = $dt->format('Y-m-d H:i:s');

    // Validar disponibilidad servicio a servicio
    $currentStart = new DateTime($fechaInicio);
    foreach ($input['servicios'] as $srv) {
      $duracion = intval(strClean($srv['duracionM']));
      $dtServiceEnd = clone $currentStart;
      $dtServiceEnd->modify("+{$duracion} minutes");

      $startSrv = $currentStart->format('Y-m-d H:i:s');
      $endSrv = $dtServiceEnd->format('Y-m-d H:i:s');
      $empId = intval(strClean($srv['empleado_id']));

      $conflictos = $this->model->getCitasDisEmpleado($empId, $startSrv, $endSrv);
      if (!empty($conflictos)) {
        $empleadoNombre = $conflictos[0]['empleadoNombre'];
        $hIni12 = (new DateTime($startSrv))->format('g:i A');
        $hFin12 = (new DateTime($endSrv))->format('g:i A');

        echo json_encode(['status' => false, 'msg' => "El empleado “{$empleadoNombre}” ya tiene una cita de {$hIni12} a {$hFin12}."], JSON_UNESCAPED_UNICODE);
        return;
      }

      // avanzamos al siguiente servicio
      $currentStart = $dtServiceEnd;
    }

    try {
      // Insertar cita principal
      $newCitaId = $this->model->insertCita(
        $clienteId,
        $fechaInicio,
        $fechaFin,
        $notas,
        $total
      );

      if ($newCitaId > 0) {
        // Insertar cada servicio con sus tiempos
        $currentStart = new DateTime($fechaInicio);
        foreach ($input['servicios'] as $srv) {
          $duracion     = intval(strClean($srv['duracionM']));
          $dtServiceEnd = clone $currentStart;
          $dtServiceEnd->modify("+{$duracion} minutes");

          $startSrv = $currentStart->format('Y-m-d H:i:s');
          $endSrv   = $dtServiceEnd->format('Y-m-d H:i:s');

          $this->model->insertCitaServicio(
            $newCitaId,
            intval(strClean($srv['servicio_id'])),
            intval(strClean($srv['empleado_id'])),
            $duracion,
            $startSrv,
            $endSrv,
            intval(strClean($srv['precio']))
          );

          $currentStart = $dtServiceEnd;
        }

        $arrResponse = ['status' => true, 'msg' => 'Cita agendada correctamente', 'id' => $newCitaId];
      } else {
        $arrResponse = ['status' => false, 'msg' => 'Error al insertar la cita'];
      }
    } catch (\Throwable $e) {
      $arrResponse = ['status' => false, 'msg' => 'Excepción: ' . $e->getMessage()];
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }



  public function getCitas()
  {
    $rawData = $this->model->selectCitas();

    $citas = [];
    foreach ($rawData as $row) {
      $id = $row['id'];

      if (!isset($citas[$id])) {
        $citas[$id] = [
          'id'        => $id,
          'cliente'   => $row['cliente'],
          'start'     => $row['start'],
          'end'       => $row['end'],
          'servicios' => [],
          'empleados' => [],
          'duraciones' => [],
          'total'     => intval($row['total']),
          'status'    => intval($row['status']),
          'notas'     => $row['notas']
        ];
      }

      $citas[$id]['servicios'][] = $row['servicio'];
      $citas[$id]['empleados'][] = $row['empleado'];
      $citas[$id]['duraciones'][] = intval($row['duracionM']);
    }

    // Reindexar para que sea un array plano
    $citas = array_values($citas);

    echo json_encode($citas, JSON_UNESCAPED_UNICODE);
  }

  public function cancelarCita()
  {
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (empty($input['id'])) {
      echo json_encode(['status' => false, 'msg' => 'ID de cita no proporcionado'], JSON_UNESCAPED_UNICODE);
      return;
    }
    $citaId = intval(strClean($input['id']));

    try {
      $result = $this->model->cancelarCita($citaId);

      if ($result > 0) {
        echo json_encode(['status' => true, 'msg' => 'Cita cancelada correctamente'], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró la cita o ya estaba cancelada'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false, 'msg' => 'Error al cancelar la cita: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
  }


  public function getClientes()
  {
    $arrData = $this->model->selectClientes();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getServicios()
  {
    $arrData = $this->model->selectServicios();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getEmpleados()
  {
    $arrData = $this->model->selectEmpleados();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }
}
