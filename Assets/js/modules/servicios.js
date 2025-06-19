/* let tbl_clientes = document.querySelector("#tbl_clientes"); */
let cards_servicios = document.querySelector("#cards_servicios");
let btnAgregar = document.querySelector("#btnAgregar");
let frmCrearServicio = document.querySelector("#frmCrearServicio");

let txtIdServicio = document.querySelector("#txtIdServicio");
let txtNombre = document.querySelector("#txtNombre");
let txtPrecio = document.querySelector("#txtPrecio");
let txtDuracion = document.querySelector("#txtDuracion");
let txtDescripcion = document.querySelector("#txtDescripcion");
let txtImagen = document.querySelector("#txtImagen");
let imgPreview = document.querySelector("#imgPreview");

loadCards();

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

btnAgregar.addEventListener("click", () => {
  limpiarFormulario();
  $("#crearServicioModal").modal("show");
  imgPreview.hidden = true;
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
        limpiarFormulario();
      } else {
        Swal.fire({
          title: "¡Error!",
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
        confirmButtonText: "Sí, eliminar",
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
                title: data.status ? "¡Correcto!" : "Error",
                text: data.msg,
                icon: data.status ? "success" : "error",
              }).then(() => {
                window.location.reload();
              });
              $("#crearServicioModal").modal("hide");
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
            document.getElementById("modalTitle").textContent = "Actualizar datos";
            document.getElementById("btnEnviar").textContent = "Actualizar";
            txtNombre.value = data.nombre;
            txtPrecio.value = data.precio;
            txtDuracion.value = data.duracionMinutos;
            txtDescripcion.value = data.descripcion;
            imgPreview.hidden = false;
            imgPreview.src = data.imagen;
            txtIdServicio.value = data.id;
            $("#crearServicioModal").modal("show");
          } else {
            Swal.fire({
              title: "¡Error!",
              text: data.msg,
              icon: "error",
            });
            cards_servicios.api().ajax.reload(function () {});
          }
        });
    }
  } catch {}
});

function loadCards() {
  fetch(base_url + "/servicios/getServicios")
    .then((res) => res.json())
    .then((servicios) => {
      cards_servicios.innerHTML = ""; // Limpiar contenido previo
      const mensaje = document.querySelector("#mensajeSinServicios");

      if (servicios.length === 0) {
        mensaje.style.display = "block"; // Mostrar mensaje
      } else {
        mensaje.style.display = "none"; // Ocultar si hay servicios
        servicios.forEach((servicio) => {
          cards_servicios.innerHTML += servicio.card;
        });
      }
    })
    .catch((error) => {
      console.log(error);
    });
}

function limpiarFormulario() {
  document.getElementById("modalTitle").textContent = "Añadir servicio";
  document.getElementById("btnEnviar").textContent = "Añadir";
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
