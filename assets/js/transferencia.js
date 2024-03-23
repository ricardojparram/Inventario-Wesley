$(document).ready(function () {
  let mostrar;
  let registrarPermiso, editarPermiso, eliminarPermiso;
  $.post(
    "",
    { getPermisos: "" },
    function (permisos) {
      registrarPermiso = permisos.Editar ? "" : "disabled";
      editarPermiso = permisos.Editar ? "" : "disabled";
      eliminarPermiso = permisos.Eliminar ? "" : "disabled";
    },
    "json",
  ).then(() => rellenar(true));

  function rellenar(bitacora = false) {
    $.post("", { mostrar: "", bitacora }, function (data) {
      let tabla;
      JSON.parse(data).forEach((row) => {
        tabla += `
          <tr>
              <td>${row.id_transferencia}</th>
              <td scope="col">${row.nombre_sede}</td>
              <td scope="col">${row.fecha ? row.fecha : ""}</td>
              <td >
                <span class="d-flex justify-content-center">
                  <button type="button" ${editarPermiso} title="Editar" class="btn btn-success editar mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                  <button type="button" ${eliminarPermiso} title="Eliminar" class="btn btn-danger borrar mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
                  <button type="button" ${eliminarPermiso} title="Detalles" class="btn btn-dark detalle mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
                </span>
              </td>
          </tr>`;
      });
      $("#tabla tbody").html(tabla ? tabla : "");
      mostrar = $("#tabla").DataTable({ resposive: true });
    }).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
  }
  let id;
  $(document).on('click', '.detalle', function () {
    id = this.id;
    $.post("", { detalle: '', id_transferencia: id }, res => {
      let tabla = "";
      $("#Detalle h5").html(res[0].nombre_sede)
      res.forEach(row => {
        tabla += `
          <tr>
              <td>${row.lote}</th>
              <td>${row.cod_producto}</th>
              <td>${row.cantidad}</td>
              <td>${row.fecha_vencimiento ? row.fecha_vencimiento : ""}</td>
          </tr>`;
      })
      $("#tabla_detalle tbody").html(tabla ? tabla : "");
    }, "json").fail(e => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar detalles: " + e);
    })
  })

});
