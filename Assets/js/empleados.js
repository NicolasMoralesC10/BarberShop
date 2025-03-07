let tbl_empleados = document.querySelector("#tbl_empleados");

function loadTable() {
  tbl_empleados = $("#tbl_empleados").dataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    language: {
      url: `${base_url}/Assets/vendor/datatables/dataTables_es.json`,
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
    ],
    responsive: "true",
    iDisplayLength: 10,
    order: [[0, "asc"]],
  });
}

loadTable();
