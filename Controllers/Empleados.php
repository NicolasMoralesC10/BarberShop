<?php

class Empleados extends Controllers{
    public function __construct()
    {
        parent::__construct();
    }
    public function empleados()
    {
        $data['page_name'] = "empleados";
        $data['script'] = "empleados";
        $data['page_title'] = "Empleados";
        
        $this->views->getView($this, "empleados", $data);
    }
}