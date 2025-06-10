// Variables globales para los gráficos
let citasChart = null;
let ventasChart = null;

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar cards de estadísticas básicas
  loadBasicStats();
  
  // Inicializar card de citas semanales
  loadCitasWeekComplete();
  
  // Inicializar card de ventas semanales
  loadSalesWeekComplete();
  
  // Cargar gráfico de ventas anuales
  loadYearlySalesChart();
});

// ===== ESTADÍSTICAS BÁSICAS =====
function loadBasicStats() {
  // Función auxiliar para manejar respuestas que pueden ser null
function handleResponse(data) {
  // Verificar que es un array y tiene elementos
  if (!Array.isArray(data) || data.length === 0) {
    return 0;
  }
  
  const firstItem = data[0];
  return firstItem.total === null || firstItem.total === undefined ? 0 : parseFloat(firstItem.total);
}

  // Ventas de hoy
  fetch(base_url + `/dashboard/selectSalesToday`)
    .then((res) => res.json())
    .then((data) => {
      const total = handleResponse(data);
      document.getElementById("ventasHoy").innerText = `$${total}`;
    })
    .catch((err) => {
      console.error("Error al cargar ventas de hoy", err);
      document.getElementById("ventasHoy").innerText = "$0";
    });

  // Citas de hoy
  fetch(base_url + `/dashboard/selectCountCitasToday`)
    .then((res) => res.json())
    .then((data) => {
      const total = handleResponse(data);
      document.getElementById("citasHoy").innerText = total;
    })
    .catch((err) => {
      console.error("Error al cargar citas de hoy", err);
      document.getElementById("citasHoy").innerText = "0";
    });

  // Ventas por citas hoy
  fetch(base_url + `/dashboard/selectSalesCitasToday`)
    .then((res) => res.json())
    .then((data) => {
      const total = handleResponse(data);
      document.getElementById("ventasCitasHoy").innerText = `$${total}`;
    })
    .catch((err) => {
      console.error("Error al cargar ventas por citas de hoy", err);
      document.getElementById("ventasCitasHoy").innerText = "$0";
    });

  // Ventas de productos hoy
  fetch(base_url + `/dashboard/selectSalesProductsToday`)
    .then((res) => res.json())
    .then((data) => {
      const total = handleResponse(data);
      document.getElementById("ventasProductosHoy").innerText = `$${total}`;
    })
    .catch((err) => {
      console.error("Error al cargar ventas de productos hoy", err);
      document.getElementById("ventasProductosHoy").innerText = "$0";
    });
}

// ===== CARD DE CITAS SEMANALES =====
function loadCitasWeekComplete() {
  // Cargar estadísticas de la card de citas
  fetch(base_url + "/dashboard/getWeekStatsCard")
    .then(response => response.json())
    .then(data => {
      console.log("Stats de citas recibidas:", data);
      
      // Actualizar estadísticas de citas
      updateElement('total-citas', data.total_citas || '0');
      updateElement('promedio-dia', parseFloat(data.promedio_dia || 0).toFixed(1));
      updateElement('dia-destacado', data.dia_destacado || 'N/A');
      
      // Actualizar badge de tendencia de citas
      updateTrendBadge(data.porcentaje_cambio, data.tendencia);
    })
    .catch(error => {
      console.error("Error cargando estadísticas de citas:", error);
      showLoadingError(['total-citas', 'promedio-dia', 'dia-destacado']);
    });

  // Cargar datos del gráfico de citas
  fetch(base_url + "/dashboard/selectCitasWeek")
    .then(response => response.json())
    .then(data => {
      console.log("Datos del gráfico de citas:", data);
      
      // Preparar datos para el gráfico de barras
      const diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
      const citasPorDia = diasSemana.map(dia => {
        const found = data.find(item => item.dia === dia);
        return found ? parseInt(found.total_citas) : 0;
      });
      
      // Renderizar gráfico de citas
      renderCitasChart(citasPorDia);
    })
    .catch(error => {
      console.error("Error cargando datos del gráfico de citas:", error);
    });
}

