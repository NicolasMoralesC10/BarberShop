<?php


class Ventas extends Controllers
{
  public function __construct()
  {
    parent::__construct();
  }
  public function Ventas()
  {
    $data['page_title'] = "Ventas";
    $data['page_name'] = "ventas";
    $data['script'] = "ventas";


    $this->views->getView($this, "ventas", $data);
  }

  public function setVentas()
  {
    // 1) Leer JSON bruto
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    // 2) Validar datos mínimos
    if (
      empty($input['cliente_id']) || empty($input['empleado_id']) || empty($input['fecha_venta']) || empty($input['metodo_pago']) || empty($input['productos']) || !is_array($input['productos']) || empty($input['cantidad']) || !is_array($input['cantidad'])
    ) {
      echo json_encode(['status' => false, 'msg' => 'Datos inválidos'], JSON_UNESCAPED_UNICODE);
      return;
    }

    // 3) Sanitizar y preparar variables
    $clienteId = intval(strClean($input['cliente_id']));
    $empleadoId = intval(strClean($input['empleado_id']));
    $fechaVenta = strClean($input['fecha_venta']); // "YYYY-MM-DD HH:ii:ss"
    $total = intval(strClean($input['empleado_id']));
    $metodoPago = strClean($input['metodo_pago']);
    $observaciones = isset($input['observaciones']) ? strClean($input['observaciones']) : null;

    /*     $status = 1; // pendiente por defecto */


    try {
      // 7) Insertar cita principal
      $newVentaId = $this->model->insertVenta(
        $clienteId,
        $empleadoId,
        $fechaVenta,
        $total,
        $metodoPago,
        $observaciones
      );

      if ($newCitaId > 0) {
        // 8) Insertar cada servicio con sus tiempos
        foreach ($input['productos'] as $index => $producto) {
          $precio = intval($this->model->selectPrecioProducto($producto));
          $subtotal = intval($input['cantidad'][$index]) * $precio;
          $this->model->insertVentaProducto(
            $newVentaId,
            $producto,
            $input['cantidad'][$index],
            $subtotal
          );
        }

        $arrResponse = ['status' => true, 'msg' => 'Venta agendada correctamente', 'id' => $newCitaId];
      } else {
        $arrResponse = ['status' => false, 'msg' => 'Error al insertar la venta'];
      }
    } catch (\Throwable $e) {
      $arrResponse = ['status' => false, 'msg' => 'Excepción: ' . $e->getMessage()];
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }



  public function getVentas()
  {
    $rawData = $this->model->selectVentas();

    $ventas = [];
    foreach ($rawData as $row) {
      $id = $row['id'];

      if (!isset($ventas[$id])) {
        $ventas[$id] = [
          'id'        => $id,
          'cliente'   => $row['cliente'],
          'empleado'  => $row['empleado'],
          'metodo_pago' => $row['metodo_pago'],
          'fecha'     => $row['fecha'],
          'productos' => [],
          'total'     => intval($row['total']),
          'notas'     => $row['notas']
        ];
      }

      $ventas[$id]['productos'][] = $row['producto'];
    }

    // Reindexar para que sea un array plano
    $ventas = array_values($ventas);

    echo json_encode($ventas, JSON_UNESCAPED_UNICODE);
  }

  public function cancelarCita()
  {
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (empty($input['id'])) {
      echo json_encode(['status' => false, 'msg' => 'ID de venta no proporcionado'], JSON_UNESCAPED_UNICODE);
      return;
    }
    $ventaId = intval(strClean($input['id']));

    try {
      $result = $this->model->cancelarVenta($ventaId);

      if ($result > 0) {
        echo json_encode(['status' => true, 'msg' => 'Venta cancelada correctamente'], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró la venta o ya estaba cancelada'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false,'msg' => 'Error al cancelar la venta: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
  }


  public function getClientes()
  {
    $arrData = $this->model->selectClientes();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getProductos()
  {
    $arrData = $this->model->selectProductos();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getEmpleados()
  {
    $arrData = $this->model->selectEmpleados();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }
}
