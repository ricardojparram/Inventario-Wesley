$(document).ready(function () {
    let mostrar;
    $.getJSON("", { getPermisos: '' })
        .fail(e => {
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
            throw new Error('Error al obtener permisos: ' + e);
        })
        .then((data) => {
            permisos = data;
            rellenar(true)
        });

    function rellenar(bitacora = false) {
        $.getJSON("", { mostrar: "", bitacora }, function (data) {
            const permisoEditar = (!permisos["Editar"]) ? 'disabled' : '';
            const permisoEliminar = (!permisos["Eliminar"]) ? 'disabled' : '';

            let tabla = data.reduce((acc, row) => {
                return (acc += `
          <tr>
            <td>${row.id_recepcion}</th>
            <td scope="col">${row.nombre_sede}</td>
            <td scope="col">${row.fecha || ""}</td>
            <td >
              <span class="d-flex justify-content-center">
                <button type="button" ${permisoEliminar} title="Eliminar" class="btn btn-danger eliminar mx-2" id="${row.id_recepcion}" data-bs-toggle="modal" data-bs-target="#Eliminar"><i class="bi bi-trash3"></i></button>
                <button type="button" title="Detalles" class="btn btn-dark detalle mx-2" id="${row.id_recepcion}" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
              </span>
            </td>
          </tr>`);
            }, "");
            $("#tabla tbody").html(tabla || "");
            mostrar = $("#tabla").DataTable({ resposive: true });
        }).fail((e) => {
            Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
            throw new Error("Error al mostrar listado: " + e);
        });
    }

    let id;

    $(document).on('click', '.eliminar', function () {
        validarPermiso(permisos["Eliminar"]);
        id = this.id
    });

    $('#anular').click(function () {
        $.post("", { eliminar: '', id }, function (res) {
            Toast.fire({ icon: "success", title: res.msg });
            mostrar.destroy();
            $('.cerrar').click();
            rellenar();
        }, "json").fail((e) => {
            Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
            throw new Error(e.responseJSON.msg);
        });
    })

})