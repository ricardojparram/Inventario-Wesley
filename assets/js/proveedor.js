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
      data: { mostrar: "prove", bitacora },
      success(data) {
        let tabla;
        editarPermiso =
          typeof permisos.Editar === "undefined" ? "disabled" : "";
        eliminarPermiso =
          typeof permisos.Eliminar === "undefined" ? "disabled" : "";
        data.forEach((row) => {
          tabla += `
                        <tr>
                            <td>${row.rif_proveedor}</th>
                            <td scope="col">${row.razon_social}</td>
                            <td scope="col">${row.direccion}</td>                      
                            <td scope="col">${row.telefono}</td>
                            <td scope="col">${row.contacto == null ? "" : row.contacto}</td>
                            <td class="d-flex justify-content-center">
                              <button type="button" ${editarPermiso} class="btn btn-registrar editar mx-2" id="${row.rif_proveedor}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                              <button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.rif_proveedor}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
                            </td>
                        </tr>`;
        });
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

  function validarRifBD(input, div) {
    $.post("", { rif: input.val(), validar: "rif" }, function (data) {
      let mensaje = JSON.parse(data);
      if (mensaje.resultado === "Error de rif") {
        div.text(mensaje.error);
        input.attr("style", "border-color: red;");
        input.attr(
          "style",
          "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
        );
      }
    });
  }

  $("#rif").inputmask("rif");
  $("#rif").keyup(() => {
    let valid = validarRif($("#rif"), $("#error"), "Error de RIF,");
    if (valid) validarRifBD($("#rif"), $("#error"));
  });
  $("#razon").keyup(() => {
    validarNombre($("#razon"), $("#error"), "Error de nombre,");
  });
  $("#direccion").keyup(() => {
    validarDireccion($("#direccion"), $("#error"), "Error de direccion,");
  });
  $("#telefono").keyup(() => {
    validarTelefono($("#telefono"), $("#error"), "Error de telefono,");
  });

  let click = 0;
  setInterval(() => {
    click = 0;
  }, 2000);

  $("#registrar").click((e) => {
    e.preventDefault();

    if (typeof permisos.Registrar === "undefined") {
      Toast.fire({
        icon: "error",
        title: "No tienes permisos para esta acción.",
      });
      throw new Error("Permiso denegado.");
    }

    if (click >= 1) throw new Error("Spam de clicks");

    let vrif, vnombre, vdireccion, vtelefono;
    // validarCedula($("#rif"), $("#error"), "Error de RIF,");
    vnombre = validarNombre($("#razon"), $("#error"), "Error de nombre,");
    vdireccion = validarDireccion(
      $("#direccion"),
      $("#error"),
      "Error de direccion,",
    );
    vtelefono = validarTelefono(
      $("#telefono"),
      $("#error"),
      "Error de telefono,",
    );

    if (!vnombre || !vdireccion || !vtelefono) {
      throw new Error("Error.");
    }

    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: {
        rif: $("#rif").val(),
        razon: $("#razon").val(),
        direccion: $("#direccion").val(),
        telefono: $("#telefono").val(),
        contacto: $("#contacto").val(),
      },
      success(data) {
        if (data.resultado === "Error de rif") {
          $("#error").text(data.error);
          $("#rif").attr("style", "border-color: red;");
          $("#rif").attr(
            "style",
            "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
          );
          throw new Error("Rif inválido");
        }

        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }

        mostrar.destroy();
        rellenar();
        $("#agregarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Proveedor registrado" });
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al mostrar listado: " + e);
      },
    });
    click++;
  });

  let id;

  $(document).on("click", ".editar", function () {
    id = this.id;
    $.ajax({
      method: "post",
      url: "",
      dataType: "json",
      data: { select: "prove", id },
      success(data) {
        $("#rifEdit").val(data[0].rif_proveedor);
        $("#razonEdit").val(data[0].razon_social);
        $("#direccionEdit").val(data[0].direccion);
        $("#telefonoEdit").val(data[0].telefono);
        $("#contactoEdit").val(data[0].contacto);
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al mostrar listado: " + e);
      },
    });
  });

  $("#rifEdit").inputmask("rif");
  $("#rifEdit").keyup(() => {
    let valid = validarRif($("#rifEdit"), $("#error"), "Error de RIF,");
    if (valid) validarRifBD($("#rifEdit"), $("#error"));
  });
  $("#razonEdit").keyup(() => {
    validarNombre($("#razonEdit"), $("#errorEdit"), "Error de nombre,");
  });
  $("#direccionEdit").keyup(() => {
    validarDireccion(
      $("#direccionEdit"),
      $("#errorEdit"),
      "Error de direccion,",
    );
  });
  $("#telefonoEdit").keyup(() => {
    validarTelefono($("#telefonoEdit"), $("#errorEdit"), "Error de telefono,");
  });

  $("#editar").click((e) => {
    e.preventDefault();

    if (typeof permisos.Editar === "undefined") {
      Toast.fire({
        icon: "error",
        title: "No tienes permisos para esta acción.",
      });
      throw new Error("Permiso denegado.");
    }

    if (click >= 1) throw new Error("spaaam");

    let vrif, vnombre, vdireccion, vtelefono;

    validarRif($("#rifEdit"), $("#error"), "Error de RIF,");
    vnombre = validarNombre(
      $("#razonEdit"),
      $("#errorEdit"),
      "Error de nombre,",
    );
    vdireccion = validarDireccion(
      $("#direccionEdit"),
      $("#errorEdit"),
      "Error de direccion,",
    );
    vtelefono = validarTelefono(
      $("#telefonoEdit"),
      $("#errorEdit"),
      "Error de telefono,",
    );

    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: {
        rifEdit: $("#rifEdit").val(),
        razonEdit: $("#razonEdit").val(),
        direccionEdit: $("#direccionEdit").val(),
        telefonoEdit: $("#telefonoEdit").val(),
        contactoEdit: $("#contactoEdit").val(),
        id,
      },
      success(data) {
        if (data.resultado === "Error de rif") {
          $("#errorEdit").text(data.ok);
          $("#rifEdit").attr("style", "border-color: red;");
          $("#rifEdit").attr(
            "style",
            "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
          );
          throw new Error("Rif ya registrado.");
        }

        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }
        mostrar.destroy();
        rellenar();
        $("#editarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Proveedor modificado" });
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al mostrar listado: " + e);
      },
    });
    click++;
  });

  $(document).on("click", ".cerrar", function () {
    $("#agregarform").trigger("reset");
    $("#editarform").trigger("reset");
    $("#agregarform input").attr(
      "style",
      "border-color: none; background-image: none;",
    );
    $("#editarform input").attr(
      "style",
      "border-color: none; background-image: none;",
    );
    $("#error").text("");
    $("#errorEdit").text("");
  });

  $(document).on("click", ".borrar", function () {
    id = this.id;
  });

  $("#borrar").click(() => {
    if (typeof permisos.Eliminar === "undefined") {
      Toast.fire({
        icon: "error",
        title: "No tienes permisos para esta acción.",
      });
      throw new Error("Permiso denegado.");
    }

    if (click >= 1) throw new Error("spaaam");
    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: { eliminar: "prove", id },
      success(data) {
        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }
        mostrar.destroy();
        $(".cerrar").click();
        rellenar();
        Toast.fire({ icon: "success", title: "Proveedor eliminado" });
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al mostrar listado:" + e);
      },
    });
    click++;
  });
});
