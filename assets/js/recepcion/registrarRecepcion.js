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
            // const permisoEliminar = (!permisos["Eliminar"]) ? 'disabled' : '';

            let tabla = data.reduce((acc, row) => {
                return (acc += `
          <tr>
            <td>${row.id_transferencia}</th>
            <td scope="col">${row.nombre_sede}</td>
            <td scope="col">${row.fecha || ""}</td>
            <td >
              <span class="d-flex justify-content-center">
                <button type="button" title="Registrar recepcion" class="btn btn-success registrar mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Registrar"><i class="bi bi-clipboard2-plus-fill"></i></button>
                <button type="button" title="Detalles" class="btn btn-dark detalle mx-2" id="${row.id_transferencia}" data-bs-toggle="modal" data-bs-target="#Detalle"><i class="bi bi-journal-text"></i></button>
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

    let id = "";
    $(document).on('click', ".registrar", function () {
        id = this.id;
        $.getJSON("", { datosTransferencia: '', id }, function (res) {
            $('#sede').val(res.transferencia.id_sede);
            $("#fecha").val(res.transferencia.fecha);
            const filas = res.productos.reduce((acc, row) => {
                return acc += `
                <tr>
                    <td width='30%' class="position-relative">
                        <select disabled class="select-productos select-asd" name="producto">
                            <option selected value="${row.id_producto_sede}">${row.lote}</option>
                        </select>
                    </td>
                    <td class="cantidad position-relative">
                        <input class="select-asd" type="number" value="${row.cantidad}" />
                        <span class="d-none floating-error">error</span>
                    </td>
                </tr>`;
            }, "");
            $('#tablaProductos').html(filas);
        }).fail(e => {
            Toast.fire({ icon: 'error', title: e.responseJSON.msg || 'Ha ocurrido un error.' });
            throw new Error(e.responseJSON.msg);
        })
    })

    const getProductos = () => {
        return Object.values(document.querySelectorAll('.select-productos')).map(item => {
            let cantidad = $(item).closest('tr').find('.cantidad input').val();
            return { id_producto: item.value, cantidad };
        });
    }
    let valid_sede, valid_fecha;
    $('#sede').change(() => valid_sede = validarNumero($('#sede'), $('#error1'), "Error de sede,"))
    // $('#fecha').change(() => valid_fecha = validarFecha($('#fecha'), $('#error2'), "Error de fecha,"))

    $('#registrar').click(function (e) {
        e.preventDefault();

        valid_fecha = true // validarFecha($('#fecha'), $('#error2'), "Error de fecha,");
        let valid_productos = validarProductosRepetidos(false);
        let valid_cantidad = validarInventario();

        if (!valid_fecha || !valid_productos || !valid_cantidad) return;

        productos = getProductos();
        let data = {
            registrar: '',
            transferencia: id,
            fecha: $("#fecha").val(),
            productos,
        };

        $.post("", data, function (res) {
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