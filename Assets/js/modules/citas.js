let btnAgregar = document.querySelector("#btnAgregarCita");
let intIdCita = document.querySelector("#intIdCita");

btnAgregar.addEventListener("click", () => {
  limpiarFormularioCita();
  $("#modalCrearCita").modal("show");
});

// globales
let calendar;
let contenedor;
let serviciosList = [];
let empleadosList = [];
let fechaCita;
let tsCliente;

// Función para limpiar el formulario (antes de crear o editar)
function limpiarFormularioCita() {
  document.getElementById("formCrearCita").reset(); // Limpia campos básicos

  // Si estás usando TomSelect, reseteamos así:
  const clienteSelect = document.querySelector("#selectCliente")?.tomselect;
  if (clienteSelect) clienteSelect.clear();

  // Limpiar contenedor de servicios
  const contenedor = document.getElementById("serviciosContainer");
  contenedor.innerHTML = "";

  document.querySelector("#modalCrearCita .modal-title").textContent = "Agendar Nueva Cita";
  document.querySelector("#formCrearCita button[type=submit]").textContent = "Guardar Cita";
  // Reiniciar total
  document.getElementById("spanTotal").textContent = "$0";
}

// Recalcula el total de la cita
function recalcularTotal() {
  const total = Array.from(contenedor.querySelectorAll(".inputPrecio")).reduce(
    (sum, ip) => sum + Number(ip.dataset.raw || 0),
    0
  );
  document.getElementById("spanTotal").textContent = new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP",
  }).format(total);
}

// Agrega una nueva fila de servicio+empleado
function nuevaFilaServicio() {
  //  Crear la fila HTML
  const row = document.createElement("div");
  row.className = "row g-2 mb-0";
  row.innerHTML = `
    <div class="col-md-4">
      <label class="form-label">Servicio</label>
      <select class="form-select select-servicio" required>
        <option value="" disabled selected>Selecciona un servicio…</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Empleado</label>
      <select class="form-select select-empleado" required>
        <option value="" disabled selected>Selecciona un empleado…</option>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Duración (min)</label>
      <input type="number" class="form-control inputDuracion" readonly>
    </div>
    <div class="col-md-2">
      <label class="form-label">Precio</label>
      <input type="text" class="form-control inputPrecio" readonly data-raw="0">
    </div>
   <div class="col-md-1 d-flex align-items-end pt-4">
      <button type="button" class="btn btn-danger btn-sm btn-eliminar w-100 mt-2">×</button>
   </div>

  `;
  document.getElementById("serviciosContainer").appendChild(row);

  // Rellenar opciones desde tus listas
  const selServ = row.querySelector(".select-servicio");
  const selEmp = row.querySelector(".select-empleado");

  serviciosList.forEach((s) => {
    selServ.insertAdjacentHTML(
      "beforeend",
      `
      <option value="${s.id}"
              data-precio="${s.precio}"
              data-duracion="${s.duracionMinutos}">
        ${s.nombre}
      </option>`
    );
  });

  empleadosList.forEach((e) => {
    selEmp.insertAdjacentHTML(
      "beforeend",
      `
      <option value="${e.id}">${e.nombre}</option>`
    );
  });

  // Listener nativo para cambio en servicio
  selServ.addEventListener("change", () => {
    const opt = selServ.selectedOptions[0];
    const dur = parseInt(opt.dataset.duracion) || 0;
    const precio = parseInt(opt.dataset.precio) || 0;
    row.querySelector(".inputDuracion").value = dur;
    const ip = row.querySelector(".inputPrecio");
    ip.value = new Intl.NumberFormat("es-CO", {
      style: "currency",
      currency: "COP",
    }).format(precio);
    ip.dataset.raw = precio;
    recalcularTotal();
  });

  // Eliminar fila
  row.querySelector(".btn-eliminar").addEventListener("click", () => {
    row.remove();
    recalcularTotal();
  });
}

