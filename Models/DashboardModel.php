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

    public function selectSalesProductsToday()
    {
        $sql = "SELECT 
            SUM(v.total) AS total
          FROM ventas v
          WHERE DATE(v.fecha_venta) = CURDATE() AND v.status = 1";
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

      public function chartTimelineSales()
    {
        $sql = "SELECT 
        DATE_FORMAT(fecha, '%Y-%m') AS mes,
        SUM(total) AS total_mensual
        FROM (
            -- Ventas
            SELECT 
                fecha_venta AS fecha,
                total
            FROM ventas
            WHERE status = 1  -- Si quieres filtrar por activos

            UNION ALL

            -- Citas
            SELECT 
                fechaInicio AS fecha,
                total
            FROM citas
            WHERE status = 1  -- Si quieres filtrar por activos
        ) AS datos_combinados
        GROUP BY mes
        ORDER BY mes;";
        $request = $this->select($sql);
        return $request;
    }
}
