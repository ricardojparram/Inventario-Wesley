$(document).ready(function () {
  let mostrar;
  $.getJSON("", { getPermisos: "" })
    .fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al obtener permisos: " + e);
    })
    .then((data) => {
      permisos = data;
      rellenar(true);
    });

  function rellenar(bitacora = false) {
    $.getJSON("", { mostrar: "", bitacora }, function (data) {
      const permisoEditar = !permisos["Editar"] ? "disabled" : "";
      const permisoEliminar = !permisos["Eliminar"] ? "disabled" : "";
      let tabla = data.reduce((acc, row) => {
        return (acc += `
                <tr>
                    <td scope="col">${row.num_descargo}</td>
                    <td scope="col">${row.fecha || ""}</td>
                    <td>
                        <span class="d-flex justify-content-center">
                            <button type="button" ${permisoEliminar} title="Eliminar" class="btn btn-danger eliminar mx-2" id="${
          row.id_descargo
        }" data-bs-toggle="modal" data-bs-target="#Eliminar"><i class="bi bi-trash3"></i></button>
                            <button type="button" title="Detalles" class="btn btn-dark detalle mx-2" id="${
                              row.id_descargo
                            }" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
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
      $(".detalle_titulo").html(`Descargo: ${res.descargo.num_descargo}`);
      let imgs = res.img.reduce((acc, row) => {
        return (acc += `
          <a href="${row.src}" target="_blank">
            <img src="${row.src}" class="img-thumbnail" style="witdh: 200px; height: 200px">
          </a>
        `);
      }, "");
      let descargo = `
          <dt class="col-sm-3">Sede</dt>
          <dd class="col-sm-9">${res.descargo.nombre_sede}</dd>
          <dt class="col-sm-3">Fecha</dt>
          <dd class="col-sm-9">${res.descargo.fecha}</dd>
        `;
      let tabla = res.detalle.reduce((acc, row) => {
        return (acc += `
            <tr>
              <td>${row.lote}</th>
              <td>${row.presentacion_producto}</th>
              <td>${row.cantidad}</td>
              <td>${row.fecha_vencimiento ? row.fecha_vencimiento : ""}</td>
            </tr>`);
      }, "");
      $("#tabla_detalle tbody").html(tabla || "");
      $(".images").html(imgs);
      $(".recepcion_detalle dl").html(descargo);
    }).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar detalles: " + e);
    });
  });

  fechaHoy($("#fecha"));
  $(".cantidad input").inputmask("cantidad");
  $("#num_descargo").inputmask("cantidad");

  const mostrarProductos = () => {
    $.getJSON("", { select_producto: "" }, (data) => {
      let option = data.reduce((acc, row) => {
        return (acc += `<option value="${row.id_producto_sede}">${row.presentacion_producto} ${row.fecha_vencimiento}</option>`);
      }, "");
      $(".select-productos").each(function () {
        if (this.children.length == 1) {
          $(this).append(option);
          $(this).chosen({
            width: "100%",
            placeholder_text_single: "Selecciona un producto",
            search_contains: true,
            allow_single_deselect: true,
          });
        }
      });
    });
  };

  let productosRepetidos, productos;
  const validarProductosRepetidos = (status = true) => {
    let validacion = [];
    let $select = document.querySelectorAll(".select-productos");
    if ($select.length < 1) {
      $("#error").html("No hay filas.");
      return false;
    } else {
      $("#error").html("");
    }
    productos = Object.values(
      document.querySelectorAll(".select-productos")
    ).map((item) => {
      return item.value;
    });
    productosRepetidos = productos.filter(
      (elemento, index) => productos.indexOf(elemento) !== index
    );
    $(".select-productos").each(function () {
      if (this.value === "" || this.value === null) {
        if (status != true) {
          $(this)
            .closest("td")
            .find("div.chosen-container")
            .addClass("input-error");
        }
        validacion.push(false);
      } else if (productosRepetidos.includes(this.value)) {
        $(this)
          .closest("td")
          .find("div.chosen-container")
          .addClass("input-error");
        validacion.push(false);
      } else {
        $(this)
          .closest("td")
          .find("div.chosen-container")
          .removeClass("input-error");
        validacion.push(true);
      }
    });
    return !validacion.includes(false);
  };
  const validarProductos = () => {
    let validacion = [];
    $("input.select-asd").each(function () {
      if (this.value === "" || this.value === null || this.value < 1) {
        $(this).addClass("input-error");
        validacion.push(false);
      } else {
        $(this).removeClass("input-error");
        validacion.push(true);
      }
    });
    return !validacion.includes(false);
  };

  const mostrarInventarioProducto = (item) => {
    let $cantidad = $(item).closest("tr").find(".cantidad input");
    let producto_inventario = item.value;
    $.getJSON("?url=transferencia", { producto_inventario }, function (data) {
      $cantidad.val(data.cantidad);
    });
  };

  const validarInventario = async (item) => {
    let $cantidad = $(item);
    let $error = $(item).next();
    let producto_inventario = $cantidad
      .closest("tr")
      .find(".select-productos")
      .val();
    let cantidad = item.value;
    let valid = false;
    if (!Number.isInteger(Number(cantidad))) return false;
    await $.getJSON(
      "?url=transferencia",
      { producto_inventario },
      function (data) {
        if (cantidad > data.cantidad) {
          $cantidad.attr("valid", false);
          $error
            .html(`No hay suficiente.(Disponible: ${data.cantidad})`)
            .removeClass("d-none");
          valid = false;
        } else {
          $cantidad.attr("valid", true);
          $error.addClass("d-none");
          valid = true;
        }
      }
    );
    return valid;
  };

  const validarCantidad = () => {
    let validacion = [];
    $(".cantidad input").each(function () {
      if (this.value === "" || this.value === null || Number(this.value) < 1) {
        $(this).addClass("input-error");
        validacion.push(false);
      } else if ($(this).attr("valid") === "false") {
        $(this).addClass("input-error");
        validacion.push(false);
      } else {
        $(this).removeClass("input-error");
        validacion.push(true);
      }
    });
    return !validacion.includes(false);
  };

  const filaPlantilla = `
    <tr>
        <td width="1%"><a class="eliminarFila a-asd" role="button"><i class="bi bi-trash-fill"></i></a></td>
        <td width='30%' class="position-relative">
            <select class="select-productos select-asd" name="producto">
                <option></option>
            </select>
            <span class="d-none floating-error">error</span>
        </td>
        <td class="cantidad position-relative">
            <input class="select-asd" type="text" value="" />
            <span class="d-none floating-error">error</span>
        </td>
        <td class="descripcion position-relative">
          <input class="select-asd" type="text" value="" />
          <span class="d-none floating-error">error</span>
        </td>
    </tr>`;

  const agregarFila = () => {
    $("#tablaSeleccionarProductos").append(filaPlantilla);
    mostrarProductos();
  };

  mostrarProductos();
  /* Evento Agregar fila */
  $(".agregarFila").on("click", function (e) {
    agregarFila();
    validarProductosRepetidos();
    $(".cantidad input").inputmask("cantidad");
    $(".fecha input").inputmask("fecha");
  });

  /* Evento de cambio en los productos */
  $(document).on("change", ".select-productos", function () {
    validarProductosRepetidos();
    mostrarInventarioProducto(this);
  });

  /* Evento de cambio en la cantidad*/
  $(document).on("change", ".cantidad input", function () {
    validarInventario(this);
  });

  /* Evento Eliminar fila */
  $("body").on("click", ".eliminarFila", function (e) {
    $(this).closest("tr").remove();
    validarProductosRepetidos();
  });

  const getProductos = () => {
    return Object.values(document.querySelectorAll(".select-productos")).map(
      (item) => {
        let cantidad = $(item).closest("tr").find(".cantidad input").val();
        let descripcion = $(item)
          .closest("tr")
          .find(".descripcion input")
          .val();
        return { id_producto: item.value, cantidad, descripcion };
      }
    );
  };
  let valid_sede, valid_fecha;
  $("#num_descargo").change(
    () =>
      (valid_descargo = validarNumero(
        $("#num_descargo"),
        $("#error1"),
        "Error de descargo,"
      ))
  );
  $("#fecha").change(
    () =>
      (valid_fecha = validarFecha($("#fecha"), $("#error2"), "Error de fecha,"))
  );

  $(".custom-file-input").on("change", function () {
    var files = Array.from(this.files);
    var fileName = files
      .map((f) => {
        return f.name;
      })
      .join(", ");
    $(".custom-file-label").addClass("selected").html(fileName);
  });

  $("#agregarform").submit(function (e) {
    e.preventDefault();

    valid_descargo = validarNumero(
      $("#num_descargo"),
      $("#error1"),
      "Error de de descargo,"
    );
    valid_fecha = validarFecha($("#fecha"), $("#error2"), "Error de fecha,");
    let valid_productos = validarProductosRepetidos(false);
    let valid_lotes_cantidad = validarProductos();
    let valid_cantidad = validarCantidad();

    if (
      !valid_descargo ||
      !valid_fecha ||
      !valid_productos ||
      !valid_lotes_cantidad ||
      !valid_cantidad
    )
      return;

    let productos = getProductos();
    let form = new FormData();
    form.set("num_descargo", $("#num_descargo").val());
    form.set("sede", $("#sede").val());
    form.set("fecha", $("#fecha").val());
    form.set("productos", JSON.stringify(productos));
    const photos = document.querySelector('input[type="file"][multiple]');
    for (const [i, photo] of Array.from(photos.files).entries()) {
      form.append(`img[]`, photo);
    }

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.ajax({
      type: "POST",
      url: "",
      dataType: "JSON",
      data: form,
      contentType: false,
      processData: false,
      success(res) {
        Toast.fire({ icon: "success", title: res.msg });
        mostrar.destroy();
        $(".cerrar").click();
        rellenar();
      },
    })
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON.msg || "Ha ocurrido un error.",
        });
        console.error(e.responseJSON.msg || "Ha ocurrido un error.");
      })
      .always(() => {
        $(this).find('button[type="submit"]').prop("disabled", false);
      });
  });

  $(document).on("click", ".eliminar", function () {
    validarPermiso(permisos["Eliminar"]);
    id = this.id;
  });

  $("#anular").click(function () {
    $(this).prop("disabled", true);
    $.post(
      "",
      { eliminar: "", id },
      function (res) {
        Toast.fire({ icon: "success", title: res.msg });
        mostrar.destroy();
        $(".cerrar").click();
        rellenar();
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON.msg || "Ha ocurrido un error.",
        });
        console.log(e.responseJSON.msg || "Ha ocurrido un error.");
      })
      .always(() => {
        $(this).prop("disabled", false);
      });
  });

  $(".cerrar").click(() => {
    $("#agregarform").trigger("reset");
    $(".eliminarFila").click();
    $("#Agregar input").removeClass("input-error");
    $("#Agregar select").removeClass("input-error");
    $(".error").text("");
    agregarFila();
    fechaHoy($("#fecha"));
  });
});
