$(document).ready(function () {
  let mostrar;
  rellenar(true);
  async function rellenar(bitacora = false) {
    await $.getJSON("", { mostrar: "", bitacora }, function (data) {
      let tabla = data.reduce((acc, row) => {
        return (acc += `
             <tr>
                 <td>${row.presentacion_producto}</th>
                 <td>${row.presentacion_peso}</th>
                 <td>${row.medida}</td>
                 <td>${row.lote}</th>
                 <td>${row.fecha_vencimiento}</td>
                 <td>${row.inventario}</td>                      
                 <td>${row.tipo}</td>
                 <td>${row.clase}</td>
             </tr>`);
      }, "");
      $("#tableMostrar tbody").html(tabla || "");
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
