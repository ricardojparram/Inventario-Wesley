$(document).ready(function () {
  fechaHoy($("#fecha"));

  let tablaMostrar;
  let permisos;

  $.post("", { getPermisos: "" })
    .fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    })
    .then((data) => {
      permisos = JSON.parse(data);
      rellenar(true);
    });

  function rellenar(bitacora = false) {
    $.post(
      "",
      { mostrar: "", bitacora },
      function (data) {
        let tabla;
        const permisoEditar = !permisos["Editar"] ? "disabled" : "";
        const permisoEliminar = !permisos["Eliminar"] ? "disabled" : "";
        data.forEach((row) => {
          tabla += `
            <tr>
            <td>${row.cod_producto}</td>
            <td>${row.nombrepro}</td>
            <td>${row.pres}</td>
            <td class="d-flex justify-content-center">
            <button type="button" ${permisoEditar} id="${row.cod_producto}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
            <button type="button" ${permisoEliminar} id="${row.cod_producto}" class="btn btn-danger borrar mx-2"  data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
            
           
            </td>
            </tr>
            `;
        });
        $("#tbody").html(tabla);
        tablaMostrar = $("#tableMostrar").DataTable({
          resposive: true,
        });
      },
      "json"
    ).fail((e) => {
      console.error(e.responseJSON?.msg || "Ha ocurrido un error");
    });
  }

  $("#cod_producto").keyup(() => {
    validarStringLength(
      $("#cod_producto"),
      $("#error1"),
      "Error de codigo",
      50
    );
  });
  $("#posologia").keyup(() => {
    validarStringLength(
      $("#posologia"),
      $("#error8"),
      "Error de posologia",
      400
    );
  });
  $("#contraIn").keyup(() => {
    validarStringLength(
      $("#contraIn"),
      $("#error9"),
      "Error elige ubicación",
      400
    );
  });

  $("#agregarform").submit(function (e) {
    e.preventDefault();

    let cod_producto = validarStringLength(
      $("#cod_producto"),
      $("#error1"),
      "Error del código",
      50
    );
    let tipoprod = validarSelect(
      $("#tipoprod"),
      $("#error2"),
      "Error tipo producto"
    );
    let presentacion = validarSelect(
      $("#presentacion"),
      $("#error3"),
      "Error presentación"
    );
    //let laboratorio = validarSelect($("#laboratorio"),$("#error4"),"Error laboratorio");
    let tipo = validarSelect($("#tipoP"), $("#error5"), "Error tipo producto");
    let clase = validarSelect($("#clase"), $("#error6"), "Error clase");
    let composicion = validarStringLength(
      $("#composicion"),
      $("#error7"),
      "Error de Composición",
      50
    );
    let posologia = validarStringLength(
      $("#posologia"),
      $("#error8"),
      "Error de posologia",
      400
    );
    let contraIn = validarStringLength(
      $("#contraIn"),
      $("#error9"),
      "Error contraindicaciones",
      400
    );

    if (
      !cod_producto &&
      !tipoprod &&
      !presentacion &&
      !tipoP &&
      !clase &&
      !composicion &&
      !posologia &&
      !contraIn &&
      !tipo
    ) {
      throw new Error("Datos invalidos");
    }

    const data = {
      cod_producto: $("#cod_producto").val(),
      tipoprod: $("#tipoprod").val(),
      presentacion: $("#presentacion").val(),
      laboratorio: $("#laboratorio").val(),
      tipoP: $("#tipoP").val(),
      clase: $("#clase").val(),
      composicionP: $("#composicion").val(),
      posologia: $("#posologia").val(),
      contrain: $("#contraIn").val(),
    };

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      data,
      function (data) {
        tablaMostrar.destroy();
        rellenar();
        $("#agregarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Producto registrado." });
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON?.msg || "Ha ocurrido un error.",
        });
        console.error(e.responseJSON?.msg || "Ha ocurrido un error");
      })
      .always(() => {
        $(this).find('button[type="submit"]').prop("disabled", false);
      });
  });

  $(document).on("click", ".cerrar", function () {
    $("#agregarform").trigger("reset");
    $("#editarform").trigger("reset");
    $("#agregarform input").attr(
      "style",
      "border-color: none; background-image: none;"
    );
    $("#editarform input").attr(
      "style",
      "border-color: none; background-image: none;"
    );
    $("#error").text("");
    $("#errorEdit").text("");
    fechaHoy($("#fecha"));
  });

  let id;

  $(document).on("click", ".editar", function () {
    id = this.id;
    $.post(
      "",
      { select: "edit", id },
      function (data) {
        $("#cod_productoEd").val(data[0].cod_producto);
        $("#tipoprodEd").val(data[0].tipoprod);
        $("#presentacionEd").val(data[0].cod_pres);
        $("#laboratorioEd").val(data[0].rif_laboratorio);
        $("#tipoEd").val(data[0].id_tipo);
        $("#claseEd").val(data[0].id_clase);
        $("#composicionEd").val(data[0].composicion);
        $("#posologiaEd").val(data[0].posologia);
        $("#contraInEd").val(data[0].contraindicaciones);
        $("#tipoprodEd").val(data[0].id_tipoprod);
      },
      "json"
    );
  });

  $("#cod_productoEd").keyup(() =>
    validarStringLength($("#cod_productoEd"), $("#errorE1"), "Error del código")
  );
  $("#tipoprodEd").change(function () {
    validarSelect(
      $("#tipoprodEd"),
      $("#errorE2"),
      "Error al seleccionar el nombre del producto"
    );
  });
  $("#presentaciónEd").change(function () {
    validarSelect($("#presentaciónEd"), $("#errorE3"), "Error presentación");
  });
  $("#laboratorioEd").change(function () {
    validarSelect($("#laboratorioEd"), $("#errorE4"), "Error laboratorio");
  });
  $("#tipoEd").change(function () {
    validarSelect($("#tipoEd"), $("#errorE5"), "Error tipo producto");
  });
  $("#claseEd").change(function () {
    validarSelect($("#claseEd"), $("#errorE6"), "Error clase");
  });
  $("#composicionEd").keyup(() => {
    validarStringLength(
      $("#composicionEd"),
      $("#errorE7"),
      "Error de Composición"
    );
  });
  $("#posologiaEd").keyup(() => {
    validarStringLength($("#posologiaEd"), $("#errorE8"), "Error de posologia");
  });
  $("#contraInEd").keyup(() => {
    validarStringLength(
      $("#contraInEd"),
      $("#errorE9"),
      "Error de contraindicaciones"
    );
  });

  $("#editarform").submit(function (e) {
    e.preventDefault();

    let cod_producto = validarStringLength(
      $("#cod_productoEd"),
      $("#errorE1"),
      "Error del código",
      50
    );
    let tipoprod = validarSelect(
      $("#tipoprodEd"),
      $("#errorE2"),
      "Error al seleccionar nombre del producto"
    );
    let presentación = validarSelect(
      $("#presentacionEd"),
      $("#errorE3"),
      "Error presentación"
    );
    let laboratorio = validarSelect(
      $("#laboratorioEd"),
      $("#errorE4"),
      "Error laboratorio"
    );
    let tipo = validarSelect(
      $("#tipoEd"),
      $("#errorE5"),
      "Error tipo producto"
    );
    let clase = validarSelect($("#claseEd"), $("#errorE6"), "Error clase");
    let composicion = validarStringLength(
      $("#composicionEd"),
      $("#errorE7"),
      "Error de Composición",
      50
    );
    let posologia = validarStringLength(
      $("#posologiaEd"),
      $("#errorE8"),
      "Error de posologia",
      400
    );
    let contraIn = validarStringLength(
      $("#contraInEd"),
      $("#errorE9"),
      "Error de contraindicaciones",
      400
    );

    if (
      !cod_producto &&
      !tipoprod &&
      !presentacion &&
      !tipoP &&
      !clase &&
      !composicion &&
      !posologia &&
      !contraIn &&
      !tipo
    ) {
      throw new Error("Datos invalidos");
    }

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      {
        cod_productoEd: $("#cod_productoEd").val(),
        tipoprodEd: $("#tipoprodEd").val(),
        presentacionEd: $("#presentacionEd").val(),
        laboratorioEd: $("#laboratorioEd").val(),
        tipoEd: $("#tipoEd").val(),
        claseEd: $("#claseEd").val(),
        composicionEd: $("#composicionEd").val(),
        posologiaEd: $("#posologiaEd").val(),
        contraInEd: $("#contraInEd").val(),
        id,
      },
      function (data) {
        tablaMostrar.destroy();
        rellenar(); // FUNCIÓN PARA RELLENAR
        $("#editarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Producto actualizado." });
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON?.msg || "Ha ocurrido un error.",
        });
        console.error(e.responseJSON?.msg || "Ha ocurrido un error");
      })
      .always(() => {
        $(this).find('button[type="submit"]').prop("disabled", false);
      });
  });

  $(document).on("click", ".borrar", function () {
    id = this.id;
  });

  $("#delete").click(function () {
    $(this).prop("disabled", true);
    $.post(
      "",
      { delete: "delete", id },
      function (data) {
        tablaMostrar.destroy();
        rellenar();
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Producto eliminado." }); // ALERTA
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON?.msg || "Ha ocurrido un error.",
        });
        console.error(e.responseJSON?.msg || "Ha ocurrido un error");
      })
      .always(() => {
        $(this).prop("disabled", false);
      });
  });
});
