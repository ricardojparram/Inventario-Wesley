$(document).ready(function () {
  let mostrar;
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
    $.ajax({
      type: "GET",
      url: "",
      dataType: "json",
      data: { mostrar: "", bitacora },
      success(data) {
        const permisoEditar = !permisos["Editar"] ? "disabled" : "";
        const permisoEliminar = !permisos["Eliminar"] ? "disabled" : "";
        let tabla;
        data.forEach((row) => {
          tabla += `
            <tr>
              <td>${row.nombre}</td>
              <td>${row.telefono}</td>
              <td>${row.direccion}</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${permisoEditar} id="${row.id_sede}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                <button type="button" ${permisoEliminar} id="${row.id_sede}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>
        `;
        });
        $("#tbody").html(tabla || "");
        mostrar = $("#tableMostrar").DataTable({
          resposive: true,
        });
      },
    });
  }

  $("#sedeNombre").inputmask("nombre");
  $("#sedeNombre").keyup(() =>
    validarNombre($("#sedeNombre"), $("#error1"), "Error de Nombre de sede")
  );
  $("#sedeTelefono").inputmask({ mask: "99999999999", placeholder: "" });
  $("#sedeTelefono").keyup(() =>
    validarTelefono(
      $("#sedeTelefono"),
      $("#error2"),
      "Error de Telefono de sede"
    )
  );
  $("#sedeDireccion").inputmask("direccion");
  $("#sedeDireccion").keyup(() =>
    validarDireccion(
      $("#sedeDireccion"),
      $("#error3"),
      "Error de direccion de sede"
    )
  );

  $("#agregarform").submit(function (e) {
    e.preventDefault();
    validarPermiso(permisos["Registrar"]);

    let vnombre, vtelefono, vdireccion;
    vnombre = validarNombre(
      $("#sedeNombre"),
      $("#error1"),
      "Error de Nombre de sede"
    );
    vtelefono = validarTelefono(
      $("#sedeTelefono"),
      $("#error2"),
      "Error de Telefono de sede"
    );
    vdireccion = validarDireccion(
      $("#sedeDireccion"),
      $("#error3"),
      "Error de direccion de sede"
    );

    if (!vnombre || !vtelefono || !vdireccion) {
      throw new Error("Error en las entradas de los inputs.");
    }

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      {
        nombre: $("#sedeNombre").val(),
        telefono: $("#sedeTelefono").val(),
        direccion: $("#sedeDireccion").val(),
        registrar: "",
      },
      function (data) {
        mostrar.destroy();
        rellenar();
        $("#registrar").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "Registrado con exito." });
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

  let id;
  $(document).on("click", ".editar", function () {
    validarPermiso(permisos["Editar"]);
    id = this.id;
    $.ajax({
      method: "GET",
      url: "",
      dataType: "json",
      data: { select: "", id },
      success(data) {
        $("#sedeNombreEditar").val(data.nombre);
        $("#sedeTelefonoEditar").val(data.telefono);
        $("#sedeDireccionEditar").val(data.direccion);
      },
    });
  });

  $("#sedeNombreEditar").inputmask("nombre");
  $("#sedeNombreEditar").keyup(() =>
    validarNombre(
      $("#sedeNombreEditar"),
      $("#error1"),
      "Error de Nombre de sede"
    )
  );
  $("#sedeTelefonoEditar").inputmask({ mask: "99999999999", placeholder: "" });
  $("#sedeTelefonoEditar").keyup(() =>
    validarTelefono(
      $("#sedeTelefonoEditar"),
      $("#error2"),
      "Error de Telefono de sede"
    )
  );
  $("#sedeDireccionEditar").inputmask("direccion");
  $("#sedeDireccionEditar").keyup(() =>
    validarDireccion(
      $("#sedeDireccionEditar"),
      $("#error3"),
      "Error de direccion de sede"
    )
  );
  $("#editarform").submit((e) => {
    e.preventDefault();
    validarPermiso(permisos["Editar"]);

    vnombre = validarNombre($("#sedeNombreEditar"), $("#error1"), "Nombre,");
    vtelefono = validarTelefono(
      $("#sedeTelefonoEditar"),
      $("#error2"),
      "Telefono"
    );
    vdireccion = validarDireccion(
      $("#sedeDireccionEditar"),
      $("#error3"),
      "Sede de envío,"
    );

    if (!vnombre || !vtelefono || !vdireccion) {
      throw new Error("Error en las entradas de los inputs.");
    }

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      {
        id,
        editar: "",
        nombre: $("#sedeNombreEditar").val(),
        telefono: $("#sedeTelefonoEditar").val(),
        direccion: $("#sedeDireccionEditar").val(),
      },
      function (data) {
        mostrar.destroy();
        rellenar();
        $("#editarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({
          icon: "success",
          title: "Se ha editado con exito.",
          showCloseButton: true,
        });
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON?.msg || "Ha ocurrido un error.",
        });
        console.error(
          e.responseJSON?.msg ? e.responseJSON?.msg : "Ha ocurrido un error"
        );
      })
      .always(() => {
        $(this).find('button[type="submit"]').prop("disabled", false);
      });
  });

  $(document).on("click", ".borrar", function () {
    validarPermiso(permisos["Eliminar"]);
    id = this.id;
  });

  $("#borrar").click(() => {
    validarPermiso(permisos["Eliminar"]);

    $.post(
      "",
      { eliminar: "", id },
      function (data) {
        mostrar.destroy();
        $(".cerrar").click();
        rellenar();
        Toast.fire({ icon: "success", title: "Sede eliminado con exito." });
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
});
