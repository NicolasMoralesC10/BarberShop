let btnAgregar = document.querySelector("#btnAgregarVenta");

btnAgregar.addEventListener("click", () => {
  $("#modalCrearVenta").modal("show");
});
document.addEventListener("DOMContentLoaded", function () {
  // Datos iniciales
  let productosList = [];
  let totalGlobal = 0;
  // Fetch datos de clientes, servicios y empleados
  fetch(base_url + "/ventas/getClientes")
    .then((r) => r.json())
    .then((data) => {
      console.log(data),
        new TomSelect("#selectCliente", {
          options: data.map((c) => ({ value: c.id, text: c.nombre })),
          create: false,
          sortField: { field: "text", direction: "asc" },
        });
    });

  fetch(base_url + "/ventas/getProductos")
    .then((r) => r.json())
    .then((data) => (productosList = data));
  fetch(base_url + "/ventas/getEmpleados")
    .then((r) => r.json())
    .then((data) => {
      console.log(data),
        new TomSelect("#selectEmpleado", {
          options: data.map((e) => ({ value: e.id, text: e.nombre })),
          create: false,
          sortField: { field: "text", direction: "asc" },
        });
    });
  Array.from(document.querySelectorAll(".inputCantidad")).forEach((el) => {
    el.addEventListener("input", (e) => {
      const input = e.target;
      const row = input.closest(".row");
      const precio = Number(row.querySelector(".inputPrecio").dataset.raw) || 0;
      const cantidad = Number(input.value) || 0;
      const total = precio * cantidad;
      console.log(precio, cantidad, total);
      // CAMBIO: usar dataset.raw en lugar de input.value
      input.dataset.raw = cantidad;
      recalcularTotal();
    });
  });
  const contenedor = document.getElementById("productosContainer");
  function recalcularTotal() {
    const contenedor = document.getElementById("productosContainer");
    const totalGlobal = Array.from(contenedor.children).reduce((sum, row) => {
      const precio = Number(row.querySelector(".inputPrecio").dataset.raw) || 0;
      const cantidad = Number(row.querySelector(".inputCantidad").value) || 0;
      const total = precio * cantidad;
      return sum + total;
    }, 0);

    document.getElementById("spanTotal").textContent = new Intl.NumberFormat(
      "es-CO",
      {
        style: "currency",
        currency: "COP",
      }
    ).format(totalGlobal);
  }

  // Añadir listeners a todos los inputs de cantidad
  document.querySelectorAll(".inputCantidad").forEach((input) => {
    input.addEventListener("input", recalcularTotal);
  });

  // Llamada inicial para calcular total al cargar la página
  recalcularTotal();
  // TomSelect para servicio
  function crearTomSelectProducto(row) {
    return new TomSelect(row.querySelector(".select-producto"), {
      options: productosList.map((s) => ({
        value: s.id,
        text: s.nombre,
        precio: s.precio,
      })),
      labelField: "text",
      valueField: "value",
      maxItems: 1, // solo un servicio
      create: false, // no permitir crear nuevos
      onChange: function (v) {
        const data = this.options[v];
        if (!data) return;
        const ip = row.querySelector(".inputPrecio");
        ip.value = new Intl.NumberFormat("es-CO", {
          style: "currency",
          currency: "COP",
        }).format(data.precio);
        ip.dataset.raw = data.precio;
        const ic = row.querySelector(".inputCantidad");
        ic.value = 1; // valor por defecto
      },
      onItemAdd: function (value, item) {
        // Una vez seleccione, bloqueo la entrada
        this.control_input.setAttribute("readonly", "readonly");
      },
      onItemRemove: function (value) {
        // Si remueve la selección, la vuelvo editable
        this.control_input.removeAttribute("readonly");
      },
    });
  }

  function nuevaFilaProducto() {
    // 1. Crear contenedor de fila
    const row = document.createElement("div");
    row.className = "row g-2 mb-2";
    row.innerHTML = `
      <div class="col-md-4">
        <label class="form-label">Producto</label>
        <select class="form-select select-producto" required></select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Cantidad</label>
        <input type="number" class="form-control inputCantidad" data-raw="0">
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
    const tsServicio = crearTomSelectProducto(row);

    // 3. Botón eliminar: destruye instancias y fila
    row.querySelector(".btn-eliminar").addEventListener("click", () => {
      // destruye los TomSelect para evitar memory leaks
      tsServicio.destroy();
      // remueve la fila
      row.remove();
      // recalcula total
      recalcularTotal();
    });
  }

  document
    .getElementById("btnAgregarProducto")
    .addEventListener("click", nuevaFilaProducto);

  // Manejo de submit
  document.getElementById("formCrearVenta").addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const payload = {
      cliente_id: form.selectCliente.value,
      empleado_id: form.selectEmpleado.value,
      metodo_pago: form.metodoPago.value,
      observaciones: form.observacionesText.value,
      productos: Array.from(contenedor.children).map((row) => ({
        producto_id: row.querySelector(".select-producto").value,
        cantidad: Number(row.querySelector(".inputCantidad").value),
      })),
    };
    fetch(base_url + "/ventas/setCitas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.status) {
          Swal.fire({
            icon: "success",
            title: "Venta agendada",
            text: res.msg || "La venta fue registrada correctamente",
            timer: 1500,
            showConfirmButton: false,
          });
          limpiarFormularioVenta();
          // Opcional: cerrar modal y refrescar calendario si todo salió bien
          bootstrap.Modal.getInstance(
            document.getElementById("modalCrearVenta")
          ).hide();
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

function abrirModalEditar(data, calendar) {
  // Cabecera
  document.getElementById("btn-cancelar").dataset.id = data.id;
  document.getElementById("mc-cliente").textContent = data.cliente;

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
  const ul = document.getElementById("mc-productos");
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
  document.getElementById("mc-total").textContent = new Intl.NumberFormat(
    "es-CO",
    {
      style: "currency",
      currency: "COP",
    }
  ).format(data.total);

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
      cancelButtonText: "No, mantener",
    }).then((result) => {
      if (!result.isConfirmed) return;

      fetch(`${base_url}/ventas/cancelarCita`, {
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
              Swal.fire(
                "Error",
                "No se pudo eliminar el evento del calendario.",
                "error"
              );
            }

            bootstrap.Modal.getInstance(
              document.getElementById("modalCita")
            ).hide();
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
  new bootstrap.Modal(document.getElementById("modalVenta")).show();
}

function limpiarFormularioVenta() {
  document.getElementById("formCrearVenta").reset(); // Limpia campos básicos

  // Si estás usando TomSelect, reseteamos así:
  const clienteSelect = document.querySelector("#selectCliente")?.tomselect;
  if (clienteSelect) clienteSelect.clear();
  const empleadoSelect = document.querySelector("#selectEmpleado")?.tomselect;
  if (empleadoSelect) empleadoSelect.clear();

  // Limpiar contenedor de servicios
  const contenedor = document.getElementById("productosContainer");
  contenedor.innerHTML = "";
}
