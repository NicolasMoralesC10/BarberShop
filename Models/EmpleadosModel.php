<?php

class EmpleadosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectEmpleados()
    {
        $sql = "SELECT * FROM empleados WHERE status >= 1 and status <= 2";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEmpleadoById(int $idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
        $sql = "SELECT * FROM empleados WHERE status >= 1 and status <= 2 AND id = {$this->idEmpleado}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertEmpleado(string $strNombre, string $strPassword, string $intTelefono, string $strCargo, string $strFechaContratacion, int $intSalario)
    {
        $this->strNombre = $strNombre;
        $this->strPassword = $strPassword;
        $this->intTelefono = $intTelefono;
        $this->strCargo = $strCargo;
        $this->strFechaContratacion = $strFechaContratacion;
        $this->intSalario = $intSalario;



        $query_empleados = "SELECT * FROM empleados WHERE telefono = {$this->intTelefono} AND status = 1";

        $request = $this->select_all($query_empleados);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO empleados(nombre, password, telefono, cargo, fecha_contratacion, salario, status) VALUES(?,?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->strPassword, $this->intTelefono, $this->strCargo, $this->strFechaContratacion, $this->intSalario, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateEmpleado(int $idEmpleado, string $strNombre, string $strPassword, string $intTelefono, string $strCargo, string $strFechaContratacion, int $intSalario, int $status)
    {
        $this->strNombre = $strNombre;
        $this->strPassword = $strPassword;
        $this->intTelefono = $intTelefono;
        $this->strCargo = $strCargo;
        $this->strFechaContratacion = $strFechaContratacion;
        $this->intSalario = $intSalario;
        $this->idEmpleado = $idEmpleado;
        $this->intStatus = $status;

        $sql = "SELECT * FROM empleados WHERE (telefono = {$this->intTelefono} AND status = 1) AND id != {$this->idEmpleado}";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE empleados SET nombre = ?, password = ?, telefono = ?, cargo = ?, fecha_contratacion = ?, salario = ?, status = ? WHERE status > 0 AND id = {$this->idEmpleado}";
            $arrData = array(
                $this->strNombre,
                $this->strPassword,
                $this->intTelefono,
                $this->strCargo,
                $this->strFechaContratacion,
                $this->intSalario,
                $this->intStatus,
                
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteEmpleado(int $idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;

        $sql = "UPDATE empleados SET status = ? WHERE id = ?";
        $arrData = array(2, $this->idEmpleado);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
