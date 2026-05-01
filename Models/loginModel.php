<?php

class LoginModel extends Mysql
{
    private string $strTelefono = '';
    private string $strPassword = '';
    private int $intEmpleado = 0;
    private string $strCorreo = '';
    private string $codigo = '';
    private string $correo = '';
    private string $nueva_contra = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function loginUser(string $telefono, string $password): mixed
    {
        $this->strTelefono = $telefono;
        $this->strPassword = $password;

        $sql = "SELECT id, status FROM empleados
                WHERE telefono = '{$this->strTelefono}'
                AND password = '{$this->strPassword}'
                AND status = 1";

        return $this->select($sql);
    }

    public function sessionLogin(int $idEmple): mixed
    {
        $this->intEmpleado = $idEmple;

        $sql = "SELECT e.id, e.nombre, e.telefono, e.cargo,
                       e.fecha_contratacion, e.salario, e.status
                FROM empleados e
                WHERE e.id = {$this->intEmpleado}";

        return $this->select($sql);
    }

    public function insertRecuperacion(string $correo, string $codigo): mixed
    {
        $this->strCorreo = $correo;
        $this->codigo    = $codigo;

        $sql     = "INSERT INTO recuperacion(correo, codigo) VALUES(?,?)";
        $arrData = [$this->strCorreo, $this->codigo];

        return $this->insert($sql, $arrData);
    }

    public function selectCodigoYCorreo(string $codigo, string $correo): mixed
    {
        $this->codigo = $codigo;
        $this->correo = $correo;

        $sql = "SELECT * FROM recuperacion
                WHERE codigo = '{$this->codigo}' AND correo = '{$this->correo}'";

        return $this->select_all($sql);
    }

    public function updatePassword(string $nueva_contra, string $correo): mixed
    {
        $this->nueva_contra = $nueva_contra;
        $this->correo       = $correo;

        $sql     = "UPDATE usuario SET password = ? WHERE correo = '{$this->correo}'";
        $arrData = [$this->nueva_contra];

        return $this->update($sql, $arrData);
    }

    public function deleteCodeRecu(string $codigo, string $correo): mixed
    {
        $this->codigo = $codigo;
        $this->correo = $correo;

        $sql = "DELETE FROM recuperacion
                WHERE codigo = '{$this->codigo}' AND correo = '{$this->correo}'";

        return $this->delete($sql);
    }
}
