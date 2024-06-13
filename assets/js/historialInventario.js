$(document).ready(function () {
  let mostrar;
  rellenar(true);

  async function rellenar(bitacora = false) {
    await $.getJSON("", { mostrar: "inv", bitacora }, function (data) {
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
    }).fail((e) => {
      Toast.fire({
        icon: "error",
        title: e.responseJSON?.msg || "Ha ocurrido un error.",
      });
      console.error(e.responseJSON?.msg || "Ha ocurrido un error");
    });
  }
});
