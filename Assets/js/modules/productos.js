let tbl_productos = document.querySelector("#tbl_productos");
let btnAgregar = document.querySelector("#btnAgregar");
let frmCrearProducto = document.querySelector("#frmCrearProducto");

let txtIdProducto = document.querySelector("#txtIdProducto");
let txtNombre = document.querySelector("#txtNombre");
let txtDescripcion = document.querySelector("#txtDescripcion");
let txtStock = document.querySelector("#txtStock");
let txtPrecio = document.querySelector("#txtPrecio");
let txtStockMin = document.querySelector("#txtStockMin");
let txtEstado = document.querySelector("#txtEstado");

cargarTabla();

btnAgregar.addEventListener("click", () => {
  limpiarFormulario();
  $("#crearProductoModal").modal("show");
    opcionEstado(false);
});
txtStock.addEventListener("input", (e) => {
  // Eliminar cualquier carácter que no sea dígito
  let valor = e.target.value.replace(/\D/g, "");

  // Aplicar formato con puntos como separador de miles
  valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  e.target.value = valor;
});
txtStockMin.addEventListener("input", (e) => {
  // Eliminar cualquier carácter que no sea dígito
  let valor = e.target.value.replace(/\D/g, "");

  // Aplicar formato con puntos como separador de miles
  valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  e.target.value = valor;
});
txtPrecio.addEventListener("input", (e) => {
  // Eliminar cualquier carácter que no sea dígito
  let valor = e.target.value.replace(/\D/g, "");

  // Aplicar formato con puntos como separador de miles
  valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  e.target.value = valor;
});

// Limpiar el formato al enviar el formulario
document.querySelector("form").addEventListener("submit", function () {
  const raw = txtPrecio.value.replace(/\./g, "");
  txtPrecio.value = raw;
  const raw2 = txtStock.value.replace(/\./g, "");
  txtStock.value = raw2;
  const raw3 = txtStockMin.value.replace(/\./g, "");
  txtStockMin.value = raw3;
});
btnCerrar.addEventListener("click", () => {
  limpiarFormulario();
});
frmCrearProducto.addEventListener("submit", (e) => {
  e.preventDefault();
  const raw = txtPrecio.value.replace(/\./g, "");
  txtPrecio.value = raw;
  const raw2 = txtStock.value.replace(/\./g, "");
  txtStock.value = raw2;
  const raw3 = txtStockMin.value.replace(/\./g, "");
  txtStockMin.value = raw3;
  let frmProducto = new FormData(frmCrearProducto);
  fetch(base_url + "/productos/setProducto", {
    method: "POST",
    body: frmProducto,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "Registro Producto",
          text: data.msg,
          icon: "success",
        });
        tbl_productos.api().ajax.reload(function () {});
        $("#crearProductoModal").modal("hide");
        /*  clearForm() */
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
        icon: "warning",
        title: "¿Estás seguro?",
        text: "El producto sera eliminado y no podrás revertirlo.",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, mantener",
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("txtIdProducto", id);
          fetch(base_url + "/productos/deleteProducto", {
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
              tbl_productos.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "edit") {
      fetch(base_url + "/productos/getProductoById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;
            console.log(data);
            txtNombre.value = data.nombre;
            txtDescripcion.value = data.descripcion;
            txtStock.value = data.stock;
            txtStockMin.value = data.stockMin;
            txtPrecio.value = data.precio;
            txtIdProducto.value = data.id;
            txtEstado.value = data.status;
            $("#crearProductoModal").modal("show");   
            opcionEstado(true);   
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error",
            });
            tbl_productos.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

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
  const selects = frmCrearProducto.querySelectorAll("select");
  selects.forEach((select) => {
    select.selectedIndex = 0;
  });
}
function opcionEstado(mode) {
  let userStatus = document.getElementById("productStatusZone");
  if (mode) {
    userStatus.style.display = "block";
  } else {
    userStatus.style.display = "none";
  }
}
function cargarTabla() {
  tbl_productos = $("#tbl_productos").dataTable({
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
      emptyTable: "No hay datos disponibles en la tabla Productos",
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
      url: " " + base_url + "/productos/getProductos",
      dataSrc: "",
    },
    columns: [
      { data: "nombreF" },
      { data: "descripcionF" },
      { data: "precioF" },
      { data: "stockF" },
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
    let filtro = $("#tbl_productos_filter");
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
function limpiarFormulario() {
  const inputs = frmCrearProducto.querySelectorAll("input");
  const txtDescripcion = document.querySelector("#txtDescripcion");
  txtDescripcion.value = "";
  inputs.forEach((input) => {
    if (input.hasAttribute("data-ignore-clear")) return;
    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
    } else {
      input.value = "";
      input.value = "";
    }
  });
}
