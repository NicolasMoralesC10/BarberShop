<?php


class Citas extends Controllers
{
  public function __construct()
  {
    parent::__construct();
  }
  public function Citas()
  {
    $data['page_title'] = "Citas";
    $data['page_name'] = "Citas";
    $data['script'] = "citas";


    $this->views->getView($this, "citas", $data);
  }

  public function setCitas()
  {
    // 1) Leer JSON bruto
    $json = file_get_contents('php://input');
    $input = json_decode($json, true);

    // 2) Validar datos mínimos
    if (empty($input['cliente_id']) || empty($input['fechaInicio']) || empty($input['servicios']) || !is_array($input['servicios'])) {
      echo json_encode(['status' => false, 'msg' => 'Datos inválidos']);
      return;
    }

    // 3) Sanitizar y preparar variables
    $clienteId   = intval(strClean($input['cliente_id']));
    $fechaInicio = strClean($input['fechaInicio']);
    $notas       = isset($input['notas']) ? strClean($input['notas']) : null;
  /*   $status      = 1; // pendiente por defecto */

    // 4) Calcular total y minutos totales
    $total = 0;
    $minutosTotales = 0;
    foreach ($input['servicios'] as $srv) {
      $precio   = intval(strClean($srv['precio']));
      $duracion = intval(strClean($srv['duracionM']));
      $total    += $precio;
      $minutosTotales += $duracion;
    }

    // 5) Calcular fechaFin
    $dt = new DateTime($fechaInicio);
    $dt->modify("+{$minutosTotales} minutes");
    $fechaFin = $dt->format('Y-m-d H:i:s');

    try {
      // 6) Insertar cita principal
      $newCitaId = $this->model->insertCita(
        $clienteId,
        $fechaInicio,
        $fechaFin,
        $notas,
        $total,
    /*     $status */
      );

      if ($newCitaId > 0) {
        // 7) Insertar cada servicio en pivot
        foreach ($input['servicios'] as $srv) {
          $this->model->insertCitaServicio(
            $newCitaId,
            intval(strClean($srv['servicio_id'])),
            intval(strClean($srv['empleado_id'])),
            intval(strClean($srv['duracionM'])),
            intval(strClean($srv['precio']))
          );
        }
        $arrResponse = [
          'status' => true,
          'msg'    => 'Cita agendada correctamente',
          'id'     => $newCitaId
        ];
      } else {
        $arrResponse = [
          'status' => false,
          'msg'    => 'Error al insertar la cita'
        ];
      }
    } catch (\Throwable $e) {
      $arrResponse = [
        'status' => false,
        'msg'    => 'Excepción: ' . $e->getMessage()
      ];
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }


  public function getCitas()
  {
    $arrData = $this->model->selectCitas();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
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
