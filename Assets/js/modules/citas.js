let btnAgregar = document.querySelector("#btnAgregarCita");

btnAgregar.addEventListener("click", () => {
  $("#modalCrearCita").modal("show");
});
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

  // Inicializar Flatpickr
  flatpickr("#inputFechaHora", { enableTime: true, dateFormat: "Y-m-d H:i", minDate: "today" });

  // Datos iniciales
  let serviciosList = [];
  let empleadosList = [];
  let totalGlobal = 0;

  // Fetch datos de clientes, servicios y empleados
  fetch(base_url + "/citas/getClientes")
    .then((r) => r.json())
    .then((data) => {
      new TomSelect("#selectCliente", {
        options: data.map((c) => ({ value: c.id, text: c.nombre })),
        create: false,
        sortField: { field: "text", direction: "asc" }
      });
    });

  fetch(base_url + "/citas/getServicios")
    .then((r) => r.json())
    .then((data) => (serviciosList = data));
  fetch(base_url + "/citas/getEmpleados")
    .then((r) => r.json())
    .then((data) => (empleadosList = data));

  const contenedor = document.getElementById("serviciosContainer");
  const spanTotal = document.getElementById("spanTotal");

  // FUNCION recalcularTotal (modificado para usar dataset.raw)
  function recalcularTotal() {
    totalGlobal = Array.from(contenedor.children).reduce((sum, row) => {
      // CAMBIO: usar dataset.raw en lugar de input.value
      const precio = Number(row.querySelector(".inputPrecio").dataset.raw) || 0;
      return sum + precio;
    }, 0);
    spanTotal.textContent = new Intl.NumberFormat("es-CO", { style: "currency", currency: "COP" }).format(
      totalGlobal
    );
  }

  // TomSelect para servicio
  function crearTomSelectServicio(row) {
    return new TomSelect(row.querySelector(".select-servicio"), {
      options: serviciosList.map((s) => ({
        value: s.id,
        text: s.nombre,
        precio: s.precio,
        duracionMinutos: s.duracionMinutos
      })),
      labelField: "text",
      valueField: "value",
      maxItems: 1, // solo un servicio
      create: false, // no permitir crear nuevos
      onChange: function (v) {
        const data = this.options[v];
        if (!data) return;
        row.querySelector(".inputDuracion").value = data.duracionMinutos;
        const ip = row.querySelector(".inputPrecio");
        ip.value = new Intl.NumberFormat("es-CO", {
          style: "currency",
          currency: "COP"
        }).format(data.precio);
        ip.dataset.raw = data.precio;
        recalcularTotal();
      },
      onItemAdd: function (value, item) {
        // Una vez seleccione, bloqueo la entrada
        this.control_input.setAttribute("readonly", "readonly");
      },
      onItemRemove: function (value) {
        // Si remueve la selección, la vuelvo editable
        this.control_input.removeAttribute("readonly");
      }
    });
  }

  // TomSelect para empleado
  function crearTomSelectEmpleado(row) {
    return new TomSelect(row.querySelector(".select-empleado"), {
      options: empleadosList.map((e) => ({ value: e.id, text: e.nombre })),
      labelField: "text",
      valueField: "value",
      maxItems: 1,
      create: false,
      onItemAdd: function () {
        this.control_input.setAttribute("readonly", "readonly");
      },
      onItemRemove: function () {
        this.control_input.removeAttribute("readonly");
      }
    });
  }

  // Crear fila de servicio
  function nuevaFilaServicio() {
    const row = document.createElement("div");
    row.className = "row g-2 align-items-end mb-2";
    row.innerHTML = `
    <div class="col-md-4">
      <label class="form-label">Servicio</label>
      <select class="form-select select-servicio" required></select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Empleado</label>
      <select class="form-select select-empleado" required></select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Duración (min)</label>
      <input type="number" class="form-control inputDuracion" readonly>
    </div>
    <div class="col-md-2">
      <label class="form-label">Precio</label>
      <input type="text" class="form-control inputPrecio" readonly>
    </div>
    <div class="col-md-1 text-end">
      <button type="button" class="btn btn-danger btn-sm btn-eliminar">×</button>
    </div>
  `;
    contenedor.appendChild(row);

    // 2. Inicializar TomSelect para servicio y empleado
    const tsServicio = crearTomSelectServicio(row);
    const tsEmpleado = crearTomSelectEmpleado(row);

    // 3. Botón eliminar: destruye instancias y fila
    row.querySelector(".btn-eliminar").addEventListener("click", () => {
      // destruye los TomSelect para evitar memory leaks
      tsServicio.destroy();
      tsEmpleado.destroy();
      // remueve la fila
      row.remove();
      // recalcula total
      recalcularTotal();
    });
  }

  document.getElementById("btnAgregarServicio").addEventListener("click", nuevaFilaServicio);

  // Manejo de submit
  document.getElementById("formCrearCita").addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const payload = {
      cliente_id: form.selectCliente.value,
      fechaInicio: form.inputFechaHora.value,
      servicios: Array.from(contenedor.children).map((row) => ({
        servicio_id: row.querySelector(".select-servicio").value,
        empleado_id: row.querySelector(".select-empleado").value,
        duracionM: Number(row.querySelector(".inputDuracion").value),
        precio: Number(row.querySelector(".inputPrecio").dataset.raw)
      }))
    };
    fetch(base_url + "/citas/setCitas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    })
      .then((r) => r.json())
      .then((res) => {
        // refresca calendario, cierra modal, notifica
        calendar.refetchEvents();
        bootstrap.Modal.getInstance(document.getElementById("modalCrearCita")).hide();
      });
  });
});

function abrirModalEditar(data) {
  // data: { id, cliente, start, end, servicios:[], empleados:[], total, status, notas }
  document.getElementById("mc-cliente").textContent = data.cliente;
  document.getElementById("mc-fecha").textContent = new Date(data.start).toLocaleDateString("es-CO", {
    dateStyle: "medium"
  });
  document.getElementById("mc-hora").textContent =
    new Date(data.start).toLocaleTimeString("es-CO", { timeStyle: "short" }) +
    " A " +
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
