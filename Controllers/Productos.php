<?php

class Productos extends Controllers{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
          header('Location: ' . base_url() . '/login');
        }
    }
    public function productos()
    {
        $data['page_name'] = "productos";
        $data['script'] = "productos";
        $data['page_title'] = "Productos";
        $this->views->getView($this, "productos", $data);
    }
    public function getProductos()
    {
      $arrData = $this->model->selectProductos();
      for ($i = 0; $i < count($arrData); $i++) {
        $arrData[$i]['nombreF'] = ' <div class="d-flex">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">' . $arrData[$i]['nombre'] . '</h6>
                        </div>
                      </div>';
        $arrData[$i]['descripcionF'] = ' <p class="text-xs font-weight-bold mb-0"  style="text-align:left">' . $arrData[$i]['descripcion'] . '</p>
                   ';
  
        $arrData[$i]['precioF'] =  '
                      <span class="text-center text-secondary text-xs font-weight-bold">' . $arrData[$i]['precio'] . '</span>
                    ';
                      
        $arrData[$i]['stockF'] =  '
        <span class="text-center text-secondary text-xs font-weight-bold">' . $arrData[$i]['stock'] . '</span>
      ';
        $arrData[$i]['accion'] = '<button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                            <i class="material-symbols-rounded">Person_Edit</i>
                          </button>
                          
                          <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                            <i class="material-symbols-rounded">Delete</i>
                          </button>';
  
        if ($arrData[$i]['status'] == 1) {
          $arrData[$i]['status'] = '
                          <span class="badge badge-sm bg-gradient-success" style="font-size:0.67rem;">Activos</span>
                 ';
        } else {
          $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary" style="font-size:0.67rem;">Offline</span>';
        }
      }
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
  
    public function setProducto()
    {
      $arrPosts = [
        'txtNombre',
        'txtDescripcion',
        'txtPrecio',
        'txtStock',
        'txtStockMin',
        'txtEstado'
      ];
  
      if (check_post($arrPosts)) {
  
        $strNombre = strClean($_POST['txtNombre']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $intPrecio = intval(strClean($_POST['txtPrecio']));
        $intStock = intval(strClean($_POST['txtStock']));
        $intStockMin = intval(strClean($_POST['txtStockMin']));
        $intStatus = intval(strClean($_POST['txtEstado']));
        $intIdProducto = intval(strClean($_POST['txtIdProducto']));
  
  
        try {
          if ($intIdProducto == 0 || $intIdProducto == "" || $intIdProducto == "0") {
            $insert = $this->model->insertProducto(
              $strNombre,
              $strDescripcion,
              $intPrecio,
              $intStock,
              $intStockMin,
            );
            $option = 1;
          } else {
            if ($intStatus == 0) {
              $intStatus = 1;
            }
            $insert = $this->model->updateProducto(
              $intIdProducto,
              $strNombre,
              $strDescripcion,
              $intPrecio,
              $intStock,
              $intStockMin,
              $intStatus
            );
            $option = 2;
          }
  
          if (intval($insert) > 0) {
  
            if ($option == 1) {
              $arrResponse = array('status' => true, 'msg' => 'Producto insertado correctamente');
            }
  
            if ($option == 2) {
              $arrResponse = array('status' => true, 'msg' => 'Producto actualizado correctamente');
            }
          } else {
            $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
          }
        } catch (\Throwable $th) {
          $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
      }
  
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  
    public function getProductoById($id)
    {
  
      $intId = intval(strClean($id));
  
      if ($intId > 0) {
        $arrData = $this->model->selectProductoById($id);
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
  
    function deleteProducto()
    {
      if ($_POST) {
        $intIdProducto = intval($_POST['txtIdProducto']);
        $requestDelete = $this->model->deleteProducto($intIdProducto);
  
        if ($requestDelete) {
          $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto');
        }
  
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
      }
    }
  }