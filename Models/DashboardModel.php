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
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCountCitasToday()
    {
        $sql = "SELECT 
            COUNT(c.id) AS total
          FROM citas c
          WHERE DATE(c.fechaInicio) = CURDATE() AND c.status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectSalesProductsToday()
    {
        $sql = "SELECT 
            SUM(v.total) AS total
          FROM ventas v
          WHERE DATE(v.fecha_venta) = CURDATE() AND v.status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCitasWeek()
    {
        $sql = "SELECT
        CASE DAYOFWEEK(fechaInicio)
            WHEN 1 THEN 'D'  -- Domingo
            WHEN 2 THEN 'L'  -- Lunes
            WHEN 3 THEN 'M'  -- Martes
            WHEN 4 THEN 'X'  -- Miércoles
            WHEN 5 THEN 'J'  -- Jueves
            WHEN 6 THEN 'V'  -- Viernes
            WHEN 7 THEN 'S'  -- Sábado
        END AS dia,
        COUNT(*) AS total_citas
        FROM citas
        WHERE status = 1 
        AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE(), 1)
        GROUP BY DAYOFWEEK(fechaInicio), dia
        ORDER BY FIELD(dia, 'L', 'M', 'X', 'J', 'V', 'S', 'D');";

        $request = $this->select_all($sql);
        return $request;
    }

    // 2. Estadísticas completas de la semana actual
    public function getWeekStats()
    {
        $sql = "SELECT 
        -- Total de citas de la semana actual
        COUNT(*) as total_citas_semana,
        
        -- Promedio por día (total/7)
        ROUND(COUNT(*) / 7, 1) as promedio_por_dia,
        
        -- Día con más citas
        (SELECT 
            CASE DAYOFWEEK(fechaInicio)
                WHEN 1 THEN 'Domingo'
                WHEN 2 THEN 'Lunes'
                WHEN 3 THEN 'Martes'
                WHEN 4 THEN 'Miércoles'
                WHEN 5 THEN 'Jueves'
                WHEN 6 THEN 'Viernes'
                WHEN 7 THEN 'Sábado'
            END
         FROM citas c2 
         WHERE c2.status = 1 
         AND YEARWEEK(c2.fechaInicio, 1) = YEARWEEK(CURDATE(), 1)
         GROUP BY DAYOFWEEK(c2.fechaInicio)
         ORDER BY COUNT(*) DESC 
         LIMIT 1
        ) as dia_con_mas_citas,
        
        -- Máximo de citas en un día
        (SELECT COUNT(*) 
         FROM citas c3 
         WHERE c3.status = 1 
         AND YEARWEEK(c3.fechaInicio, 1) = YEARWEEK(CURDATE(), 1)
         GROUP BY DATE(c3.fechaInicio)
         ORDER BY COUNT(*) DESC 
         LIMIT 1
        ) as max_citas_dia
        
        FROM citas 
        WHERE status = 1 
        AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE(), 1);";

        $request = $this->select($sql);
        return $request;
    }

    // 3. Comparación con la semana anterior
    public function getWeekComparison()
    {
        $sql = "SELECT 
        -- Citas semana actual
        (SELECT COUNT(*) 
         FROM citas 
         WHERE status = 1 
         AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE(), 1)
        ) as citas_semana_actual,
        
        -- Citas semana anterior
        (SELECT COUNT(*) 
         FROM citas 
         WHERE status = 1 
         AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)
        ) as citas_semana_anterior,
        
        -- Cálculo del porcentaje de cambio
        CASE 
            WHEN (SELECT COUNT(*) FROM citas WHERE status = 1 AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)) = 0 
            THEN 100
            ELSE ROUND(
                ((SELECT COUNT(*) FROM citas WHERE status = 1 AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE(), 1)) - 
                 (SELECT COUNT(*) FROM citas WHERE status = 1 AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1))) * 100.0 / 
                 (SELECT COUNT(*) FROM citas WHERE status = 1 AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1)), 1
            )
        END as porcentaje_cambio;";

        $request = $this->select($sql);
        return $request;
    }

    // 4. Método combinado que devuelve todos los datos necesarios para la card
    public function getCitasWeekComplete()
    {
        $citasPorDia = $this->selectCitasWeek();
        $estadisticas = $this->getWeekStats();
        $comparacion = $this->getWeekComparison();

        return [
            'citas_por_dia' => $citasPorDia,
            'estadisticas' => $estadisticas,
            'comparacion' => $comparacion
        ];
    }

    // 5. Consulta para el porcentaje de ocupación (asumiendo horarios disponibles)
    public function getOccupancyRate()
    {
        $sql = "SELECT 
        -- Total de citas de la semana
        COUNT(*) as citas_realizadas,
        
        -- Suponiendo 8 horas de trabajo por día, 7 días a la semana, 1 cita por hora
        (7 * 8) as slots_disponibles,
        
        -- Porcentaje de ocupación
        ROUND((COUNT(*) * 100.0) / (7 * 8), 1) as porcentaje_ocupacion
        
        FROM citas 
        WHERE status = 1 
        AND YEARWEEK(fechaInicio, 1) = YEARWEEK(CURDATE(), 1);";

        $request = $this->select($sql);
        return $request;
    }

    // Método principal para obtener todos los datos de la card
    public function selectSalesWeekCard()
    {
        $data = array();

        // 1. Datos para el gráfico - ventas por día de la semana actual
        $data['grafico'] = $this->selectSalesWeekChart();

        // 2. Estadísticas principales de la semana
        $data['estadisticas'] = $this->selectSalesWeekStats();

        // 3. Comparación con semana anterior
        $data['comparacion'] = $this->selectSalesWeekComparison();

        // 4. Día destacado y método de pago top
        $data['destacados'] = $this->selectSalesWeekHighlights();

        return $data;
    }

    // Datos para el gráfico de barras
    public function selectSalesWeekChart()
    {
        $sql = "SELECT 
                    CASE DAYOFWEEK(fecha_venta)
                        WHEN 1 THEN 'Domingo'
                        WHEN 2 THEN 'Lunes'
                        WHEN 3 THEN 'Martes'
                        WHEN 4 THEN 'Miércoles'
                        WHEN 5 THEN 'Jueves'
                        WHEN 6 THEN 'Viernes'
                        WHEN 7 THEN 'Sábado'
                    END as dia_semana,
                    DAYOFWEEK(fecha_venta) as orden_dia,
                    COUNT(*) as total_ventas,
                    SUM(total) as monto_total_dia
                FROM ventas 
                WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)
                    AND status = 1
                GROUP BY DAYOFWEEK(fecha_venta)
                ORDER BY DAYOFWEEK(fecha_venta)";

        $request = $this->select_all($sql);

        // Asegurar que todos los días estén presentes (incluso con 0 ventas)
        $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $resultado = array();

        foreach ($diasSemana as $index => $dia) {
            $encontrado = false;
            foreach ($request as $row) {
                if ($row['dia_semana'] == $dia) {
                    $resultado[] = $row;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $resultado[] = array(
                    'dia_semana' => $dia,
                    'orden_dia' => $index + 1,
                    'total_ventas' => 0,
                    'monto_total_dia' => 0
                );
            }
        }

        return $resultado;
    }

    // Estadísticas principales de la semana
    public function selectSalesWeekStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_ventas_semana,
                    COALESCE(SUM(total), 0) as monto_total_semana,
                    ROUND(COUNT(*) / 7, 0) as promedio_ventas_dia,
                    ROUND(COALESCE(AVG(total), 0), 2) as ticket_promedio
                FROM ventas 
                WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)
                    AND status = 1";

        $request = $this->select($sql);
        return $request;
    }

    // Comparación con semana anterior
    public function selectSalesWeekComparison()
    {
        $sql = "SELECT 
                    COUNT(*) as ventas_semana_anterior,
                    COALESCE(SUM(total), 0) as monto_semana_anterior
                FROM ventas 
                WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1) - 1
                    AND status = 1";

        $request = $this->select($sql);
        return $request;
    }

    // Día destacado y método de pago más usado
    public function selectSalesWeekHighlights()
    {
        $data = array();

        // Día con más ventas
        $sqlDia = "SELECT 
                    CASE DAYOFWEEK(fecha_venta)
                        WHEN 1 THEN 'Domingo'
                        WHEN 2 THEN 'Lunes'
                        WHEN 3 THEN 'Martes'
                        WHEN 4 THEN 'Miércoles'
                        WHEN 5 THEN 'Jueves'
                        WHEN 6 THEN 'Viernes'
                        WHEN 7 THEN 'Sábado'
                    END as dia_destacado,
                    COUNT(*) as cantidad_ventas
                FROM ventas 
                WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)
                    AND status = 1
                GROUP BY DAYOFWEEK(fecha_venta)
                ORDER BY COUNT(*) DESC
                LIMIT 1";

        $resultDia = $this->select($sqlDia);
        $data['dia_destacado'] = $resultDia ? $resultDia['dia_destacado'] : 'N/A';

        // Método de pago más usado
        $sqlMetodo = "SELECT 
                        metodo_pago,
                        COUNT(*) as cantidad_usos
                    FROM ventas 
                    WHERE YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)
                        AND status = 1
                    GROUP BY metodo_pago
                    ORDER BY COUNT(*) DESC
                    LIMIT 1";

        $resultMetodo = $this->select($sqlMetodo);
        $data['metodo_pago_top'] = $resultMetodo ? $resultMetodo['metodo_pago'] : 'N/A';

        return $data;
    }

    public function selectSalesToday()
    {
        $sql = "SELECT SUM(total_combined) AS total
            FROM (
                SELECT SUM(v.total) AS total_combined
                FROM ventas v
                WHERE DATE(v.fecha_venta) = CURDATE() AND v.status = 1
                
                UNION ALL
                
                SELECT SUM(c.total) AS total_combined
                FROM citas c
                WHERE DATE(c.fechaInicio) = CURDATE() AND c.status = 1
            ) AS combined_totals";

        $request = $this->select_all($sql);
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
        $request = $this->select_all($sql);
        return $request;
    }
}
