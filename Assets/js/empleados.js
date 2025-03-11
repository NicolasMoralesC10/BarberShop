let tbl_empleados = document.querySelector("#tbl_empleados");

function cargarTabla() {
  tbl_empleados = $("#tbl_empleados").dataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    dom: "Bfrtip",
    buttons: [
      { extend: "copy", text: "Copiar", className: "bg-gradient-dark shadow-dark" },
      { extend: "csv", text: "CSV", className: "bg-gradient-dark shadow-dark" },
      { extend: "excel", text: "Excel", className: "bg-gradient-dark shadow-dark" },
      { extend: "pdf", text: "PDF", className: "bg-gradient-dark shadow-dark" },
      { extend: "print", text: "Imprimir", className: "bg-gradient-dark shadow-dark" },
      { extend: "colvis", text: "Columnas", className: "bg-gradient-dark shadow-dark" },
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
        next: "Siguiente",
        previous: "Anterior",
      },
    },
    ajax: {
      url: " " + base_url + "/empleados/getEmpleados",
      dataSrc: "",
    },
    columns: [
      { data: "nombreF" },
      { data: "cargoF" },
      { data: "status" },
      { data: "fecha_contratacionF" },
      { data: "accion" },
    ],
    responsive: "true",
    iDisplayLength: 5,
    order: [[0, "asc"]],
  });

  //? Funcion para cambiar el input de busqueda de DataTables
  setTimeout(function () {
    let filtro = $("#tbl_empleados_filter");
    let input = filtro.find("input");

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
  }, 300);
}

cargarTabla();
