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
    $.post("", { mostrar: "", bitacora }, (data) => {
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
      "json",
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
  }

  function validarRifBD(input, div, edit = false) {
    if (input.val() === edit) return true;
    $.getJSON("", { rif: input.val(), validar: "rif", edit })
      .fail(e => {
        console.log(e)
        div.text(e.responseJSON.msg);
        input.addClass('input-error');
      })
  }
  $("#rif").inputmask("rif");
  $("#rif").keyup(() => {
    let valid = validarRif($("#rif"), $("#error"), "Error de RIF,");
    if (valid) validarRifBD($("#rif"), $("#error"));
  });
  $("#razon").inputmask("nombre");
  $("#razon").keyup(() => {
    validarNombre($("#razon"), $("#error"), "Error de nombre,");
  });
  $("#direccion").keyup(() => {
    validarDireccion($("#direccion"), $("#error"), "Error de direccion,");
  });

  let click = 0;
  setInterval(() => {
    click = 0;
  }, 2000);

  $("#registrar").click((e) => {
    e.preventDefault();
    validarPermiso(permisos["Registrar"]);

    if (click >= 1) throw new Error("Spam de clicks");

    let vrif, vnombre, vdireccion, vtelefono;
    validarRif($("#rif"), $("#error"), "Error de RIF,");
    vnombre = validarNombre($("#razon"), $("#error"), "Error de nombre,");
    vdireccion = validarDireccion(
      $("#direccion"),
      $("#error"),
      "Error de direccion,",
    );

    if (!vnombre || !vdireccion) {
      throw new Error("Error.");
    }

    const body = {
      rif: $("#rif").val(),
      razon: $("#razon").val(),
      direccion: $("#direccion").val(),
    };
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
      "json",
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
    click++;
  });

  let id;

  $(document).on("click", ".editar", function () {
    id = this.id;
    validarPermiso(permisos["Editar"]);
    $.post(
      "",
      { select: "", id },
      (data) => {
        $("#rifEdit").val(data[0].rif_laboratorio);
        $("#razonEdit").val(data[0].razon_social);
        $("#direccionEdit").val(data[0].direccion);
      },
      "json",
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
  });

  $("#rifEdit").inputmask("rif");
  $("#rifEdit").keyup(() => {
    let valid = validarRif($("#rifEdit"), $("#errorEdit"), "Error de RIF,");
    if (valid) validarRifBD($("#rifEdit"), $("#errorEdit"), id);
  });
  $("#razonEdit").inputmask("nombre");
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

  $("#editar").click((e) => {
    e.preventDefault();
    validarPermiso(permisos["Editar"]);
    if (click >= 1) throw new Error("spaaam");

    let vrif, vnombre, vdireccion, vtelefono;
    vrif = validarRif($("#rifEdit"), $("#errorEdit"), "Error de RIF,");
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

    if (!vnombre || !vdireccion || !vrif) throw new Error("Error.");

    const body = {
      rifEdit: $("#rifEdit").val(),
      razonEdit: $("#razonEdit").val(),
      direccionEdit: $("#direccionEdit").val(),
      id,
    };
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
      "json",
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
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
    validarPermiso(permisos["Eliminar"]);

    if (click >= 1) throw new Error("spaaam");
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
      "json",
    ).fail((e) => {
      Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
      throw new Error("Error al mostrar listado: " + e);
    });
    click++;
  });
});
