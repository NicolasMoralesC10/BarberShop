<?php

class Servicios extends Controllers
{
  public function __construct()
  {
    parent::__construct();
  }

  public function servicios()
  {
    $data['page_title'] = "Servicios";
    $data['page_name'] = "Servicios";
    $data['script'] = "servicios";

    $this->views->getView($this, "servicios", $data);
  }

  public function getServicios()
  {
    $arrData = $this->model->selectServicios();
    for ($i = 0; $i < count($arrData); $i++) {
      if ($arrData[$i]['status'] >= 0) {
        $arrData[$i]['card'] = '<div class="card col-3 mt-5" data-animation="true">
                                      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                        <a class="d-block blur-shadow-image">
                                          <img src="' . $arrData[$i]['imagen'] . '" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                        </a>
                                        <div class="colored-shadow" style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);"></div>
                                      </div>
                                      <div class="card-body text-center">
                                        <div class="d-flex mt-n6 mx-auto">
                                          <button class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Edit" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                                            <i class="material-symbols-rounded text-lg">edit</i>
                                          </button>
                                          <button class="btn btn-link text-info me-auto border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                                            <i class="material-symbols-rounded text-lg">delete</i>
                                          </button>
                                        </div>
                                        <h5 class="font-weight-normal mt-3">
                                          <a href="javascript:;">' . $arrData[$i]['nombre'] . '</a>
                                        </h5>
                                        <p class="mb-0">
                                          ' . $arrData[$i]['descripcion'] . '
                                        </p>
                                      </div>
                                      <hr class="dark horizontal my-0">
                                      <div class="card-footer d-flex">
                                        <p class="font-weight-normal my-auto ms-auto me-auto">$' . $arrData[$i]['precio'] . '</p>
                                      </div>
                                    </div>';
      }

      /* $arrData[$i]['accion'] = '<button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="edit" data-id="' . $arrData[$i]['id'] . '">
                                  <i class="material-symbols-rounded">Person_Edit</i>
                                </button>

                                <button type="button" class="text-secondary font-weight-bold text-xs" style="text-align:left; border:none; background:transparent" data-action="delete" data-id="' . $arrData[$i]['id'] . '">
                                  <i class="material-symbols-rounded">Delete</i>
                                </button>'; */

      /* if ($arrData[$i]['status'] == 1) {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-success" style="font-size:0.7rem;">Online</span>';
      } else {
        $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary">Offline</span>';
      } */
    }
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }

  public function getServicioById($id)
  {
    $intId = intval(strClean($id));

    if ($intId > 0) {
      $arrData = $this->model->selectServicioById($id);
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

  public function setServicio()
  {
    $arrPosts = [
      'txtNombre',
      'txtPrecio',
      'txtDuracion'
    ];

    if (check_post($arrPosts)) {
      $strNombre = strClean($_POST['txtNombre']);
      $intPrecio = intval(strClean($_POST['txtPrecio']));
      $intDuracion = intval(strClean($_POST['txtDuracion']));
      $strDesc = strClean($_POST['txtDescripcion']);
      $intStatus = 1;
      $intIdServicio = intval(strClean($_POST['txtIdServicio']));

      // Manejo de imagen
      $strImagen = '';
      if (isset($_FILES['txtImagen']) && $_FILES['txtImagen']['error'] === 0) {
        $nombreOriginal = $_FILES['txtImagen']['name'];
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nombreNuevo = uniqid('img_') . '.' . $extension;
        $rutaDestino = 'uploads/servicios/' . $nombreNuevo;

        // Mover el archivo a la carpeta final
        if (move_uploaded_file($_FILES['txtImagen']['tmp_name'], $rutaDestino)) {
          $strImagen = $rutaDestino; // Ruta que vas a guardar en la base de datos
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al subir la imagen.');
          echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
          return;
        }
      }

      try {
        if ($intIdServicio === 0 || $intIdServicio === "" || $intIdServicio === "0") {
          $insert = $this->model->insertServicio(
            $strNombre,
            $intPrecio,
            $intDuracion,
            $strDesc,
            $strImagen,
            $intStatus,
          );
          $option = 1;
        } else {
          if ($intStatus == 0) {
            $intStatus = 1;
          }
          $insert = $this->model->updateServicio(
            $intIdServicio,
            $strNombre,
            $intPrecio,
            $intDuracion,
            $strDesc,
            $strImagen,
            $intStatus,
          );
          $option = 2;
        }

        if (intval($insert) > 0) {
          if ($option == 1) {
            $arrResponse = array('status' => true, 'msg' => 'Servicio creado correctamente.');
          }

          if ($option == 2) {
            $arrResponse = array('status' => true, 'msg' => 'Servicio actualizado correctamente.');
          }
        } else if ($insert == 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Ya existe este servicio.');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al crear el servicio.');
        }
      } catch (\Throwable $th) {
        $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
      }
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos.');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  function deleteServicio()
  {
    if ($_POST) {
      $intIdServicio = intval($_POST['txtIdServicio']);
      $requestDelete = $this->model->deleteServicio($intIdServicio);

      if ($requestDelete) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el servicio');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el servicio');
      }

      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }
}
