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
							   <button class="btn btn-registrar detalleV" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleVenta">Ver Detalles</button>
							`;
						}
					},
					{ data: 'fecha' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							  <button class="btn btn-registrar detalleTipo" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleTipoPago">Ver Metodos Pago</button>
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

	$(document).on('click', '.detalleV' , function(){

		id = this.id; 
		$.post('',{detalleProductos : 'detV' , id}, function(data){
			let lista = JSON.parse(data);
			let tabla;

			lista.forEach(row=>{
				tabla += `
				<tr>
				<td>${row.producto}</td>
				<td>${row.cantidad}</td>
				<td>${row.precio_actual}</td>                      
				</tr>
				`  
			})
			$('#ventaNombre').text(`Numero de Factura #${lista[0].num_fact}.`);
			$('#bodyDetalle').html(tabla);
			$('.factura').attr("id", id);
		})
	})

  $(document).on('click', '.detalleTipo' , function(){

       id = this.id; // id = id
       $.post('',{detalleTipo : 'detTipoPago' , id}, function(data){
        let lista = JSON.parse(data);
        let tabla;

        lista.forEach(row=>{
          tabla += `
          <tr>
          <td>${row.tipo_pago}</td>
          <td>${row.referencia}</td>
          <td>${row.monto_pago}</td>                    
          </tr>
          `  
        })
        $('#ventaNombreTipoPago').text(`Numero de Factura #${lista[0].num_fact}.`);
        $('#bodyDetalleTipo').html(tabla);
      })
     })


		// Función para cargar y mostrar opciones de pacientes y personal en un formulario select
		selectCliente();
		function selectCliente() {
			$.ajax({
				type: 'POST',
				url: '', 
				dataType: 'json',
				data: { selectCliente: 'cliente' },
				success: function(data) {
					let selectHtml = buildSelectHTML(data);
					$('#cedula').html(selectHtml);
					$('.select2').select2({
						theme: 'bootstrap-5',
						dropdownParent: $('#Agregar .modal-body'),
						width: '70%'
					});
				}
			});
		}

		function generateOptionHTML(item) {
			return '<option value="' + item.cedula + '" class="' + item.tipo + '">' + item.nombre + ' ' + item.apellido + ' ' + item.cedula + '</option>';
		}

		// Función para construir el HTML del formulario select
		function buildSelectHTML(data){
			let personalOptions = data.filter(item => item.tipo === 'Personal').map(generateOptionHTML).join('');
			let pacienteOptions = data.filter(item => item.tipo === 'Paciente').map(generateOptionHTML).join('');

			return '<select class="form-control select2 grupo-opciones" data-placeholder="Cliente" id="cedula">' +
		                '<option></option>' +
		                '<optgroup label="Personal">' + personalOptions + '</optgroup>' +
		                '<optgroup label="Pacientes">' + pacienteOptions + '</optgroup>' +
		            '</select>';
		}

		
		valorDolar();
		function valorDolar(){
			$.ajax({
				type: 'POST',
				url: '', 
				dataType: 'json',
				data: { valorDolar: 'dolar' },
				success: function(data) {
					let dolar = data[0].valor;
					$('.dolar').text(dolar);
				}
			});
		}
	
    calculate()
    function calculate(){
    	let total_price = 0,
    	total_tax = 0;
    	let moneda = $('.dolar').first().text();

    	$('.table-body tbody tr').each( function(){
    		let row = $(this),
    		rate = row.find('.rate input').val(),
    		amount = row.find('.amount input').val();

    		let sum = rate * amount;

    		total_price = total_price + sum;

    		row.find('.sum').text( sum.toFixed(2) );

    	});

    	let precioTotal = (total_price).toFixed(2);
        let cambio = (precioTotal / moneda).toFixed(2);
    	 if(isNaN(cambio) || moneda == 0){
    	 	cambio = "0"
    	 }

    	$('#montos').html(`Total: <strong>${precioTotal}</strong>`)
    	$('#cambio').html(`Total Dolares: <strong>${cambio}</strong>`)
    	$('#monto').val(precioTotal);

      let totalRows = $('.precioPorTipo').length;
      let precioPorFila = (total_price / totalRows).toFixed(2);

      $('.precioPorTipo').each(function() {
        $(this).find('input').val(precioPorFila);
      });


    }

    $(document).on('keyup', '.precio-tipo', () => {
    let montoMax = parseFloat($('#monto').val());
    let preciosPorTipo = $('.precio-tipo');
    let numFilas = preciosPorTipo.length;

    if (numFilas === 1) {
      preciosPorTipo.val(montoMax.toFixed(2));
    } 

    let totalAsignado = 0;
    preciosPorTipo.each(function() {
      totalAsignado += parseFloat($(this).val());
    });

    if (totalAsignado !== montoMax || totalAsignado < 1) {
      preciosPorTipo.css({"border": "solid 1px", "border-color": "red"});
      preciosPorTipo.attr('valid', 'false');
    } else {
      preciosPorTipo.css({"border": "none"});
      preciosPorTipo.attr('valid', 'true');
    }
  });


    //Evento keyup para que funcione calculate()
    $('#ASD').on('keyup','input',function(){
      calculate();
    })
    

    let selectRepetidos, select_t;
    const validarSelectRepetidos = (selected, error, status = true) => {
    	let validacion = [];
    	let $select = document.querySelectorAll(selected);
    	if ($select.length < 1) {
    		$(error).html('No hay filas.');
    		return false
    	} else {
    		$(error).html('');
    	}
    	select_t = Object.values(document.querySelectorAll(selected)).map(item => {
    		return item.value;
    	});
    	selectRepetidos = select_t.filter((elemento, index) => select_t.indexOf(elemento) !== index);
    	$(selected).each(function () {
    		if (this.value === "" || this.value === null) {
    			if (status != true) {
    				$(this).closest('td').find('div.chosen-container').addClass('select-error')
    			}
    			validacion.push(false);
    		} else if (selectRepetidos.includes(this.value)) {
    			$(this).closest('td').find('div.chosen-container').addClass('select-error')
    			validacion.push(false);
    		} else {
    			$(this).closest('td').find('div.chosen-container').removeClass('select-error')
    			validacion.push(true);
    		}
    	})
    	return !validacion.includes(false);
    }


    // fila de tipo pago
    let newRowTipo = ` <tr>
                        <td width="1%"><a class="removeRowPagoTipo a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                        <td width='30%'> 
                          <select class="select-tipo select-asd" name="TipoPago">
                            <option></option>
                          </select>
                        </td>
                         <td width='15%' class="referencia"><input class="select-asd ref" type="number" value=""/></td>
                        <td width='15%' class="precioPorTipo"><input class="select-asd precio-tipo" type="number" value=""/></td>
                      </tr>`;


      selectTipoPago();
      function selectTipoPago(){
      	$.ajax({
      		url: '',
      		type: 'POST',
      		dataType: 'json',
      		data:{ selectTipoPago: 'selectTipoPago'},
      		success(data){
      			let option = data.map(row => `<option value="${row.id_forma_pago}">${row.tipo_pago}</option>`).join('');

      			$('.select-tipo').each(function(){
      				if(this.children.length == 1){
      					$(this).append(option);
      					$(this).chosen({
      						width: '25vw',
      						placeholder_text_single: "Selecciona un metodo",
      						search_contains: true
      					});
      				}
      			})

      		}
      	})
      }

    function TipoPagoSeleccionado() {
      $('.table-body-tipo tbody tr').each(function() {
        let tipoPago = $(this).find('.select-tipo').find('option:selected').text().toLowerCase();
        let disableRef = !(tipoPago === 'pago movil' || tipoPago === 'transferencia');

        let ref = $(this).find('.ref');
        ref.prop('disabled', disableRef);
        ref.attr("valid", "true"); 

        if (disableRef) {
          ref.val('');
          ref.attr("valid", "false").css({'border': 'none'});
        }
      });
    }
         
       
      $(document).on('keyup', '.ref[valid="true"]', function() { validarReferencia() });
      function validarReferencia() {
        let isValid = true;
        const referenciaInput = $('.ref[valid="true"]');
        const regex = /^[0-9]{5,12}$/;
        const filaTipoPago = $('.filaTipoPago');

        referenciaInput.each(function() {
          const referencia = $(this).val();
          const isInputValid = regex.test(referencia);

          if (!isInputValid) {
            isValid = false;
            const borderStyle = {'border': 'solid 1px', 'border-color': 'red'};
            $(this).css(borderStyle);
            filaTipoPago.text('referencia inválida.');
          } else {
            const borderStyle = {'border': 'none'};
            $(this).css(borderStyle);
            filaTipoPago.text('');
          }
        });

        return isValid;
      }
   

      function validarValoresPositivos(inputs) {
        let isValid = true;
        inputs.each(function() {
          let value = parseFloat($(this).val());
          if (value <= 0) {
            $(this).css({ "border": "solid 1px", "border-color": "red" });
            $(this).attr('valid', 'false');
            isValid = false;
          } else {
            $(this).css({ "border": "none" });
            $(this).attr('valid', 'true');
          }
        });
        return isValid;
      }

      function validarTipoPorPrecio(montoM, precioXtipo) {
        let montoMax = parseFloat(montoM.val());
        let preciosPorTipo = precioXtipo;

        if (!validarValoresPositivos(preciosPorTipo)) {
          $('#pValid').text('Error: Precio por tipo no puede ser negativo o cero');
          return false;
        }

        let totalAsignado = 0;
        preciosPorTipo.each(function() {
          totalAsignado += parseFloat($(this).val());
        });

        let resto = montoMax - totalAsignado;

        if (totalAsignado > montoMax) {
          preciosPorTipo.css({ "border": "solid 1px", "border-color": "red" });
          $(preciosPorTipo).attr('valid', 'false');
          $('#pValid').text('Excede el monto máximo por ' + resto.toFixed(2) + ' bs');
          return false;
        } else if (totalAsignado < montoMax) {
          preciosPorTipo.css({ "border": "solid 1px", "border-color": "red" });
          $(preciosPorTipo).attr('valid', 'false');
          $('#pValid').text('Falta ' + resto.toFixed(2) + ' bs para alcanzar el monto máximo');
          return false;
        } else if (totalAsignado < 1) {
          preciosPorTipo.css({ "border": "solid 1px", "border-color": "red" });
          $(preciosPorTipo).attr('valid', 'false');
          $('#pValid').text('');
          return false;
        } else {
          preciosPorTipo.css({ "border": "none" });
          $(preciosPorTipo).attr('valid', 'true');
          return true;
        }
      }


    // Caracteriticas de la fila Tipo Pago
      function addNewRowPago(){
        $('#FILL').append(newRowTipo);
        selectTipoPago();
      }

    // Agregar fila para insertar tipo de pago
     $('.newRowPago').on('click',function(e){
       addNewRowPago();
       validarSelectRepetidos('.select-tipo' , '.filaTipoPago');     
     });

    // Evento de cambio en los tipo de pago 
    $(document).on("change", ".select-tipo", function () {
    	validarSelectRepetidos('.select-tipo' , '.filaTipoPago');
      TipoPagoSeleccionado();
      validarReferencia();
    });


     // ELiminar fila Tipo de Pago
     $('body').on('click','.removeRowPagoTipo', function(e){
        $(this).closest('tr').remove();
        validarSelectRepetidos('.select-tipo' , '.filaTipoPago');
        validarTipoPorPrecio($("#monto"), $('.precio-tipo'))
     });

 

     //  fila que se inserta
     let newRow = `<tr>
                      <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                      <td width='30%'> 
                        <select class="select-productos select-asd" name="productos">
                          <option></option>
                        </select>
                      </td>
                      <td width='10%' class="amount"><input class="select-asd stock" type="number" value=""/></td>
                      <td width='15%' class="rate"><input class="select-asd" type="number" disabled value="" /></td>
                      <td width='15%' class="sum"></td>
                    </tr>`;

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
        			let option = data.map(row => `<option value="${row.id_producto_sede}">${row.producto} ${row.lote}</option>`).join('');

        			$('.select-productos').each(function(){
        				if(this.children.length == 1){
        					$(this).append(option);
        					$(this).chosen({
        						width: '25vw',
        						placeholder_text_single: "Selecciona un producto",
        						search_contains: true
        					});
        				}
        			})

        		}
        	})
        }

     let producto, select , cantidad, stock;
    //Selecciona cada producto 
    cambio();
    function cambio(){
    	$('.select-productos').change(function(){
    		select = $(this);
    		producto = $(this).val();
    		cantidad = select.closest('tr').find('.amount input');
    		fillData();
    	})
    }

    //  Rellena los inputs con el precio y cantidad de cada producto
    function fillData(){
    	$.getJSON('',{producto, filas: "data"}, function(data){

    		let precio = select.closest('tr').find('.rate input');
    		stock = data[0].cantidad;
    		valor = data[0].precio;
    		cantidad.val(stock);
    		cantidad.attr("placeholder", stock);
    		precio.val(valor);
    		calculate();
    		validarStock(cantidad, stock);

    	})
    }

    function validarStock(input, max){
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

    validCantidad = () => {
      let cantidadValue = $('.stock').val();
      let isValidCantidad = !$('.stock').is('[valid="false"]') && cantidadValue !== "" && cantidadValue !== '0';

      $('#pValid').text(isValidCantidad ? '' : 'Cantidad inválida.');
      return isValidCantidad;
    }


    // Caracteriticas de la fila Producto
    function addNewRow(){
      $('#ASD').append(newRow);
      selectProductos();
      cambio();
    }

    // Agregar fila para insertar producto
     $('.newRow').on('click',function(e){
       addNewRow();    
       validarSelectRepetidos('.select-productos' , '.filaProductos');   
     });


     // Evento de cambio en los productos 
    $(document).on("change", ".select-productos", function () {
    	validarSelectRepetidos('.select-productos' , '.filaProductos'); 
    });


    // ELiminar fila
     $('body').on('click','.removeRow', function(e){
        $(this).closest('tr').remove();
        validarSelectRepetidos('.select-productos' , '.filaProductos');  
        calculate(); 
     });


     const FilasProducto = () => {
     let datos = [];
     $('.table-body tbody tr').each(function() {
        producto = $(this).find('.select-productos').val();
        cantidad = $(this).find('.amount input').val();
        precio = $(this).find('.rate input').val();

        let productoObj = {
          producto : producto,
          cantidad: cantidad,
          precio: precio
        }

        datos.push(productoObj);
        
      });
     
     return datos

     }

     const FilasTipoPago = () => {     
      let datos = [];
      $('.table-body-tipo tbody tr').each(function() {
        TipoPago = $(this).find('.select-tipo').val();
        referencia = $(this).find('.ref').val();
        precioTipo = $(this).find('.precio-tipo').val();

        let productoObj = {
          TipoPago : TipoPago,
          referencia : referencia,
          precioTipo : precioTipo
        }

        datos.push(productoObj);
      
      });
      return datos

    }


     let cedula , montoTotal , totalDolares , valid_productos , valid_precioTipo;

    $('#cedula').change(()=>{ let cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula"); });

    $('#registrar').click(function(e) {
      e.preventDefault();

       cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula");
       montoTotal = validarNumero($("#monto"),$("#error3"),"Error de monto");
       valid_productos = validarSelectRepetidos('.select-productos' , '.filaProductos' , false);
       valid_tipoPago =  validarSelectRepetidos('.select-tipo' , '.filaTipoPago', false);
       stock = validCantidad();
       referencia = validarReferencia();
       valid_precioTipo = $('.precio-tipo').is('[valid="false"]')? false : true;
       validarTipoPorPrecio($("#monto"), $('.precio-tipo'));

       if(cedula && montoTotal && valid_productos && valid_tipoPago && stock && referencia && valid_precioTipo){
        
        cedula = $('#cedula').val();
        tipoCliente = $('#cedula').find('option:selected').attr('class');
        montoTotal = $("#monto").val();
        totalDolares = $('#cambio').find('strong').text();
        datosProducto = FilasProducto();
        datosTipoPago = FilasTipoPago()

        $.ajax({
          url: '',
          type: 'POST',
          dataType: 'json',
          data: {cedula , tipoCliente , montoTotal , totalDolares , datosProducto , datosTipoPago},
          success(data){
            if(data.resultado === 'ok'){
            mostrar.destroy();
            rellenar();  // FUNCIÓN PARA RELLENAR
            $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
            $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
            $('.select2 ').removeClass('input-error');
            $('.cerrar').click(); // CERRAR EL MODAL
            $('.removeRow').click(); 
            $('.removeRowPagoTipo').click(); 
            $('.error').text('');
            addNewRow()
            addNewRowPago()
            Toast.fire({ icon: 'success', title: data.msg }) // ALERTA 
            }
          }
        }).fail(function(e){
          Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
          throw new Error(e.responseJSON.msg);
        }).always(function() {
          console.log("complete");
        });
               
       }

    });

    $('#cerrar').click(()=>{
     $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('.select2 ').removeClass('input-error');
     $('#Agregar input').removeClass('input-error');
     $('.removeRow').click(); // LIMPIAR FILAS
     $('.removeRowPagoTipo').click(); // LIMPIAR FILAS TIPO PAGO
     $('.error').text(" ");
     addNewRow() // 
     addNewRowPago()
  })

	
});