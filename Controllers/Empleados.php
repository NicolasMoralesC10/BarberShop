<?php

class Empleados extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }
    public function empleados()
    {
        $data['page_title'] = "Empleados";
        $data['page_name'] = "empleados";
        $data['script'] = "empleados";


        $this->views->getView($this, "empleados", $data);
    }
    public function getEmpleados()
    {
        $arrData = $this->model->selectEmpleados();
        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['nombreF'] = ' <div class="d-flex px-2 py-1">
                      <div>
                        <img src="/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">' . $arrData[$i]['nombre'] . '</h6>
                        <p class="text-xs text-secondary mb-0">john@creative-tim.com</p>
                      </div>
                    </div>';
            $arrData[$i]['cargoF'] = ' <p class="text-xs font-weight-bold mb-0">' . $arrData[$i]['cargo'] . '</p>
                    <p class="text-xs text-secondary mb-0">Organization</p>';

            $arrData[$i]['fecha_contratacionF'] =  '
                    <span class="text-secondary text-xs font-weight-bold" style="margin-left: 35%;">' . $arrData[$i]['fecha_contratacion'] . '</span>
                  ';
            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-success" style="margin-left: 30%;">Online</span>';
            } else {
                $arrData[$i]['status'] = '<span class="badge badge-sm bg-gradient-secondary">Offline</span>';
            }
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
