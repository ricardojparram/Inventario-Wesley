$(document).ready(function () {
    let mostrar;
    const status = {
        0: "Por confirmar",
        1: "Confirmado",
        2: "Rechazado",
    }
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
            // const permisoConfirmar = !permisos["Eliminar"] ? "disabled" : "";
            let tabla = data.reduce((acc, row) => {
                return (acc += `
            <tr>
                <td>${row.num_fact}</td>
                <td scope="col">${row.cedula}</td>
                <td scope="col">${row.nombre}</td>
                <td scope="col">${row.fecha || ""}</td>
                <td scope="col">${row.monto_fact}</td>
                <td scope="col">${status[row.status_pago]}</td>
                <td >
                <span class="d-flex justify-content-center">
                    <button type="button" title="Confirmar pago" class="btn btn-dark detalle mx-2" id="${row.id_pago}" data-bs-toggle="modal" data-bs-target="#ConfirmarPago"><i class="bi bi-journal-text"></i></button>
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

    $(document).on("click", ".detalle", function () {
        id = this.id;
        $.getJSON("", { detalle: "", id_pago: id }, (res) => {
            let tabla = "";

            $("#ConfirmarPago .pago_titulo").html(`Factura: ${res[0].num_fact}`);
            $("#ConfirmarPago .pago_status").html(`Estado: ${status[res[0].status_pago]}`);
            $("#monto_divisa").html(res[0].total_divisa)
            $("#monto_bs").html(res[0].monto_fact)
            res.forEach((row) => {
                tabla += `
              <tr>
                <td>${row.tipo_pago}</th>
                <td>${row.referencia}</th>
                <td>${row.monto_pago}</td>
              </tr>`;
            });
            $("#tabla_detalle tbody").html(tabla || "");
        }).fail((e) => {
            Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
            throw new Error("Error al mostrar detalles: " + e);
        });
    });

    $(document).on("click", ".respuesta", function () {
        const status = this.attributes.status.value;
        $.post("", { status, id_pago: id }, function (res) {
            Toast.fire({ icon: "success", title: res.msg });
            mostrar.destroy();
            $('.cerrar').click();
            rellenar();
        }, 'json').fail((e) => {
            Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
            throw new Error("Error al mostrar detalles: " + e);
        });
    })

})