function renderCitasChart(data) {
  const ctx = document.getElementById("chart-bars");
  if (!ctx) return;
  
  // Destruir gráfico existente si lo hay
  if (citasChart) {
    citasChart.destroy();
  }
  
  const ctx2d = ctx.getContext("2d");
  
  // Crear gradiente
  const gradientStroke = ctx2d.createLinearGradient(0, 230, 0, 50);
  gradientStroke.addColorStop(1, "rgba(94, 114, 228, 0.8)");
  gradientStroke.addColorStop(0.2, "rgba(94, 114, 228, 0.2)");
  gradientStroke.addColorStop(0, "rgba(94, 114, 228, 0)");
  
  citasChart = new Chart(ctx2d, {
    type: "bar",
    data: {
      labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
      datasets: [{
        label: "Citas",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: gradientStroke,
        data: data,
        maxBarThickness: 6
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: Math.max(...data) + 2,
            beginAtZero: true,
            padding: 10,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            },
            color: "#fff"
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            },
          }
        },
      },
    },
  });
}

// ===== CARD DE VENTAS SEMANALES =====
function loadSalesWeekComplete() {
  fetch(base_url + "/dashboard/selectSalesWeekCard")
    .then(response => response.json())
    .then(data => {
      console.log("Datos de ventas semanales:", data);
      
      // Actualizar estadísticas de ventas
      updateElement('total-ventas', data.estadisticas.total_ventas_semana || '0');
      updateElement('promedio-dia-ventas', parseFloat(data.estadisticas.promedio_ventas_dia || 0).toFixed(1));
      updateElement('monto-total', '$' + formatNumber(data.estadisticas.monto_total_semana || 0));
      updateElement('ticket-promedio', '$' + formatNumber(data.estadisticas.ticket_promedio || 0));
      
      // Actualizar destacados
      updateElement('dia-destacado-ventas', data.destacados.dia_destacado || 'N/A');
      updateElement('metodo-top', data.destacados.metodo_pago_top || 'N/A');
      
      // Actualizar badge de porcentaje de cambio
      const badge = document.getElementById('porcentaje-cambio');
      if (badge) {
        const porcentaje = data.porcentaje_cambio || 0;
        const isPositive = data.tendencia === 'positiva';
        
        badge.className = `badge fs-6 ${isPositive ? 'bg-success' : 'bg-danger'}`;
        badge.innerHTML = `
          <i class="fas fa-arrow-${isPositive ? 'up' : 'down'} me-1"></i>
          ${isPositive ? '+' : ''}${porcentaje}%
        `;
      }
      
      // Renderizar gráfico de ventas
      renderVentasChart(data.grafico);
    })
    .catch(error => {
      console.error("Error cargando datos de ventas:", error);
      showLoadingError(['total-ventas', 'promedio-dia', 'monto-total', 'ticket-promedio']);
    });
}

function renderVentasChart(graficoDatos) {
  const ctx = document.getElementById("chart-ventas");
  if (!ctx) return;
  
  // Destruir gráfico existente si lo hay
  if (ventasChart) {
    ventasChart.destroy();
  }
  
  const ctx2d = ctx.getContext("2d");
  
  // Preparar datos ordenados por día de la semana
  const diasOrdenados = graficoDatos.sort((a, b) => a.orden_dia - b.orden_dia);
  const labels = diasOrdenados.map(item => item.dia_semana.substring(0, 3)); // Primeras 3 letras
  const ventasData = diasOrdenados.map(item => parseInt(item.total_ventas) || 0);
  
  // Crear gradiente
  const gradientStroke = ctx2d.createLinearGradient(0, 230, 0, 50);
  gradientStroke.addColorStop(1, "rgba(94, 114, 228, 0.8)");
  gradientStroke.addColorStop(0.2, "rgba(94, 114, 228, 0.2)");
  gradientStroke.addColorStop(0, "rgba(94, 114, 228, 0)");
  
  ventasChart = new Chart(ctx2d, {
    type: "line",
    data: {
      labels: labels,
      datasets: [{
        label: "Ventas",
        tension: 0.4,
        borderWidth: 3,
        pointRadius: 4,
        pointBackgroundColor: "rgba(94, 114, 228, 1)",
        pointBorderColor: "transparent",
        borderColor: "rgba(94, 114, 228, 1)",
        backgroundColor: gradientStroke,
        fill: true,
        data: ventasData,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            suggestedMin: 0,
            beginAtZero: true,
            padding: 10,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            },
            color: "#fff"
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            },
          }
        },
      },
    },
  });
}

