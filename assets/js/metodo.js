$(document).ready(function () {

  let mostrar;
  let permiso, editarPermiso, eliminarPermiso, registrarPermiso;

  $.ajax({
    method: 'POST', url: "", dataType: 'json', data: { getPermisos: '' },
    success(permisos) {
      registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
      editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
      eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
    }
  }).then(() => rellenar(true));


  function rellenar(bitacora = false) {
    $.ajax({
      type: "POST",
      url: "",
      dataType: "json",
      data: { mostrar: "metodo", bitacora },
      success(data) {
        let tabla;
        data.forEach(row => {
          tabla += `
            <tr>
            <td>${row.tipo_pago}</td>
            <td class="d-flex justify-content-center">
            <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
            <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
            </td>
            </tr>
            `;
        })
        $('#tbody').html(tabla);
        mostrar = $('#tabla').DataTable({
          resposive: true
        })
      }, error(e) {
        Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
        throw new Error('Error al mostrar listado: ' + e);
      }
    })
  }

  function validarTipoPago(input, div, id = false) {
    return new Promise((resolve, reject) => {
      $.post('', { tipoPago: input.val(), validarTipoPago: "metodo", id },
        function (data) {
          let mensaje = JSON.parse(data);
          if (mensaje.resultado === "error") {
            div.text(mensaje.msg);
            input.addClass('input-error')
            return reject(false);
          } else {
            div.text(" ");
            return resolve(true);
          }
        })
    })
  }

  let ytipo;
  let valid;
  let click = 0;
  setInterval(() => { click = 0; }, 2000);

  $('#tipo').keyup(() => {
    valid = validarStringLong($("#tipo"), $("#error"), "Error de tipo de pago")
    if (valid) validarTipoPago($("#tipo"), $("#error"))
  })

  $("#enviar").click((e) => {
    e.preventDefault()

    if (registrarPermiso === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    if (click >= 1) throw new Error('Spam de clicks');

    ytipo = validarStringLong($("#tipo"), $("#error"), "Error de tipo de pago");

    if (ytipo) {

      $.ajax({

        type: "POST",
        url: "",
        dataType: "json",
        data: {
          metodo: $("#tipo").val()
        },
        success(data) {
          if (data.resultado == 'registrado correctamente') {
            mostrar.destroy();
            rellenar();
            $('#user').trigger('reset');
            $("#close").click();
            $("#tipo").removeClass('input-error');
            $("#error").text("");
            Toast.fire({ icon: 'success', title: 'Metodo de pago registrado', showCloseButton: true });
          } else if (data.resultado === 'error') {
            $("#error").text(data.msg);
            $("#tipo").addClass('input-error');
          }
        }
      })
    }
    click++;
  })


  $(".cerrar").click(() => {
    $('#user').trigger('reset');
    $("input").attr("style", "borde-color:none; backgraund-image: none;");
    $("input").removeClass('input-error');
    $(".error").text("");
  })

  function validarExitencia() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: '',
        dataType: "json",
        data: { validarE: "existe", id },
        success(data) {
          if (data.resultado === "Error de metodo") {
            mostrar.destroy();
            rellenar();
            $('.cerrar').click();
            Toast.fire({ icon: 'error', title: 'Error de metodo de pago', showCloseButton: true }) // ALERTA 
            reject(false);
          } else {
            resolve(true);
          }

        }
      })
    })
  }

  let id;
  $(document).on('click', '.borrar', function () {
    id = this.id;
  })

  $("#deletes").click((e) => {
    e.preventDefault();

    if (click >= 1) throw new Error('Spam de clicks');

    validarExitencia().then(() => {

      $.ajax({
        type: "POST",
        url: '',
        dataType: 'json',
        data: {
          eliminar: 'eliminar',
          id
        },
        success(data) {
          if (data.resultado === "Eliminado") {
            $("#closeModal").click();
            mostrar.destroy();
            Toast.fire({ icon: 'success', title: 'Tipo de pago eliminado', showCloseButton: true })
            rellenar();
          }
        }
      })
    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;
  })


  $(document).on('click', '.editar', function () {
    id = this.id;

    $.ajax({
      type: "POST",
      url: '',
      dataType: 'json',
      data: {
        editar: 'editar metodo de pago',
        id
      },
      success(data) {
        $("#tipoEdit").val(data[0].tipo_pago);
      }
    })
  })

  $("#tipoEdit").keyup(() => {
    valid = validarStringLong($("#tipoEdit"), $("#error2"), "Error de tipo pago")
    if (valid) { validarTipoPago($("#tipoEdit"), $("#error2"), id) }
  });


  let ctipo;
  $("#enviarEdit").click((e) => {
    e.preventDefault()

    if (click >= 1) throw new Error('Spam de clicks');

    validarExitencia().then(() => {

      ctipo = validarStringLong($("#tipoEdit"), $("#error2"), "Error de tipo pago");
      if (ctipo) {
        $.ajax({

          type: "POST",
          url: "",
          dataType: "json",
          data: {
            tipoEdit: $("#tipoEdit").val(),
            id

          },
          success(data) {
            if (data.resultado == 'Editado') {
              mostrar.destroy();
              $("#closeEdit").click();
              Toast.fire({ icon: 'success', title: 'Tipo de pago editado', showCloseButton: true });
              rellenar();
            } else if (data.resultado === 'error') {
              $("#error2").text(data.msg);
              $("#tipoEdit").addClass('input-error')
            }
          }
        })
      }
    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;

  })

});
