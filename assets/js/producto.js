$(document).ready(function () {
  fechaHoy($("#fecha"));

  let tablaMostrar;
  let permiso, eliminarPermiso, registrarPermiso;

  $.ajax({
    method: "POST",
    url: "",
    dataType: "json",
    data: { getPermisos: "permiso" },
    success(data) {
      permiso = data;
    },
  }).then(function () {
    rellenar(true);
    registrarPermiso = permiso.registrar != 1 ? "disable" : "";
    $("#agregarModal").attr(registrarPermiso, "");
  });

  function rellenar(bitacora = false) {
    $.ajax({
      method: "POST",
      url: " ",
      dataType: "json",
      data: { mostrar: "produ", bitacora },
      success(data) {
        let tabla;
        data.forEach((row) => {
          editarPermiso = permiso.editar != 1 ? "disable" : "";
          imagenPermiso = permiso.imagen != 1 ? "disable" : "";
          eliminarPermiso = permiso.eliminar != 1 ? "disable" : "";

          tabla += `
            <tr>
            <td>${row.cod_producto}</td>
            <td>${row.nombrepro}</td>
            <td>${row.pres}</td>
            <td class="d-flex justify-content-center">
            <button type="button" ${editarPermiso} id="${row.cod_producto}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
            <button type="button" ${eliminarPermiso} id="${row.cod_producto}" class="btn btn-danger borrar mx-2"  data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
            
           
            </td>
            </tr>
            `;
        });
        $("#tbody").html(tabla);
        tablaMostrar = $("#tableMostrar").DataTable({
          resposive: true,
        });
      },
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

  let click = 0;
  setInterval(() => {
    click = 0;
  }, 2000);

  /* --- AGREGAR --- */

  $("#agregarform").submit((e) => {
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
      cod_producto &&
      tipoprod &&
      presentacion &&
      tipoP &&
      clase &&
      composicion &&
      posologia &&
      contraIn
    ) {
      $cod_producto = $("#cod_producto");
      $tipoprod = $("#tipoprod");
      $presentacionP = $("#presentacion");
      $laboratorioP = $("#laboratorio");
      $tipoP = $("#tipoP");
      $clase = $("#clase");
      $composicionP = $("#composicion");
      $posologiaP = $("#posologia");
      $contraInP = $("#contraIn");

      //console.log(laboratorioP);

      //  ENVÍO DE DATOS
      $.ajax({
        type: "POST",
        url: "",
        dataType: "json",
        data: {
          cod_producto: $cod_producto.val(),
          tipoprod: $tipoprod.val(),
          presentacion: $presentacionP.val(),
          laboratorio: $laboratorioP.val(),
          tipoP: $tipoP.val(),
          clase: $clase.val(),
          composicionP: $composicionP.val(),
          posologia: $posologiaP.val(),
          contrain: $contraInP.val(),
        },
        success(data) {
          tablaMostrar.destroy();
          rellenar(); // FUNCIÓN PARA RELLENAR
          $("#agregarform").trigger("reset"); // LIMPIAR EL FORMULARIO
          $(".cerrar").click();
          //fechaHoy($('#fecha'));
          Toast.fire({ icon: "success", title: "Producto registrado" });
        },
      });
    }
  });

  /* --- CERRAR REGISTRAR --- */

  $("#cerrar").click(() => {
    $("#agregarform").trigger("reset"); // LIMPIAR EL FORMULARIO
    $("#basicModal input").attr(
      "style",
      "borden-color:none;",
      "borden-color:none;"
    );
    $("#basicModal select").attr(
      "style",
      "borden-color:none;",
      "borden-color:none;"
    );
    $(".error").text("");
    fechaHoy($("#fecha"));
  });

  /* --- CERRAR MODIFICAR --- */

  $("#Cancelar").click(() => {
    $("#editModal input").attr(
      "style",
      "borden-color:none;",
      "borden-color:none;"
    );
    $("#editModal select").attr(
      "style",
      "borden-color:none;",
      "borden-color:none;"
    );
    $("#error").text("");
    fechaHoy($("#fecha"));
  });

  /* --- EDITAR --- */
  let id;

  $(document).on("click", ".editar", function () {
    id = this.id; // se obtiene el id del botón, previamente le puse de id el codigo en rellenar()
    // RELLENA LOS INPUTS
    $.ajax({
      method: "POST",
      url: "",
      dataType: "json",
      data: { select: "edit", id },
      success(data) {
        console.log(data);

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
    });
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

  $("#actualizar").click((e) => {
    //VALIDACIONES
    if (click >= 1) throw new Error("Spam de clicks");

    let cod_productoE = validarStringLength(
      $("#cod_productoEd"),
      $("#errorE1"),
      "Error del código",
      50
    );
    let tipoprodEd = validarSelect(
      $("#tipoprodEd"),
      $("#errorE2"),
      "Error al seleccionar nombre del producto"
    );
    let presentaciónEd = validarSelect(
      $("#presentacionEd"),
      $("#errorE3"),
      "Error presentación"
    );
    let laboratorioEd = validarSelect(
      $("#laboratorioEd"),
      $("#errorE4"),
      "Error laboratorio"
    );
    let tipoEd = validarSelect(
      $("#tipoEd"),
      $("#errorE5"),
      "Error tipo producto"
    );
    let claseEd = validarSelect($("#claseEd"), $("#errorE6"), "Error clase");
    let composicionEd = validarStringLength(
      $("#composicionEd"),
      $("#errorE7"),
      "Error de Composición",
      50
    );
    let posologiaEd = validarStringLength(
      $("#posologiaEd"),
      $("#errorE8"),
      "Error de posologia",
      400
    );
    let contraInEd = validarStringLength(
      $("#contraInEd"),
      $("#errorE9"),
      "Error de contraindicaciones",
      400
    );

    if (
      cod_productoEd &&
      tipoprodEd &&
      presentaciónEd &&
      tipoEd &&
      claseEd &&
      composicionEd &&
      posologiaEd &&
      contraInEd
    ) {
      //  ENVÍO DE DATOS
      $.ajax({
        type: "POST",
        url: "",
        dataType: "json",
        data: {
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
        success(data) {
          tablaMostrar.destroy();
          rellenar(); // FUNCIÓN PARA RELLENAR
          $("#editarform").trigger("reset");
          $(".cerrar").click();
          Toast.fire({ icon: "success", title: "Producto Actualizado" });
        },
      });
    } else {
      e.preventDefault();
    }
    click++;
  });

  /* --- ELIMINAR --- */

  $(document).on("click", ".borrar", function () {
    id = this.id;
  });

  $("#delete").click(() => {
    if (click >= 1) {
      throw new Error("Spam de clicks");
    }
    console.log(id);
    $.ajax({
      type: "POST",
      url: "",
      data: { delete: "delete", id },
      success(data) {
        tablaMostrar.destroy();
        rellenar();
        $(".cerrar").click();
        Toast.fire({ icon: "success", title: "producto Eliminado" }); // ALERTA
      },
    });
    click++;
  });

  $(document).on("click", ".infoImg", function () {
    id = this.id;
    $.ajax({
      method: "post",
      url: "",
      dataType: "json",
      data: { select1: true, id },
      success(data) {
        $("#imgEditar").attr("src", data[0].img);
      },
    });
  });

  let imagen = document.getElementById("imgModal");
  let imgPreview = document.getElementById("imgEditar");
  let input = document.getElementById("img");
  let default_img = "assets/img/productos/producto_imagen.png";

  $("#borrarFoto").click(() => {
    imgPreview.src = default_img;
  });

  let cropper;
  $("#fotoModal")
    .on("shown.bs.modal", function () {
      cropper = new Cropper(imagen, {
        aspectRatio: 1,
        viewMode: 3,
      });
    })
    .on("hidden.bs.modal", function () {
      cropper.destroy();
      cropper = null;
    });

  let canvas;
  $("#aceptarCroppedImg").click(function () {
    if (!cropper) throw new Error("Error al recortar");

    canvas = cropper.getCroppedCanvas({
      width: 500,
      height: 500,
    });
    imgPreview.src = canvas.toDataURL();
    $("#fotoModal").modal("hide");
  });

  $("#actualizarImg").click((e) => {
    e.preventDefault();
    if (click >= 1) throw new Error("Spam de clicks");

    let form = new FormData($("#formEditar")[0]);
    form.append("id", id);
    let borrar = $("#imgEditar").is(`[src="${default_img}"]`);

    if (borrar != true) {
      if (typeof canvas === "undefined" || typeof canvas == null) {
        Toast.fire({ icon: "warning", title: "No ha cambiado la imagen." });
        throw Error("Canvas no tiene ninguna imagen cortada");
      } else {
        canvas.toBlob(function (blob) {
          form.set("foto", blob, "avatar.png");
          editarImagen(form);
        });
      }
    }

    if (borrar) {
      form.append("borrar", "borrarImg");
      editarImagen(form);
    }

    click++;
  });

  function editarImagen(form) {
    form.append("editarImg", "");
    $.ajax({
      type: "POST",
      url: "",
      dataType: "JSON",
      data: form,
      contentType: false,
      processData: false,
      xhr: () => loading(),
      success(data) {
        $("#displayProgreso").hide();
        if (data.respuesta == "error") {
          $("#error").text(data.error);
          throw new Error("Error de foto.");
        }
        if (data.respuesta == "ok") {
          $("#formEditar").trigger("reset");
          imgPreview.src = "#";
          Toast.fire({
            icon: "success",
            title: "Foto del producto actualizada",
          });
          $(".cerrar").click();
        }
      },
      error(data) {
        $("#displayProgreso").hide();
        Toast.fire({
          icon: "error",
          title: "Ha ocurrido un error al subir la imágen.",
        });
        console.log(data);
      },
    });
  }

  function loading() {
    let xhr = new window.XMLHttpRequest();
    $("#displayProgreso").show();
    xhr.upload.addEventListener(
      "progress",
      function (event) {
        if (event.lengthComputable) {
          let porcentaje = parseInt((event.loaded / event.total) * 100, 10);
          $("#progressBar").data("aria-valuenow", porcentaje);
          $("#progressBar").css("width", porcentaje + "%");
          $("#progressBar").html(porcentaje + "%");
        }
      },
      false
    );
    xhr.addEventListener(
      "progress",
      function (e) {
        if (e.lengthComputable) {
          percentComplete = parseInt((e.loaded / e.total) * 100, 10);
          $("#progressBar").data("aria-valuenow", percentComplete);
          $("#progressBar").css("width", percentComplete + "%");
          $("#progressBar").html(percentComplete + "%");
        } else {
          $("#progressBar").html("Upload");
        }
      },
      false
    );

    return xhr;
  }
});
