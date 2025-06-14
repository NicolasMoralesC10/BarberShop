document.addEventListener("DOMContentLoaded", function () {
  // Datos iniciales
  let productosList = [];
  let totalValidado = 0;
  let idVentaEdit = null;
  let tbl_ventas = document.querySelector("#tbl_ventas");
  let btnAgregar = document.querySelector("#btnAgregarVenta");
  cargarTabla();

  btnAgregar.addEventListener("click", () => {
    $("#modalCrearVenta").modal("show");
  });

  fetch(base_url + "/ventas/getVentas")
    .then((r) => r.json())
    .then((data) => {
      console.log(data);
    });

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

  const contenedor = document.getElementById("productosContainer");

  function recalcularTotal() {
    const totalGlobal = Array.from(contenedor.children).reduce((sum, row) => {
      const precio = Number(row.querySelector(".inputPrecio").dataset.raw) || 0;
      const cantidad = Number(row.querySelector(".inputCantidad").value) || 0;
      const total = precio * cantidad;
      totalValidado = sum + total;
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
        recalcularTotal();
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
        <input type="number" min="1" class="form-control inputCantidad" data-raw="0">
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
    // ✅ Agrega el listener al nuevo input
    row
      .querySelector(".inputCantidad")
      .addEventListener("change", recalcularTotal);
    recalcularTotal();
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

    // Obtener referencias
    const selectProducto = row.querySelector(".select-producto");
    const inputCantidad = row.querySelector(".inputCantidad");

    // Validar stock en vivo
    inputCantidad.addEventListener("change", function () {
      const productoId = selectProducto.value;
      const cantidadIngresada = Number(this.value);

      // Buscar el producto en productosList
      const producto = productosList.find((p) => p.id == productoId);
      if (!producto) return;

      if (cantidadIngresada > producto.stock) {
        Swal.fire({
          icon: "warning",
          title: "Stock insuficiente",
          text: `Solo hay ${producto.stock} unidades disponibles de "${producto.nombre}".`,
        });
        this.value = producto.stock > 0 ? producto.stock : 1;
        recalcularTotal();
      }
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
      total: totalValidado,
      productos: Array.from(contenedor.children).map((row) => ({
        producto_id: row.querySelector(".select-producto").value,
        cantidad: Number(row.querySelector(".inputCantidad").value),
      })),
    };
    if (idVentaEdit) {
      payload.idVenta = idVentaEdit;
    }
    fetch(base_url + "/ventas/setVentas", {
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
          tbl_ventas.api().ajax.reload(function () {});
          limpiarFormularioVenta();
          idVentaEdit = null; // <-- Limpia el id después de guardar
          bootstrap.Modal.getInstance(
            document.getElementById("modalCrearVenta")
          ).hide();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al agendar",
            text: res.msg || "Ocurrió un error inesperado",
          });
        }
      })
      .catch((err) => {
        console.error(err);
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo conectar con el servidor. Intenta más tarde.",
        });
      });
  });

  // Unifica el event click aquí
  document.addEventListener("click", (e) => {
    try {
      let btn = e.target.closest("[data-action]");
      if (!btn) return;
      let action = btn.getAttribute("data-action");
      let id = btn.getAttribute("data-id");

      if (action == "delete") {
        Swal.fire({
          icon: "warning",
          title: "¿Estás seguro?",
          text: "La venta será eliminada y no podrás revertirlo.",
          showCancelButton: true,
          confirmButtonText: "Sí, eliminar",
          cancelButtonText: "No, mantener",
        }).then((result) => {
          if (result.isConfirmed) {
            let frmData = new FormData();
            frmData.append("ventaId", id);
            fetch(base_url + "/ventas/cancelarVenta", {
              method: "POST",
              body: frmData,
            })
              .then((res) => res.json())
              .then((data) => {
                Swal.fire({
                  title: data.status ? "Correcto" : "Error",
                  text: data.msg,
                  icon: data.status ? "success" : "error",
                });
                tbl_ventas.api().ajax.reload(function () {});
              });
          }
        });
      }

      if (action == "edit") {
        fetch(base_url + "/ventas/getVentaById/" + id)
          .then((res) => res.json())
          .then((data) => {
            if (data.status) {
              data = data.data;
              idVentaEdit = data.id; // Guarda el ID de la venta a editar
              // Selecciona cliente, empleado y método de pago
              if (document.querySelector("#selectCliente")?.tomselect)
                document
                  .querySelector("#selectCliente")
                  .tomselect.setValue(data.cliente_id);
              if (document.querySelector("#selectEmpleado")?.tomselect)
                document
                  .querySelector("#selectEmpleado")
                  .tomselect.setValue(data.empleado_id);
              if (document.getElementById("metodoPago"))
                document.getElementById("metodoPago").value = data.metodo_pago;
              if (document.getElementById("observacionesText"))
                document.getElementById("observacionesText").value =
                  data.observaciones;

              // Limpiar productos previos
              contenedor.innerHTML = "";

              // Por cada producto, agrega fila y selecciona valores
              data.productos.forEach((prod) => {
                // Crea la fila
                const row = document.createElement("div");
                row.className = "row g-2 mb-2";
                row.innerHTML = `
                  <div class="col-md-4">
                    <label class="form-label">Producto</label>
                    <select class="form-select select-producto" required></select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" class="form-control inputCantidad" data-raw="0">
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

                // Inicializa TomSelect y selecciona el producto por nombre
                const ts = new TomSelect(
                  row.querySelector(".select-producto"),
                  {
                    options: productosList.map((s) => ({
                      value: s.id,
                      text: s.nombre,
                      precio: s.precio,
                    })),
                    labelField: "text",
                    valueField: "value",
                    maxItems: 1,
                    create: false,
                    onChange: function (v) {
                      const data = this.options[v];
                      if (!data) return;
                      const ip = row.querySelector(".inputPrecio");
                      ip.value = new Intl.NumberFormat("es-CO", {
                        style: "currency",
                        currency: "COP",
                      }).format(data.precio);
                      ip.dataset.raw = data.precio;
                      recalcularTotal();
                    },
                  }
                );

                // Selecciona el producto por nombre
                const productoObj = productosList.find(
                  (p) => p.nombre === prod.nombre
                );
                if (productoObj) {
                  ts.setValue(productoObj.id);
                  // Setea cantidad
                  row.querySelector(".inputCantidad").value = prod.cantidad;
                  recalcularTotal();
                }

                // Listener para recalcular total al cambiar cantidad
                row
                  .querySelector(".inputCantidad")
                  .addEventListener("change", recalcularTotal);

                // Botón eliminar
                row
                  .querySelector(".btn-eliminar")
                  .addEventListener("click", () => {
                    ts.destroy();
                    row.remove();
                    recalcularTotal();
                  });
              });

              $("#modalCrearVenta").modal("show");
            } else {
              Swal.fire({
                title: "Error",
                text: data.msg,
                icon: "error",
              });
              tbl_ventas.api().ajax.reload(function () {});
            }
          });
      }
      if (action == "ver") {
        fetch(base_url + "/ventas/getVentaById/" + id)
          .then((res) => res.json())
          .then((data) => {
            if (data.status) {
              const productos = data.data.productos || [];
              console.log(productos);
              let html = "";
              productos.forEach((prod) => {
                html += `
                  <tr>
                    <td>${prod.nombre}</td>
                    <td>${prod.cantidad}</td>
                    <td>${new Intl.NumberFormat("es-CO", {
                      style: "currency",
                      currency: "COP",
                    }).format(prod.cantidad * (prod.precio || 0))}</td>
                  </tr>
                `;
              });
              document.getElementById("detalleVentaBody").innerHTML = html;
              $("#modalDetallesVenta").modal("show");
            } else {
              Swal.fire({
                title: "Error",
                text: data.msg,
                icon: "error",
              });
            }
          });
      }
    } catch {}
  });

  // DataTable y funciones auxiliares
  function cargarTabla() {
    tbl_ventas = $("#tbl_ventas").dataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      dom: "Bfrtip",
      buttons: [
        {
          extend: "copy",
          text: "Copiar",
          className: "bg-gradient-dark shadow-dark",
        },
        /*  { extend: "csv", text: "CSV", className: "bg-gradient-dark shadow-dark" }, */
        {
          extend: "excel",
          text: "Excel",
          className: "bg-gradient-dark shadow-dark",
        },
        /*  { extend: "pdf", text: "PDF", className: "bg-gradient-dark shadow-dark" },
        { extend: "print", text: "Imprimir", className: "bg-gradient-dark shadow-dark" }, */
        {
          extend: "colvis",
          text: "Columnas",
          className: "bg-gradient-dark shadow-dark",
        },
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
        decimal: ",",
        emptyTable: "No hay datos disponibles en la tabla Ventas",
        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
        lengthMenu: "Mostrar MENU entradas",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar:",
        zeroRecords: "No se encontraron resultados",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "›",
          previous: "‹",
        },
      },
      ajax: {
        url: " " + base_url + "/ventas/getVentas",
        dataSrc: "",
      },
      columns: [
        { data: "fechaF" },
        { data: "clienteF" },
        { data: "empleadoF" },
        { data: "metodoF" },
        { data: "totalF" },
        { data: "observacionesF" },
        { data: "status" },
        { data: "accion" },
      ],
      responsive: "true",
      iDisplayLength: 5,
      order: [
        [2, "asc"],
        [3, "asc"],
      ],
      /*  columnDefs: [
          {
              targets: 2, // columna Estado
              render: function (data, type, row) {
                if (type === "display") {
                  if (data == 1) {
                    return '<span class="badge badge-sm bg-gradient-success" style="font-size:0.67rem;">Online</span>';
                } else {
                    return '<span class="badge badge-sm bg-gradient-secondary" style="font-size:0.67rem;">Offline</span>';
                }
                }
                return data; 
              }
          }
      ] */
    });

    //? Funcion para cambiar el input de busqueda de DataTables
    setTimeout(function () {
      let filtro = $("#tbl_ventas_filter");
      let input = filtro.find("input");

      // Agregar clase personalizada al input
      input.attr("class", "form-buscar");
      // Crea el nuevo contenedor con el ícono de búsqueda
      let nuevoFiltro = $(`
        <div class="search-container">
            <span class="material-symbols-rounded search-icon" translate="no">search</span>
        </div>
    `);

      // Mover el input original dentro del contenedor nuevo
      input.appendTo(nuevoFiltro);

      let clearButton = $(
        `<span class="material-symbols-rounded clear-icon" translate="no">close</span>`
      );
      clearButton.click(function () {
        input.val("").trigger("keyup"); // Limpia el input y actualiza DataTables
      });
      nuevoFiltro.append(clearButton);

      // Reemplazar el contenido del filtro con la nueva estructura
      filtro.html(nuevoFiltro);
    }, 200);
  }

  function limpiarFormularioVenta() {
    document.getElementById("formCrearVenta").reset();
    const clienteSelect = document.querySelector("#selectCliente")?.tomselect;
    if (clienteSelect) clienteSelect.clear();
    const empleadoSelect = document.querySelector("#selectEmpleado")?.tomselect;
    if (empleadoSelect) empleadoSelect.clear();
    contenedor.innerHTML = "";
    idVentaEdit = null;
  }
});
