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

    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (empty($input['cliente_id']) || empty($input['fechaInicio']) || empty($input['servicios']) || !is_array($input['servicios'])) {
      echo json_encode(['status' => false, 'msg' => 'Datos inválidos, proporcione todo los datos.'], JSON_UNESCAPED_UNICODE);
      return;
    }

    $citaId      = isset($input['id']) ? intval(strClean($input['id'])) : 0;
    $clienteId   = intval(strClean($input['cliente_id']));
    $fechaInicio = strClean($input['fechaInicio']);
    $notas       = $input['notas'] ?? null;
    $excludeCita = $citaId > 0 ? $citaId : 0;

    $total = 0;
    $minutosTotales = 0;
    $currentStart = new DateTime($fechaInicio);

    foreach ($input['servicios'] as $srv) {
      $precio = intval(strClean($srv['precio']));
      $duracion = intval(strClean($srv['duracionM']));
      $total += $precio;
      $minutosTotales += $duracion;
    }

    $dt = new DateTime($fechaInicio);
    $dt->modify("+{$minutosTotales} minutes");
    $fechaFin = $dt->format('Y-m-d H:i:s');


    $currentStart = new DateTime($fechaInicio);
    foreach ($input['servicios'] as $srv) {
      $duracion = intval(strClean($srv['duracionM']));
      $dtServiceEnd = clone $currentStart;
      $dtServiceEnd->modify("+{$duracion} minutes");

      $startSrv = $currentStart->format('Y-m-d H:i:s');
      $endSrv = $dtServiceEnd->format('Y-m-d H:i:s');
      $empId = intval(strClean($srv['empleado_id']));

      if ($citaId > 0) {
        $conflictos = $this->model->getCitasDisEmpleadoRepro($empId, $startSrv, $endSrv, $excludeCita);
      } else {
        $conflictos = $this->model->getCitasDisEmpleado($empId, $startSrv, $endSrv);
      }


      if (!empty($conflictos)) {
        $empleadoNombre = $conflictos[0]['empleadoNombre'];
        $hIni12 = (new DateTime($startSrv))->format('g:i A');
        $hFin12 = (new DateTime($endSrv))->format('g:i A');

        echo json_encode(['status' => false, 'msg' => "El empleado “{$empleadoNombre}” ya tiene una cita de {$hIni12} a {$hFin12} "], JSON_UNESCAPED_UNICODE);
        return;
      }

      // avanzamos al siguiente servicio
      $currentStart = $dtServiceEnd;
    }


    try {
      if (intval($citaId) > 0) {
        // ─── FLUJO DE ACTUALIZAR / REPROGRAMAR ───
        $upd = $this->model->updateCita(
          $citaId,
          $clienteId,
          $fechaInicio,
          $fechaFin,
          $notas,
          $total
        );

        if (intval($upd) > 0) {
          // Borrar servicios anteriores
          $this->model->deleteCitaServicios($citaId);

          // Reinsertar pivot con nuevos tiempos
          $currentStart = new DateTime($fechaInicio);
          foreach ($input['servicios'] as $srv) {
            $duracion     = intval(strClean($srv['duracionM']));
            $dtSrvEnd     = clone $currentStart;
            $dtSrvEnd->modify("+{$duracion} minutes");

            $this->model->insertCitaServicio(
              $citaId,
              intval(strClean($srv['servicio_id'])),
              intval(strClean($srv['empleado_id'])),
              $duracion,
              $currentStart->format('Y-m-d H:i:s'),
              $dtSrvEnd->format('Y-m-d H:i:s'),
              intval(strClean($srv['precio']))
            );
            $currentStart = $dtSrvEnd;
          }

          $arrResponse = ['status' => true, 'msg' => 'Cita actualizada correctamente', 'id' => $citaId];
        } else {
          $arrResponse = ['status' => false, 'msg' => 'No se pudo actualizar la cita'];
        }
      } else {
        // Insertar cabecera
        $newId = $this->model->insertCita(
          $clienteId,
          $fechaInicio,
          $fechaFin,
          $notas,
          $total
        );

        if ($newId > 0) {
          // Insertar pivot
          $currentStart = new DateTime($fechaInicio);
          foreach ($input['servicios'] as $srv) {
            $duracion = intval(strClean($srv['duracionM']));
            $dtSrvEnd = clone $currentStart;
            $dtSrvEnd->modify("+{$duracion} minutes");

            $this->model->insertCitaServicio(
              $newId,
              intval(strClean($srv['servicio_id'])),
              intval(strClean($srv['empleado_id'])),
              $duracion,
              $currentStart->format('Y-m-d H:i:s'),
              $dtSrvEnd->format('Y-m-d H:i:s'),
              intval(strClean($srv['precio']))
            );

            $currentStart = $dtSrvEnd;
          }

          $arrResponse = ['status' => true, 'msg' => 'Cita agendada correctamente', 'id' => $newId];
        } else {
          $arrResponse = ['status' => false, 'msg' => 'Error al insertar la cita'];
        }
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

  public function getCitaById()
  {
    $id = intval(strClean($_GET['id'] ?? 0));
    if ($id <= 0) {
      echo json_encode(['status' => false, 'msg' => 'ID inválido'], JSON_UNESCAPED_UNICODE);
      return;
    }

    $rawData = $this->model->selectCitaById($id);
    if (empty($rawData)) {
      echo json_encode(['status' => false, 'msg' => 'Cita no encontrada'], JSON_UNESCAPED_UNICODE);
      return;
    }

    $first = $rawData[0];
    $cita = [
      'id'         => intval($first['id']),
      'cliente_id' => intval($first['cliente_id']),
      'cliente'    => $first['cliente'],
      'start'      => $first['start'],
      'end'        => $first['end'],
      'servicios'  => [],
      'empleados'  => [],
      'duraciones' => [],
      'total'      => intval($first['total']),
      'status'     => intval($first['status']),
      'notas'      => $first['notas']
    ];

    foreach ($rawData as $row) {
      $cita['servicios'][]  = $row['servicio'];
      $cita['empleados'][]  = $row['empleado'];
      $cita['servicio_ids'][]  = intval($row['servicio_id']);
      $cita['empleado_ids'][]  = intval($row['empleado_id']);
      $cita['duraciones'][] = intval($row['duracionM']);
      $cita['precios'][]    = intval($row['precio']);
    }

    // Devolverlo (sin reindexar porque es un solo objeto)
    echo json_encode(['status' => true, 'data' => $cita], JSON_UNESCAPED_UNICODE);
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
      $this->model->deleteCitaServicios($citaId);

      if ($result > 0) {
        echo json_encode(['status' => true, 'msg' => 'Cita cancelada correctamente'], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró la cita o ya estaba cancelada'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false, 'msg' => 'Error al cancelar la cita: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
  }

  public function updateNotas(){
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (empty($input['citaId']) || empty($input['notas'])) {
      echo json_encode(['status' => false, 'msg' => 'ID o notas no proporcionados'], JSON_UNESCAPED_UNICODE);
      return;
    }

    $citaId = intval(strClean($input['citaId']));
    $notas  = strClean($input['notas']);

    try {
      $result = $this->model->updateNotas($citaId, $notas);
      if ($result > 0) {
        echo json_encode(['status' => true, 'msg' => 'Notas actualizadas correctamente'], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró la cita o no se pudo actualizar'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false, 'msg' => 'Error al actualizar las notas: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
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
