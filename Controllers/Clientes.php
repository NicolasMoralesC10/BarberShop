<?php

class Clientes extends Controllers
{
  public function __construct()
  {
    parent::__construct();
  }

  public function clientes()
  {
    $data['page_title'] = "Clientes";
    $data['page_name'] = "Clientes";
    $data['script'] = "clientes";

    $this->views->getView($this, "clientes", $data);
  }

  public function getClientes()
  {
    $arrData = $this->model->selectClientes();
    for ($i = 0; $i < count($arrData); $i++) {
      $arrData[$i]['nombreF'] = '<div class="d-flex px-2 py-1">
                                  <div>
                                    <img src="./Assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                  </div>
                                  <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">' . $arrData[$i]['nombre'] . '</h6>
                                  </div>
                                </div>';

      $arrData[$i]['telefonoF'] = '<p class="text-xs font-weight-bold mb-0" style="text-align:left">' . $arrData[$i]['telefono'] . '</p>
                                   <p class="text-xs text-secondary mb-0" style="text-align:left">Organization</p>';

      $arrData[$i]['accion'] = '<button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                                  <i class="material-symbols-rounded">Person_Edit</i>
                                </button>

                                <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                                  <i class="material-symbols-rounded">Delete</i>
                                </button>';

      if ($arrData[$i]['status'] == 1) {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-success" style="font-size:0.7rem;">Online</span>';
      } else {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary">Offline</span>';
      }
    }
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getClienteById($id)
  {
    $intId = intval(strClean($id));

    if ($intId > 0) {
      $arrData = $this->model->selectClienteById($id);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Tipo de dato no permitido.');
    }

    if (!empty($arrData)) {
      $arrResponse = array('status' => true, 'data' => $arrData);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este ID.');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  public function setCliente()
  {
    $arrPosts = [
      'txtNombre',
      'txtTelefono',
      'txtEstado'
    ];

    if (check_post($arrPosts)) {
      $strNombre = strClean($_POST['txtNombre']);
      $strTelefono = strClean($_POST['txtTelefono']);
      $intStatus = intval(strClean($_POST['txtEstado']));
      $intIdCliente = intval(strClean($_POST['txtIdCliente']));

      try {
        if ($intIdCliente == 0 || $intIdCliente == "" || $intIdCliente == "0") {
          $insert = $this->model->insertCliente(
            $strNombre,
            $strTelefono,
          );
          $option = 1;
        } else {
          if ($intStatus == 0) {
            $intStatus = 1;
          }
          $insert = $this->model->updateCliente(
            $intIdCliente,
            $strNombre,
            $strTelefono,
            $intStatus
          );
          $option = 2;
        }

        if (intval($insert) > 0) {
          if ($option == 1) {
            $arrResponse = array('status' => true, 'msg' => 'Cliente creado correctamente.');
          }

          if ($option == 2) {
            $arrResponse = array('status' => true, 'msg' => 'Cliente actualizado correctamente.');
          }
        } else if ($insert == 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Ya existe un cliente con este telefono.');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al crear el usuario.');
        }
      } catch (\Throwable $th) {
        $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
      }
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos.');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  function deleteCliente()
  {
    if ($_POST) {
      $intIdCliente = intval($_POST['txtIdCliente']);
      $requestDelete = $this->model->deleteCliente($intIdCliente);

      if ($requestDelete) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el cliente');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el cliente');
      }

      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }
}
