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
      const permisoEliminar = (!permisos["Eliminar"]) ? 'disabled' : '';

      let tabla = data.reduce((acc, row) => {
        return (acc += `
        <tr>
          <td>${row.id_transferencia}</th>
          <td scope="col">${row.nombre_sede}</td>
          <td scope="col">${row.fecha || ""}</td>
          <td >
            <span class="d-flex justify-content-center">
              <!-- <button type="button" ${permisoEditar} title="Editar" class="btn btn-success editar mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button> -->
              <button type="button" ${permisoEliminar} title="Eliminar" class="btn btn-danger eliminar mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Eliminar"><i class="bi bi-trash3"></i></button>
              <button type="button" title="Detalles" class="btn btn-dark detalle mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
            </span>
          </td>
        </tr>`);
      }, "");
      $("#tabla tbody").html(tabla ? tabla : "");
      mostrar = $("#tabla").DataTable({ resposive: true });
    }).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
  }
  let id;
  $(document).on("click", ".detalle", function () {
    id = this.id;
    $.getJSON("", { detalle: "", id_transferencia: id }, (res) => {
      let tabla = "";
      $("#Detalle h5").html(res[0].nombre_sede);
      res.forEach((row) => {
        tabla += `
          <tr>
            <td>${row.lote}</th>
            <td>${row.id_producto_sede}</th>
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

  fechaHoy($("#fecha"));

  const mostrarProductos = () => {
    $.getJSON("", { select_producto: "" }, (data) => {
      let option = data.reduce((acc, row) => {
        return (acc += `<option value="${row.id_producto_sede}">${row.lote}</option>`);
      }, "");
      $(".select-productos").each(function () {
        if (this.children.length == 1) {
          $(this).append(option);
          $(this).chosen({
            width: "25vw",
            placeholder_text_single: "Selecciona un producto",
            search_contains: true,
            allow_single_deselect: true,
          });
        }
      });
    });
  };

  let productosRepetidos, productos;
  function validarProductosRepetidos() {
    let validacion = [];
    productos = Object.values(document.querySelectorAll('.select-productos')).map(item => {
      return item.value;
    });
    productosRepetidos = productos.filter((elemento, index) => productos.indexOf(elemento) !== index);
    $(".select-productos").each(function () {
      if (this.value === "") {
        $(this).closest('td').find('div.chosen-container').addClass('input-error')
        validacion.push(false);
      }
      if (productosRepetidos.includes(this.value)) {
        $(this).closest('td').find('div.chosen-container').addClass('input-error')
        validacion.push(false);
      } else {
        $(this).closest('td').find('div.chosen-container').removeClass('input-error')
        validacion.push(true);
      }
    })
    return !validacion.includes(false);
  }

  const mostrarInventarioProducto = (item) => {
    $cantidad = $(item).closest('tr').find('.cantidad input');
    producto_inventario = item.value;
    $.getJSON('', { producto_inventario }, function (data) {
      $cantidad.val(data[0].cantidad);
    })
  }

  const filaPlantilla = `
  <tr>
    <td width="1%"><a class="eliminarFila a-asd" role="button"><i class="bi bi-trash-fill"></i></a></td>
    <td width='30%' class="position-relative">
      <select class="select-productos select-asd" name="producto">
        <option></option>
      </select>
      <span class="d-none floating-error">error</span>
    </td>
    <td class="cantidad"><input class="select-asd" type="number" value="" /></td>
  </tr>`;

  const agregarFila = () => {
    $("#tablaSeleccionarProductos").append(filaPlantilla);
    mostrarProductos();
  };

  mostrarProductos();
  /* Evento Agregar fila */
  $(".agregarFila").on("click", function (e) {
    agregarFila();

  });

  /* Evento de cambio en los productos */
  $(document).on("change", ".select-productos", function () {
    validarProductosRepetidos();
    mostrarInventarioProducto(this);
  });

  /* Evento Eliminar fila */
  $("body").on("click", ".eliminarFila", function (e) {
    $(this).closest("tr").remove();
    validarProductosRepetidos();
  });

  const getProductos = () => {
    return Object.values(document.querySelectorAll('.select-productos')).map(item => {
      let cantidad = $(item).closest('tr').find('.cantidad input').val();
      return { id_producto: item.value, cantidad };
    });
  }

  $('#registrar').click(function (e) {
    e.preventDefault();

    let valid_sede = validarNumero($('#sede'), $('#error1'), "Error de sede,");
    let valid_fecha = validarFecha($('#fecha'), $('#error2'), "Error de fecha,");
    let valid_productos = validarProductosRepetidos();

    if (!valid_sede || !valid_fecha || !valid_productos) return;

    productos = getProductos();
    let data = {
      registrar: '',
      sede: $("#sede").val(),
      fecha: $("#fecha").val(),
      productos,
    };

    $.post("", data, function (res) {
      Toast.fire({ icon: "success", title: res.msg });
      mostrar.destroy();
      $('.cerrar').click();
      rellenar();
    }, "json").fail((e) => {
      Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
      throw new Error(e.responseJSON.msg);
    });
  })

  $(document).on('click', '.eliminar', function () {
    validarPermiso(permisos["Eliminar"]);
    id = this.id
  });

  $('#anular').click(function () {
    $.post("", { eliminar: '', id }, function (res) {
      Toast.fire({ icon: "success", title: res.msg });
      mostrar.destroy();
      $('.cerrar').click();
      rellenar();
    }, "json").fail((e) => {
      Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
      throw new Error(e.responseJSON.msg);
    });
  })

});
