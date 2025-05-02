<?php

class Empleados extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
  }
  public function Empleados()
  {
    $data['page_title'] = "Empleados";
    $data['page_name'] = "Empleados";
    $data['script'] = "empleados";


    $this->views->getView($this, "empleados", $data);
  }
  public function getEmpleados()
  {
    $arrData = $this->model->selectEmpleados();
    for ($i = 0; $i < count($arrData); $i++) {
      $arrData[$i]['nombreF'] = ' <div class="d-flex">
                      <div>
                        <img src="./Assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">' . $arrData[$i]['nombre'] . '</h6>
                        <p class="text-xs text-secondary mb-0" style="text-align:left"> ' . $arrData[$i]['telefono'] . '</p>
                      </div>
                    </div>';
      $arrData[$i]['cargoF'] = ' <p class="text-xs font-weight-bold mb-0"  style="text-align:left">' . $arrData[$i]['cargo'] . '</p>
                    <p class="text-xs text-secondary mb-0" style="text-align:left">Barber Shop</p>';

      $arrData[$i]['fecha_contratacionF'] =  '
                    <span class="text-center text-secondary text-xs font-weight-bold">' . $arrData[$i]['fecha_contratacion'] . '</span>
                  ';
      $arrData[$i]['accion'] = '<button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                          <span class="material-symbols-rounded" translate="no">Person_Edit</span>
                        </button>
                        
                        <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                          <span class="material-symbols-rounded" translate="no">Delete</span>
                        </button>';

      if ($arrData[$i]['status'] == 1) {
        $arrData[$i]['status'] = '
                        <span class="badge badge-sm bg-gradient-success" style="font-size:0.67rem;padding-top:7px">Activo</span>
               ';
      } else {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary" style="font-size:0.67rem;padding-top:7px">Inactivo</span>';
      }
    }
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function setEmpleado()
  {
    $arrPosts = [
      'txtNombre',
      'txtPassword',
      'txtTelefono',
      'txtSalario',
      'txtCargo',
      'txtFechaContratacion',
      'txtEstado'
    ];

    if (check_post($arrPosts)) {

      $strNombre = strClean($_POST['txtNombre']);
      $strPassword = hash("SHA256", strClean($_POST['txtPassword']));
      $strTelefono = strClean($_POST['txtTelefono']);
      $intSalario = intval(strClean($_POST['txtSalario']));
      $strCargo = strClean($_POST['txtCargo']);
      $strFechaContratacion = strClean($_POST['txtFechaContratacion']);
      $intStatus = intval(strClean($_POST['txtEstado']));
      $intIdEmpleado = intval(strClean($_POST['txtIdEmpleado']));


      try {
        if ($intIdEmpleado == 0 || $intIdEmpleado == "" || $intIdEmpleado == "0") {
          $insert = $this->model->insertEmpleado(
            $strNombre,
            $strPassword,
            $strTelefono,
            $strCargo,
            $strFechaContratacion,
            $intSalario,
          );
          $option = 1;
        } else {
          if ($intStatus == 0) {
            $intStatus = 1;
          }
          $insert = $this->model->updateEmpleado(
            $intIdEmpleado,
            $strNombre,
            $strPassword,
            $strTelefono,
            $strCargo,
            $strFechaContratacion,
            $intSalario,
            $intStatus
          );
          $option = 2;
        }

        if (intval($insert) > 0) {

          if ($option == 1) {
            $arrResponse = array('status' => true, 'msg' => 'Empleado insertado correctamente');
          }

          if ($option == 2) {
            $arrResponse = array('status' => true, 'msg' => 'Empleado actualizado correctamente');
          }
        } else if ($insert == 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Ya existe un Empleado con el mismo Telefono');
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

  public function getEmpleadoById($id)
  {

    $intId = intval(strClean($id));

    if ($intId > 0) {
      $arrData = $this->model->selectEmpleadoById($id);
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

  function deleteEmpleado()
  {
    if ($_POST) {
      $intIdEmpleado = intval($_POST['txtIdEmpleado']);
      $requestDelete = $this->model->deleteEmpleado($intIdEmpleado);

      if ($requestDelete) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario');
      }

      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }
}
