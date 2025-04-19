document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendarioCitas");

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "es",
    themeSystem: "bootstrap5",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    buttonText: {
      today: "Hoy",
      month: "Mes",
      week: "Semana",
      day: "Día"
    },
    slotLabelFormat: {
      hour: "numeric",
      minute: "2-digit",
      hour12: true
    },
    allDayText: "Todo el día",
    height: "auto",
    nowIndicator: true,

    // === Reemplazamos events.url por una función que fetch + transforma ===
    events: function (fetchInfo, successCallback, failureCallback) {
      fetch(base_url + "/citas/getCitas")
        .then((res) => {
          if (!res.ok) throw new Error("Error HTTP" + res.status);
          return res.json();
        })
        .then((data) => {
          // data es tu array de objetos con servicios y empleados como strings
          const events = data.map((item) => {
            // convertimos strings a arrays
            const servicios = item.servicios ? item.servicios.split(",").map((s) => s.trim()) : [];
            const empleados = item.empleados ? item.empleados.split(",").map((e) => e.trim()) : [];
            const duraciones = item.duraciones ? item.duraciones.split(",").map((d) => Number(d)) : [];
            // parseamos total a number
            const total = Number(item.total);

            /*    // mapeo de colores según status (ajusta valores según tu paleta)
            const classMap = {
              1: { bg: "#FFEB3B", border: "#FBC02D" }, // pendiente
              2: { bg: "#4CAF50", border: "#388E3C" }, // confirmada
              3: { bg: "#F44336", border: "#D32F2F" } // cancelada
            }; */
            /* const colors = colorMap[item.status] || { bg: "#9E9E9E", border: "#616161" }; */

            return {
              id: item.id,
              title: item.cliente,
              start: item.start,
              end: item.end,
              classNames: ["bg-gradient-dark text-light"],
              /* backgroundColor: colors.bg, */
              /*  borderColor: colors.border, */
              extendedProps: {
                servicios,
                empleados,
                duraciones,
                total,
                status: item.status
              }
            };
          });

          successCallback(events);
        })
        .catch((err) => {
          console.error(err);
          failureCallback(err);
        });
    },

    // eventContent y eventDidMount asumiendo ya servicios/empleados como arrays
    eventContent: function (arg) {
      let serv = arg.event.extendedProps.servicios.join(", ");
      let total = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP"
      }).format(arg.event.extendedProps.total);
      return {
        html: `
          <div class="fc-event-title fc-font-weight-bold" style="display: flex; justify-content: space-between;">
            ${arg.event.title}
            <div class="fc-event-time">${arg.timeText}</div>
          </div>
          
        `
      };
    },
    eventDidMount: function (info) {
      let e = info.event.extendedProps;
      let servicios = e.servicios.join(", ");
      let empleados = e.empleados.join(", ");
      let total = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP"
      }).format(e.total);
      let tpl = `
        <strong>Cliente:</strong> ${info.event.title}<br/>
        <strong>Servicios:</strong> ${servicios}<br/>
        <strong>Empleados:</strong> ${empleados}<br/>
        <strong>Total:</strong> ${total}<br/>
        
      `;
      new bootstrap.Tooltip(info.el, {
        title: tpl,
        html: true,
        placement: "top",
        container: calendarEl
      });
    },

    eventClick: function (info) {
      // 1) Prepara los datos que necesitas en la modal
      const data = {
        id: info.event.id,
        cliente: info.event.title,
        start: info.event.startStr,
        end: info.event.endStr,
        servicios: info.event.extendedProps.servicios,
        empleados: info.event.extendedProps.empleados,
        duracionPorServicio: info.event.extendedProps.duraciones,
        total: info.event.extendedProps.total,
        status: info.event.extendedProps.status,
        notas: info.event.extendedProps.notas || ""
      };

      // 2) Abre la modal pasando esos datos
      abrirModalEditar(data);
    }
  });

  calendar.render();
});

function abrirModalEditar(data) {
  // data: { id, cliente, start, end, servicios:[], empleados:[], total, status, notas }
  document.getElementById("mc-cliente").textContent = data.cliente;
  document.getElementById("mc-fecha").textContent = new Date(data.start).toLocaleDateString("es-CO", {
    dateStyle: "medium"
  });
  document.getElementById("mc-hora").textContent =
    new Date(data.start).toLocaleTimeString("es-CO", { timeStyle: "short" }) +
    " – " +
    new Date(data.end).toLocaleTimeString("es-CO", { timeStyle: "short" });
  // badge de estado
  const statusBadge = document.getElementById("mc-status");
  const statusMap = {
    1: ["Pendiente", "bg-gradient-dark text-light"],
    2: ["Confirmada", "bg-success"],
    3: ["Cancelada", "bg-danger"]
  };
  const [label, classes] = statusMap[data.status] || ["Desconocido", "bg-secondary"];
  statusBadge.textContent = label;
  statusBadge.className = "badge " + classes;

  // servicios list
  const ul = document.getElementById("mc-servicios");
  ul.innerHTML = "";
  data.servicios.forEach((serv, i) => {
    const li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between";
    li.innerHTML = `
      <span>${serv}</span>
      <span class="text-muted small">${data.duracionPorServicio[i] || ""} min / ${data.empleados[i]}</span>
    `;
    ul.appendChild(li);
  });

  // notas y total
  document.getElementById("mc-notas").value = data.notas || "";
  document.getElementById("mc-total").textContent = new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP"
  }).format(data.total);

  // muestra modal
  new bootstrap.Modal(document.getElementById("modalCita")).show();
}
