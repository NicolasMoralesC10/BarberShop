<?php

class Dashboard extends Controllers
{

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }
    public function dashboard()
    {
        $data['page_title'] = "Página de dashboard";
        $data['page_name'] = "dashboard";
        $data['script'] = "dashboard";

        $this->views->getView($this, "dashboard", $data);
    }
    public function selectSalesCitasToday()
    {
        $arrData = $this->model->selectSalesCitasToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectCountCitasToday()
    {
        $arrData = $this->model->selectCountCitasToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectSalesProductsToday()
    {
        $arrData = $this->model->selectSalesProductsToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectSalesToday()
    {
        $arrData = $this->model->selectSalesToday();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function chartTimelineSales()
    {
        $arrData = $this->model->chartTimelineSales();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectCitasWeek()
    {
        $arrData = $this->model->selectCitasWeek();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function chartCitasWeekComplete()
    {
        $data = $this->model->getCitasWeekComplete();
        $ocupacion = $this->model->getOccupancyRate();

        // Agregar datos de ocupación
        $data['ocupacion'] = $ocupacion;

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    // Citas por semana
    public function getWeekStatsCard()
    {
        $estadisticas = $this->model->getWeekStats();
        $comparacion = $this->model->getWeekComparison();
        $ocupacion = $this->model->getOccupancyRate();

        $response = [
            'total_citas' => $estadisticas['total_citas_semana'] ?? 0,
            'promedio_dia' => $estadisticas['promedio_por_dia'] ?? 0,
            'dia_destacado' => $estadisticas['dia_con_mas_citas'] ?? 'N/A',
            'porcentaje_cambio' => $comparacion['porcentaje_cambio'] ?? 0,
            'citas_semana_anterior' => $comparacion['citas_semana_anterior'] ?? 0,
            'porcentaje_ocupacion' => $ocupacion['porcentaje_ocupacion'] ?? 0,
            'tendencia' => ($comparacion['porcentaje_cambio'] ?? 0) >= 0 ? 'up' : 'down'
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    // Endpoint principal para la card de ventas (RECOMENDADO)
    public function selectSalesWeekCard()
    {
        $arrData = $this->model->selectSalesWeekCard();

        // Calcular porcentaje de cambio
        if (isset($arrData['estadisticas']) && isset($arrData['comparacion'])) {
            $ventasActual = $arrData['estadisticas']['total_ventas_semana'];
            $ventasAnterior = $arrData['comparacion']['ventas_semana_anterior'];

            if ($ventasAnterior > 0) {
                $porcentajeCambio = round((($ventasActual - $ventasAnterior) / $ventasAnterior) * 100, 1);
                $arrData['porcentaje_cambio'] = $porcentajeCambio;
                $arrData['tendencia'] = $porcentajeCambio >= 0 ? 'positiva' : 'negativa';
            } else {
                $arrData['porcentaje_cambio'] = $ventasActual > 0 ? 100 : 0;
                $arrData['tendencia'] = 'positiva';
            }
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function selectSalesTotalWeekChart()
    {
        $arrData = $this->model->selectSalesTotalWeekChart();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
