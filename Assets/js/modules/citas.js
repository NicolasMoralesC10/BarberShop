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
          const events = data.map((item) => {
            let cur = new Date(item.start);
            const items = item.servicios.map((svc, i) => {
              const dur = item.duraciones[i];
              const end = new Date(cur.getTime() + dur * 60000);
              const obj = {
                servicio: svc,
                empleado: item.empleados[i],
                duracion: dur,
                start: cur.toISOString(),
                end: end.toISOString()
              };
              cur = end;
              return obj;
            });

            return {
              id: item.id,
              title: item.cliente,
              start: item.start,
              end: item.end,
              classNames: ["bg-gradient-dark", "text-light"],
              extendedProps: {
                items,
                servicios: item.servicios,
                empleados: item.empleados,
                duraciones: item.duraciones,
                total: item.total,
                status: item.status,
                notas: item.notas
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
      /*     let serv = arg.event.extendedProps.servicios.join(", ");
      let total = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP"
      }).format(arg.event.extendedProps.total); */
      //  Formato 12 horas
      const startTime12 = arg.event.start.toLocaleTimeString("es-CO", {
        hour: "numeric",
        minute: "2-digit",
        hour12: true
      });

      return {
        html: `
          <div class="fc-event-title fc-font-weight-bold" style="display: flex; justify-content: space-between;">
            ${arg.event.title}
            <div class="fc-event-time">${startTime12}</div>
          </div>
        `
      };
    },
    eventDidMount: function (info) {
      let e = info.event.extendedProps;

      // Validaciones para evitar errores si servicios o empleados son undefined
      let servicios = Array.isArray(e.servicios) ? e.servicios.join(", ") : "No definido";
      let empleados = Array.isArray(e.empleados) ? e.empleados.join(", ") : "No definido";

      let total = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP"
      }).format(e.total || 0);

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
      const data = {
        id: info.event.id,
        cliente: info.event.title,
        start: info.event.startStr,
        end: info.event.endStr,
        items: info.event.extendedProps.items,
        total: info.event.extendedProps.total,
        status: info.event.extendedProps.status,
        notas: info.event.extendedProps.notas || ""
      };

      abrirModalEditar(data, calendar);
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

  function nuevaFilaServicio() {
    // 1. Crear contenedor de fila
    const row = document.createElement("div");
    row.className = "row g-2 mb-2";
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
        <input type="text" class="form-control inputPrecio" readonly data-raw="0">
      </div>
      <div class="col-md-1 text-end">
        <button type="button" class="btn btn-danger btn-sm btn-eliminar" style="margin-top:32px">×</button>
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
        if (res.status) {
          Swal.fire({
            icon: "success",
            title: "Cita agendada",
            text: res.msg || "La cita fue registrada correctamente",
            timer: 1500,
            showConfirmButton: false
          });
          limpiarFormularioCita();
          // Opcional: cerrar modal y refrescar calendario si todo salió bien
          bootstrap.Modal.getInstance(document.getElementById("modalCrearCita")).hide();
          calendar?.refetchEvents?.();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al agendar",
            text: res.msg || "Ocurrió un error inesperado"
          });
        }
      })
      .catch((err) => {
        // Error de red o de JS
        console.error(err);
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo conectar con el servidor. Intenta más tarde."
        });
      });
  });
});