// Abre el modal de edición (ver detalles)
function abrirModalEditar(data, calendar) {
  // Cabecera
  document.getElementById("btn-cancelar").dataset.id = data.id;
  document.getElementById("mc-cliente").textContent = data.cliente;
  document.getElementById("mc-fecha").textContent = new Date(data.start).toLocaleDateString(
    "es-CO",
    {
      dateStyle: "medium",
    }
  );
  document.getElementById("mc-hora").textContent =
    new Date(data.start).toLocaleTimeString("es-CO", { timeStyle: "short", hour12: true }) +
    " – " +
    new Date(data.end).toLocaleTimeString("es-CO", { timeStyle: "short", hour12: true });

  // Estado
  const statusMap = {
    1: ["Pendiente", "bg-gradient-dark"],
    2: ["Confirmada", "bg-success"],
    3: ["Cancelada", "bg-danger"],
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
      hour12: true,
    });
    const hFin = new Date(item.end).toLocaleTimeString("es-CO", {
      hour: "numeric",
      minute: "2-digit",
      hour12: true,
    });
    const li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `
      <div class="d-flex justify-content-between">
        <div>
          <strong>${item.servicio}</strong>
          <br><small>${hIni} – ${hFin} (${item.duracion} min)</small>
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
    currency: "COP",
  }).format(data.total);

  // Mostrar modal
  new bootstrap.Modal(document.getElementById("modalCita")).show();
}

// Abre el modal de creación/edición con todos los datos de la cita
function abrirModalCrearOModificar(cita) {
  limpiarFormularioCita();

  document.querySelector("#selectCliente").value = cita.cliente_id;
  tsCliente.setValue(cita.cliente_id);
  intIdCita.value = cita.id || 0;
  fechaCita.setDate(cita.start, true);

  document.getElementById("mc-notas").value = cita.notas || "";

  //   Por cada servicio (usar los IDs, no los nombres)
  cita.servicio_ids.forEach((svcId, i) => {
    nuevaFilaServicio();
    const row = contenedor.lastElementChild;

    // Seleccionar el servicio por su ID
    const selServ = row.querySelector(".select-servicio");
    selServ.value = svcId;
    selServ.dispatchEvent(new Event("change"));

    // Seleccionar el empleado por su ID
    const selEmp = row.querySelector(".select-empleado");
    selEmp.value = cita.empleado_ids[i];
    selEmp.dispatchEvent(new Event("change"));

    // Ajusto duración y precio (por si el listener no lo cubre)
    row.querySelector(".inputDuracion").value = cita.duraciones[i];
    const ip = row.querySelector(".inputPrecio");
    ip.dataset.raw = cita.precios[i];
    ip.value = new Intl.NumberFormat("es-CO", { style: "currency", currency: "COP" }).format(
      cita.precios[i]
    );
  });

  // Cambiar el texto del botón
  document.querySelector("#modalCrearCita .modal-title").textContent = "Actualizar Cita";
  document.querySelector("#formCrearCita button[type=submit]").textContent = "Guardar Cambios";

  // Muestra el modal
  new bootstrap.Modal(document.getElementById("modalCrearCita")).show();
}

document.addEventListener("DOMContentLoaded", function () {
  contenedor = document.getElementById("serviciosContainer");
  const calendarEl = document.getElementById("calendarioCitas");

  // Inicializar calendario
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "es",
    themeSystem: "bootstrap5",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    buttonText: {
      today: "Hoy",
      month: "Mes",
      week: "Semana",
      day: "Día",
    },
    slotLabelFormat: {
      hour: "numeric",
      minute: "2-digit",
      hour12: true,
    },
    allDayText: "Todo el día",
    height: "auto",
    nowIndicator: true,

    // Carga de eventos
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
                end: end.toISOString(),
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
                notas: item.notas,
              },
            };
          });

          successCallback(events);
        })

        .catch((err) => {
          console.error(err);
          failureCallback(err);
        });
    },

    // contenido dentro del evento
    eventContent: function (arg) {
      //  Formato 12 horas
      const startTime12 = arg.event.start.toLocaleTimeString("es-CO", {
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
      });

      return {
        html: `
          <div class="fc-event-title fc-font-weight-bold" style="display: flex; justify-content: space-between;">
            ${arg.event.title}
            <div class="fc-event-time">${startTime12}</div>
          </div>
        `,
      };
    },
    // Tooltip al pasar sobre el evento
    eventDidMount: function (info) {
      let e = info.event.extendedProps;

      // Validaciones para evitar errores si servicios o empleados son undefined
      let servicios = Array.isArray(e.servicios) ? e.servicios.join(", ") : "No definido";
      let empleados = Array.isArray(e.empleados) ? e.empleados.join(", ") : "No definido";

      let total = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP",
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
        container: calendarEl,
      });
    },

    // Click en evento, vista de detalles
    eventClick: function (info) {
      const detalle = {
        id: info.event.id,
        cliente: info.event.title,
        start: info.event.startStr,
        end: info.event.endStr,
        items: info.event.extendedProps.items,
        total: info.event.extendedProps.total,
        status: info.event.extendedProps.status,
        notas: info.event.extendedProps.notas || "",
      };
      abrirModalEditar(detalle);

      // Reconfiguracion en botón de Reprogramar
      const oldBtn = document.getElementById("btn-reprogramar"),
        newBtn = oldBtn.cloneNode(true);
      oldBtn.parentNode.replaceChild(newBtn, oldBtn);

      newBtn.addEventListener("click", () => {
        bootstrap.Modal.getInstance(document.getElementById("modalCita")).hide();

        fetch(`${base_url}/citas/getCitaById?id=${info.event.id}`)
          .then((r) => (r.ok ? r.json() : Promise.reject(r.status)))
          .then((resp) => {
            if (!resp.status) throw new Error(resp.msg);
            abrirModalCrearOModificar(resp.data);
          })
          .catch((err) => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la cita para reprogramar", "error");
          });
      });
    },
  });
  calendar.render();

  // 1. Elimina el event listener existente
  const btnCancelar = document.getElementById("btn-cancelar");
  const btnCancelarClonado = btnCancelar.cloneNode(true);
  btnCancelar.parentNode.replaceChild(btnCancelarClonado, btnCancelar);

  // 2. Agrega el event listener al botón clonado
  btnCancelarClonado.addEventListener("click", () => {
    const idCita = btnCancelarClonado.dataset.id;

    if (!idCita) return;

    console.log("ID de cita a cancelar:", idCita);

    Swal.fire({
      icon: "warning",
      title: "¿Estás seguro?",
      text: "La cita se marcará como cancelada y no podrás revertirla.",
      showCancelButton: true,
      confirmButtonText: "Sí, cancelar",
      cancelButtonText: "No, mantener",
    }).then((result) => {
      if (!result.isConfirmed) return;

      fetch(`${base_url}/citas/cancelarCita`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: idCita }),
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
              showConfirmButton: false,
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

  // Inicializzacion Flatpickr
  fechaCita = flatpickr("#inputFechaHora", {
    enableTime: true,
    time_24hr: false,
    altInput: true, // Muestra valor alternativo (formato 12h)
    altFormat: "Y-m-d h:i K", // Formato mostrado al usuario
    dateFormat: "Y-m-d H:i", // Formato del valor real (24h)
    minDate: "today",
    locale: "es",
  });

  // TomSelect para cliente
  fetch(base_url + "/citas/getClientes")
    .then((r) => r.json())
    .then((data) => {
      if (!tsCliente) {
        tsCliente = new TomSelect("#selectCliente", {
          options: data.map((c) => ({ value: c.id, text: c.nombre })),
          create: false,
          sortField: { field: "text", direction: "asc" },
        });
      } else {
        tsCliente.clearOptions();
        tsCliente.addOptions(data.map((c) => ({ value: c.id, text: c.nombre })));
      }
    })
    .catch((err) => console.error("Error cargando clientes:", err));

  // Carga servicios y empleados
  fetch(`${base_url}/citas/getServicios`)
    .then((r) => r.json())
    .then((data) => (serviciosList = data));
  fetch(`${base_url}/citas/getEmpleados`)
    .then((r) => r.json())
    .then((data) => (empleadosList = data));

  // Botón agregar servicio
  document.getElementById("btnAgregarServicio").addEventListener("click", nuevaFilaServicio);

  // Submit para crear cita
  document.getElementById("formCrearCita").addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const payload = {
      id: intIdCita.value || 0,
      cliente_id: form.selectCliente.value,
      fechaInicio: form.inputFechaHora.value,
      servicios: Array.from(contenedor.children).map((row) => ({
        servicio_id: row.querySelector(".select-servicio").value,
        empleado_id: row.querySelector(".select-empleado").value,
        duracionM: Number(row.querySelector(".inputDuracion").value),
        precio: Number(row.querySelector(".inputPrecio").dataset.raw),
      })),
    };
    fetch(base_url + "/citas/setCitas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.status) {
          Swal.fire({
            icon: "success",
            title: "Cita agendada",
            text: res.msg || "La cita fue registrada correctamente",
            timer: 1500,
            showConfirmButton: false,
          });
          limpiarFormularioCita();
          // cerrar modal y refrescar calendario
          bootstrap.Modal.getInstance(document.getElementById("modalCrearCita")).hide();
          calendar?.refetchEvents?.();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al agendar",
            text: res.msg || "Ocurrió un error inesperado",
          });
        }
      })
      .catch((err) => {
        // Error de red o de JS
        console.error(err);
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo conectar con el servidor. Intenta más tarde.",
        });
      });
  });
});
