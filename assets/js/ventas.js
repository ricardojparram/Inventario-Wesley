$(document).ready(function(){

	let mostrar;
	let permiso , eliminarPermiso, registrarPermiso;

	$.ajax({method: 'POST' , url: '' , dataType: 'json' , data: {getPermiso: '' },
		success(permisos){
			registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
			eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		}
	}).then(()=> { rellenar(true) });

		function rellenar(bitacora = false){
		$.ajax({
			type: 'POST',
			url: "",
			dataType: 'json',
			data: {mostrar : 'ventas', bitacora},
			success(data){
				data ? data : '';
				mostrar = $('#tabla').DataTable({
					resposive: true,
					data: data,
					columns:[
					{ data: 'cedula' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							   <button class="btn btn-success detalleV" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleVenta">Ver Detalles</button>
							`;
						}
					},
					{ data: 'fecha' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							  <button class="btn btn-success detalleTipo" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleTipoPago">Ver Metodos Pago</button>
							`;
						}
					},
					{ data: 'total_divisa' },
					{ data: 'monto_fact' },
					{
						data: null,
						render: function(data, type, row) {
							return `
								<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>						
							`;
						}
					}
					]
				})

			}
		})
	}

	    //  SELECT2 CON BOOTSTRAP-5 
	    $(".select2").select2({
	    	theme: 'bootstrap-5',
	    	dropdownParent: $('#Agregar .modal-body'),
	    	width: '70%' 
	    });

	    $(".tipo-seleccion").click(function() {
		    var seleccion = $(this).text();
		    
		    $(".grupo-opciones optgroup").hide(); // Ocultar todas las opciones
		    
		    if (seleccion === "Paciente") {
		        $(".grupo-opciones optgroup[label='Pacientes']").show(); // Mostrar opciones de Pacientes
		    } else if (seleccion === "Personal") {
		        $(".grupo-opciones optgroup[label='Personal']").show(); // Mostrar opciones de Personal
		    } else {
		        $(".grupo-opciones optgroup").show(); // Mostrar todas las opciones
		    }
		});


	
});