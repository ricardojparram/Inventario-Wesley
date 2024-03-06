$(document).ready(function(){

	let mostrar;
	let permisos , editarPermiso , eliminarPermiso, registrarPermiso;

    $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
      success(data){ permiso = data; }

    }).then(function(){
    	rellenar(true);
    	registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
        $('#agregarModal').attr(registrarPermiso, '');
    })

    function rellenar(bitacora = false){
    	$.ajax({
    		type: 'POST',
    		url: '',
    		dataType: 'json',
    		data: {mostrar: "", bitacora},
    		success(data){
    	      let tabla;
    	      data.forEach(row =>{
    	      	editarPermiso = (permiso.editar != 1)?  'disable' : '';
    	      	eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';
    	      	tabla += `
	    	      	<tr>
	    	      	<td>${row.rif}</td>
	    	      	<td>${row.nombre}</td>
	    	      	<td>${row.contacto}</td>
	    	      	<td class="d-flex justify-content-center">
	    	      	<button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.id_empresa}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
	    	      	<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.id_empresa}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
	    	      	</td>
	    	      	</tr>
    	         	`
    	          })
    	      	$('#tbody').html(tabla);
    	      	mostrar = $('#tableMostrar').DataTable({
    	      		resposive: true
    	      	})
    		}
    	})
    }

    function validarRif(input , div , id = null){
    	return new Promise((resolve , reject)=>{
    		$.post('' ,{rif: input.val(), validarRif: "rif" , id},
    			function(data){
    				let mensaje = JSON.parse(data);
    				if(mensaje.resultado === "Error Datos"){
    					div.text(mensaje.error);
    					input.attr("style","border-color: red;")
    					input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
    					return reject(false);
    				}else{
    					div.text(" ");
    					return resolve(true);
    				}
    			})
    	})
    }

    $('#rif').keyup(()=>{ let valid = validarCedula($('#rif') , $('#error1') , "Error de Rif")
     if(valid){
     	validarRif($('#rif') , $('#error1'));
     }
     })
    $('#nombre').keyup(()=>{ validarStringLong($('#nombre'), $('#error2') , "Nombre invalido"); });
    $('#contacto').keyup(()=>{ validarTelefonoOp($("#contacto"),$("#error3") ,"Error de telefono,") });

     let click = 0;
     setInterval(() => { click = 0 ;}, 2000);

    $('#registrar').click((e)=>{
    	e.preventDefault();
        
         if(click >=  1) throw new Error('Spam de clicks');
  
         let vRif, nombre , vContacto , rif , name , contacto;
         vRif = validarCedula($('#rif') , $('#error1') , "Error de Rif")
         nombre = validarStringLong($('#nombre'), $('#error2') , "Nombre invalido");
         vContacto = validarTelefonoOp($("#contacto"),$("#error3") ,"Error de telefono,");

         validarRif($('#rif') , $('#error')).then((valide)=>{

         	if(vRif && nombre && vContacto && valide){
         		rif = $('#rif').val();
         		name = $('#nombre').val();
         		contacto = $('#contacto').val();
                $.ajax({
                	type: 'POST',
                	url: '',
                	dataType: 'json',
                	data: {rif , name , contacto , registra: 'si'},
                	success(data){
                        if(data.resultado == "Empresa registrado."){
                            mostrar.destroy(); 
                            rellenar(); 
                            $('#agregarform').trigger('reset'); 
                            $('.cerrar').click(); 
                            Toast.fire({ icon: 'success', title: 'Empresa registrada' }) 
                        }
                	}
                })
         	}
         }).catch(()=>{
            $("#error").text("El rif ya está registrado. Ingrese otro. ");
            throw new Error('no exite.');
        })
        click++;
    })

        function validarClick(){
        return new Promise((resolve, reject) =>{
            $.ajax({
                type: "POST",
                url: '',
                dataType: "json",
                data: { validarC: "existe", id},
                success(data) {
                if (data.resultado === "Error de empresa") {  
                    Toast.fire({ icon: 'error', title: 'Esta empresa ya esta anulada' }); // ALERTA 
                    mostrar.destroy();
                    rellenar();
                    $('.cerrar').click();
                    reject(false);
                }else{
                    resolve(true);
                }

            }
        })
      })
    }


    let id;

    $(document).on("click", '.editar', function(){
        id = this.id;
        $.ajax({
            method: "post",
            url: "",
            dataType: "json",
            data: {clickEdit: "editar Empresa", id},
            success(data){
               $("#rifEdit").val(data[0].rif);
               $("#nombreEdit").val(data[0].nombre);
               $("#contactoEdit").val(data[0].contacto);
            }
        })
    })

    $('#rifEdit').keyup(()=>{ let valid = validarCedula($('#rifEdit') , $('#error4') , "Error de Rif")
         if(valid){
            validarRif($('#rifEdit') , $('#error4'), id);
        }
    })
    $('#nombreEdit').keyup(()=>{ validarStringLong($('#nombreEdit'), $('#error5') , "Nombre invalido"); });
    $('#contactoEdit').keyup(()=>{ validarTelefonoOp($("#contactoEdit"),$("#error6") ,"Error de telefono,") });

    $('#editar').click((e)=>{
        e.preventDefault();

        if(click >=  1) throw new Error('Spam de clicks');

        let vRif, nombre , vContacto , rifEdit , nameEdit , contactoEdit;

        validarClick().then(()=>{

         vRif = validarCedula($('#rifEdit') , $('#error4') , "Error de Rif")
         nombre = validarStringLong($('#nombreEdit'), $('#error5') , "Nombre invalido");
         vContacto = validarTelefonoOp($("#contactoEdit"),$("#error6") ,"Error de telefono,");

         validarRif($('#rifEdit') , $('#error4'), id).then((valide)=>{

            rifEdit = $('#rifEdit').val();
            nameEdit = $('#nombreEdit').val();
            contactoEdit = $('#contactoEdit').val();

            $.ajax({
                type: 'POST',
                url: '',
                dataType: 'json',
                data: {rifEdit , nameEdit, contactoEdit , id , editar: 'editar'},
                success(data){
                    if(data.resultado === "Empresa editado."){
                        mostrar.destroy();
                        rellenar(); 
                        $('#editarform').trigger('reset'); 
                        $('.cerrar').click(); 
                        Toast.fire({ icon: 'success', title: 'Empresa Editada' }) 
                    }
                }
            })

         }).catch(()=>{
            $("#errorEdit").text("El rif ya está registrado. Ingrese otro. ");
            throw new Error('si exite.');
         })

        }).catch(() =>{
         throw new Error('No existe.');
     })

        click++;

    })

    $('.cerrar').click(()=>{
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('select').attr("style","borden-color:none;","borden-color:none;");
     $('input').attr("style","borden-color:none;","borden-color:none;");
     $('.error').text(" ");
    })

    $(document).on('click', '.borrar', function(){
        id = this.id;
    })

    $('#borrar').click((e)=>{

        e.preventDefault();

        if(click >=  1) throw new Error('Spam de clicks');
        console.log(id)
        validarClick().then(()=>{
         $.ajax({
             type: 'POST',
             url: '',
             dataType: 'json',
             data: {ElimnarEmpresa: 'eliminar', id},
             success(data){
                if (data.resultado === "Empresa eliminada.") {
                mostrar.destroy();
                rellenar();
                $('.cerrar').click();
                Toast.fire({ icon: 'success', title: 'Banco eliminada' }); // ALERTA 
                }
             }
         })

     }).catch(()=>{
       throw new Error('No exite.');
   })

 })

})