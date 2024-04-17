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
		<button type="button" ${editarPermiso} id="${row.id_sede}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
		<button type="button" ${eliminarPermiso} id="${row.id_sede}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi bi-trash3"></i></button>
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

$("#sedeNomb").keyup(() =>{ validarNombre($("#sedeNomb"),$("#error1"), "Error de Nombre de sede")});
$("#sedeTele").keyup(() =>{ validarTelefono($("#sedeTele"),$("#error2"), "Error de Telefono de sede")});
$("#sedeDirec").keyup(() =>{ validarDireccion($("#sedeDirec"),$("#error3"), "Error de direccion de sede")});



$("#registrar").click((e)=>{

	if(registrarPermiso === 'disabled'){
		Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
		throw new Error('Permiso denegado.');
	}

	e.preventDefault()

	if(click >= 1) throw new Error('Spam de clicks');

	let  vnombre, vtelefono, vdireccion ;
	vnombre = validarNombre($('#sedeNomb'),$('#error1'), 'Error de Nombre de sede');
	vtelefono = validarTelefono($('#sedeTele'),$('#error2'), 'Error de Telefono de sede');
	vdireccion = validarDireccion($('#sedeDirec'),$('#error3'), 'Error de direccion de sede');

	if(!vnombre || !vtelefono || ! vdireccion){
		throw new Error('Error en las entradas de los inputs.');
	}
	
	$.ajax({type: "post",dataType: "json", url: '', 
	data: {
		nombre : $("#sedeNomb").val(),
		telefono : $("#sedeTele"). val(),
		direccion : $("#sedeDirec").val(),
   		registrar : ''
	},
	success(data){
		if(data.resultado){
			mostrar.destroy(); 
			rellenar(); 
			$('#registrar').trigger('reset'); 
			$('.cerrar').click(); 
			Toast.fire({ icon: 'success', title:'Registrado con exito.' }) 
		}else{
			Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.'}); 
		}
	}

})

})

let id;
$(document).on('click', '.editar', function() {
	id = this.id; 
	$.ajax({method: "post",url: "",dataType: "json",data: {select: "", id },
		success(data){
			$("#sedeNombEditar").val(data[0].nombre);
			$("#sedeTeleEditar").val(data[0].telefono);
			$("#ubicacionEdit").val(data[0].direccion);

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

	vnombre = validarNombre($('#sedeNombEditar'),$('#error1'), 'Nombre,');
	vtelefono = validarTelefono($('#sedeTeleEditar'),$('#error2'), 'Telefono');
	vdireccion = validarDireccion($('#ubicacionEdit'),$('#error3'), 'Sede de envío,');

	if(!vnombre || !vtelefono || !vdireccion){
		throw new Error('Error en las entradas de los inputs.');
	}

			$.ajax({type: "post",dataType: "json", url: '', 
				data: {
					id,
					editar : '',
					nombre : $("#sedeNombEditar").val(),
					telefono : $("#sedeTeleEditar").val(),
					direccion: $("#ubicacionEdit").val()
					
				},
				success(data){
					if(data.resultado){
						mostrar.destroy(); 
						rellenar(); 
						$('#editarform').trigger('reset'); 
						$('.cerrar').click(); 
						Toast.fire({ icon: 'success', title: 'Se ha editado con exito.', showCloseButton: true  }) 
					}else{
						Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' , showCloseButton: true }); 
					}
				}

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
				Toast.fire({ icon: 'success', title:'Sede eliminado con exito.' })
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
