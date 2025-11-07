"use strict";
var url = location.origin;
var path = window.location.pathname;
var funcion = "cargarHistorial";
var table = $("#tablaMovimientos").DataTable({
  processing: true,
  deferRender: true,
  stateSave: true,
  ajax: {
    url: "config/helpers/cargarHistorial.php",
    method: "POST",
    data: {
      funcion: funcion
    },
    dataSrc: "data"
  },
  columns: [
    {
      data: "id"
    },
    {
      data: "movement_type"
    },
    {
      data: "notes"
    },
    {
      data: "nombre"
    },
    {
      data: "movement_date"
    }
  ],
  columnDefs: [],
  order: [[0, "ASC"] /* ORDEN POR FECHA */],
  ordering: true,
  responsive: true,
  autowdith: false,
  language: {
    lengthMenu: "Ver _MENU_ por pagina",
    zeroRecords:
      "SIN INFORMACION POR MOSTRAR! - Agrega informacion al catalogo",
    info: "Pagina _PAGE_ de _PAGES_",
    infoEmpty: "SIN REGISTROS EXISTENTES!",
    infoFiltered: "(Filtrado por _MAX_ registros totales)",
    search: "Buscar:",
    paginate: {
      next: "Siguiente",
      previous: "Anterior"
    },
    loadingRecords: "CARGANDO..."
  },
  dom: "Bfrtilp",
  buttons: {
    dom: {
      button: {
        className: "btn"
      }
    },
    buttons: [
      {
        extend: "excel",
        title: "Historial de Movimientos",
        text: '<i class="fas fa-file-excel"></i> Exportar Excel',
        className: "btn btn-success mb-2",
        excelStyles: {
          template: "blue_medium"
        }
      }
    ]
  }
});
table.buttons().container().appendTo("#crud_wrapper .col-md-6:eq(0)");
