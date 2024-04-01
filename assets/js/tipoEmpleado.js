$(document).ready(function () {

  let mostrar;
  let permiso, editarPermiso, eliminarPermiso, registrarPermiso;

  $.ajax({
    method: 'POST', url: '', dataType: 'json', data: { getPermiso: '' },
    success(permisos) {
      registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
      editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
      eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
    }
  }).then(() => { rellenar(true) });

  function rellenar(bitacora = false) {
    $.ajax({
      type: 'POST',
      url: "",
      dataType: 'json',
      data: { mostrar: 'tipoEmpleado', bitacora },
      success(data) {
        let tabla;
        data.forEach(row => {
          tabla += `
            <tr>
              <td>${row.nombre_e}</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.tipo_em}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.tipo_em}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>
         `;
        })
        $('#tbody').html(tabla);
        mostrar = $('#tabla').DataTable({
          resposive: true
        })

      }
    })
  }

  function validarTipoEmpleado(input, div, id = false) {
    return new Promise((resolve, reject) => {
      $.post('', { tipoEmpleado: input.val(), validarTipoEmpleado: "tipo empleado", id },
        function (data) {
          let mensaje = JSON.parse(data);
          if (mensaje.resultado === "error") {
            div.text(mensaje.msg);
            input.attr("style", "border-color: red;")
            input.attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
            return reject(false);
          } else {
            div.text(" ");
            return resolve(true);
          }
        })
    })
  }

  let tipoEmpleado;
  let valid;
  let click = 0;
  setInterval(() => { click = 0; }, 2000);

  $('#tipoEmpleado').keyup(() => {
    valid = validarStringLong($("#tipoEmpleado"), $("#error"), "Error de tipo empleado");
    if (valid) validarTipoEmpleado($("#tipoEmpleado"), $("#error"));
  })

  // Registrar Empleado

  $('#registrar').click((e) => {
    e.preventDefault();

    if (registrarPermiso === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    if (click >= 1) throw new Error('Spam de clicks');

    tipoEmpleado = validarStringLong($("#tipoEmpleado"), $("#error"), "Error de tipo empleado");

    if (tipoEmpleado) {

      $.ajax({
        type: 'POST',
        url: '',
        dataType: 'json',
        data: {
          tipoEmpleado: $("#tipoEmpleado").val()
        },
        success(data) {
          if (data.resultado === 'registrado correctamente') {
            mostrar.destroy();
            rellenar();
            $('#user').trigger('reset');
            $("#close").click();
            $("#tipoEmpleado").attr("style", "borde-color:none; backgraund-image: none;");
            $("#error").text("");
            Toast.fire({ icon: 'success', title: 'Tipo empleado registrado', showCloseButton: true });
          } else if (data.resultado === 'error') {
            $("#error").text(data.msg);
            $("#tipoEmpleado").attr("style", "border-color: red;")
            $("#tipoEmpleado").attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
          }
        }
      })
    }

    click++;

  })

  // Cerrar Modal

  $(".cerrar").click(() => {
    $('#user').trigger('reset');
    $("input").attr("style", "borde-color:none; backgraund-image: none;");
    $(".error").text("");
  })

  // Validar si existe ID

  function validarExitencia() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: '',
        dataType: "json",
        data: { validarE: "existe", id },
        success(data) {
          if (data.resultado === "Error de empleado") {
            mostrar.destroy();
            rellenar();
            $('.cerrar').click();
            Toast.fire({ icon: 'error', title: 'Error de tipo de empleado', showCloseButton: true }) // ALERTA 
            reject(false);
          } else {
            resolve(true);
          }
        }
      })
    })
  }

  let id

  $(document).on('click', '.editar', function () {
    id = this.id;

    $.ajax({
      type: 'POST',
      url: '',
      dataType: 'json',
      data: { mostrarEdit: 'editar', id },
      success(data) {
        $('#tipoEmpladoEdit').val(data[0].nombre_e);
      }

    })
  })

  $('#tipoEmpladoEdit').keyup(() => {
    valid = validarStringLong($("#tipoEmpladoEdit"), $("#error2"), "Error de tipo empleado");
    if (valid) validarTipoEmpleado($("#tipoEmpladoEdit"), $("#error2"), id);
  })

  let tipoEmpleadoEdit;

  $('#registrarEdit').click((e) => {
    e.preventDefault();

    if (click >= 1) throw new Error('Spam de clicks');

    validarExitencia().then(() => {

      tipoEmpleadoEdit = validarStringLong($("#tipoEmpladoEdit"), $("#error2"), "Error de tipo empleado");

      if (tipoEmpleadoEdit) {

        $.ajax({
          type: "POST",
          url: "",
          dataType: "json",
          data: { tipoEmpleadoEdit: $("#tipoEmpladoEdit").val(), id },
          success(data) {
            if (data.resultado == 'Editado') {
              mostrar.destroy();
              $("#closeEdit").click();
              Toast.fire({ icon: 'success', title: 'Tipo de empleado editado', showCloseButton: true });
              rellenar();
            } else if (data.resultado === 'error') {
              $("#error2").text(data.msg);
              $("#tipoEmpladoEdit").attr("style", "border-color: red;")
              $("#tipoEmpladoEdit").attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
            }
          }
        })

      }

    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;

  })


  $(document).on('click', '.borrar', function () {
    id = this.id;
  })

  // Borrar Empleado

  $('#borrar').click((e) => {
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
          if (data.resultado === 'Eliminado') {
            $("#closeModal").click();
            mostrar.destroy();
            Toast.fire({ icon: 'success', title: 'Tipo de empleado eliminado', showCloseButton: true })
            rellenar();
          }
        }
      })
    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;
  })


})
$(document).ready(function () {

  let mostrar;
  let permiso, editarPermiso, eliminarPermiso, registrarPermiso;

  $.ajax({
    method: 'POST', url: '', dataType: 'json', data: { getPermiso: '' },
    success(permisos) {
      registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
      editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
      eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
    }
  }).then(() => { rellenar(true) });

  function rellenar(bitacora = false) {
    $.ajax({
      type: 'POST',
      url: "",
      dataType: 'json',
      data: { mostrar: 'tipoEmpleado', bitacora },
      success(data) {
        data ? data : '';
        mostrar = $('#tabla').DataTable({
          resposive: true,
          data: data,
          columns: [
            { data: 'nombre_e' },
            {
              data: null,
              render: function (data, type, row) {
                return `
                <button type="button" ${editarPermiso} id="${row.tipo_em}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.tipo_em}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
                `;
              }
            }
          ]
        })

      }
    })
  }

  function validarTipoEmpleado(input, div, id = false) {
    return new Promise((resolve, reject) => {
      $.post('', { tipoEmpleado: input.val(), validarTipoEmpleado: "tipo empleado", id },
        function (data) {
          let mensaje = JSON.parse(data);
          if (mensaje.resultado === "error") {
            div.text(mensaje.msg);
            input.addClass('input-error');
            return reject(false);
          } else {
            div.text(" ");
            return resolve(true);
          }
        })
    })
  }

  let tipoEmpleado;
  let valid;
  let click = 0;
  setInterval(() => { click = 0; }, 2000);

  $('#tipoEmpleado').keyup(() => {
    valid = validarStringLong($("#tipoEmpleado"), $("#error"), "Error de tipo empleado");
    if (valid) validarTipoEmpleado($("#tipoEmpleado"), $("#error"));
  })

  // Registrar Empleado

  $('#registrar').click((e) => {
    e.preventDefault();

    if (registrarPermiso === 'undefined') {
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    if (click >= 1) throw new Error('Spam de clicks');

    tipoEmpleado = validarStringLong($("#tipoEmpleado"), $("#error"), "Error de tipo empleado");

    if (tipoEmpleado) {

      $.ajax({
        type: 'POST',
        url: '',
        dataType: 'json',
        data: {
          tipoEmpleado: $("#tipoEmpleado").val()
        },
        success(data) {
          if (data.resultado === 'registrado correctamente') {
            mostrar.destroy();
            rellenar();
            $('#user').trigger('reset');
            $("#close").click();
            $("#tipoEmpleado").removeClass('input-error');
            $("#error").text("");
            Toast.fire({ icon: 'success', title: 'Tipo empleado registrado', showCloseButton: true });
          } else if (data.resultado === 'error') {
            $("#error").text(data.msg);
            $("#tipoEmpleado").addClass('input-error');
          }
        }
      })
    }

    click++;

  })

  // Cerrar Modal

  $(".cerrar").click(() => {
    $('#user').trigger('reset');
    $("input").attr("style", "borde-color:none; backgraund-image: none;");
    $("input").removeClass('input-error')
    $(".error").text("");
  })

  // Validar si existe ID

  function validarExitencia() {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "POST",
        url: '',
        dataType: "json",
        data: { validarE: "existe", id },
        success(data) {
          if (data.resultado === "Error de empleado") {
            mostrar.destroy();
            rellenar();
            $('.cerrar').click();
            Toast.fire({ icon: 'error', title: 'Error de tipo de empleado', showCloseButton: true }) // ALERTA 
            reject(false);
          } else {
            resolve(true);
          }
        }
      })
    })
  }

  let id

  $(document).on('click', '.editar', function () {
    id = this.id;

    $.ajax({
      type: 'POST',
      url: '',
      dataType: 'json',
      data: { mostrarEdit: 'editar', id },
      success(data) {
        $('#tipoEmpleadoEdit').val(data[0].nombre_e);
      }

    })
  })

  $('#tipoEmpleadoEdit').keyup(() => {
    valid = validarStringLong($("#tipoEmpleadoEdit"), $("#error2"), "Error de tipo empleado");
    if (valid) validarTipoEmpleado($("#tipoEmpleadoEdit"), $("#error2"), id);
  })

  let tipoEmpleadoEdit;

  $('#registrarEdit').click((e) => {
    e.preventDefault();

    if (click >= 1) throw new Error('Spam de clicks');

    validarExitencia().then(() => {

      tipoEmpleadoEdit = validarStringLong($("#tipoEmpleadoEdit"), $("#error2"), "Error de tipo empleado");

      if (tipoEmpleadoEdit) {

        $.ajax({
          type: "POST",
          url: "",
          dataType: "json",
          data: { tipoEmpleadoEdit: $("#tipoEmpleadoEdit").val(), id },
          success(data) {
            if (data.resultado == 'Editado') {
              mostrar.destroy();
              $("#closeEdit").click();
              Toast.fire({ icon: 'success', title: 'Tipo de empleado editado', showCloseButton: true });
              rellenar();
            } else if (data.resultado === 'error') {
              $("#error2").text(data.msg);
              $("#tipoEmpleadoEdit").addClass('input-error');
            }
          }
        })

      }

    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;

  })


  $(document).on('click', '.borrar', function () {
    id = this.id;
  })

  // Borrar Empleado

  $('#borrar').click((e) => {
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
          if (data.resultado === 'Eliminado') {
            $("#closeModal").click();
            mostrar.destroy();
            Toast.fire({ icon: 'success', title: 'Tipo de empleado eliminado', showCloseButton: true })
            rellenar();
          }
        }
      })
    }).catch(() => {
      throw new Error('No exite.');
    })
    click++;
  })


})