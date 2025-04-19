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

  public function getCitas()
  {
    $arrData = $this->model->selectCitas();
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
  }
}
