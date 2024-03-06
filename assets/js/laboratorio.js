$(document).ready(function(){

	let mostrar
	let registrarPermiso, editarPermiso, eliminarPermiso;
    $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:''},
        success(permisos){
        	registrarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : ''; 
        	editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
        	eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
        }
    }).then(() => rellenar(true));

	function rellenar(bitacora = false){ 
        $.ajax({ type: "post", url: "", dataType: "json", data: {mostrar: "labs", bitacora},
            success(data){
                let tabla;
               
                data.forEach(row => {
                    tabla += `
                        <tr>
                            <td>${row.rif}</th>
                            <td scope="col">${row.razon_social}</td>
                            <td scope="col">${row.direccion}</td>                      
                            <td scope="col">${row.telefono}</td>
                            <td scope="col">${(row.contacto == null) ? '' : row.contacto}</td>
                            <td >
                            	<span class="d-flex justify-content-center">
                            		<button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.cod_lab}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
                            		<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.cod_lab}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
                            	</span>
                            </td>
                        </tr>`;
                });
                $('#tableMostrar tbody').html(tabla);
                mostrar = $('#tableMostrar').DataTable({
                    resposive: true
                });
            },
            error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
        })

    }

	function validarRif(input, div, edit = false){
		$.post('',{rif : input.val(), validar: "rif", edit}, function(data){
			let mensaje = JSON.parse(data);
			if(mensaje.resultado === "error"){
				div.text(mensaje.msg);
				input.attr("style","border-color: red;")
				input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
			}
		})
	}

	$("#rif").keyup(()=> {  let valid = validarCedula($("#rif"),$("#error") ,"Error de RIF,");
		if(valid) validarRif($("#rif"), $("#error"));
	});
	$("#razon").keyup(()=> {  validarNombre($("#razon"),$("#error") , "Error de nombre,") });
	$("#direccion").keyup(()=> {  validarDireccion($("#direccion"),$("#error") , "Error de direccion,") });
	$("#telefono").keyup(()=> {  validarTelefono($("#telefono"),$("#error") ,"Error de telefono,") });

	let click = 0;
	setInterval(()=>{ click = 0; }, 2000);

	$("#registrar").click((e)=>{
		e.preventDefault()

		if(registrarPermiso === 'undefined'){
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}

		if(click >= 1) throw new Error('Spam de clicks');

		let vrif, vnombre, vdireccion, vtelefono;
		validarCedula($("#rif"),$("#error") ,"Error de RIF,");
		vnombre = validarNombre($("#razon"),$("#error") , "Error de nombre,");
		vdireccion = validarDireccion($("#direccion"),$("#error") , "Error de direccion,");
		vtelefono = validarTelefono($("#telefono"),$("#error") ,"Error de telefono,");

		if(!vnombre || !vdireccion || !vtelefono){
			throw new Error('Error.');
		}

		$.ajax({ type: "post", url: '', dataType : "json",
			data: {
				rif : $("#rif").val(),
				razon : $("#razon").val(),
				direccion : $("#direccion").val(),
				telefono : $("#telefono").val(),
				contacto : $("#contacto").val()
			},
			success(data){

				if(data.resultado === "error"){
					$("#error").text(data.msg);
					$("#rif").attr("style","border-color: red;")
					$("#rif").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
					throw new Error('Rif inválido');
				}

				if(data.resultado != "ok"){
					Toast.fire({ icon: 'error', title: data.msg });
					throw new Error(data.msg);
				}

				mostrar.destroy(); 
				rellenar(); 
				$('#agregarform').trigger('reset'); 
				$('.cerrar').click(); 
				Toast.fire({ icon: 'success', title: 'Laboratorio registrado' }) 
			},
			error(e){
				Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
				throw new Error('Error al mostrar listado: '+e);
			}

		})
		click++;
	})

	let id 

    $(document).on('click', '.editar', function() {
        id = this.id; 
       		$.ajax({ method: "post",url: "", dataType: "json", data: {select: "labs", id},
		        success(data){
		        	$("#rifEdit").val(data[0].rif);
		        	$("#razonEdit").val(data[0].razon_social);
		        	$("#direccionEdit").val(data[0].direccion);
		        	$("#telefonoEdit").val(data[0].telefono);
		        	$("#contactoEdit").val(data[0].contacto);
		        },
		        error(e){
		        	Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
		        	throw new Error('Error al mostrar listado: '+e);
		        }

		    })

	});


	$("#rifEdit").keyup(()=> {  let valid = validarCedula($("#rifEdit"),$("#errorEdit") ,"Error de RIF,") 
		if(valid)	validarRif($("#rifEdit"), $("#errorEdit"), id);
	});
	$("#razonEdit").keyup(()=> {  validarNombre($("#razonEdit"),$("#errorEdit") , "Error de nombre,") });
	$("#direccionEdit").keyup(()=> {  validarDireccion($("#direccionEdit"),$("#errorEdit") , "Error de direccion,") });
	$("#telefonoEdit").keyup(()=> {  validarTelefono($("#telefonoEdit"),$("#errorEdit") ,"Error de telefono,") });


	$("#editar").click((e)=>{
		e.preventDefault();

		if(editarPermiso === 'undefined'){
            Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
            throw new Error('Permiso denegado.');
        }

		if(click >= 1) throw new Error('spaaam');

		let vrif, vnombre, vdireccion, vtelefono;
		validarCedula($("#rifEdit"),$("#errorEdit") ,"Error de RIF,");
		vnombre =  validarNombre($("#razonEdit"),$("#errorEdit") , "Error de nombre,");
		vdireccion = validarDireccion($("#direccionEdit"),$("#errorEdit") , "Error de direccion,");
		vtelefono = validarTelefono($("#telefonoEdit"),$("#errorEdit") ,"Error de telefono,");

		$.ajax({ type: "post", url: '', dataType: "json",
			data: {
				rifEdit : $("#rifEdit").val(),
				razonEdit : $("#razonEdit").val(),
				direccionEdit : $("#direccionEdit").val(),
				telefonoEdit : $("#telefonoEdit").val(),
				contactoEdit : $("#contactoEdit").val(),
				id
			},
			success(data){
				if(data.resultado === "error"){
					$("#errorEdit").text(data.msg);
					$("#rifEdit").attr("style","border-color: red;")
					$("#rifEdit").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
					throw new Error('Rif ya registrado.');
				}

				if(data.resultado != "ok"){
					Toast.fire({ icon: 'error', title: data.msg });
					throw new Error(data.msg);
				}

				mostrar.destroy();
				rellenar(); 
				$('#editarform').trigger('reset');
				$('.cerrar').click();
				Toast.fire({ icon: 'success', title: 'Laboratorio modificado' })

			},
			error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }

		})
		click++

	})

	$(document).on('click', '.cerrar', function() {
		$('#agregarform').trigger('reset'); 
		$('#editarform').trigger('reset');
		$('#agregarform input').attr('style', 'border-color: none; background-image: none;');
		$('#editarform input').attr('style', 'border-color: none; background-image: none;');
		$('#error').text('');
		$('#errorEdit').text('');
	});

	$(document).on('click', '.borrar', function(){ id = this.id });

	$('#borrar').click(()=>{
		if(eliminarPermiso === 'undefined'){
            Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
            throw new Error('Permiso denegado.');
        }

		if(click >= 1) throw new Error('spaaam');
		$.ajax({ type : 'post', url : '', dataType: "json", data : {eliminar : 'asd', id},
			success(data){
				if(data.resultado != "ok"){
					Toast.fire({ icon: 'error', title: data.msg });
					throw new Error(data.msg);
				}
				mostrar.destroy();
				$('.cerrar').click();
				rellenar();
				Toast.fire({ icon: 'success', title: 'Laboratorio eliminado' })
			},
			error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
		})
		click++;
	})

})

