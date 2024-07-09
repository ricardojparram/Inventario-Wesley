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
                            <td>${row.rif_int}</th>
                            <td scope="col">${row.razon_social}</td>
                            <td scope="col">${row.direccion}</td>                      
                            <td scope="col">${row.contacto}</td>
                            <td >
                                <span class="d-flex justify-content-center">
                                    <button type="button" ${permisoEditar} class="btn btn-registrar editar mx-2" id="${row.rif_int}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                                    <button type="button" ${permisoEliminar} class="btn btn-danger borrar mx-2" id="${row.rif_int}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
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
                div.text(e.responseJSON.msg);
                input.addClass('input-error');
            })
    }

    $("#rif").inputmask("rif");
    $("#rif").keyup((e) => {
        if (e.which === 13) return clearTimeout(timeout);
        let valid = validarRif($("#rif"), $("#errorRif"), "Error de RIF,");
        clearTimeout(timeout)
        timeout = setTimeout(function () {
            if (valid) validarRifBD($("#rif"), $("#errorRif"));
        }, 700)
    });
    $("#razon").inputmask("nombre");
    $("#razon").keyup(() => {
        validarNombre($("#razon"), $("#errorRazon"), "Error de Razon Social,");
    });
    $("#direccion").keyup(() => {
        validarDireccion($("#direccion"), $("#errorDirec"), "Error de direccion,");
    });
    $("#contacto").keyup(() => {
        validarTelefono($("#contacto"), $("#errorContac"), "Error de Contacto,");
    });
    let click = 0;
    let timeout, cedulaId
    setInterval(() => { click = 0; }, 1500);


    $("#agregarform").submit((e) => {
        if (click >= 1) throw new Error('Spam de clicks');
        e.preventDefault();
        if (typeof permisos.Registrar === 'undefined') {
            Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
            throw new Error('Permiso denegado.');
        }
        let vrif = validarRif($("#rif"), $("#errorRif"), "Error de RIF,");
        let vnombre = validarNombre($("#razon"), $("#errorRazon"), "Error de Razon Social,");
        let vdireccion = validarDireccion($("#direccion"), $("#errorDirec"), "Error de direccion,");
        let vcontacto = validarTelefono($("#contacto"), $("#errorContac"), "Error de Contacto,");

        if (!vnombre || !vdireccion || !vrif, !vcontacto) throw new Error("Error.");


        validarRifBD($("#rif"), $("#errorRif"))
        const body = {
            rif: $("#rif").val(),
            razon: $("#razon").val(),
            direccion: $("#direccion").val(),
            contacto: $("#contacto").val()
        };
        $("#registrar").prop('disabled', true);
        $.post("", body, (data) => {
            if (data.resultado != "ok") {
                Toast.fire({ icon: "error", title: data.msg });
                throw new Error(data.msg);
            }
            mostrar.destroy();
            rellenar();
            $("#cerraR").click();
            Toast.fire({ icon: "success", title: data.msg });
        }, "json",).fail((m) => {
            Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
            console.error("Error: " + m);
        }).always(() => {
            $("#registrar").prop('disabled', false);
        });
        click++
    })


    $(document).on('click', '.editar', function () {
        cedulaId = this.id;
        $.ajax({
            method: "post",
            url: '',
            dataType: "json",
            data: { select: "xd", cedulaId },
            success(data) {
                $("#rifEdit").val(data[0].rif_int),
                    $("#razonEdit").val(data[0].razon_social),
                    $("#direccionEdit").val(data[0].direccion),
                    $("#contactoEdit").val(data[0].contacto)

            }
        })
    });

    $("#rifEdit").inputmask("rif");
    $("#rifEdit").keyup((e) => {
        if (e.which === 13) return clearTimeout(timeout);
        let valid = validarRif($("#rifEdit"), $("#errorRifEdit"), "Error de RIF,");
        clearTimeout(timeout)
        timeout = setTimeout(function () {
            if (valid) validarRifBD($("#rifEdit"), $("#errorRifEdit"), cedulaId);
        }, 700)
    });
    $("#razonEdit").inputmask("nombre");
    $("#razonEdit").keyup(() => {
        validarNombre($("#razonEdit"), $("#errorRazonEdit"), "Error de Razon Social,");
    });
    $("#direccionEdit").keyup(() => {
        validarDireccion($("#direccionEdit"), $("#errorDirecEdit"), "Error de direccion,");
    });
    $("#contactoEdit").keyup(() => {
        validarTelefono($("#contactoEdit"), $("#errorContacEdit"), "Error de Contacto,");
    });

    $("#editarform").submit((e) => {
        e.preventDefault();
        if (click >= 1) throw new Error("spaaam");

        let vrif = validarRif($("#rifEdit"), $("#errorRifEdit"), "Error de RIF,");
        let vnombre = validarNombre($("#razonEdit"), $("#errorRazonEdit"), "Error de Razon Social,");
        let vdireccion = validarDireccion($("#direccionEdit"), $("#errorDirecEdit"), "Error de direccion,");
        let vcontacto = validarTelefono($("#contactoEdit"), $("#errorContacEdit"), "Error de Contacto,");

        if (!vnombre || !vdireccion || !vrif, !vcontacto) throw new Error("Error.");


        const body = {
            rifEdit: $("#rifEdit").val(),
            razonEdit: $("#razonEdit").val(),
            direccionEdit: $("#direccionEdit").val(),
            contactoEdit: $("#contactoEdit").val(),
            cedulaId,
        };
        $("#editar").prop('disabled', true);
        $.post("", body, (data) => {
            if (data.resultado != "ok") {
                Toast.fire({ icon: "error", title: data.msg });
                throw new Error(data.msg);
            }

            mostrar.destroy();
            rellenar();
            $("#cerrarE").click();
            Toast.fire({ icon: "success", title: data.msg });
        }, "json",).always(() => {
            $("#editar").prop('disabled', false);
        }).fail((e) => {
            Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
            console.error(e.responseJSON.msg);
        });
        click++
    });

    $(document).on("click", ".borrar", function () {
        cedulaId = this.id;
    });

    $("#borrar").click(() => {
        validarPermiso(permisos["Eliminar"]);
        if (click >= 1) throw new Error("spaaam");

        $("#borrar").prop('disabled', true);

        $.post("", { eliminar: "", cedulaId }, (data) => {
            if (data.resultado !== "ok") {
                Toast.fire({ icon: "error", title: data.msg });
                throw new Error(data.msg);
            }
            console.log(data);
            mostrar.destroy();
            $("#cerrarB").click();
            rellenar();
            Toast.fire({ icon: "success", title: data.msg });
        }, "json").always(() => {
            $("#borrar").prop('disabled', false);
        }).fail((e) => {
            Toast.fire({ icon: "error", title: e.responseJSON.msg });
            throw new Error(e.responseJSON.msg);
        });
        click++
    });

    // Vacio de Modales
    $(document).on('click', '#cerrarE', function () {
        $('#editarform p').text(" ");
        $("#editarform input").removeClass('input-error')
    })
    $(document).on('click', '#cerraR', function () {
        $('#agregarform p').text(" ");
        $("#agregarform input").removeClass('input-error')
    })
    $(document).on('click', '#cerrarB', function () {
        $("#deleteModal p").text(" ");
    })






})