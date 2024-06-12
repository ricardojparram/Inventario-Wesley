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


    $('#exportar').click((e)=> {
        e.preventDefault()

        $.ajax({
            type: "POST",
            url: '',
            dataType: 'json',
            data: {
                exportar: 'xd'
            },
            success(data) {
                tablaM.destroy();
                $('#cerrar').click();
                mostrar();
                Toast.fire({ icon: 'success', title: 'Copia de Seguridad Creada', showCloseButton: true  });
            },
            error(e) {
                Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
                throw new Error(e.responseJSON.msg);
            },
            // complete() {
            //     $("#eliminar").prop('disabled', false);
            // }
        })
    })
})