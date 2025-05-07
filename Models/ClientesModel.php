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

    public function insertCliente(string $strNombre, string $strTelefono, string $strObservaciones)
    {
        $this->strNombre = $strNombre;
        $this->strTelefono = $strTelefono;
        if (empty($strObservaciones)) {
            $this->strObservaciones = null;
        } else {
            $this->strObservaciones = $strObservaciones;
        }

        $query_clientes = "SELECT * FROM clientes WHERE telefono = {$this->strTelefono} AND status > 0";
        $request = $this->select_all($query_clientes);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO clientes (nombre, telefono, observaciones, status) VALUES (?,?,?,?)";
            $arrData = array($this->strNombre, $this->strTelefono, $this->strObservaciones, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $respuesta = $request_insert;
        }

        return $respuesta;
    }

    public function updateCliente(int $idCliente, string $strNombre, string $strTelefono, string $strObservaciones, int $status)
    {
        $this->idCliente = $idCliente;
        $this->strNombre = $strNombre;
        $this->strTelefono = $strTelefono;
        if (empty($strObservaciones)) {
            $this->strObservaciones = null;
        } else {
            $this->strObservaciones = $strObservaciones;
        }
        $this->intStatus = $status;

        $sql = "SELECT * FROM clientes WHERE (telefono = {$this->strTelefono} AND status > 0) AND id != {$this->idCliente}";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE clientes SET nombre = ?, telefono = ?, observaciones = ?, status = ? WHERE status > 0 AND id = {$this->idCliente}";
            $arrData = array(
                $this->strNombre,
                $this->strTelefono,
                $this->strObservaciones,
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
