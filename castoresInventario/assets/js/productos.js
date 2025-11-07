"use strict";

var url = location.origin;
var path = window.location.pathname;
var funcion = "cargarProductos";
var table = $("#tablaProductos").DataTable({
  processing: true,
  deferRender: true,
  stateSave: true,
  ajax: {
    url: "config/helpers/cargarProductos.php",
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
      data: "name"
    },
    {
      data: "price"
    },
    {
      data: "stock"
    },
    {
      data: "active"
    },
    {
      // Columna de opciones no ligada a datos del backend
      data: null,
      defaultContent: ""
    }
  ],
  columnDefs: [
    {
      targets: [4],
      data: "active",
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
      render: function (data, type, row) {
        let buttons = "<center>";

        // Botones según el rol del usuario
        if (userRole === 1) {
          // Administrador: Entrada (solo si activo), Editar y Activar/Desactivar
          if (row.active == 1) {
            buttons += `<button type="button" class="btn btn-success btn-sm m-1 btn-entrada-stock" data-id="${row.id}" data-nombre="${row.name}" data-stock="${row.stock}" data-toggle="tooltip" title="ENTRADA DE STOCK">
                          <i class="bi bi-plus-circle-fill"></i> Entrada
                        </button>`;
          }
          buttons += `<a id="idproducto" type="button" class="btn btn-primary btn-sm m-1" data-toggle="tooltip" title="EDITAR PRODUCTO" data-bs-toggle="modal" data-bs-target="#editProducto">
                        <i class="bi bi-pencil-fill"></i> Editar
                      </a>`;
          // Botón de baja/alta lógica
          if (row.active == 1) {
            buttons += `<button type="button" class="btn btn-danger btn-sm m-1 btn-toggle-estado" data-id="${row.id}" data-estado="0" title="Desactivar producto">
                          <i class="bi bi-x-circle-fill"></i> Desactivar
                        </button>`;
          } else {
            buttons += `<button type="button" class="btn btn-secondary btn-sm m-1 btn-toggle-estado" data-id="${row.id}" data-estado="1" title="Activar producto">
                          <i class="bi bi-check-circle-fill"></i> Activar
                        </button>`;
          }
        } else if (userRole === 2) {
          // Almacenista: Salida (solo si activo)
          if (row.active == 1) {
            buttons += `<button type="button" class="btn btn-warning btn-sm m-1 btn-salida-stock" data-id="${row.id}" data-nombre="${row.name}" data-stock="${row.stock}" data-toggle="tooltip" title="SALIDA DE STOCK">
                          <i class="bi bi-dash-circle-fill"></i> Salida
                        </button>`;
          }
        }

        buttons += "</center>";
        return buttons;
      }
    }
  ],
  order: [[0, "ASC"] /* ORDEN id */],
  ordering: true,
  responsive: true,
  autoWidth: false,
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
        title: "Productos_Catalogo",
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

// Event listener para Entrada de stock
$("#tablaProductos tbody").on("click", ".btn-entrada-stock", function () {
  const productId = $(this).data("id");
  const productName = $(this).data("nombre");
  const currentStock = $(this).data("stock");

  Swal.fire({
    title: `Entrada de Stock - ${productName}`,
    html: `
      <p>Stock actual: <strong>${currentStock}</strong></p>
      <label for="cantidad-entrada" class="form-label">Cantidad a agregar:</label>
      <input type="number" id="cantidad-entrada" class="swal2-input" min="1" value="1" placeholder="Ingresa la cantidad">
    `,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Registrar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#28a745",
    preConfirm: () => {
      const cantidad = document.getElementById("cantidad-entrada").value;
      if (!cantidad || cantidad <= 0) {
        Swal.showValidationMessage(
          "Debes ingresar una cantidad válida mayor a 0"
        );
        return false;
      }
      return { cantidad: parseInt(cantidad) };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const cantidad = result.value.cantidad;

      $.ajax({
        url: "config/helpers/aumentarStock.php",
        method: "POST",
        dataType: "json",
        data: {
          funcion: "movimientoStock",
          type: "ENTRADA",
          productId: productId,
          cantidad: cantidad
        },
        success: function (response) {
          Swal.fire({
            icon: "success",
            title: "Entrada registrada",
            text: `Se agregaron ${cantidad} unidades al producto.`,
            showConfirmButton: false,
            timer: 2000
          });
          // Recargar la tabla
          table.ajax.reload(null, false);
        },
        error: function (xhr, status, error) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text:
              xhr.responseJSON?.message || "No se pudo registrar la entrada."
          });
        }
      });
    }
  });
});

// Event listener para Salida de stock
$("#tablaProductos tbody").on("click", ".btn-salida-stock", function () {
  const productId = $(this).data("id");
  const productName = $(this).data("nombre");
  const currentStock = $(this).data("stock");

  Swal.fire({
    title: `Salida de Stock - ${productName}`,
    html: `
      <p>Stock actual: <strong>${currentStock}</strong></p>
      <label for="cantidad-salida" class="form-label">Cantidad a retirar:</label>
      <input type="number" id="cantidad-salida" class="swal2-input" min="1" value="1" placeholder="Ingresa la cantidad">
    `,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Registrar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#ffc107",
    preConfirm: () => {
      const cantidad = document.getElementById("cantidad-salida").value;
      if (!cantidad || cantidad <= 0) {
        Swal.showValidationMessage(
          "Debes ingresar una cantidad válida mayor a 0"
        );
        return false;
      }
      return { cantidad: parseInt(cantidad) };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const cantidad = result.value.cantidad;

      $.ajax({
        url: "config/helpers/aumentarStock.php",
        method: "POST",
        dataType: "json",
        data: {
          funcion: "movimientoStock",
          type: "SALIDA",
          productId: productId,
          cantidad: cantidad
        },
        success: function (response) {
          Swal.fire({
            icon: "success",
            title: "Salida registrada",
            text: `Se retiraron ${cantidad} unidades del producto.`,
            showConfirmButton: false,
            timer: 2000
          });
          table.ajax.reload(null, false);
        },
        error: function (xhr) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text:
              xhr.responseJSON?.message ||
              "No se pudo registrar la salida. Verifica el stock disponible."
          });
        }
      });
    }
  });
});

// Event listener para Activar/Desactivar (solo admin)
$("#tablaProductos tbody").on("click", ".btn-toggle-estado", function () {
  const productId = $(this).data("id");
  const targetState = parseInt($(this).data("estado"), 10); // 0 = desactivar, 1 = activar
  const isDeactivating = targetState === 0;

  Swal.fire({
    title: isDeactivating ? "Desactivar producto" : "Activar producto",
    text: isDeactivating
      ? "El producto no podrá recibir movimientos mientras esté inactivo."
      : "El producto volverá a estar disponible para movimientos.",
    icon: isDeactivating ? "warning" : "question",
    showCancelButton: true,
    confirmButtonText: isDeactivating ? "Desactivar" : "Activar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: isDeactivating ? "#dc3545" : "#6c757d"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "config/helpers/cambiarEstatusProducto.php",
        method: "POST",
        dataType: "json",
        data: { productId: productId, active: targetState },
        success: function (resp) {
          Swal.fire({
            icon: "success",
            title: resp.message || "Actualizado",
            timer: 1500,
            showConfirmButton: false
          });
          table.ajax.reload(null, false);
        },
        error: function (xhr) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text:
              xhr.responseJSON?.message ||
              "No se pudo actualizar el estado del producto."
          });
        }
      });
    }
  });
});
