<?php

class ClientesModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectClientes()
    {
        $sql = "SELECT * FROM clientes WHERE status > 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectClienteById(int $idCliente)
    {
        $this->idCliente = $idCliente;
        $sql = "SELECT * FROM clientes WHERE status > 0 AND id = {$this->idCliente}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertCliente(string $strNombre, string $strTelefono)
    {
        $this->strNombre = $strNombre;
        $this->strTelefono = $strTelefono;

        $query_empleados = "SELECT * FROM clientes WHERE telefono = {$this->strTelefono} AND status > 0";

        $request = $this->select_all($query_empleados);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO clientes (nombre, telefono, status) VALUES (?,?,?)";
            $arrData = array($this->strNombre, $this->strTelefono, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateCliente(int $idCliente, string $strNombre, string $strTelefono, int $status)
    {
        $this->idCliente = $idCliente;
        $this->strNombre = $strNombre;
        $this->strTelefono = $strTelefono;
        $this->intStatus = $status;

        $sql = "SELECT * FROM clientes WHERE (telefono = {$this->strTelefono} AND status = 1) AND id != {$this->idCliente}";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE clientes SET nombre = ?, telefono = ?, status = ? WHERE status > 0 AND id = {$this->idCliente}";
            $arrData = array(
                $this->strNombre,
                $this->strTelefono,
                $this->intStatus,
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteCliente(int $idCliente)
    {
        $this->idCliente = $idCliente;

        $sql = "UPDATE clientes SET status = ? WHERE id = ?";
        $arrData = array(0, $this->idCliente);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
