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
      let tabla = JSON.parse(data).reduce((acc, row) => {
        return acc += `
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
      }, "");
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

  fechaHoy($('#fecha'));

  const mostrarProductos = () => {
    $.post('', { select_producto: "" }, data => {
      let option = data.reduce((acc, row) => {
        return acc += `<option value="${row.cod_producto}">${row.lote}</option>`;
      }, '')
      console.log(option)
      $('.select-productos').each(function () {
        if (this.children.length == 1) {
          $(this).append(option);
          $(this).chosen({
            width: '25vw',
            placeholder_text_single: "Selecciona un producto",
            search_contains: true,
            allow_single_deselect: true,
          });
        }
      })

    }, "json")
  }

  const filaPlantilla = `
  <tr>
    <td width="1%"><a class="eliminarFila a-asd" role="button"><i class="bi bi-trash-fill"></i></a></td>
    <td width='30%'>
      <select class="select-productos select-asd" name="TipoPago">
        <option></option>
      </select>
    </td>
    <td width='15%' class="precioPorTipo"><input class="select-asd precio-tipo" type="number" value="" /></td>
  </tr>`;

  const agregarFila = () => {
    $('#tablaSeleccionarProductos').append(filaPlantilla);
    mostrarProductos();
    // cambio();
    // validarRepetido();
    // selectMultifila($('.select-productos'), $('.filaProductos'), '.table-body', 'No debe haber productos vacíos.');;
    // validarFila($('#ASD'), $('.filaProductos'));
  }

  mostrarProductos();

  /* Evento Agregar fila */
  $('.agregarFila').on('click', function (e) {
    agregarFila();
  });

  /* Evento Eliminar fila */
  $('body').on('click', '.eliminarFila', function (e) {
    $(this).closest('tr').remove();
    // validarFila($('#ASD'), $('.filaProductos'));
    // calculate()
  });



});
