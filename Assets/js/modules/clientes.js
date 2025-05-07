let tbl_clientes = document.querySelector("#tbl_clientes");
let btnAgregar = document.querySelector("#btnAgregar");
let frmCrearCliente = document.querySelector("#frmCrearCliente");

let txtIdCliente = document.querySelector("#txtIdCliente");
let txtNombre = document.querySelector("#txtNombre");
let txtTelefono = document.querySelector("#txtTelefono");
let txtObservaciones = document.querySelector("#txtObservaciones");
let txtEstado = document.querySelector("#txtEstado");

cargarTabla();

btnAgregar.addEventListener("click", () => {
  limpiarFormulario();
  opcionEstado(false);
  $("#crearClienteModal").modal("show");
});

frmCrearCliente.addEventListener("submit", (e) => {
  e.preventDefault();
  let frmCliente = new FormData(frmCrearCliente);
  fetch(base_url + "/clientes/setCliente", {
    method: "POST",
    body: frmCliente,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "Registro Cliente",
          text: data.msg,
          icon: "success",
        });
        tbl_clientes.api().ajax.reload(function () {});
        $("#crearClienteModal").modal("hide");
        limpiarFormulario();
      } else {
        Swal.fire({
          title: "Error",
          text: data.msg,
          icon: "error",
        });
      }
    });
});

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let id = e.target.closest("button").getAttribute("data-id");

    if (action == "delete") {
      Swal.fire({
        title: "Eliminar cliente",
        text: "¿Desea eliminar este cliente?",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: `Cancelar`,
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("txtIdCliente", id);
          fetch(base_url + "/clientes/deleteCliente", {
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
              tbl_clientes.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "edit") {
      fetch(base_url + "/clientes/getClienteById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;
            console.log(data);
            document.getElementById("modalTitle").textContent =
              "Actualizar datos";
            document.getElementById("btnEnviar").textContent = "Actualizar";
            txtNombre.value = data.nombre;
            txtTelefono.value = data.telefono;
            txtObservaciones.value = data.observaciones;
            txtEstado.value = data.status;
            txtIdCliente.value = data.id;
            $("#crearClienteModal").modal("show");
            opcionEstado(true);
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error",
            });
            tbl_clientes.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

function opcionEstado(mode) {
  let userStatus = document.getElementById("userStatusZone");

  if (mode) {
    userStatus.style.display = "block";
  } else {
    userStatus.style.display = "none";
  }
}

function cargarTabla() {
  tbl_clientes = $("#tbl_clientes").dataTable({
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
      emptyTable: "No hay datos disponibles en la tabla Clientes",
      infoEmpty: "Mostrando 0 a 0 de 0 entradas",
      lengthMenu: "Mostrar MENU entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "No se encontraron resultados",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
    ajax: {
      url: " " + base_url + "/clientes/getClientes",
      dataSrc: "",
    },
    columns: [
      { data: "nombreF" },
      { data: "telefonoF" },
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
  });

  //? Funcion para cambiar el input de busqueda de DataTables
  setTimeout(function () {
    let filtro = $("#tbl_clientes_filter");
    let input = filtro.find("input");

    // Agregar clase personalizada al input
    input.attr("class", "form-buscar");

    // Crea el nuevo contenedor con el ícono de búsqueda
    let nuevoFiltro = $(`
      <div class="search-container">
          <span class="material-symbols-rounded search-icon">search</span>
      </div>
  `);

    // Mover el input original dentro del contenedor nuevo
    input.appendTo(nuevoFiltro);

    let clearButton = $(
      `<span class="material-symbols-rounded clear-icon">close</span>`
    );
    clearButton.click(function () {
      input.val("").trigger("keyup"); // Limpia el input y actualiza DataTables
    });
    nuevoFiltro.append(clearButton);

    // Reemplazar el contenido del filtro con la nueva estructura
    filtro.html(nuevoFiltro);
  }, 200);
}

function limpiarFormulario() {
  document.getElementById("modalTitle").textContent = "Añadir cliente";
  document.getElementById("btnEnviar").textContent = "Añadir";
  const inputs = frmCrearCliente.querySelectorAll("input");
  inputs.forEach((input) => {
    if (input.hasAttribute("data-ignore-clear")) return;
    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
    } else {
      input.value = "";
      input.value = "";
    }
  });

  const textareas = frmCrearCliente.querySelectorAll("textarea");
  textareas.forEach((textarea) => {
    textarea.value = "";
  });
}
