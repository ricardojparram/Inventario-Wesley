$(document).ready(function () {
  let mostrar;
  let permisos, editarPermiso, eliminarPermiso;
  $.ajax({
    method: "POST",
    url: "",
    dataType: "json",
    data: { getPermisos: "" },
    success(data) {
      permisos = data;
    },
  }).then(() => rellenar(true));

  function rellenar(bitacora = false) {
    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: { mostrar: "inv", bitacora },
      success(data) {
        editarPermiso =
          typeof permisos.Editar === "undefined" ? "disabled" : "";
        eliminarPermiso =
          typeof permisos.Eliminar === "undefined" ? "disabled" : "";
        let tabla = data.reduce((acc, row) => {
          return (acc += `
             <tr>
                 <td>${row.usuario}</th>
                 <td>${row.nombre_sede}</th>
                 <td>${row.fecha}</td>
                 <td>${row.presentacion_producto}</th>
                 <td>${row.entrada}</td>
                 <td>${row.salida}</td>                      
                 <td>${row.tipo_movimiento}</td>
                 <td>${row.producto_lote}</td>
                 <td>${row.cantidad}</td>
             </tr>`);
        }, "");
        $("#tableMostrar tbody").html(tabla);
        mostrar = $("#tableMostrar").DataTable({
          resposive: true,
        });
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al mostrar listado: " + e);
      },
    });
  }
});
