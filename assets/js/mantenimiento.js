$(document).ready(function(){

    let permisos, editarPermiso, eliminarPermiso, registrarPermiso;
	$.ajax({
		method: 'POST', url: "", dataType: 'json', data: { getPermisos: 'a' },
		success(data) { permisos = data; }
	}).then(function () {
		mostrar(true);
		exportarPermiso = (typeof permisos.Accion1 === 'undefined') ? 'disabled' : '';
		// editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
		// eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		$('#agregarMoneda, #agregarCambio').attr(exportarPermiso, '');
	});

    let tabla;
    rellenar();
    function rellenar(){
        $.ajax({
            method: "post",
            url: '',
            dataType: 'JSON',
            data: {mostrar: "xd"},
            success(list){
                list.forEach(row => {
                    tabla+=`
                    <tr>
                        <td>${row.nombre}</td>
                        <td>${row.descripcion}</td>
                        <td>${row.fecha}</td>
                    </tr>
                    `;
                });
                $('#tabla tbody').html(tabla);
                tabla = $("#tabla").DataTable({
                    responsive: true,
                    "order": [[ 2, "desc" ]]
                });
            }
        })
    }


    $('#exportar').click((e)=> {
        e.preventDefault()
        $("#exportar").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: '',
            dataType: 'json',
            data: {
                exportar: 'xd'
            },
            success(data) {
                tabla.destroy();
                $('#cerrar').click();
                rellenar();
                Toast.fire({ icon: 'success', title: 'Copia de Seguridad Creada', showCloseButton: true  });
            },
            error(e) {
                Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
                console.error(e.responseJSON.msg);
            },
            complete() {
                $("#exportar").prop('disabled', false);
            }
        })
    })
})