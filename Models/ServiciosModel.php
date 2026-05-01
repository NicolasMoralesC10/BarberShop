<?php

class ServiciosModel extends Mysql
{
    private int $idServicio = 0;
    private string $strNombre = '';
    private int $intPrecio = 0;
    private int $intDuracion = 0;
    private ?string $strDesc = null;
    private ?string $strImagen = null;
    private int $intStatus = 0;

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

    public function insertServicio(string $strNombre, int $intPrecio, int $intDuracion, string $strDesc, string $strImagen)
    {
        $this->strNombre = $strNombre;
        $this->intPrecio = $intPrecio;
        $this->intDuracion = $intDuracion;
        if (empty($strDesc)) {
            $this->strDesc = null;
        } else {
            $this->strDesc = $strDesc;
        }
        $this->strImagen = $strImagen;

        $query_servicios = "SELECT * FROM servicios WHERE nombre = '{$this->strNombre}' AND status > 0";
        $request = $this->select_all($query_servicios);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO servicios (nombre, precio, duracionMinutos, descripcion, imagen, status) VALUES (?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->intPrecio, $this->intDuracion, $this->strDesc, $this->strImagen, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateServicio(int $idServicio, string $strNombre, int $intPrecio, int $intDuracion, string $strDesc, string $strImagen, int $intStatus)
    {
        $this->idServicio = $idServicio;
        $this->strNombre = $strNombre;
        $this->intPrecio = $intPrecio;
        $this->intDuracion = $intDuracion;
        $this->intStatus = $intStatus;

        if (empty($strDesc)) {
            $this->strDesc = null;
        } else {
            $this->strDesc = $strDesc;
        }
        if (empty($strImagen)) {
            $this->strImagen = null;
        } else {
            $this->strImagen = $strImagen;
        }

        $sql = "SELECT * FROM servicios WHERE (nombre = '{$this->strNombre}' AND status > 0) AND id != {$this->idServicio}";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {

            if (empty($this->strImagen)) {
                $query_insert = "UPDATE servicios SET nombre = ?, precio = ?, duracionMinutos = ?, descripcion = ?, status = ? WHERE status > 0 AND id = {$this->idServicio}";
                $arrData = array(
                    $this->strNombre,
                    $this->intPrecio,
                    $this->intDuracion,
                    $this->strDesc,
                    $this->intStatus,
                );
                $reques_insert = $this->update($query_insert, $arrData);
                $respuesta = $reques_insert;
            } else {
                $query_insert = "UPDATE servicios SET nombre = ?, precio = ?, duracionMinutos = ?, descripcion = ?, imagen = ?, status = ? WHERE status > 0 AND id = {$this->idServicio}";
                $arrData = array(
                    $this->strNombre,
                    $this->intPrecio,
                    $this->intDuracion,
                    $this->strDesc,
                    $this->strImagen,
                    $this->intStatus,
                );
                $reques_insert = $this->update($query_insert, $arrData);
                $respuesta = $reques_insert;
            }
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
