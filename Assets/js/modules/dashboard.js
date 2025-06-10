var ctx = document.getElementById("chart-bars").getContext("2d");

new Chart(ctx, {
  type: "bar",
  data: {
    labels: ["M", "T", "W", "T", "F", "S", "S"],
    datasets: [
      {
        label: "Sales",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: "rgba(255, 255, 255, .8)",
        data: [50, 20, 10, 22, 50, 10, 40],
        maxBarThickness: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
    },
    interaction: {
      intersect: false,
      mode: "index",
    },
    scales: {
      y: {
        grid: {
          drawBorder: false,
          display: true,
          drawOnChartArea: true,
          drawTicks: false,
          borderDash: [5, 5],
          color: "rgba(255, 255, 255, .2)",
        },
        ticks: {
          suggestedMin: 0,
          suggestedMax: 500,
          beginAtZero: true,
          padding: 10,
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
          color: "#fff",
        },
      },
      x: {
        grid: {
          drawBorder: false,
          display: true,
          drawOnChartArea: true,
          drawTicks: false,
          borderDash: [5, 5],
          color: "rgba(255, 255, 255, .2)",
        },
        ticks: {
          display: true,
          color: "#f8f9fa",
          padding: 10,
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
        },
      },
    },
  },
});

var ctx2 = document.getElementById("chart-line").getContext("2d");

new Chart(ctx2, {
  type: "line",
  data: {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Mobile apps",
        tension: 0,
        borderWidth: 0,
        pointRadius: 5,
        pointBackgroundColor: "rgba(255, 255, 255, .8)",
        pointBorderColor: "transparent",
        borderColor: "rgba(255, 255, 255, .8)",
        borderColor: "rgba(255, 255, 255, .8)",
        borderWidth: 4,
        backgroundColor: "transparent",
        fill: true,
        data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
        maxBarThickness: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
    },
    interaction: {
      intersect: false,
      mode: "index",
    },
    scales: {
      y: {
        grid: {
          drawBorder: false,
          display: true,
          drawOnChartArea: true,
          drawTicks: false,
          borderDash: [5, 5],
          color: "rgba(255, 255, 255, .2)",
        },
        ticks: {
          display: true,
          color: "#f8f9fa",
          padding: 10,
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
        },
      },
      x: {
        grid: {
          drawBorder: false,
          display: false,
          drawOnChartArea: false,
          drawTicks: false,
          borderDash: [5, 5],
        },
        ticks: {
          display: true,
          color: "#f8f9fa",
          padding: 10,
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
        },
      },
    },
  },
});

var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

new Chart(ctx3, {
  type: "line",
  data: {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [
      {
        label: "Mobile apps",
        tension: 0,
        borderWidth: 0,
        pointRadius: 5,
        pointBackgroundColor: "rgba(255, 255, 255, .8)",
        pointBorderColor: "transparent",
        borderColor: "rgba(255, 255, 255, .8)",
        borderWidth: 4,
        backgroundColor: "transparent",
        fill: true,
        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
        maxBarThickness: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
    },
    interaction: {
      intersect: false,
      mode: "index",
    },
    scales: {
      y: {
        grid: {
          drawBorder: false,
          display: true,
          drawOnChartArea: true,
          drawTicks: false,
          borderDash: [5, 5],
          color: "rgba(255, 255, 255, .2)",
        },
        ticks: {
          display: true,
          padding: 10,
          color: "#f8f9fa",
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
        },
      },
      x: {
        grid: {
          drawBorder: false,
          display: false,
          drawOnChartArea: false,
          drawTicks: false,
          borderDash: [5, 5],
        },
        ticks: {
          display: true,
          color: "#f8f9fa",
          padding: 10,
          font: {
            size: 14,
            weight: 300,
            family: "Roboto",
            style: "normal",
            lineHeight: 2,
          },
        },
      },
    },
  },
});

var ctx1 = document.getElementById("chart-line-sales").getContext("2d");

var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

fetch(base_url + "/dashboard/chartTimelineSales")
  .then((response) => response.json())
  .then((data) => {
    const monthlySales = Array(12).fill(0);
    const mes = parseInt(data.mes.split("-")[1], 10) - 1;
    monthlySales[mes] = parseFloat(data.total_mensual);
    // continuar con el chart...

    // Renderizamos el chart con los datos dinámicos
    const ctx1 = document.getElementById("chart-line-sales").getContext("2d");

    const gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
    gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
    gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");

    new Chart(ctx1, {
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
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: monthlySales,
            maxBarThickness: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
            },
            ticks: {
              display: true,
              padding: 10,
              color: "#fbfbfb",
              font: {
                size: 11,
                family: "Open Sans",
                style: "normal",
                lineHeight: 2,
              },
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5],
            },
            ticks: {
              display: true,
              color: "#ccc",
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: "normal",
                lineHeight: 2,
              },
            },
          },
        },
      },
    });
  })
  .catch((error) => console.error("Error cargando datos:", error));

document.addEventListener("DOMContentLoaded", function () {
  // Función auxiliar para validar y formatear el número
  function formatValue(value) {
    return value === null || value === undefined || isNaN(value) ? 0 : parseFloat(value);
  }

  // Función para manejar los valores que podrían ser null
  function handleResponse(data) {
    // Verificamos si 'total' es null o undefined
    return data.total === null || data.total === undefined ? 0 : parseFloat(data.total);
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
      document.getElementById("ventasHoy").innerText = "$0"; // En caso de error mostrar 0
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
      document.getElementById("citasHoy").innerText = "0"; // En caso de error mostrar 0
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
      document.getElementById("ventasCitasHoy").innerText = "$0"; // En caso de error mostrar 0
    });

  // Ventas del mes
  fetch(base_url + `/dashboard/selectSalesProductsToday`)
    .then((res) => res.json())
    .then((data) => {
      const total = handleResponse(data);
      document.getElementById("ventasProductosHoy").innerText = `$${total}`;
    })
    .catch((err) => {
      console.error("Error al cargar ventas del mes", err);
      document.getElementById("ventasProductosHoy").innerText = "$0"; // En caso de error mostrar 0
    });
});
