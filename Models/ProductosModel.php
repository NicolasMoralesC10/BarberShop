<?php

class ProductosModel extends mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectProductos()
    {
        $sql = "SELECT * FROM productos WHERE status >= 1 and status <= 2";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProductoById(int $idProducto)
    {
        $this->idProducto = $idProducto;
        $sql = "SELECT * FROM productos WHERE status >= 1 and status <= 2 AND id = {$this->idProducto}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertProducto(string $strNombre, string $strDescripcion, string $intPrecio, string $intStock, string $intStockMin)
    {
        $this->strNombre = $strNombre;
        $this->strDescripcion = $strDescripcion;
        $this->intPrecio = $intPrecio;
        $this->intStock = $intStock;
        $this->intStockMin = $intStockMin;

            $query_insert = "INSERT INTO productos(nombre, descripcion, precio, stock, stockMin, status) VALUES(?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->strDescripcion, $this->intPrecio, $this->intStock, $this->intStockMin, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        return $respuesta;
    }

    public function updateProducto(int $idProducto, string $strNombre, string $strDescripcion, string $intPrecio, string $intStock, string $intStockMin, int $status)
    {
        $this->strNombre = $strNombre;
        $this->strDescripcion = $strDescripcion;
        $this->intPrecio = $intPrecio;
        $this->intStock = $intStock;
        $this->intStockMin = $intStockMin;
        $this->idProducto = $idProducto;
        $this->intStatus = $status;

            $query_insert = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, stockMin = ?, status = ? WHERE status > 0 AND id = {$this->idProducto}";
            $arrData = array(
                $this->strNombre,
                $this->strDescripcion,
                $this->intPrecio,
                $this->intStock,
                $this->intStockMin,
                $this->intStatus,
                
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        

        return $respuesta;
    }

    public function deleteProducto(int $idProducto)
    {
        $this->idProducto = $idProducto;

        $sql = "UPDATE productos SET status = ? WHERE id = ?";
        $arrData = array(0, $this->idProducto);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}