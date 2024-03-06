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
			type: "POST",
			url: "",
			dataType: "json",
			data: {mostrar: "Bank" , bitacora},
			success(data){
				let tabla;
				data.forEach(row =>{
                    editarPermiso = (permiso.editar != 1)?  'disable' : '';
                    eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';
                    tipo = (row.telefono == null)? 'Tranferencia' : 'Pago Movil';
					tabla += `
					<tr>
					<td>${row.nombre}</td>
					<td>${row.rif_cedula}</td>
					<td>${tipo}</td>
					<td class="d-flex justify-content-center">
					<button type="button" ${editarPermiso} id="${row.id_datos_cobro}" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
					<button type="button" ${eliminarPermiso} id="${row.id_datos_cobro}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi bi-trash3"></i></button>
					</td>
					</tr>
					`;
				})
				$('#tbody').html(tabla);
				mostrar = $('#tabla').DataTable({
					responsive: true
				})


			}
		})
	}
    
    $(".nombreBanco").select2({
      theme: 'bootstrap-5',
      dropdownParent: $('#Agregar .modal-body'),
      width: '80%' 

  })


    
    
    selecTipoPago();

    function selecTipoPago(){

    	$.post(``,{ selecTipoPago: 'selecTipoPago'}, function(data){
    		let lista = JSON.parse(data);
    		let option = "";
    		lista.forEach((row) =>{
    			option += `<option value="${row.id_tipo_pago}">${row.des_tipo_pago}</option>`;
    		}) 
    		$('.tipoP').each(function(){
    			if(this.children.length == 1){
    				$(this).append(option);
    			}
    		})

    	})

    }

    OcultarDiv();
    function OcultarDiv(){
    	$('.tipoP').change(function() {
    		let selectedOption = $(this).find('option:selected').text();
    		if (selectedOption == "Pago movil" || selectedOption == "Pago Movil" || selectedOption == "pago movil" || selectedOption ==
             "pago Movil" || selectedOption == "pagomovil") {
    			$('.cuentaBancaria').css("display", "none");
    			$('.telefono').css("display", "block");
    		} else if(selectedOption == "Transferencia" || selectedOption == "transferencia" || selectedOption == "tranferencia" || selectedOption ==
             "Tranferencia") {
    			$('.cuentaBancaria').css("display", "block");
    			$('.telefono').css("display", "none");
    		}else{
                $('#error1').text("Solo se permite Transferencia o Pago movil") 
                $('#tipoP').attr("style","border-color: red;")
                $('#tipoP').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         
            }
    	});
    }  


        function validarTipoP(input, div){
          return new Promise((resolve, reject) => {
        $.post('',{id : input.val(), validar: "tipoP"}, function(data){
            let mensaje = JSON.parse(data);
            if(mensaje.resultado === "Error de tipoP"){
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
    
    function ValidarDatos(dato ,dato1 , dato2 , dato3, div , id = null){
        return new Promise((resolve, reject) => {
            $.post('',{tipoP : dato , tipo : dato1.val(), cedulaRif : dato2.val(), nombre: dato3.val() , id ,validarD: "CedulaRif"},
               function(data){
                let mensaje = JSON.parse(data);
                if(mensaje.resultado === "Error Datos"){
                    div.text(mensaje.error);
                    return reject(false);
                }else{
                    div.text(" ");
                    return resolve(true);
                }
            })
        })
    }

    function selectOption(select , error){
     $(select).change(()=>{
        let tipoP = $(select).find('option:selected').text();
        switch(tipoP){

            case 'Seleccione una opción':
            $(error).text(" seleccione una opción") 
            $(select).attr("style","border-color: red;")
            $(select).attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         
            break;

            case 'Pago movil' || 'Pago Movil' || 'pago movil' || 'pago Movil' || 'pagomovil' :
            $(error).text("") 
            $(select).attr("style","border-color: none")
            $(select).attr("style","border-color: none");                         
            break

            case 'Transferencia' || 'transferencia' || 'tranferencia' || 'Tranferencia' : 
            $(error).text("") 
            $(select).attr("style","border-color: none")
            $(select).attr("style","border-color: none");                         
            break

            default:

            $(error).text("Solo se permite Transferencia o Pago movil") 
            $(select).attr("style","border-color: red;")
            $(select).attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         

            break; 
        }
    })
 }

      let ValidarT , idNombre;

      $('#tipoP').change(function(){

        validarTipoP($("#tipoP"), $("#error")).then(()=>{
         selectOption($('#tipoP') , $('#error1'));
        })      

      })
      $('#nombre').change(() =>{ validarSelec2($("#nombre"),$(".select2-selection"),$("#error2"),"Error de Nombre"); });
      $('#cedulaRif').keyup(() =>{ validarCedula($('#cedulaRif') , $('#error3') , "Error de Rif") } );
      $('#cuentaBank').keyup(() =>{ validarBanco($('#cuentaBank'), $('#error4') , "Error de codigo banco") });
      $('#telefono').keyup(() =>{ validarTelefono($('#telefono'), $('#error5') , "Error de telefono") });

     $('#cuentaBank').keyup(()=>{ ValidarDatos($('#tipoP').find('option:selected').text() , $('#cuentaBank'), $('#cedulaRif'), $('#nombre'), $("#error")) }); 
     $('#telefono').keyup(()=>{ ValidarDatos($('#tipoP').find('option:selected').text() , $('#telefono'), $('#cedulaRif'), $('#nombre'), $("#error")) });


     let click = 0;
     setInterval(() => { click = 0 ;}, 2000);

        $('#registrar').click((e) =>{
            e.preventDefault();

            if(click >=  1) throw new Error('Spam de clicks');

            let tipoP , Nombre , cedulaRif , cuentaBank  , telefono , validar;

            let tPago = $('#tipoP').find('option:selected').text();
            let datos = [];
            validarTipoP($("#tipoP"), $("#error")).then(()=>{

                switch(tPago){

                    case 'Seleccione una opción':
                    $('#error1').text(" seleccione una opción") 
                    $('#tipoP').attr("style","border-color: red;")
                    $('#tipoP').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         
                    break;

                    case 'Pago movil' || 'Pago Movil' || 'pago movil' || 'pago Movil' || 'pagomovil' :
                // Validar 
                tipoP = validarSelect($('#tipoP'), $('#error1'), "Tipo invalido");
                Nombre = validarSelec2($("#nombre"),$(".select2-selection"),$("#error2"),"Error de Nombre")
                cedulaRif = validarCedula($('#cedulaRif'), $('#error3'), 'Error de Rif');
                telefono = validarTelefono($('#telefono'), $('#error5'), "Error de telefono");
                

                ValidarDatos(tPago , $('#telefono'), $('#cedulaRif'), $('#nombre'), $("#error")).then((validar) => {
                  if (tipoP && Nombre && cedulaRif && telefono && validar) {
                    let name = $('#nombre').val();
                    let cRif = $('#cedulaRif').val();
                    let cell = $('#telefono').val();
                    datos = [tPago, name, cRif, cell];
                    $.ajax({
                        type: "post",
                        url : "" ,
                        dataType: "json",
                        data: {data : datos , registro : "Registro"},
                        success(data){
                         if(data.resultado === "banco registrado."){
                            mostrar.destroy();
                            rellenar(); 
                            $('#agregarform').trigger('reset');  
                            $('.nombreBanco').val(0).trigger('change'); // LIMPIA EL SELECT2
                            $('.cerrar').click(); 
                            Toast.fire({ icon: 'success', title: 'Banco registrado' }) 
                        }
                    }
                })
                } else {
                    e.preventDefault();
                }
            });
                break;

                case 'Transferencia' || 'transferencia' || 'tranferencia' || 'Tranferencia' : 
                // Validar
                tipoP = validarSelect($('#tipoP'), $('#error1') , "Tipo invalido");
                Nombre = validarSelec2($("#nombre"),$(".select2-selection"),$("#error2"),"Error de Nombre");
                cedulaRif = validarCedula($('#cedulaRif'), $('#error3') , 'Error de Rif');
                cuentaBank = validarBanco($('#cuentaBank'), $('#error4') , 'Error de Rif');
                
                ValidarDatos(tPago, $('#cuentaBank'), $('#cedulaRif'),  $('#nombre') ,$("#error")).then((validar) => {
                  if (tipoP && Nombre && cedulaRif && cuentaBank && validar) {
                    let name = $('#nombre').val();
                    let cRif = $('#cedulaRif').val();
                    let Banco = $('#cuentaBank').val();
                    datos = [tPago, name, cRif, Banco];
                    $.ajax({
                        type: "post",
                        url : "" ,
                        dataType: "json",
                        data: {data : datos , registro : "Registro"},
                        success(data){
                           if(data.resultado === "banco registrado."){
                            mostrar.destroy();
                            rellenar(); 
                            $('#agregarform').trigger('reset'); 
                            $('.nombreBanco').val(0).trigger('change'); // LIMPIA EL SELECT2
                            $('.cerrar').click(); 
                            Toast.fire({ icon: 'success', title: 'Banco registrado' }) 
                        }
                    }
                })
                } else {
                    e.preventDefault();
                }
            });
                
                break;

                default:

                $('#error1').text("Solo se permite Transferencia o Pago movil") 
                $('#tipoP').attr("style","border-color: red;")
                $('#tipoP').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         

                break; 
            }
        }).catch(()=>{
            $("#error1").text("El tipo pago no está registrado. Seleccione otro. ");
            throw new Error('No exite.');
        })
        click++;
    })

    let id;

    $(document).on("click", '.editar', function(){
        id = this.id;
        let cuentaBancaria = $('.cuentaBancaria');
        let codigoBanco = $('.CodigoBanco');
        let telefono = $('.telefono');

            $.ajax({
                method: "post",
                url: "",
                dataType: "json",
                data: {clickEdit: "editar Banco", id},
                success(data){
                  let tp = data[0].telefono;
                  if (tp  != null){
                    cuentaBancaria.css("display", "none");
                    telefono.css("display", "block");
                    $("#tipopEdit").val(4);
                    $("#nombreEdit").val(data[0].id_banco);
                    $("#cedulaRifEdit").val(data[0].rif_cedula);
                    $("#telefonoEdit").val(data[0].telefono);
                }else{
                    cuentaBancaria.css("display", "block");
                    telefono.css("display", "none");
                    $("#tipopEdit").val(5);
                    $("#nombreEdit").val(data[0].id_banco);
                    $("#cedulaRifEdit").val(data[0].rif_cedula);
                    $('#cuentaBankEdit').val(data[0].num_cuenta);
                }
            }
        });
    });
    
    function validarClick(){
        return new Promise((resolve, reject) =>{
            $.ajax({
                type: "POST",
                url: '',
                dataType: "json",
                data: { validarC: "existe", id},
                success(data) {
                if (data.resultado === "Error de banco") {  
                    Toast.fire({ icon: 'error', title: 'Esta banco ya esta anulada' }); // ALERTA 
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

    $('#tipopEdit').change(function(){

        validarTipoP($("#tipopEdit"), $("#errorEdit")).then(()=>{
            selectOption($('#tipopEdit'), $("#errorEdit1"));
        })
       
      })
      $('#nombreEdit').change(() =>{ validarSelect($('#nombreEdit'), $('#errorEdit2') , "Nombre invalido"); });
      $('#cedulaRifEdit').keyup(() =>{ validarCedula($('#cedulaRifEdit') , $('#errorEdit3') , "Error de Rif") } );
      $('#cuentaBankEdit').keyup(() =>{ validarBanco($('#cuentaBankEdit'), $('#errorEdit4') , "Error de codigo banco") });
      $('#telefonoEdit').keyup(() =>{ validarTelefono($('#telefonoEdit'), $('#errorEdit6') , "Error de telefono") });

      $('#cuentaBankEdit').keyup(()=>{ ValidarDatos($('#tipopEdit').find('option:selected').text() , $('#cuentaBankEdit'), $('#cedulaRifEdit'), $('#nombreEdit'), $("#errorEdit"), id) }); 
      $('#telefonoEdit').keyup(()=>{ ValidarDatos($('#tipopEdit').find('option:selected').text() , $('#telefonoEdit'), $('#cedulaRifEdit'), $('#nombreEdit'),$("#errorEdit"), id) });

      $('#editar').click((e) =>{
         e.preventDefault();

         if(click >=  1) throw new Error('Spam de clicks');

         validarClick().then(()=>{

           let tipopEdit , nombreEdit , cedulaRifEdit , cuentaBankEdit , codBankEdit , telefonoEdit , validarE;

           let tPagoEdit = $('#tipopEdit').find('option:selected').text();
           let datos = [];

           switch(tPagoEdit){

            case 'Seleccione una opción':
            $('#errorEdit1').text(" seleccione una opción") 
            $('#tipopEdit').attr("style","border-color: red;")
            $('#tipopEdit').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         
            break;

            case 'Pago movil' || 'Pago Movil' || 'pago movil' || 'pago Movil' || 'pagomovil' :
            // Validar 
            tipopEdit = validarSelect($('#tipopEdit'), $('#errorEdit1'), "Tipo invalido");
            nombreEdit = validarSelect($('#nombreEdit'), $('#errorEdit2'), "Nombre invalido");
            cedulaRifEdit = validarCedula($('#cedulaRifEdit'), $('#errorEdit3'), 'Error de Rif');
            telefonoEdit = validarTelefono($('#telefonoEdit'), $('#errorEdit6'), "Error de telefono");

            ValidarDatos(tPagoEdit , $('#telefonoEdit'), $('#cedulaRifEdit'),  $('#nombreEdit'), $("#errorEdit"), id).then((validarE) => {
              if (tipopEdit && nombreEdit && cedulaRifEdit && telefonoEdit && validarE) {
                let pago = $('#tipopEdit').val();
                let name = $('#nombreEdit').val();
                let cRif = $('#cedulaRifEdit').val();
                let cell = $('#telefonoEdit').val();
                datos = [tPagoEdit, name, cRif, cell];
                $.ajax({
                    type: "post",
                    url : "" ,
                    dataType: "json",
                    data: {data : datos , id ,editar : "editar"},
                    success(data){
                     if(data.resultado === "banco editado."){
                        mostrar.destroy();
                        rellenar(); 
                        $('#agregarformEdit').trigger('reset'); 
                        $('.cerrar').click(); 
                        Toast.fire({ icon: 'success', title: 'Banco editado' }) 
                    }
                }
            })
            } else {
                e.preventDefault();
            }
        });
            break;

            case 'Transferencia' || 'transferencia' || 'tranferencia' || 'Tranferencia' : 
            // Validar
            tipopEdit = validarSelect($('#tipopEdit'), $('#errorEdit1'), "Tipo invalido");
            nombreEdit = validarSelect($('#nombreEdit'), $('#errorEdit2'), "Nombre invalido");
            cedulaRifEdit = validarCedula($('#cedulaRifEdit'), $('#errorEdit3'), 'Error de Rif');
            cuentaBankEdit = validarBanco($('#cuentaBankEdit'), $('#errorEdit4') , 'Error de Rif');

            ValidarDatos(tPagoEdit , $('#cuentaBankEdit'), $('#cedulaRifEdit'), $('#nombreEdit') , $("#errorEdit"), id).then((validarE) => {
                
              if (tipopEdit && nombreEdit && cedulaRifEdit && cuentaBankEdit && validarE) {
                let name = $('#nombreEdit').val();
                let cRif = $('#cedulaRifEdit').val();
                let Banco = $('#cuentaBankEdit').val();
                datos = [tPagoEdit, name, cRif, Banco];
                $.ajax({
                    type: "post",
                    url : "" ,
                    dataType: "json",
                    data: {data : datos , id ,editar : "editar"},
                    success(data){
                       if(data.resultado === "banco editado."){
                        mostrar.destroy();
                        rellenar(); 
                        $('#agregarformEdit').trigger('reset'); 
                        $('.cerrar').click(); 
                        Toast.fire({ icon: 'success', title: 'Banco Editado' }) 
                    }
                }
            })
           
            } else {
                e.preventDefault();
            }
        });
            
            break;

            default:
            $('#errorEdit').text("Solo se permite Transferencia o Pago movil") 
            $('#tipopEdit').attr("style","border-color: red;")
            $('#tipopEdit').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                         

            break; 
        }

      
    }).catch(() =>{
       throw new Error('No exite.');
   })

    click++;

 })

    $('#cancelar').click(()=>{
     $('#nombre').val(0).trigger('change'); // LIMPIA EL SELECT2
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('#Agregar .select2-selection').attr("style","borden-color:none;","borden-color:none;");
     $('#Agregar select').attr("style","borden-color:none;","borden-color:none;");
     $('#Agregar input').attr("style","borden-color:none;","borden-color:none;");
     $('.error').text(" ");
  })
 

    $(document).on('click', '.borrar', function(){
        id = this.id;

    })   
    
    $('#delete').click((e)=>{
       e.preventDefault();

       if(click >=  1) throw new Error('Spam de clicks');

       validarClick().then(()=>{

        $.ajax({
            type: "POST",
            url: "",
            dataType: "json",
            data: {eliminar: "eliminar Banco" , id},
            success(data){
                if (data.resultado === "Eliminado") {
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

    click++;
})

})