function abrirModalEditar(data, calendar) {
  // Cabecera
  document.getElementById("btn-cancelar").dataset.id = data.id;
  document.getElementById("mc-cliente").textContent = data.cliente;
  document.getElementById("mc-fecha").textContent = new Date(data.start).toLocaleDateString("es-CO", {
    dateStyle: "medium"
  });
  document.getElementById("mc-hora").textContent =
    new Date(data.start).toLocaleTimeString("es-CO", { timeStyle: "short", hour12: true }) +
    " – " +
    new Date(data.end).toLocaleTimeString("es-CO", { timeStyle: "short", hour12: true });

  // Estado
  const statusMap = {
    1: ["Pendiente", "bg-gradient-dark"],
    2: ["Confirmada", "bg-success"],
    3: ["Cancelada", "bg-danger"]
  };
  const [lbl, cls] = statusMap[data.status] || ["Desconocido", "bg-secondary"];
  const badge = document.getElementById("mc-status");
  badge.textContent = lbl;
  badge.className = "badge " + cls;

  // Lista de servicios
  const ul = document.getElementById("mc-servicios");
  ul.innerHTML = "";
  data.items.forEach((item) => {
    const hIni = new Date(item.start).toLocaleTimeString("es-CO", {
      hour: "numeric",
      minute: "2-digit",
      hour12: true
    });
    const hFin = new Date(item.end).toLocaleTimeString("es-CO", {
      hour: "numeric",
      minute: "2-digit",
      hour12: true
    });
    const li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `
      <div class="d-flex justify-content-between">
        <div>
          <strong>${item.servicio}</strong>
          <br><small>${hIni} – ${hFin} (${item.duracion} min)</small>
        </div>
        <div class="text-end">
          <small>${item.empleado}</small>
        </div>
      </div>
    `;
    ul.appendChild(li);
  });

  // Notas y total
  document.getElementById("mc-notas").value = data.notas || "";
  document.getElementById("mc-total").textContent = new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP"
  }).format(data.total);

  // 1. Elimina el event listener existente
  const btnCancelar = document.getElementById("btn-cancelar");
  const btnCancelarClonado = btnCancelar.cloneNode(true);
  btnCancelar.parentNode.replaceChild(btnCancelarClonado, btnCancelar);

  // 2. Agrega el event listener al botón clonado
  btnCancelarClonado.addEventListener("click", () => {
    const idCita = btnCancelarClonado.dataset.id;

    if (!idCita) return;

    Swal.fire({
      icon: "warning",
      title: "¿Estás seguro?",
      text: "La cita se marcará como cancelada y no podrás revertirla.",
      showCancelButton: true,
      confirmButtonText: "Sí, cancelar",
      cancelButtonText: "No, mantener"
    }).then((result) => {
      if (!result.isConfirmed) return;

      fetch(`${base_url}/citas/cancelarCita`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: idCita })
      })
        .then((res) => {
          if (!res.ok) throw new Error("HTTP " + res.status);
          return res.json();
        })
        .then((json) => {
          if (json.status) {
            Swal.fire({
              icon: "success",
              title: "Cancelada",
              text: json.msg,
              timer: 1500,
              showConfirmButton: false
            });

            // Actualiza badge
            const badge = document.getElementById("mc-status");
            badge.textContent = "Cancelada";
            badge.className = "badge bg-danger";

            // Elimina evento del calendario
            try {
              const ev = calendar.getEventById(idCita);
              if (ev) ev.remove();
            } catch (error) {
              console.error("Error al eliminar evento del calendario:", error);
              Swal.fire("Error", "No se pudo eliminar el evento del calendario.", "error");
            }

            bootstrap.Modal.getInstance(document.getElementById("modalCita")).hide();
          } else {
            Swal.fire("Error", json.msg, "error");
          }
        })
        .catch((err) => {
          console.error(err);
          Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        });
    });
  });

  // Mostrar modal
  new bootstrap.Modal(document.getElementById("modalCita")).show();
}

function limpiarFormularioCita() {
  document.getElementById("formCrearCita").reset(); // Limpia campos básicos

  // Si estás usando TomSelect, reseteamos así:
  const clienteSelect = document.querySelector("#selectCliente")?.tomselect;
  if (clienteSelect) clienteSelect.clear();

  // Limpiar contenedor de servicios
  const contenedor = document.getElementById("serviciosContainer");
  contenedor.innerHTML = "";

  // Reiniciar total
  document.getElementById("spanTotal").textContent = "$0";
}
