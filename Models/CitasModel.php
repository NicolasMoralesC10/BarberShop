<?php

class CitasModel extends mysql{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectCitas()
    {
        $sql = "SELECT * FROM citas WHERE status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

}