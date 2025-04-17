<?php

class UsuariosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectUsuarios()
    {
        $sql = "SELECT * FROM usuarios WHERE status >= 0 and status <= 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertUsuario(string $strNombre, string $strPassword, string $intTelefono, string $strCargo, string $strFechaContratacion, int $intSalario)
    {
        $this->strNombre = $strNombre;
        $this->strPassword = $strPassword;
        $this->intTelefono = $intTelefono;
        $this->strCargo = $strCargo;
        $this->strFechaContratacion = $strFechaContratacion;
        $this->intSalario = $intSalario;



        $query_usuarios = "SELECT * FROM usuarios WHERE telefono = {$this->intTelefono} AND status = 1";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO usuarios(nombre, password, telefono, cargo, fechaContratacion, salario, status) VALUES(?,?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->strPassword, $this->intTelefono, $this->strCargo, $this->strFechaContratacion, $this->intSalario, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateUsuario(int $idUsuario, string $strNombre, string $strApellido, int $intDocumento, int $intTelefono, int $intGenero, string $strEmail, string $strCodigo, string $strRol, string $strFirma, int $status)
    {
        $this->strNombre = $strNombre;
        $this->strApellido = $strApellido;
        $this->intDocumento = $intDocumento;
        $this->intTelefono = $intTelefono;
        $this->intGenero = $intGenero;
        $this->strEmail = $strEmail;
        $this->strCodigo = $strCodigo;
        $this->strFirma = $strFirma;
        $this->strRol = $strRol;
        $this->idUduario = $idUsuario;
        $this->intStatus = $status;

        $sql = "SELECT * FROM usuario WHERE (documento = '{$this->intDocumento}' AND codigo = '{$this->strCodigo}' AND idUsuarios != {$this->idUduario})";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, genero = ?, correo = ?, codigo = ?, firma = ?, rol = ?, status = ? WHERE status > 0 AND idUsuarios = {$this->idUduario}";
            $arrData = array(
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->intGenero,
                $this->strEmail,
                $this->strCodigo,
                $this->strFirma,
                $this->strRol,
                $this->intStatus
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;

        $sql = "UPDATE usuarios SET status = ? WHERE id = ?";
        $arrData = array(2, $this->idUsuario);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
