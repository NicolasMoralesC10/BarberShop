<?php

class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectSalesCitasToday()
    {
        $sql = "SELECT 
            SUM(c.total) AS total
          FROM citas c
          WHERE DATE(c.fechaInicio) = CURDATE() AND c.status = 1";
        $request = $this->select($sql);
        return $request;
    }

    public function selectCountCitasToday()
    {
        $sql = "SELECT 
            COUNT(c.id) AS total_citas
          FROM citas c
          WHERE DATE(c.fechaInicio) = CURDATE() AND c.status = 1";
        $request = $this->select($sql);
        return $request;
    }

    public function selectSalesCitasMonth()
    {
        $sql = "SELECT 
            SUM(c.total) AS total
          FROM citas c
          WHERE MONTH(c.fechaInicio) = MONTH(CURDATE()) AND YEAR(c.fechaInicio) = YEAR(CURDATE()) AND c.status = 1";
        $request = $this->select($sql);
        return $request;
    }

    public function selectSalesToday()
    {
        $sql = "SELECT SUM(v.total) AS total
        FROM ventas v
        WHERE DATE(v.fecha_venta) = CURDATE() AND v.status = 1";
        $request = $this->select($sql);
        return $request;
    }
}
