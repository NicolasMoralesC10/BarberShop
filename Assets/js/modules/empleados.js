let tbl_empleados = document.querySelector("#tbl_empleados");
let btnAgregar = document.querySelector("#btnAgregar");
let frmCrearEmpleado = document.querySelector("#frmCrearEmpleado");

let txtIdEmpleado = document.querySelector("#txtIdEmpleado");
let txtNombre = document.querySelector("#txtNombre");
let txtPassword = document.querySelector("#txtPassword");
let txtTelefono = document.querySelector("#txtTelefono");
let txtSalario = document.querySelector("#txtSalario");
let txtCargo = document.querySelector("#txtCargo");
let txtFechaContratacion = document.querySelector("#txtFechaContratacion");
let txtEstado = document.querySelector("#txtEstado");

cargarTabla();

btnAgregar.addEventListener("click", () => {
  $("#crearEmpleadoModal").modal("show");
  opcionEstado(false);
});

txtSalario.addEventListener("input", (e) => {
  // Eliminar cualquier carácter que no sea dígito
  let valor = e.target.value.replace(/\D/g, "");

  // Aplicar formato con puntos como separador de miles
  valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  e.target.value = valor;
});

// Limpiar el formato al enviar el formulario
document.querySelector("form").addEventListener("submit", function () {
  const raw = txtSalario.value.replace(/\./g, "");
  txtSalario.value = raw;
});

frmCrearEmpleado.addEventListener("submit", (e) => {
  e.preventDefault();
  const raw = txtSalario.value.replace(/\./g, "");
  txtSalario.value = raw;
  let frmEmpleado = new FormData(frmCrearEmpleado);
  fetch(base_url + "/empleados/setEmpleado", {
    method: "POST",
    body: frmEmpleado
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "Registro Empleado",
          text: data.msg,
          icon: "success"
        });
        tbl_empleados.api().ajax.reload(function () {});
        $("#crearEmpleadoModal").modal("hide");
        /*  clearForm() */
      } else {
        Swal.fire({
          title: "Error",
          text: data.msg,
          icon: "error"
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
        title: "Eliminar empleado",
        text: "¿Está seguro de eliminar el empleado?",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: `Cancelar`
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("txtIdEmpleado", id);
          fetch(base_url + "/empleados/deleteEmpleado", {
            method: "POST",
            body: frmData
          })
            .then((res) => res.json())
            .then((data) => {
              Swal.fire({
                title: data.status ? "Correcto" : "Error",
                text: data.msg,
                icon: data.status ? "success" : "error"
              });
              tbl_empleados.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "edit") {
      fetch(base_url + "/empleados/getEmpleadoById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;
            console.log(data);
            txtNombre.value = data.nombre;
            txtPassword.value = data.password;
            txtTelefono.value = data.telefono;
            txtCargo.value = data.cargo;
            txtFechaContratacion.value = data.fecha_contratacion;
            txtSalario.value = data.salario;
            txtIdEmpleado.value = data.id;
            txtEstado.value = data.status;
            $("#crearEmpleadoModal").modal("show");
            opcionEstado(true);
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
            tbl_empleados.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

function opcionEstado(mode) {
  const userStatus = document.getElementById("userStatusZone");
  const passwordZone = document.getElementById("passwordZone");
  const nombreZone = document.getElementById("nombreZone");
  const telefonoZone = document.getElementById("telefonoZone");
  const fechaZone = document.getElementById("fechaZone");

  if (mode) {
    // Ocultar contraseña
    passwordZone.style.display = "none";
    nombreZone.classList.remove("col-4");
    nombreZone.classList.add("col-6");
    telefonoZone.classList.remove("col-4");
    telefonoZone.classList.add("col-6");

    // Mostrar estado y restaurar columnas
    userStatus.style.display = "block";
    userStatus.classList.remove("d-none");
    fechaZone.classList.remove("col-12");
    fechaZone.classList.add("col-6");
  } else {
    // Mostrar campo de contraseña
    passwordZone.style.display = "block";
    passwordZone.classList.add("col-4");
    nombreZone.classList.remove("col-6");
    nombreZone.classList.add("col-4");
    telefonoZone.classList.remove("col-6");
    telefonoZone.classList.add("col-4");
    // Ocultar estado y expandir fecha de contratación
    userStatus.style.display = "none";
    fechaZone.classList.remove("col-6");
    fechaZone.classList.add("col-12");
  }
}

function limpiarFormulario() {
  // Limpiar inputs
  const inputs = formulario.querySelectorAll("input");
  inputs.forEach((input) => {
    if (input.hasAttribute("data-ignore-clear")) return;

    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
    } else {
      input.value = "";
    }
  });

  // Limpiar selects
  const selects = frmCrearEmpleado.querySelectorAll("select");
  selects.forEach((select) => {
    select.selectedIndex = 0;
  });
}

function cargarTabla() {
  tbl_empleados = $("#tbl_empleados").dataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    dom: "Bfrtip",
    buttons: [
      { extend: "copy", text: "Copiar", className: "bg-gradient-dark shadow-dark" },
      /*  { extend: "csv", text: "CSV", className: "bg-gradient-dark shadow-dark" }, */
      { extend: "excel", text: "Excel", className: "bg-gradient-dark shadow-dark" },
      /*  { extend: "pdf", text: "PDF", className: "bg-gradient-dark shadow-dark" },
      { extend: "print", text: "Imprimir", className: "bg-gradient-dark shadow-dark" }, */
      { extend: "colvis", text: "Columnas", className: "bg-gradient-dark shadow-dark" }
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
      decimal: ",",
      emptyTable: "No hay datos disponibles en la tabla Empleados",
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
        previous: "‹"
      }
    },
    ajax: {
      url: " " + base_url + "/empleados/getEmpleados",
      dataSrc: ""
    },
    columns: [
      { data: "nombreF" },
      { data: "cargoF" },
      { data: "status" },
      { data: "fecha_contratacionF" },
      { data: "accion" }
    ],
    responsive: "true",
    iDisplayLength: 5,
    order: [
      [2, "asc"],
      [3, "asc"]
    ]
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
    let filtro = $("#tbl_empleados_filter");
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

    let clearButton = $(`<span class="material-symbols-rounded clear-icon">close</span>`);
    clearButton.click(function () {
      input.val("").trigger("keyup"); // Limpia el input y actualiza DataTables
    });
    nuevoFiltro.append(clearButton);

    // Reemplazar el contenido del filtro con la nueva estructura
    filtro.html(nuevoFiltro);
  }, 200);
}
