let tbl_empleados = document.querySelector("#tbl_empleados");

function loadTable() {
  tbl_empleados = $("#tbl_empleados").dataTable({
    language: {
      url: `http://localhost/barbershop/Assets/vendor/datatables/dataTables_es.json`,
    },
    ajax: {
      url: "http://localhost/barbershop/empleados/getEmpleados",
      dataSrc: "",
    },
    columns: [
      { data: "nombreF" },
      { data: "cargoF" },
      { data: "status" },
      { data: "fecha_contratacionF" },
    ],
    responsive: "true",
    iDisplayLength: 10,
    order: [[0, "asc"]],
  });
}

loadTable();
