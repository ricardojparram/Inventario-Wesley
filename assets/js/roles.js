$(document).ready(function () {

    let permisos;
    $.post("", { getPermisos: '' })
        .fail(e => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al obtener permisos: ' + e);
        })
        .then((data) => {
            permisos = JSON.parse(data);
            rellenar(true)
        });

    let mostrar;
    const rellenar = (bitacora = false) => {
        $.post("", { mostrar: "", bitacora }, function (data) {
            let tabla;
            const permisoAcciones = (!permisos["Modificar acciones"]) ? 'disabled' : '';
            const permisoEditar = (!permisos["Editar"]) ? 'disabled' : '';
            const permisoEliminar = (!permisos["Eliminar"]) ? 'disabled' : '';
            data.forEach(row => {
                tabla += `
                <tr>
                    <td>${row.nombre}</td>
                    <td>${row.totales}</td>
                    <td class="d-flex justify-content-center">
                        <button type="button" ${permisoAcciones} id="${row.id}" title="Asignar permisos"  class="btn btn-dark asignar_permisos mx-2" data-bs-toggle="modal" data-bs-target="#modal_permisos"><i class="bi bi-shield-lock-fill"></i></button>
                        <button type="button" ${permisoEditar} id="${row.id}" title="Editar" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#modal_editar"><i class="bi bi-pencil"></i></button>
                        <button type="button" ${permisoEliminar} id="${row.id}" title="Eliminar" class="btn btn-danger eliminar mx-2" data-bs-toggle="modal" data-bs-target="#modal_borrar"><i class="bi bi-trash3"></i></button>
                    </td>
                </tr>`;
            });
            $('#tabla tbody').html(tabla);
            mostrar = $('#tabla').DataTable({ resposive: true });
        }, 'json').fail((e) => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        })
    }

    const icons = {
        Consultar: '<i class="bi bi-eye-fill"></i>',
        Registrar: '<i class="bi bi-plus-circle-fill"></i>',
        Editar: '<i class="bi bi-pencil-fill"></i>',
        Eliminar: '<i class="bi bi-trash-fill"></i>',
        "Modificar acciones": '<i class="bi bi-pencil-fill"></i>',
        "Modificar acceso": '<i class="bi bi-eye-slash-fill"></i>',
        "Exportar reporte": '<i class="bi bi-file-pdf"></i>',
        "Exportar reporte estadistico": '<i class="bi bi-file-spreadsheet"></i>',
        "Comprobar pago": '<i class="bi bi-journal-check"></i>',
        "Asignar estado": '<i class="bi bi-journal-check"></i>'
    }

    let id

    $(document).on('click', '.asignar_permisos', function () {
        validarPermiso(permisos["Modificar acciones"]);

        id = this.id;
        $(this).prop('disabled', true);
        $.post("", { mostrar_permisos: "", id }, function (data) {
            let tabla = "";
            Object.entries(data).forEach(([modulo_nombre, row]) => {
                let permisos = "";
                Object.entries(row).forEach(([nombre_permiso, permiso]) => {
                    let checked = (permiso.status == "1") ? "checked" : "";
                    let title = (nombre_permiso == "Consultar") ? "Acceso" : nombre_permiso;
                    permisos += `
                    <div title="${title}" class="d-flex px-3 flex-column justify-content-center align-items-center">
                        <label for="${permiso.id_permiso}">${icons[nombre_permiso]}</label>
                        <input class="form-check-input " ${checked} type="checkbox" id="${permiso.id_permiso}">
                    </div>
                    `
                })
                tabla += `
                <tr>
                    <td class="text-center align-middle">${modulo_nombre}</td>
                    <td>
                        <div class="d-flex  justify-content-center align-items-center">
                        ${permisos}
                        </div>
                    </td>
                </tr>
                `
            })
            $('#tabla_permisos').html(tabla);

        }, "json").fail(e => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        }).always(() => {
            $(this).prop('disabled', false);
        });

    });


    $('#enviarPermisos').click(() => {
        validarPermiso(permisos["Modificar acciones"]);
        let datos_permisos = [];
        $('#tabla_permisos td input').each(function (i) {
            let input_permiso = $(this)[0];
            datos_permisos[i] = { id_permiso: input_permiso.id, status: input_permiso.checked }
        })
        $(this).prop('disabled', true);
        $.post("", { datos_permisos, id }, function (data) {
            if (data.respuesta === "ok") {
                Toast.fire({ icon: 'success', title: data.msg });
                $('.cerrar').click();
            } else {
                Toast.fire({ icon: 'error', title: 'Hubo un error al modificar los permisos.' });
                throw new Error('Error al mostrar listado: ' + data.msg);
            }
        }, "json").fail(() => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        }).always(() => {
            $(this).prop('disabled', false);
        });
    })
    $("#rol_nombre").inputmask("nombre");
    $('#registrar').click((e) => {
        e.preventDefault();
        validarPermiso(permisos["Registrar"]);

        let rol = $("#rol_nombre").val();
        vrol = validarNombre($("#rol_nombre"), $("#error1"), "Error de nombre,");
        if (!vrol)
            throw new Error('Error de validacion.');

        $.post('', { registrar: '', rol }, function (data) {
            if (data.resultado !== "ok") {
                Toast.fire({ icon: 'error', title: data.msg });
                throw new Error(data.msg);
            }
            mostrar.destroy();
            rellenar();
            $('#agregarform').trigger('reset');
            $('.cerrar').click();
            Toast.fire({ icon: 'success', title: 'Rol registrado' })

        }, "json")
            .fail((e) => {
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: ' + e);
            })
    })

    $(document).on('click', '.editar', function () {
        validarPermiso(permisos["Editar"]);
        id = this.id;
        $(this).prop('disabled', true);
        $.post("", { select: "", id }, data => {
            $("#rol_nombre_edit").val(data[0].nombre);
        }, "json").fail(e => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        }).always(() => {
            $(this).prop('disabled', false);
        });
    });

    $("#rol_nombre_edit").inputmask("nombre");
    $('#editar').click((e) => {
        e.preventDefault();
        validarPermiso(permisos["Editar"]);

        let nombre = $("#rol_nombre_edit").val();
        vrol = validarNombre($("#rol_nombre_edit"), $("#error2"), "Error de nombre,");
        if (!vrol)
            throw new Error('Error de validacion.');

        $(this).prop('disabled', true);
        $.post('', { editar: '', id, nombre }, function (data) {

            if (data.resultado !== "ok") {
                Toast.fire({ icon: 'error', title: data.msg });
                throw new Error(data.msg);
            }
            mostrar.destroy();
            rellenar();
            $('#agregarform').trigger('reset');
            $('.cerrar').click();
            Toast.fire({ icon: 'success', title: data.msg })

        }, "json").fail((e) => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        }).always(() => {
            $(this).prop('disabled', false);
        });
    })

    $(document).on('click', '.eliminar', function () {
        validarPermiso(permisos["Eliminar"]);
        id = this.id
    });

    $('#borrar').click(() => {
        validarPermiso(permisos["Eliminar"]);
        $(this).prop('disabled', true);
        $.post('', { eliminar: '', id }, data => {
            if (data.resultado != "ok") {
                Toast.fire({ icon: 'error', title: data.msg });
                throw new Error(data.msg);
            }
            mostrar.destroy();
            $('.cerrar').click();
            rellenar();
            Toast.fire({ icon: 'success', title: data.msg })
        }, "json").fail(e => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al mostrar listado: ' + e);
        }).always(() => {
            $(this).prop('disabled', false);
        });
    })

})