$(document).ready(function(){

    let mostrar
	let editarPermiso, eliminarPermiso, registrarPermiso;
	$.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:'a'},
		success(permisos){
            registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
            editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
            eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
        }
	}).then(() => rellenar(true));

   function rellenar(bitacora = false){
	 $.ajax({
      type: "POST",
      url: '',
      dataType: 'json',
      data:{mostrar: 'xd' , bitacora},
      success(data){
      	let tabla;
	    data.forEach(row =>{

		tabla += `
		<tr>
		<td>${row.nombre}</td>
		<td>${row.telefono}</td>
		<td>${row.direccion}</td>
		<td class="d-flex justify-content-center">
		<button type="button" ${editarPermiso} id="${row.id_sede}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
		<button type="button" ${eliminarPermiso} id="${row.id_sede}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
		</td>
		</tr>
		`;
		})
		$('#tbody').html(tabla);
		mostrar = $('#tableMostrar').DataTable({
		resposive : true
		})
      }
    })
}




let click = 0;
setInterval(()=>{ click = 0; }, 2000);


$("#registrar").click((e)=>{

	if(registrarPermiso === 'disabled'){
		Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
		throw new Error('Permiso denegado.');
	}

	e.preventDefault()

	if(click >= 1) throw new Error('Spam de clicks');

	let  vnombre, vtelefono, vdireccion ;
	vnombre = validarDireccion($('#sedeNomb'),$('#error1'), 'Nombre,');
	vtelefono = validarTelefono($('#sedeTele'),$('#error2'), 'Telefono');
	vdireccion = validarDireccion($('#sedeDirec'),$('#error3'), 'Sede de envío,');

	if(!vnombre && !vtelefono && !vdireccion){
		throw new Error('Error en las entradas de los inputs.');
	}


})

let id;
$(document).on('click', '.editar', function() {
	id = this.id; 
	$.ajax({method: "post",url: "",dataType: "json",data: {select: "", id},
		success(data){

			$("#sedeNomb").val(data.nombre);
			$("#sedeTele").val(data.telefono);
			$("#sedeDirec").val(data.direccion);

		}

	})
});

$('#editar').click((e)=>{
	if(editarPermiso === 'disabled'){
		Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
		throw new Error('Permiso denegado.');
	}

	e.preventDefault()

	if(click >= 1) throw new Error('Spam de clicks');

	let vnombre, vtelefono, vdireccion ;
	vnombre = validarDireccion($('#sedeNombEditar'),$('#error1'), 'Nombre,');
	vtelefono = validarTelefono($('#sedeTeleEditar'),$('#error2'), 'Telefono');
	vdireccion = validarDireccion($('#sedeDirecEditar'),$('#error3'), 'Sede de envío,');

	if(!vnombre && !vtelefono && !vdireccion){
		throw new Error('Error en las entradas de los inputs.');
	}
	console.log($("#ubicacionEdit").val())
	$.post('', {validar:'', empresa : $('#empresa_envioEdit').val()},
		function(response){
			data = JSON.parse(response);
			if(data.resultado != true){
				Toast.fire({ icon: 'error', title: data.msg }); 
				throw new Error(data.msg);	
			}
			$.ajax({type: "post",dataType: "json", url: '', 
				data: {
					id,
					ubicacion : $("#ubicacionEdit").val(),
					estado : $("#estado_sedeEdit").val(),
					nombre : $("#nombre_sedeEdit").val(),
					empresa : $("#empresa_envioEdit").val(),
					editar : ''
				},
				success(data){
					if(data.resultado){
						mostrar.destroy(); 
						rellenar(); 
						$('#editarform').trigger('reset'); 
						$('.cerrar').click(); 
						Toast.fire({ icon: 'success', title: data.msg }) 
					}else{
						Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.'}); 
					}
				}

			})
		})
	click++;
})

$(document).on('click', '.borrar', function() {
	id = this.id; 
});

$('#borrar').click(()=>{
	if(eliminarPermiso === 'disabled'){
		Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
		throw new Error('Permiso denegado.');
	}

	if(click >= 1) throw new Error('spaaam');

	$.ajax({type : 'post',dataType: 'json',url : '',data : {eliminar : '', id},
		success(data){
			if(data.resultado){
				mostrar.destroy();
				$('.cerrar').click();
				rellenar();
				Toast.fire({ icon: 'success', title: data.msg })
			}else{
				mostrar.destroy();
				rellenar();
				$('.cerrar').click();
				Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
			}
		}
	})
	click++;
})

})
