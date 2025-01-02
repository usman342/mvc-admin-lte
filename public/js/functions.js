function getUri() { 
  var parser = document.createElement('a');
  parser.href = window.location.toString();

  var uri = parser.pathname.split('/');
  return uri.filter(e => e);
}

function dataTable() {
  $("#example1").DataTable({
    "responsive": true, "lengthChange": true, "autoWidth": false,
    "iDisplayLength": 100,
    "order": [[0, "desc"]],
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
}
dataTable();