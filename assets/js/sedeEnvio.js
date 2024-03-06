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
            type: "post",
            url: "",
            dataType: "json",
            data: {mostrar: "", bitacora},
            success(data){
                let tabla;
                data.forEach(row => {
                    tabla += `
                        <tr>
                            <td>${row.empresa}</td>
                            <td>${row.sede}</td>
                            <td>${row.ubicacion}</td>
                            <td>${row.estado}</td>
                            <td>
                                <span class="d-flex justify-content-center">
                                <button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.id_sede}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                                <button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.id_sede}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
                                </span>
                            </td>
                        </tr>`;
                });
                $('#tbody').html(tabla);
                mostrar = $('#tableMostrar').DataTable({
                    resposive: true
                });
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

    	let vselectEmpresa, vdireccion, vnombre, vselectEstado;
    	vselectEmpresa = validarSelect($('#empresa_envio'), $('#error1'), 'Empresa de envío,');
        vselectEstado = validarSelect($('#estado_sede'), $('#error2'), 'Estado,');
    	vnombre = validarDireccion($('#nombre_sede'),$('#error3'), 'Nombre,');
        vdireccion = validarDireccion($('#ubicacion'),$('#error4'), 'Sede de envío,');

    	if(!vselectEmpresa || !vselectEstado || !vnombre || !vdireccion){
    		throw new Error('Error en las entradas de los inputs.');
    	}

    	$.post('', {validar:'', empresa : $('#empresa_envio').val()},
    		function(response){
    			data = JSON.parse(response);
    			if(data.resultado != true){
    				Toast.fire({ icon: 'error', title: data.msg }); 
    				throw new Error(data.msg);	
    			}
    			$.ajax({type: "post",dataType: "json",url: '',
    				data: {
                        ubicacion : $("#ubicacion").val(),
    					estado : $("#estado_sede").val(),
                        nombre : $("#nombre_sede").val(),
    					empresa : $("#empresa_envio").val(),
    					registrar : ''
    				},
    				success(data){
    					if(data.resultado){
    						mostrar.destroy(); 
    						rellenar(); 
    						$('#agregarform').trigger('reset'); 
    						$('.cerrar').click(); 
    						Toast.fire({ icon: 'success', title: data.msg }) 
    					}else{
    						Toast.fire({ icon: 'error', title: data.msg }); 
    					}
    				}

    			})
    		})

    	click++;
    })

    let id;
    $(document).on('click', '.editar', function() {
    	id = this.id; 
    	$.ajax({method: "post",url: "",dataType: "json",data: {select: "", id},
            success(data){

                $('#empresa_envioEdit').val(data.id_empresa);
                $('#ubicacionEdit').val(data.ubicacion);
                $("#estado_sedeEdit").val(data.id_estado);
                $("#nombre_sedeEdit").val(data.nombre);

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

    	let vselectEmpresa, vdireccion, vnombre, vselectEstado;
        vselectEmpresa = validarSelect($('#empresa_envioEdit'), $('#error1'), 'Empresa de envío,');
        vselectEstado = validarSelect($('#estado_sedeEdit'), $('#error2'), 'Estado,');
        vnombre = validarNombre($('#nombre_sedeEdit'),$('#error3'), 'Nombre,');
        vdireccion = validarDireccion($('#ubicacionEdit'),$('#error4'), 'Sede de envío,');

    	if(!vselectEmpresa || !vselectEstado || !vnombre || !vdireccion){
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
