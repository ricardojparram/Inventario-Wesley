$(function () {

  // obtener campos ocultar div
  var checkbox = $("#gridCheck1");
  var hidden = $("#divOcult");

  hidden.hide();
  checkbox.change(function () {
    if (checkbox.is(':checked')) {

      $("#divOcult").fadeIn()
    } else {

      $("#divOcult").fadeOut()
      $("#telClien, #emailClien").val(""); // limpia los valores de los input al ser ocultado
      $('input[type=checkbox]').prop('checked', false);// limpia los valores de checkbox al ser ocultado

    }
  });
});


$(document).ready(function () {

  let tabla;

  //Consulta de Permisos
  let permisos, editarPermiso, eliminarPermiso, registrarPermiso;
  $.ajax({
    method: 'POST', url: "", dataType: 'json', data: { getPermisos: 'a' },
    success(data) { permisos = data; }
  }).then(function () {
    refrescar(true);
    registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
    editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
    eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
    $('#agregarModalButton').attr(registrarPermiso, '');
  });

  //Rellenar Tabla
  function refrescar(bitacora = false) {
    $.ajax({
      method: "post",
      url: "",
      dataType: "json",
      data: { mostrar: "clien", bitacora },
      success(list) {
        list.forEach(row => {
          tabla += `
              <tr>
                <td>${row.nombre}</td>
                <td>${row.apellido}</td>
                <td>${row.cedula}</td>
                <td>${row.direccion}</td>
                <td>${row.celular} </td>
                <td>${row.correo} </td>
                <td class="d-flex justify-content-center">
                  <button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.cedulaE}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                  <button type="button" ${eliminarPermiso} class="btn btn-danger eliminar mx-2" id="${row.cedulaE}" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
	    	      	</td>
              </tr>`;
        });
        $('#tbody').html(tabla);
        tabla = $("#tabla").DataTable({
          responsive: true,
        });
      }
    })
  }

  let click = 0;
	setInterval(() => { click = 0; }, 2000);

  //Validaciones Keyup Registrar
  $("#nomClien").keyup(() => { validarNombre($("#nomClien"), $("#errorNom"), "Error de nombre,") });
  $("#apeClien").keyup(() => { validarNombre($("#apeClien"), $("#errorApe"), "Error de apellido,") });
  $("#cedClien").keyup(() => {
    let valid = validarCedula($("#cedClien"), $("#errorCed"), "Error de cédula,")
    if (valid) { validarC(" ", $("#cedClien"), $("#error")); }
  });
  $("#direcClien").keyup(() => { validarDireccion($("#direcClien"), $("#errorDirec"), "Error de direccion,") });
  $("#telClien").keyup(() => { validarTelefonoOp($("#telClien"), $("#errorTele"), "Error de telefono,") });
  $("#emailClien").keyup(() => {
    let valid = validarCorreoOp($("#emailClien"), $("#errorEmail"), "Error de correo,")
    if (valid) { validarE(" ", $("#emailClien"), $("#error")) }
  });



  //Envio de Datos Registrar
  $("#enviar").click((e) => {
    e.preventDefault()
    if (click >= 1) throw new Error('Spam de clicks');

    if (typeof permisos.Registrar === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    let correo, telefono, direccion, cedula, apellido, nombre
    correo = validarCorreoOp($("#emailClien"), $("#errorEmail"), "Error de correo,");
    telefono = validarTelefonoOp($("#telClien"), $("#errorTele"), "Error de telefono,");
    direccion = validarDireccion($("#direcClien"), $("#errorDirec"), "Error de direccion,");
    cedula = validarCedula($("#cedClien"), $("#errorCed"), "Error de Cedula,");
    apellido = validarNombre($("#apeClien"), $("#errorApe"), "Error de apellido,");
    nombre = validarNombre($("#nomClien"), $("#errorNom"), "Error de nombre,");
    if (cedula) {
      validarC(" ", $("#cedClien"), $("#error")).then(() => {
        console.log(correo)
        if (correo) {
          validarE(" ", $("#emailClien"), $("#error")).then(() => {

            if (telefono && direccion && apellido && nombre) {

              $.ajax({
                type: "POST",
                url: '',
                dataType: "json",
                data: {
                  nomClien: $("#nomClien").val(),
                  apeClien: $("#apeClien").val(),
                  cedClien: $("#cedClien").val(),
                  direcClien: $("#direcClien").val(),
                  telClien: $("#telClien").val(),
                  emailClien: $("#emailClien").val()
                },
                success(user) {
                  console.log(user);
                  if (user.resultado === "Registrado correctamente.") {
                    tabla.destroy();
                    $("#cerrarModal").click();
                    Toast.fire({ icon: 'success', title: 'Cliente Registrado' })
                    refrescar();
                  } else {
                    tabla.destroy();
                    $("#error").text(user.resultado + ", " + user.error)
                    refrescar();
                  }
                }
              })
            }
          })

        }
      })
    }
    click++
  })

  //Asignacion id para Eliminar
  var cedulaDel;
  $(document).on('click', '.eliminar', function () {
    cedulaDel = this.id;
  });

  //Eliminar id asignado
  $("#delete").click((e) => {
    e.preventDefault()
    if (click >= 1) throw new Error('Spam de clicks');

    if (typeof permisos.Eliminar === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    validarC(cedulaDel, $("#NOa"), $("#errorDel")).then(() => {
      $.ajax({
        type: "POST",
        url: '',
        dataType: 'json',
        data: {
          eliminar: 'eliminar',
          cedulaDel
        },
        success(eli) {
          if (eli.resultado === "Eliminado") {
            tabla.destroy();
            $("#cerrarModalDel").click();
            Toast.fire({ icon: 'error', title: 'Cliente Eliminado' })
            refrescar();
          } else {
            $("#errorDel").text("El cliente no Pudo Ser Eliminado");
          }
        }
      })
    })
    click++
  })

  //Asignacion id para Editar con Relleno de Inputs
  var idCed;
  $(document).on('click', '.editar', function () {
    idCed = this.id;

    $.ajax({
      type: "POST",
      url: "",
      dataType: "json",
      data: { unico: 'lol', idCed },
      success(data) {
        $("#nomClienEdit").val(data[0].nombre);
        $("#apeClienEdit").val(data[0].apellido);
        $("#cedClienEdit").val(data[0].cedula);
        $("#direcClienEdit").val(data[0].direccion);
        $("#telClienEdit").val(data[0].celular);
        $("#emailClienEdit").val(data[0].correo);
      }
    })
  });

  //Validaciones Keyup para Editar
  $("#telClienEdit").keyup(() => { validarTelefonoOp($("#telClienEdit"), $("#errorTeleEdit"), "Error de Telefono,") });
  $("#emailClienEdit").keyup(() => {
    let valid = validarCorreoOp($("#emailClienEdit"), $("#errorEmailEdit"), "Error de Correo,")
    if (valid) { validarE(idCed, $("#emailClienEdit"), $("#error2")) }
  });
  $("#nomClienEdit").keyup(() => { validarNombre($("#nomClienEdit"), $("#errorNomEdit"), "Error de Nombre,") });
  $("#apeClienEdit").keyup(() => { validarNombre($("#apeClienEdit"), $("#errorApeEdit"), "Error de Apellido,") });
  $("#cedClienEdit").keyup(() => {
    let valid = validarCedula($("#cedClienEdit"), $("#errorCedEdit"), "Error de Cedula,")
    if (valid) { validarC(idCed, $("#cedClienEdit"), $("#error2")); }
  });
  $("#direcClienEdit").keyup(() => { validarDireccion($("#direcClienEdit"), $("#errorDirecEdit"), "Error de Direccion,") });

  //Envio de Datos Registrar
  $("#enviarEdit").click((e) => {
    e.preventDefault()
    if (click >= 1) throw new Error('Spam de clicks');
    
    if (typeof permisos.Editar === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    let nombreEdit = validarNombre($("#nomClienEdit"), $("#errorNomEdit"), "Error de Nombre,");
    let direccionEdit = validarDireccion($("#direcClienEdit"), $("#errorDirecEdit"), "Error de Direccion,");
    let cedulaEdit = validarCedula($("#cedClienEdit"), $("#errorCedEdit"), "Error de Cedula,");
    let apellidoEdit = validarNombre($("#apeClienEdit"), $("#errorApeEdit"), "Error de Apellido,");
    let correoEdit = validarCorreoOp($("#emailClienEdit"), $("#errorEmailEdit"), "Error de Correo,");
    let telefonoEdit = validarTelefonoOp($("#telClienEdit"), $("#errorTeleEdit"), "Error de Telefono,");

    let nomEdit = $("#nomClienEdit").val();
    let apeEdit = $("#apeClienEdit").val();
    let cedEdit = $("#cedClienEdit").val();
    let direcEdit = $("#direcClienEdit").val();
    let celuEdit = $("#telClienEdit").val();
    let emailEdit = $("#emailClienEdit").val();

    if (cedulaEdit) {
      validarC(idCed, $("#cedClienEdit"), $("#error2")).then(() => {
        if (correoEdit) {
          validarE(idCed, $("#emailClienEdit"), $("#error2")).then(() => {
            if (nombreEdit && direccionEdit && apellidoEdit && telefonoEdit) {
              $.ajax({
                type: "POST",
                url: '',
                dataType: "json",
                data: { nomEdit, apeEdit, cedEdit, direcEdit, celuEdit, emailEdit, idCed },
                success(result) {
                  console.log(result);
                  if (result.resultado === "Editado") {
                    tabla.destroy();
                    $("#cerrarModalEdit").click();
                    Toast.fire({ icon: 'success', title: 'Cliente Actualizado' })
                    refrescar();
                  } else {
                    $("#error2").text(result.resultado + ", " + result.error)
                  }
                }
              })
            }
          })
        }
      })
    }
    click++
  })

  //Reset de Error y Validaciones
  $(document).on('click', '#cerrarModalEdit', function () {
    $("#editModal p").text(" ");
    $("#editModal input").attr("style", "border-color: none;")
    $("#editModal input").attr("style", "backgraund-image: none;");
  })

  $(document).on('click', '#cerrarModal', function () {
    $("#basicModal p").text(" ");
    $("#basicModal input").attr("style", "border-color: none;")
    $("#basicModal input").attr("style", "backgraund-image: none;");
    $("#divOcult").fadeOut()
  })

  $(document).on('click', '#cerrarModalDel', function () {
    $("#delModal p").text(" ");
  })

  //Validacion para la Cedula 
  let val
  function validarC(valor, input, div) {
    val = (input.val() == undefined) ? " " : input.val()
    return new Promise((resolve, reject) => {
      $.getJSON('', {
        cedula: val,
        idVal: valor,
        validar: 'xd'
      },
        function (valid) {
          console.log(valid)
          if (valid.resultado === "Error") {
            div.text("Error de Cedula, " + valid.msj);
            input.attr("style", "border-color: red;");
            input.attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
            return reject(false);
          } else {
            div.text("");
            return resolve(true);
          }
        }
      )
    })
  }

  //Validacion para correo
  function validarE(valor, input, div) {
    return new Promise((resolve, reject) => {
      $.getJSON('', {
        correo: input.val(),
        idVal: valor,
        validarE: 'lol'
      },
        function (valid) {
          console.log(valid)
          if (valid.resultado === "Error") {
            div.text("Error de Correo, " + valid.msj);
            input.attr("style", "border-color: red;");
            input.attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
            return reject(false);
          } else {
            div.text("");
            return resolve(true);
          }
        }
      )
    })
  }


})