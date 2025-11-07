"use strict";

var url = location.origin;
var path = window.location.pathname;
var funcion = "cargarUsuarios";
var table = $("#tablaUsuarios").DataTable({
  processing: true,
  deferRender: true,
  stateSave: true,
  ajax: {
    url: "config/helpers/cargarUsuarios.php",
    method: "POST",
    data: {
      funcion: funcion
    },
    dataSrc: "data"
  },
  columns: [
    {
      data: "idUsuario"
    },
    {
      data: "nombre"
    },
    {
      data: "correo"
    },
    {
      data: "rol"
    },
    {
      data: "estatus"
    },
    {
      data: ""
    }
  ],
  columnDefs: [
    {
      targets: [4],
      data: "estatus",
      render: function (data, type, row) {
        if (data == 1) {
          return '<span class="badge btn-success">Activo</span>';
        } else {
          return '<span class="badge btn-danger">Inactivo</span>';
        }
      }
    },
    {
      targets: [5],
      data: null,
      defaultContent: `<center>
          <a id="idusuario" type="button" class="btn btn-primary m-1" data-toggle="tooltip" title="EDITAR USUARIO" data-bs-toggle="modal" data-bs-target="#editUsuario"><i class="bi bi-pencil-fill"></i>Editar</a>
          </center>`
    }
  ],
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
        title: "Usuarios",
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

/* EDICION DEL PERSONAL QX */
// $("#tablaPersonalQx tbody").on("click", "#idusuarioqx", function () {
//   let data = table.row($(this).parents()).data();
//   var url = location.origin;
//   var path = window.location.pathname;

//   let idusuario = data.idusuario;
//   var noEmpleado = parseInt(data.codigoseg);
//   var nombreEdit = data.nombre;
//   var appaterno = data.appaterno;
//   var apmaterno = data.apmaterno;
//   var user = data.usuario;
//   var passUser = data.pass;
//   var estatus = data.idestatus;
//   var rol = data.rol;

//   $("#noEmpleadoEdit").val(noEmpleado);
//   $("#nameEmpleadoEdit").val(nombreEdit);
//   $("#appEdit").val(appaterno);
//   $("#apmEdit").val(apmaterno);
//   $("#useredit").val(user);
//   $("#pwd").val(passUser);
//   $("#estatusEdit").val(estatus);
//   $("#rolEdit").val(rol);
//   $("#idusuarioEdit").val(idusuario);
// });
