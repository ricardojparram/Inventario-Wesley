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
    $.post(
      "",
      { mostrar: "", bitacora },
      (data) => {
        let tabla;
        const permisoEditar = !permisos["Editar"] ? "disabled" : "";
        const permisoEliminar = !permisos["Eliminar"] ? "disabled" : "";
        data.forEach((row) => {
          tabla += `
    			<tr>
	    			<td>${row.rif_laboratorio}</th>
	    			<td scope="col">${row.razon_social}</td>
	    			<td scope="col">${row.direccion}</td>                      
	    			<td >
		    			<span class="d-flex justify-content-center">
			    			<button type="button" ${permisoEditar} class="btn btn-success editar mx-2" id="${row.rif_laboratorio}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
			    			<button type="button" ${permisoEliminar} class="btn btn-danger borrar mx-2" id="${row.rif_laboratorio}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
		    			</span>
	    			</td>
    			</tr>`;
        });
        $("#tableMostrar tbody").html(tabla ? tabla : "");
        mostrar = $("#tableMostrar").DataTable({
          resposive: true,
        });
      },
      "json"
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
  }

  function validarRifBD(input, div, edit = false) {
    if (input.val() === edit) return true;
    $.getJSON("", { rif: input.val(), validar: "rif", edit }).fail((e) => {
      div.text(e.responseJSON?.msg);
      input.addClass("input-error");
    });
  }
  $("#rif").inputmask("rif");
  $("#rif").keyup(() => {
    let valid = validarRif($("#rif"), $("#error"), "Error de RIF,");
    debounce(() => {
      if (valid) validarRifBD($("#rif"), $("#error"));
    }, 800);
  });
  $("#razon").inputmask("razon_social");
  $("#razon").keyup(() => {
    validarRazonSocial($("#razon"), $("#error"), "Error de razon social,");
  });
  $("#direccion").keyup(() => {
    validarDireccion($("#direccion"), $("#error"), "Error de direccion,");
  });

  $("#agregarform").submit(function (e) {
    e.preventDefault();
    validarPermiso(permisos["Registrar"]);

    let vrif, vnombre, vdireccion, vtelefono;
    validarRif($("#rif"), $("#error"), "Error de RIF,");
    vnombre = validarNombre($("#razon"), $("#error"), "Error de nombre,");
    vdireccion = validarDireccion(
      $("#direccion"),
      $("#error"),
      "Error de direccion,"
    );

    if (!vnombre || !vdireccion) {
      throw new Error("Error.");
    }

    const body = {
      rif: $("#rif").val(),
      razon: $("#razon").val(),
      direccion: $("#direccion").val(),
    };

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      body,
      (data) => {
        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }

        mostrar.destroy();
        rellenar();
        $("#agregarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: data.msg });
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
    id = this.id;
    validarPermiso(permisos["Editar"]);
    $(this).prop("disabled", true);
    $.post(
      "",
      { select: "", id },
      (data) => {
        $("#rifEdit").val(data[0].rif_laboratorio);
        $("#razonEdit").val(data[0].razon_social);
        $("#direccionEdit").val(data[0].direccion);
      },
      "json"
    )
      .fail((e) => {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        console.error(e.responseJSON?.msg || "Ha ocurrido un error");
      })
      .always(() => {
        $(this).prop("disabled", false);
      });
  });

  $("#rifEdit").inputmask("rif");
  $("#rifEdit").keyup(() => {
    let valid = validarRif($("#rifEdit"), $("#errorEdit"), "Error de RIF,");
    if (valid) validarRifBD($("#rifEdit"), $("#errorEdit"), id);
  });

  $("#razonEdit").inputmask("razon_social");
  $("#razonEdit").keyup(() => {
    validarRazonSocial(
      $("#razonEdit"),
      $("#errorEdit"),
      "Error de razon social,"
    );
  });
  $("#direccionEdit").keyup(() => {
    validarDireccion(
      $("#direccionEdit"),
      $("#errorEdit"),
      "Error de direccion,"
    );
  });

  $("#editarform").submit(function (e) {
    e.preventDefault();
    validarPermiso(permisos["Editar"]);

    let vrif, vnombre, vdireccion, vtelefono;
    vrif = validarRif($("#rifEdit"), $("#errorEdit"), "Error de RIF,");
    vnombre = validarNombre(
      $("#razonEdit"),
      $("#errorEdit"),
      "Error de nombre,"
    );
    vdireccion = validarDireccion(
      $("#direccionEdit"),
      $("#errorEdit"),
      "Error de direccion,"
    );

    if (!vnombre || !vdireccion || !vrif) throw new Error("Error.");

    const body = {
      rifEdit: $("#rifEdit").val(),
      razonEdit: $("#razonEdit").val(),
      direccionEdit: $("#direccionEdit").val(),
      id,
    };

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      body,
      (data) => {
        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }

        mostrar.destroy();
        rellenar();
        $("#editarform").trigger("reset");
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: data.msg });
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
  });

  $(document).on("click", ".borrar", function () {
    validarPermiso(permisos["Eliminar"]);
    id = this.id;
  });

  $("#borrar").click(() => {
    validarPermiso(permisos["Eliminar"]);

    $(this).prop("disabled", true);
    $.post(
      "",
      { eliminar: "", id },
      (data) => {
        if (data.resultado != "ok") {
          Toast.fire({ icon: "error", title: data.msg });
          throw new Error(data.msg);
        }
        console.log(data);
        mostrar.destroy();
        $(".cerrar").click();
        rellenar();
        Toast.fire({ icon: "success", title: data.msg });
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
