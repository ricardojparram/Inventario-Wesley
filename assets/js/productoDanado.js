$(document).ready(function () {
  let mostrar;
  $.getJSON("", { getPermisos: '' })
      .fail(e => {
          Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
          throw new Error('Error al obtener permisos: ' + e);
      })
      .then((data) => {
          permisos = data;
          rellenar(true)
      });

  function rellenar(bitacora = false) {
      $.getJSON("", { mostrar: "", bitacora }, function (data) {
          const permisoEditar = (!permisos["Editar"]) ? 'disabled' : '';
          let tabla = data.reduce((acc, row) => {
              return (acc += `
              <tr>
                  <td scope="col">${row.num_descargo}</td>
                  <td scope="col">${row.fecha || ""}</td>
                  <td>
                      <span class="d-flex justify-content-center">
                          <button type="button" title="Detalles" class="btn btn-dark detalle mx-2" id="${row.id_descargo}" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
                      </span>
                  </td>
              </tr>`);
          }, "");
          $("#tabla tbody").html(tabla || "");
          mostrar = $("#tabla").DataTable({ resposive: true });
      }).fail((e) => {
          Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
          throw new Error("Error al mostrar listado: " + e);
      });
  }

  let id;
  $(document).on("click", ".detalle", function () {
      id = this.id;
      $.getJSON("", { detalle: "", id }, (res) => {
          let tabla = "";
          $(".detalle_titulo").html(`Descargo: ${res[0].num_descargo}`);
          res.forEach((row) => {
              tabla += `
          <tr>
            <td>${row.lote}</th>
            <td>${row.presentacion_producto}</th>
            <td>${row.cantidad}</td>
            <td>${row.fecha_vencimiento ? row.fecha_vencimiento : ""}</td>
          </tr>`;
          });
          $("#tabla_detalle tbody").html(tabla || "");
      }).fail((e) => {
          Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
          throw new Error("Error al mostrar detalles: " + e);
      });
  });

  

   $('#Exportar').click(function(e){
    $.ajax({
        url:'',
        type: 'POST',
        dataType:'json',
        data:{exportar: 'Exportar producto dañado'},
        success(data){
            if(data.respuesta == "Archivo guardado"){
                Toast.fire({ icon: 'success', title: 'Exportar correctamente'});
                descargarArchivo(data.ruta);
            }else{
                Toast.fire({ icon: 'error', title:'No se puedo exportar el reporte.'});
            }
        }
      })
   })
    
function descargarArchivo(ruta){
    let link=document.createElement('a');
        link.href = ruta;
        link.download = ruta.substr(ruta.lastIndexOf('/') + 1);
        link.click();
}

  $('.cerrar').click(() => {
      $('#agregarform').trigger('reset');
      $('.eliminarFila').click();
      $('#Agregar input').removeClass('input-error');
      $('#Agregar select').removeClass('input-error');
      $('.error').text('');
      agregarFila()
      fechaHoy($('#fecha'));
  })

});
