<?php

class ServiciosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectServicios()
    {
        $sql = "SELECT * FROM servicios WHERE status > 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectServicioById(int $idServicio)
    {
        $this->idServicio = $idServicio;
        $sql = "SELECT * FROM servicios WHERE status > 0 AND id = {$this->idServicio}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertServicio(string $strNombre, int $intPrecio, string $strDesc = null, string $strImagen)
    {
        $this->strNombre = $strNombre;
        $this->intPrecio = $intPrecio;
        $this->strDesc = $strDesc;
        $this->strImagen = $strImagen;

        $query_servicios = "SELECT * FROM servicios WHERE nombre = '{$this->strNombre}' AND status > 0";
        $request = $this->select_all($query_servicios);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO servicios (nombre, precio, descripcion, imagen, status) VALUES (?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->intPrecio, $this->strDesc, $this->strImagen, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateServicio(int $idServicio, string $strNombre, int $intPrecio, string $strDesc = null, int $intStatus)
    {
        $this->idServicio = $idServicio;
        $this->strNombre = $strNombre;
        $this->intPrecio = $intPrecio;
        $this->strDesc = $strDesc;
        $this->intStatus = $intStatus;

        $sql = "SELECT * FROM servicios WHERE (nombre = {$this->strNombre} AND status > 0) AND id != {$this->idServicio}";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE servicios SET nombre = ?, precio = ?, descripcion = ?, status = ? WHERE status > 0 AND id = {$this->idServicio}";
            $arrData = array(
                $this->strNombre,
                $this->intPrecio,
                $this->strDesc,
                $this->intStatus,
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteServicio(int $idServicio)
    {
        $this->idServicio = $idServicio;

        $sql = "UPDATE servicios SET status = ? WHERE id = ?";
        $arrData = array(0, $this->idServicio);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}
