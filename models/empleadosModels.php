<?php

class EmpleadosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectInasistencias($idUsuario)
    {
        $sql = "";
        $request = $this->select_all($sql);
        return $request;
    }
}