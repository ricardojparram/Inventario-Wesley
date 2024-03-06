$(document).ready(function() {


	fechaHoy($('#fecha'));

	let tablaMostrar;
	let permiso , eliminarPermiso, registrarPermiso;

	$.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
		success(data){ permiso = data; }

	}).then(function(){
		rellenar(true);
		registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
		$('#agregarModal').attr(registrarPermiso, '');
	})

	function rellenar(bitacora = false){ 
		$.ajax({
			method: "post",
			url: "",
			dataType: "json",
			data: {mostrar: "compras" , bitacora},
			success(data){
            let tabla;
            data.forEach(row =>{
            	eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';
            	tabla += `
            	<tr>
            	<td>${row.orden_compra}</td>
            	<td>${row.razon_social}</td>
            	<td><button class="btn btn-success detalleCompra" id="${row.cod_compra}" data-bs-toggle="modal" data-bs-target="#detalleCompra">Ver Detalles</button></td>
            	<td>${row.fecha}</td>
            	<td>${row.total_divisa}</td>
            	<td>${row.total}</td>
            	<td class="d-flex justify-content-center">
            	
            	<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.cod_compra}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
            	</td>
            	</tr>
            	`
            })
            $('#tbody').html(tabla);
            tablaMostrar = $('#tableMostrar').DataTable({
            	resposive : true
            })
			}
		})

	}
	selectMoneda();
	function selectMoneda(){
		$.ajax({
			url: '',
			method: 'POST',
			dataType: 'json',
			data: {
				selectM : 'moneda'
			},
			success(data){
				let option = ""
				data.forEach((row)=>{
					let alcambio = row.cambio;
					if(row.cambio == 0) alcambio = "" 
						option += `<option id="${alcambio}" value="${row.id_cambio}">${row.nombre} ${alcambio}</option>`
				})
				$('#moneda').each(function(){
					if(this.children.length == 1){
						$(this).append(option);

					}
				})
			}
		})
	}

	let click = 0;
	setInterval(()=>{ click = 0; }, 2000); 

	$(document).on('click', '.detalleCompra', function() {
		if(click >= 1) throw new Error('Spam clicks');
		id = this.id; 
		$.post('', {detalleCompra: 'xd', id}, function(data){
			let lista = JSON.parse(data);
			let tabla;
			lista.forEach(row => {
				tabla += `
				<tr>
					<td>${row.descripcion}</td>
					<td>${row.cantidad}</td>
					<td>${row.precio_compra}</td>                      
				</tr>
				`
			})
			$('#compraNombre').text(`Orden de compra #${lista[0].orden_compra}.`);
			$('#bodyDetalle').html(tabla);
		})
		click++;
	});

    var iva = parseFloat($('#config_iva').val());

	let money = 0;
	$('#moneda').on("change", function(){
		money = $(this).children(":selected").attr("id");
		calculate();
	})
	calculate();
	selectOptions();

	function calculate(){

		let total_price = 0,
		total_tax = 0;

		$('.table-body tbody tr').each( function(){
			let row = $(this),
			rate   = row.find('.rate input').val(),
			amount = row.find('.amount input').val();

			let sum = rate * amount;
			let tax = ("0."+iva)*sum;


			total_price = total_price + sum;
			total_tax = total_tax + tax;

			row.find('.sum').text( sum.toFixed(2) );
			row.find('.tax').text( tax.toFixed(2) );   

		});
		let precioTotal = Math.abs((total_price + total_tax).toFixed(2));
		let ivatotal = Math.abs(total_tax.toFixed(2));
		let total = total_price.toFixed(2);
		let cambio = (precioTotal / money).toFixed(2);
		if(isNaN(cambio) || money == 0){
			cambio = "0";
		}

		$('#montos').text(`IVA: ${ivatotal} - Total: ${total}`)
		$('#montos2').text(`Total + IVA: ${precioTotal}`)
		$('#cambio').text(`Al cambio: ${cambio}`)
		$('#monto').val(precioTotal)

	}

	function selectOptions(){
		$.ajax({
			url:'',
			type: 'post',
			dataType: 'json',
			data: {
				select: 'xd'
			},
			success(data){
				let option = ""
				data.forEach((row)=>{
					option += `<option value="${row.cod_producto}">${row.descripcion}</option>`;
				})
				$('.select-productos').each(function(){
					if(this.children.length == 1){
						$(this).append(option)
						$(this).chosen({
							width: '100%',
							no_results_text: 'No hay resultados para',
							placeholder_text_single: "Selecciona producto",
							allow_single_deselect: true,
						});

						calculate()
					}
				})

			}
		})
	}

	let producto;
	let select;

	cambio();
	function cambio(){
		$('.select-productos').change(function(){
			select = $(this);
			producto  = $(this).val();
			fillData($(this).val());
		})
	}

	function fillData(val){
		$.getJSON('', {producto, fill: 'data'}, function(data){
			if(producto == val){
				let cantidad = select.closest('tr').find('.amount input');
				let precio = select.closest('tr').find('.rate input');
				cantidad.val(data[0].stock);
				precio.val(data[0].p_venta);
				calculate()
			}
		})	
	}

	let newRow = `<tr>
					<td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
					<td width='30%'> 
					<select class="select-productos select-asd">
						<option></option>
					</select>
					</td>
					<td width='10%' class="amount"><input class="select-asd" type="number" value=""/></td>
					<td width='10%' class="rate"><input class="select-asd" type="number" value="" /></td>
					<td width='10%'class="tax"></td>
					<td width='10%' class="sum"></td>
				  </tr>`;


	function validarValores(){
		$('.amount input').keyup(function(){ validarStock($(this)) });
		$('.rate input').keyup(function(){ validarPrecio($(this)) });
	}
	validarValores();
	function filaN(){
		$('#ASD').append(newRow);
		selectOptions();
		cambio();
		validarValores();
	}
	$('.newRow').on('click',function(e){
		filaN()
	});

	$('body').on('click','.removeRow',function(e){
		$(this).closest('tr').remove();
		calculate();
	});

	$('.table-body').on('keyup','input',function(){
		calculate();
	});

	$('#config_iva').on('keyup',function(){
		iva = parseFloat($(this).val());

		if (iva < 0 || iva > 100 || isNaN(iva)){
			iva = 0;
		}
		calculate();
	});

	
	function validarStock(input){
		
		let valor = Math.abs(input.val());
		if(valor == 0 || isNaN(valor)){
			$('#error').text('Cantidad inválida.');
			input.css({'border': 'solid 2px', 'border-color':'red'})
			input.attr('valid','false')
			return false
		}else{
			$('#error').text('');
			input.css({'border': 'none'});
			input.attr('valid','true');
			return true;
		}

	}
	function validarPrecio(input){
		let valor = Math.abs(input.val());
		if(valor == 0 || isNaN(valor)){
			$('#error').text('Precio inválido.');
			input.css({'border': 'solid 2px', 'border-color':'red'})
			input.attr('valid','false')
			return false;
		}else{
			$('#error').text('');
			input.css({'border': 'none'});
			input.attr('valid','true');
			return true;
		}
	}

	function validarOrden(input, div){
		$.post('',{orden : Math.abs(input.val()), validar: "orden"}, function(data){
			let mensaje = JSON.parse(data);
			if(mensaje.resultado === "Error de orden"){
				div.text(mensaje.error);
				input.attr("style","border-color: red;")
				input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
			}
		})
	}

	$('#orden').keyup(()=>{	let valid = validarNumero($('#orden'), $('#error'), "Error de Orden,");
		if(valid){
			validarOrden($('#orden'), $('#error'));
		}
	})
	let vmoneda = false;
	$('#moneda').change(function(){
      vmoneda =  validarSelect($('#moneda'),$("#error5"),"Error de moneda")
    })

	$('#registrar').click((e)=>{
		e.preventDefault()

		if(click >= 1) throw new Error('Spam de clicks');

		let vorden, vproductos, vstock = true, vprecio = true;
		vorden = validarNumero($('#orden'), $('#error'), "Error de Orden,");
		vmoneda = validarSelect($('#moneda'),$("#error5"),"Error de moneda");
		$('.amount input').each(function(){ validarStock($(this)) });
		$('.rate input').each(function(){ validarPrecio($(this)) });
		
		if($('.amount input').is('[valid="false"]')){
			$('#error').text('Cantidad inválida.');
			vstock = false;
		}
		if($('.rate input').is('[valid="false"]')){
			$('#error').text('Precio inválido.');
			vprecio = false;
		}



		$('.table-body tbody tr').each(function(){
			let producto = $(this).find('.select-productos').val();
			if(producto == "" || producto == null){
				vproductos = false;
				$('#error').text('No debe haber productos vacíos.');
			}else{
				$('#error').text('');
				vproductos = true;
			}
		})

		if(vorden && vproductos && vstock && vprecio && vmoneda){

			$.post('',{
				proveedor : $('#proveedor').val(),
				orden : $('#orden').val(),
				fecha : $('#fecha').val(),
				montoT : $('#monto').val(),
				cambio : $('#moneda').val()
			},
			function(response){
				let data = JSON.parse(response);

				if(data.resultado === "Error de orden"){
					$('#error').text(data.error);
					$('#orden').attr("style","border-color: red;")
					$('#orden').attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
					throw new Error('Orden de compra repetida.');
				}

				if(typeof data.id != 'undefined'){
					enviarProductos(data.id);
				}

				tablaMostrar.destroy();
				rellenar();
				$('#agregarform').trigger('reset');
				$('.cerrar').click();
				$('.removeRow').click(); 
				fechaHoy($('#fecha'));
				Toast.fire({ icon: 'success', title: 'Compra registrada' })
				filaN()
			})

		}
		click++;
	})

	function enviarProductos(id){
		$('.table-body tbody tr').each(function(){
			let producto = $(this).find('.select-productos').val();
			let cantidad = Math.abs($(this).find('.amount input').val());
			let precio = Math.abs($(this).find('.rate input').val());

			$.post('',{cantidad, precio, producto, id})

		})
	}

	$(document).on('click', '.borrar', function() {
    	id = this.id;
    });

	$('#borrar').click(()=>{

		if(click >= 1) throw new Error('Spam de clicks');

		$.ajax({
			type : 'post',
			url : '',
			data : {eliminar : 'asd', id},
			success(data){
				tablaMostrar.destroy();
				$('.cerrar').click();
				Toast.fire({ icon: 'success', title: 'Compra eliminada' })
				rellenar();
			}
		})
		click++;
	})

	$('#cancelar').click(()=>{
		$('#agregarform').trigger('reset');
		$('.removeRow').click(); 
		$('#Agregar input').attr("style","border-color: none; background-image: none;")
		$('#Agregar select').attr("style","border-color: none; background-image: none;")
		$('#error').text('');
		filaN()
		fechaHoy($('#fecha'));
	})


});



