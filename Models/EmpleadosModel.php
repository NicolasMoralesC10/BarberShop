<?php

class EmpleadosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectEmpleados()
    {
        $sql = "SELECT * FROM empleados WHERE status > 0";
        $request = $this->select_all($sql);
        return $request;
    }
}
