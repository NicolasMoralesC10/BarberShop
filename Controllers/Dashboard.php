<?php

class Dashboard extends Controllers
{

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }
    public function dashboard()
    {
        $data['page_title'] = "Página de dashboard";
        $data['page_name'] = "dashboard";
        $data['script'] = "dashboard";

        $this->views->getView($this, "dashboard", $data);
    }
    public function selectSalesCitasToday()
    {
        $arrData = $this->model->selectSalesCitasToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    } 

    public function selectCountCitasToday()
    {
        $arrData = $this->model->selectCountCitasToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectSalesProductsToday()
    {
        $arrData = $this->model->selectSalesProductsToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectSalesToday()
    {
        $arrData = $this->model->selectSalesToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function chartTimelineSales(){
        $arrData = $this->model->chartTimelineSales();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectCitasWeek(){
        $arrData = $this->model->selectCitasWeek();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
