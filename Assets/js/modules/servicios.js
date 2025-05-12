/* let tbl_clientes = document.querySelector("#tbl_clientes"); */
let cards_servicios = document.querySelector("#cards_servicios");
let btnAgregar = document.querySelector("#btnAgregar");
let btnCerrar = document.querySelector("#btnCerrar");
let frmCrearServicio = document.querySelector("#frmCrearServicio");

let txtIdServicio = document.querySelector("#txtIdServicio");
let txtNombre = document.querySelector("#txtNombre");
let txtPrecio = document.querySelector("#txtPrecio");
let txtDuracion = document.querySelector("#txtDuracion");
let txtDescripcion = document.querySelector("#txtDescripcion");
let txtImagen = document.querySelector("#txtImagen");
let imgPreview = document.querySelector("#imgPreview");

txtImagen.addEventListener("change", (evento) => {
  const archivo = evento.target.files[0];
  if (archivo) {
    const lector = new FileReader();
    lector.onload = function (e) {
      imgPreview.hidden = false;
      imgPreview.setAttribute("src", e.target.result);
    };
    lector.readAsDataURL(archivo);
  }
});

loadCards();

btnAgregar.addEventListener("click", () => {
  $("#crearServicioModal").modal("show");
  imgPreview.hidden = true;
});

btnCerrar.addEventListener("click", () => {
  limpiarFormulario();
});

frmCrearServicio.addEventListener("submit", (e) => {
  e.preventDefault();
  let frmServicio = new FormData(frmCrearServicio);

  frmServicio.forEach((valor, clave) => {
    console.log(clave, valor);
  });
  fetch(base_url + "/servicios/setServicio", {
    method: "POST",
    body: frmServicio,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status) {
        Swal.fire({
          title: "¡Registro Servicio!",
          text: data.msg,
          icon: "success",
        }).then(() => {
          window.location.reload();
        });
        $("#crearServicioModal").modal("hide");
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
        title: "Eliminar servicio",
        text: "¿Desea eliminar este servicio?",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: `Cancelar`,
      }).then((result) => {
        if (result.isConfirmed) {
          let frmData = new FormData();
          frmData.append("txtIdServicio", id);
          fetch(base_url + "/servicios/deleteServicio", {
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
              /* tbl_clientes.api().ajax.reload(function () {}); */
              cards_servicios.api().ajax.reload(function () {});
            });
        }
      });
    }

    if (action == "edit") {
      fetch(base_url + "/servicios/getServicioById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;
            console.log(data);
            txtNombre.value = data.nombre;
            txtPrecio.value = data.precio;
            txtDuracion.value = data.duracionMinutos;
            txtDescripcion.value = data.descripcion;
            /* txtImagen.value = data.imagen; */
            imgPreview.hidden = false;
            imgPreview.src = data.imagen;
            txtIdServicio.value = data.id;
            $("#crearServicioModal").modal("show");
            /* opcionEstado(true); */
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error",
            });
            /* tbl_clientes.api().ajax.reload(function () {}); */
            cards_servicios.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

/* function opcionEstado(mode) {
  let userStatus = document.getElementById("userStatusZone");

  if (mode) {
    userStatus.style.display = "block";
  } else {
    userStatus.style.display = "none";
  }
} */

function loadCards() {
  fetch(base_url + "/servicios/getServicios")
    .then((res) => res.json())
    .then((servicios) => {
      servicios.map((servicio) => {
        cards_servicios.innerHTML += servicio.card;
      });
    })
    .catch((error) => {
      console.log(error);
    });
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
      url: " " + base_url + "/servicios/getServicios",
      dataSrc: "",
    },
    columns: [
      { data: "nombreF" },
      { data: "telefonoF" },
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
  const inputs = frmCrearServicio.querySelectorAll("input");
  inputs.forEach((input) => {
    if (input.hasAttribute("data-ignore-clear")) return;
    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
    } else {
      input.value = "";
    }
  });

  const textareas = frmCrearServicio.querySelectorAll("textarea");
  textareas.forEach((textarea) => {
    if (textarea.hasAttribute("data-ignore-clear")) return;
    if (textarea) {
      textarea.value = "";
    }
  });

  const imgPreview = document.querySelector("#imgPreview");
  if (imgPreview) imgPreview.src = "";
}
