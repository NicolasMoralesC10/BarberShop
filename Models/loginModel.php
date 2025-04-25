<?php

class LoginModel extends Mysql
{

    private $intIdUsuario;
    private $strTelefono;
    private $strToken;

    public function __construct()
    {
        parent::__construct();
    }

    public function loginUser(string $telefono, string $password)
    {
        $this->strTelefono = $telefono;
        $this->strPassword = $password;
        $sql = "SELECT id, status FROM empleados WHERE
        telefono = '{$this->strTelefono}' AND
        password = '{$this->strPassword}' AND
        status = 1";

        $request = $this->select($sql);
        return $request;
    }

    public function sessionLogin(int $idEmple)
    {
        $this->intEmpleado = $idEmple;

        $sql = "SELECT e.id, 
        e.nombre,
        e.telefono,
        e.cargo,
        e.fecha_contratacion,
        e.salario,
        e.status
        FROM empleados e
        WHERE e.id = {$this->intEmpleado}";

        $request = $this->select($sql);
        return $request;
    }

    public function insertRecuperacion($correo, $codigo)
    {
        $this->strCorreo = $correo;
        $this->codigo = $codigo;
        $sql = "INSERT INTO recuperacion(correo, codigo) VALUES(?,?)";
        $arrData = array($this->strCorreo, $this->codigo);
        $request_insert = $this->insert($sql, $arrData);
        $respuesta = $request_insert;

        return $respuesta;
    }

    public function selectCodigoYCorreo(string $codigo,string $correo)
    {
        $this->codigo = $codigo;
        $this->correo = $correo;
        $sql = "SELECT * FROM recuperacion WHERE codigo = '{$this->codigo}' AND correo = '{$this->correo}'";
        $requestGlobal = $this->select_all($sql);
        
        return $requestGlobal;
    }

    public function updatePassword($nueva_contra, $correo)
    {
        $this->nueva_contra = $nueva_contra;
        $this->correo = $correo;
        $sql = "UPDATE usuario SET password = ? WHERE correo = '{$this->correo}'";
        $arrData = array($this->nueva_contra);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deleteCodeRecu($codigo, $correo)
    {
        $this->codigo = $codigo;
        $this->correo = $correo;
        $sql = "DELETE FROM recuperacion WHERE codigo = '{$this->codigo}' AND correo = '{$this->correo}'";
        $request = $this->delete($sql);
        return $request;
    }
}
