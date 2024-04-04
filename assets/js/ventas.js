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

    	$('#montos').text(`Total: ${precioTotal}`)
    	$('#cambio').text(`Total Dolares: ${cambio}`)
    	$('#monto').val(precioTotal)

    }

    //Evento keyup para que funcione calculate()
    $('#ASD').on('keyup','input',function(){
      calculate();
    })
    

    let selectRepetidos, select_t;
    const validarSelectRepetidos = (selected, error, status = true) => {
    	let validacion = [];
    	let $select = document.querySelectorAll(selected);
    	console.log($select.length)
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
    			console.log(this);
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

    // Evento de cambio en los productos 
    $(document).on("change", ".select-tipo", function () {
    	validarSelectRepetidos('.select-tipo' , '.filaTipoPago');
    });


     // ELiminar fila Tipo de Pago
     $('body').on('click','.removeRowPagoTipo', function(e){
        $(this).closest('tr').remove();
        validarSelectRepetidos('.select-tipo' , '.filaTipoPago');
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



	
});