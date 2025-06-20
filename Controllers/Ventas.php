<?php
require_once './Models/ProductosModel.php';

class Ventas extends Controllers
{
  public function __construct()
  {
    $this->productsModel = new ProductosModel();
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
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
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (
      empty($input['cliente_id']) || empty($input['empleado_id']) || empty($input['total']) || empty($input['metodo_pago']) || empty($input['productos']) || !is_array($input['productos'])
    ) {
      echo json_encode(['status' => false, 'msg' => 'Datos inválidos'], JSON_UNESCAPED_UNICODE);
      return;
    }

    $clienteId = intval(strClean($input['cliente_id']));
    $empleadoId = intval(strClean($input['empleado_id']));
    $total = intval(strClean($input['total']));
    $metodoPago = strClean($input['metodo_pago']);
    $observaciones = isset($input['observaciones']) ? strClean($input['observaciones']) : null;
    $idVenta = isset($input['idVenta']) ? intval(strClean($input['idVenta'])) : 0;

    try {
      if ($idVenta > 0) {
        $update = $this->model->updateVenta(
          $idVenta,
          $clienteId,
          $empleadoId,
          $total,
          $metodoPago,
          $observaciones
        );

        // 1. Recupera productos anteriores y suma stock
        $productosAnteriores = $this->model->selectProductosVenta($idVenta);
        foreach ($productosAnteriores as $prodAnt) {
          $this->model->sumarStockProducto($prodAnt['id'], $prodAnt['cantidad']);
        }

        $this->model->deleteVentaProductos($idVenta);

        // 2. Inserta nuevos productos y descuenta stock
        foreach ($input['productos'] as $producto) {
          $precio = intval($this->model->selectPrecioProducto($producto['producto_id']));
          $subtotal = intval($producto['cantidad']) * $precio;
          $this->model->insertVentaProducto(
            $idVenta,
            $producto['producto_id'],
            $producto['cantidad'],
            $subtotal
          );
          $this->model->restarStockProducto($producto['producto_id'], $producto['cantidad']);
        }

        $arrResponse = ['status' => true, 'msg' => 'Venta actualizada correctamente', 'id' => $idVenta];
      } else {
        // INSERTAR NUEVA VENTA
        $newVentaId = $this->model->insertVenta(
          $clienteId,
          $empleadoId,
          $total,
          $metodoPago,
          $observaciones
        );
        if ($newVentaId > 0) {
          foreach ($input['productos'] as $producto) {
            $precio = intval($this->model->selectPrecioProducto($producto['producto_id']));
            $subtotal = intval($producto['cantidad']) * $precio;
            $this->model->insertVentaProducto(
              $newVentaId,
              $producto['producto_id'],
              $producto['cantidad'],
              $subtotal
            );
            $this->model->restarStockProducto($producto['producto_id'], $producto['cantidad']);
          }
          $arrResponse = ['status' => true, 'msg' => 'Venta agendada correctamente', 'id' => $newVentaId];
        } else {
          $arrResponse = ['status' => false, 'msg' => 'Error al insertar la venta'];
        }
      }
    } catch (\Throwable $e) {
      $arrResponse = ['status' => false, 'msg' => 'Excepción: ' . $e->getMessage()];
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  public function getPrecioProducto()
  {
    $json  = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (empty($input['producto_id'])) {
      echo json_encode(['status' => false, 'msg' => 'ID de producto no proporcionado'], JSON_UNESCAPED_UNICODE);
      return;
    }
    $productoId = intval(strClean($input['producto_id']));

    try {
      $precio = $this->model->selectPrecioProducto($productoId);

      if ($precio > 0) {
        echo json_encode(['status' => true, 'precio' => $precio], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró el precio del producto'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false, 'msg' => 'Error al obtener el precio: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
  }


  public function getProductosVentas()
  {
    $detalleVenta = $this->model->selectProductosVenta(26);
    echo json_encode($detalleVenta, JSON_UNESCAPED_UNICODE);
  }
  public function getVentas()
  {
    $arrData = $this->model->selectDistinctVenta();

    for ($i = 0; $i < count($arrData); $i++) {
      $maestroVenta = $this->model->selectVenta($arrData[$i]['id']);
      $detalleVenta = $this->model->selectProductosVenta($arrData[$i]['id']);
      $arrData[$i]['clienteF'] = ' <div class="d-flex">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">' . $maestroVenta['cliente'] . '</h6>
                        </div>
                      </div>';
      $arrData[$i]['empleadoF'] = ' <p class="text-xs font-weight-bold mb-0"  style="text-align:left">' . $maestroVenta['empleado'] . '</p>
                      <p class="text-xs text-secondary mb-0" style="text-align:left">Barber Shop</p>';

      $arrData[$i]['metodoF'] =  '
                      <span class="text-center text-secondary text-xs font-weight-bold">' . $maestroVenta['metodo_pago'] . '</span>
                    ';
      $productos = '';
      $cantidades = '';
      if ($detalleVenta) {
        for ($j = 0; $j < count($detalleVenta); $j++) {
          if ($j == count($detalleVenta) - 1) {
            $productos .= $detalleVenta[$j]['nombre'];
            $cantidades .= $detalleVenta[$j]['cantidad'];
          } else {
            $productos .= $detalleVenta[$j]['nombre'] . ', ';
            $cantidades .= $detalleVenta[$j]['cantidad'] . ', ';
          }
        }
      }
      $arrData[$i]['fechaF'] = '<span class="text-center text-secondary text-xs font-weight-bold">' . $maestroVenta['fecha'] . '</span>';
      $arrData[$i]['totalF'] = '<span class="text-center text-secondary text-xs font-weight-bold">' . $maestroVenta['total'] . '</span>';
      $maestroVenta['observaciones'] != "" ? $arrData[$i]['observacionesF'] = '<span class="text-center text-secondary text-xs font-weight-bold">' . $maestroVenta['observaciones'] . '</span>' : $arrData[$i]['observacionesF'] = '<span class="text-center text-secondary text-xs font-weight-bold">Sin observaciones</span>';
      $arrData[$i]['accion'] = '<button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="ver" data-id="' . $arrData[$i]['id'] . '">
                                    <i class="material-symbols-rounded">visibility</i>
                                  </button>
                          <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                            <i class="material-symbols-rounded">Person_Edit</i>
                          </button>
                          
                          <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                            <i class="material-symbols-rounded">Delete</i>
                          </button>
                          ';

      if ($maestroVenta['status'] == 1) {
        $arrData[$i]['status'] = '
                          <span class="badge badge-sm bg-gradient-success" style="font-size:0.67rem;">Activo</span>
                 ';
      } else {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary" style="font-size:0.67rem;">Offline</span>';
      }
    }
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }
  public function getVentaById($id)
  {

    $intId = intval(strClean($id));

    if ($intId > 0) {
      $maestroVenta = $this->model->selectVenta($id);
      $detalleVenta = $this->model->selectProductosVenta($id);
      $productos = [];

      foreach ($detalleVenta as $key => $value) {
        if (is_numeric($key)) {
          $productos[] = $value;
          unset($detalleVenta[$key]);
        }
      }
      $detalleVenta['productos'] = $productos;
      $arrData = array_merge($maestroVenta, $detalleVenta);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
    }

    if (!empty($arrData)) {
      $arrResponse = array('status' => true, 'data' => $arrData);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }
  public function cancelarVenta()
  {
    if ($_POST) {
      $ventaId = intval($_POST['ventaId']);
    }
    if (empty($ventaId)) {
      echo json_encode(['status' => false, 'msg' => 'ID de venta no proporcionado'], JSON_UNESCAPED_UNICODE);
      return;
    }
    try {
      $result = $this->model->cancelarVenta($ventaId);

      if ($result > 0) {
        echo json_encode(['status' => true, 'msg' => 'Venta cancelada correctamente'], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['status' => false, 'msg' => 'No se encontró la venta o ya estaba cancelada'], JSON_UNESCAPED_UNICODE);
      }
    } catch (\Throwable $e) {
      echo json_encode(['status' => false, 'msg' => 'Error al cancelar la venta: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
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
