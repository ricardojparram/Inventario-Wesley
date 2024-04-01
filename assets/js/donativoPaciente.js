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
			data: {mostrar : 'DonativoPaciente', bitacora},
			success(data){
				data ? data : '';
				mostrar = $('#tabla').DataTable({
					resposive: true,
					data: data,
					columns:[
					{ data: 'ced_pac' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							<button class="btn btn-registrar detalleD" id="${row.id_donaciones}" data-bs-toggle="modal" data-bs-target="#detalleDonacion">Ver Detalles</button>
							`;
						}
					},
					{ data: 'fecha' },
					{ data: 'beneficiario' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							<button type="button" ${eliminarPermiso} id="${row.id_donaciones}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#borrar"><i class="bi bi-trash3"></i></button>
							`;
						}
					}
					]
				})

			}
		})
	}

	let id;

  // Ver Detalles de donaciones

  $(document).on('click', '.detalleD' , function(){

       id = this.id; // id = id
       $.post('',{detalleD : 'detalle Donacion' , id}, function(data){
       	let lista = JSON.parse(data);
       	let tabla;

       	lista.forEach(row=>{
       		tabla += `
       		<tr>
       		<td>${row.nombrepro}</td>
       		<td>${row.cantidad}</td>                
       		</tr>
       		`  
       	})
       	$('#DonacionTitle').text(`Numero de Donacion #${lista[0].id_donaciones}.`);
       	$('#bodyDetalle').html(tabla);
       })

   })

 		// Funcion select Pacientes
 		selectPacientes();
 		function selectPacientes() {
 			$.ajax({
 				type: 'POST',
 				url: '',
 				dataType: 'json',
 				data: { selectPacientes: 'paciente' },
 				success(data) {
 					$('#cedula').select2({
 						data: data.map(function(item) {
 							return { id: item.ced_pac, text: item.ced_pac};
 						}),
 						theme: 'bootstrap-5',
 						dropdownParent: $('#agregar .modal-body'),
 						width: '80%'
 					});
 					$('#cedula').on('change', function() {
 						let selectedCedula = $(this).val();
 						let paciente = data.find(item => item.ced_pac === selectedCedula)
 						if (paciente) {
 							let nombreApellido = paciente.nombre + ' ' + paciente.apellido;
 							$('#beneficiario').val(nombreApellido);
 						}
 					});
 				}
 			});

 		}
    	// Funcion select Sedes
    	selectSedes(); 
    	function selectSedes(){
    		$.ajax({
    			type: 'POST',
    			url: '',
    			dataType: 'json',
    			data: { selectSedes: 'sedes' },
    			success(data) {
    				let selectSedes = $('#sede').select2({
    					data: data.map(function(item) {
    						return { id: item.id_sede, text: item.nombre};
    					}),
    					theme: 'bootstrap-5',
    					dropdownParent: $('#agregar .modal-body'),
    					width: '50%'
    				});

    			}
    		});
    	}

      //  fila que se inserta

      const newRow = `
      <tr>
      	<td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
	      <td width='25%'> 
		     <select class="select-productos select-asd" name="productos">
		       <option></option>
		     </select>
	       </td>
	      <td width='15%' class="cantidad"><input class="select-asd stock" disabled type="number" value=""/></td>
	     <td width='15%' class="unidades"><input class="select-asd unidad" type="number" value="" /></td>
      </tr>`;

      // Validar filas
      function validarFila(filas, error) {
      	let filaExiste = $(filas).find('tr').length > 0;
        $(error).text(filaExiste? '' : 'No debe haber filas vacías')
        return filaExiste;
      }

    // Funcion de select para productos 
    selectProductos()
    function selectProductos(){
    	$.ajax({
    		url: '',
    		method: 'POST',
    		dataType: 'json',
    		data: {
    			selectProductos: "productos"
    		},
    		success(data){
    			let option = data.map(row => `<option value="${row.id_producto_sede}">${row.nombrepro} ${row.lote}</option>`).join('');

    			$('.select-productos').each(function(){
    				if(this.children.length == 1){
    					$(this).append(option);
    					$(this).chosen({
    						width: '30vw',
    						placeholder_text_single: "Selecciona un producto",
    						search_contains: true
    					});
    				}
    			})

    		}
    	})
    }

    // Validar producto vacios

    function validarProductos(filas, productos, error , mensaje) {
    	let vproductos = true;

    	$(filas).each(function () {
    		let producto = $(this).find(productos).val();
    		if (producto == "" || producto == null) {
    			vproductos = false;
    			$(error).text(mensaje);
    		} else {
    			$(error).text('');
    			vproductos = true;
    		}
    	});

    	return vproductos;
    }


	// Validar producto repetidos

	let productosRepetidos, productos;
	function validarProductosRepetidos() {
		productos = Object.values(document.querySelectorAll('.select-productos')).map(item => {
			return item.value;
		});
		productosRepetidos = productos.filter((elemento, index) => productos.indexOf(elemento) !== index);
		$(".select-productos").each(function () {
			if (this.value == "") return;
			if (productosRepetidos.includes(this.value)) {
				$(this).attr('valid', false);
				$(this).closest('td').find('div.chosen-container').addClass('select-error')
			} else {
				$(this).attr('valid', true);
				$(this).closest('td').find('div.chosen-container').removeClass('select-error')
			}
		})
	}

	let cantidadStock , stock , select , unidades 
	productoSeleccionado();
	function productoSeleccionado(){
		$('.select-productos').change(function() {
			select = $(this);
			producto = $(this).val();
			cantidadStock = select.closest('tr').find('.cantidad input');
			unidades = select.closest('tr').find('.unidades input');
			fillData();
		});
	}

	function fillData(){
		$.getJSON('', {producto , filas : 'select fila'}, function(data) {
			stock = data[0].cantidad;
			unidad = data[0].unidades_restantes;
			cantidadStock.val(stock);
			unidades.val(unidad);
			unidades.attr('placeholder', unidad);
			validarUnidades(unidades , unidad);
		});
	}

	function validarUnidades(input, max){
		$(input).keyup(()=>{
			stock = Number(max);
			num = Number(input.val());
			if(num > stock || num == 0 || num < 1 || !Number.isInteger(num)){
				input.css({"border" : "solid 1px", "border-color" : "red"})
				input.attr("valid", "false");
				$('#pValid').text('Cantidad inválida.');
			}else{
				input.css({'border': 'none'})
				input.attr("valid", "true");
				$('#pValid').text(' ');
			}
		})
	}

	 validUnidades = () => {
		let unidadValue = $('.unidad').val();
		let isValidUnidad = !$('.unidad').is('[valid="false"]') && unidadValue !== "" && unidadValue !== '0';

		$('#pValid').text(isValidUnidad ? '' : 'Cantidad inválida.');
		return isValidUnidad;
	}

    // Funcion de agregar fila 
    function addNewRow(){
    	$('#ASD').append(newRow);
    	validarFila($('#ASD') ,$('.filaProductos'));
    	selectProductos();  
    	productoSeleccionado();
    }

     // Agregar fila para insertar producto a donar
     $('.newRow').on('click',function(e){
     	addNewRow();   
     });

     // Evento de cambio en los productos 
     $(document).on("change", ".select-productos", function () {
     	validarProductos($('.table-body tbody tr'),$(".select-productos"), $('.filaProductos') , 'No debe haber productos vacios');
     	validarProductosRepetidos();
     });

     // ELiminar fila
     $('body').on('click','.removeRow', function(e){
     	$(this).closest('tr').remove();
     	validarProductosRepetidos();
     	validarFila($('#ASD') ,$('.filaProductos'));
     });



    $('#cedula').change(()=>{ validarSelec2($('#cedula'),$(".select2-selection"), $('#error1') , 'Error de cedula');  })
    $('#beneficiario').keyup(()=> { validarStringLong($('#beneficiario'),  $('#error2') , 'Error de beneficiario'); });
    $('#sede').change(()=>{ validarSelec2($('#sede'),$(".select2-selection"), $('#error3') , 'Error de sede');  })
    
     let click = 0;
     let cedula , beneficiario , sede, producto;
     setInterval(()=>{click = 0}, 2000);

     $('#registrar').click(function(e) {
     	e.preventDefault();

     	 if(click >= 1){ throw new Error('Spam de clicks');}

     	 /* Validaciones para registrar*/

     	 let repetidos = true;
     	 let datos = [];

     	 cedula = validarSelec2($('#cedula'),$(".select2-selection"), $('#error1') , 'Error de cedula');
     	 beneficiario = validarStringLong($('#beneficiario'),  $('#error2') , 'Error de beneficiario');
     	 sede = validarSelec2($('#sede'),$(".select2-selection"), $('#error3') , 'Error de sede');
     	 filasValidas = validarFila($('#ASD') ,$('.filaProductos'));
     	 producto = validarProductos($('.table-body tbody tr'),$(".select-productos"), $('.filaProductos') , 'No debe haber productos vacios');
     	 repetidos = $('.select-productos').is('[valid="false"]')? false : true;
     	 unidades = validUnidades();


     	 if (cedula && beneficiario && sede && filasValidas && repetidos && producto && unidades){

     	 	$('.table-body tbody tr').each(function(){
     	 		productos = $(this).find('.select-productos').val();
     	 		unidades = $(this).find('.unidad').val();

     	 		let productoObj = {
     	 			producto : productos,
     	 			unidades: unidades
     	 		};

     	 		datos.push(productoObj);
     	 	});

     	 	$.ajax({
     	 		url: '',
     	 		type: 'POST',
     	 		dataType: 'json',
     	 		data: {cedulaPaciente: $('#cedula').val(), beneficiario: $('#beneficiario').val() , datos },
     	 		success(data){
     	 		if(data.resultado == 'registrado con exito'){
	     	 		mostrar.destroy();
	     	 		rellenar();  
		            $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
		            $('#agregar .select2-selection').attr("style","borden-color:none;","borden-color:none;");
		            $('.error').text(" ");
		            $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
		            $('#error').text('');
		            $('.removeRow').click();
		            $('.cerrar').click(); 
		            addNewRow();
		            Toast.fire({ icon: 'success', title: 'Donacion registrada' , showCloseButton: true });
     	 		 }
     	 	   }
     	 	})
     	 	.fail(function() {
     	 		console.log("error");
     	 	})

          
     	 }

     	 click++;
     });

     function validarExitencia(){
     	return new Promise((resolve, reject) =>{
     		$.ajax({
     			type: "POST",
     			url: '',
     			dataType: "json",
     			data: { validarE: "existe", id},
     			success(data) {
     				if (data.resultado === "Error de donacion") {  
     					mostrar.destroy();
     					rellenar();
     					$('.cerrar').click();
                       Toast.fire({icon: 'error', title: 'Error de Donacion', showCloseButton: true }) // ALERTA 
                       reject(false);
                   }else{
                   	resolve(true);
                   }

               }
           })
     	})
     }

     $(document).on('click', '.borrar' , function(){
     	id = this.id;
     })

     $('#delete').click(function(e) {
     	e.preventDefault();

     	if(click >= 1){ throw new Error('Spam de clicks');}

     	validarExitencia().then(()=>{

     		$.ajax({
     			url: '',
     			type: 'POST',
     			dataType: 'json',
     			data: {eliminar: 'eliminar', id},
     			success(data){
     			  if (data.resultado === 'Eliminado'){
		            $("#close").click();
		            mostrar.destroy();
		            Toast.fire({icon: 'success', title: 'Donacion eliminada', showCloseButton: true })
		            rellenar();
		          }
     			}

     		}).fail(function() {
     			console.log("error");
     		})

     	}).catch(()=>{
     		throw new Error('No exite.');
     	})

     	click++;
     });


 })