// ===== GRÁFICO DE VENTAS ANUALES =====
function loadYearlySalesChart() {
  fetch(base_url + "/dashboard/chartTimelineSales")
    .then((response) => response.json())
    .then((data) => {
      console.log("Datos de ventas anuales:", data);

      // Inicializar array con 12 meses en 0
      const monthlySales = Array(12).fill(0);

      // Iterar sobre todos los datos recibidos
      data.forEach((item) => {
        const mes = parseInt(item.mes.split("-")[1], 10) - 1; // Convertir mes a índice (0-11)
        monthlySales[mes] = parseFloat(item.total_mensual);
      });

      // Renderizar el gráfico
      renderYearlySalesChart(monthlySales);
    })
    .catch((error) => console.error("Error cargando datos anuales:", error));
}

function renderYearlySalesChart(monthlySales) {
  const ctx = document.getElementById("chart-line-sales");
  if (!ctx) return;
  
  const ctx2d = ctx.getContext("2d");

  const gradientStroke = ctx2d.createLinearGradient(0, 230, 0, 50);
  gradientStroke.addColorStop(1, "rgba(94, 114, 228, 0.2)");
  gradientStroke.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
  gradientStroke.addColorStop(0, "rgba(94, 114, 228, 0)");

  new Chart(ctx2d, {
    type: "line",
    data: {
      labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      datasets: [
        {
          label: "Ventas generales",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke,
          borderWidth: 3,
          fill: true,
          data: monthlySales,
          maxBarThickness: 6
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      interaction: {
        intersect: false,
        mode: "index"
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            padding: 10,
            color: "#fbfbfb",
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            },
            callback: function (value, index, values) {
              return new Intl.NumberFormat("es-CO", {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0
              }).format(value);
            }
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: "#ccc",
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2
            }
          }
        }
      }
    }
  });
}

// ===== FUNCIONES AUXILIARES =====
function updateElement(id, value) {
  const element = document.getElementById(id);
  if (element) {
    element.textContent = value;
  }
}

function formatNumber(value) {
  return new Intl.NumberFormat('es-CO').format(parseFloat(value) || 0);
}

function updateTrendBadge(porcentaje, tendencia) {
  const badgeElement = document.querySelector('.badge.bg-success, .badge.bg-danger, .badge.bg-warning');
  
  if (!badgeElement) return;
  
  const isPositive = tendencia === 'up' || parseFloat(porcentaje) >= 0;
  
  // Actualizar clases del badge
  badgeElement.className = `badge fs-6 ${isPositive ? 'bg-success' : 'bg-danger'}`;
  
  // Actualizar contenido del badge
  badgeElement.innerHTML = `
    <i class="fas fa-arrow-${isPositive ? 'up' : 'down'} me-1"></i>
    ${isPositive ? '+' : ''}${porcentaje}%
  `;
}

function showLoadingError(elementIds) {
  elementIds.forEach(id => {
    const element = document.getElementById(id);
    if (element) {
      element.textContent = 'Error';
      element.classList.add('text-danger');
    }
  });
}

// ===== FUNCIONES DE ACTUALIZACIÓN (OPCIONALES) =====
function refreshAllData() {
  loadBasicStats();
  loadCitasWeekComplete();
  loadSalesWeekComplete();
}

// Auto-refresh cada 5 minutos (opcional)
function startAutoRefresh() {
  setInterval(refreshAllData, 5 * 60 * 1000);
}
