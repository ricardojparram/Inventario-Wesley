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
            	<td>${row.ced_prove}</td>
            	<td><button class="btn btn-registrar detalleCompra" id="${row.orden_compra}" data-bs-toggle="modal" data-bs-target="#detalleCompra">Ver Detalles</button></td>
            	<td>${row.fecha}</td>
            	<td>${row.monto_total}</td>
            	<td class="d-flex justify-content-center">
            	
            	<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.orden_compra}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
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
					<td>${row.producto}</td>
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

	selectProducto();
	function selectProducto(){
		$.ajax({
			url:'',
			type: 'post',
			dataType:'json',
			data:{
				select:'no'
			},
			success(data){
				let option = ""
				data.forEach((row)=>{
					option += `<option value="${row.cod_producto}">${row.producto}</option>`;
				})
				$('.select-productos').each(function(){
					if(this.children.length ==1){
						$(this).append(option)
						$(this).chosen({
							width: '100%',
							no_results_text: 'No hay resultados para',
							placenholder_text_single:"selecciona producto",
						});
					
					}
				})
			}
			
		})
	}
	calculate();
	function calculate(){

		let total_price = 0;

		$('.table-body tbody tr').each( function(){
			let row = $(this),
			precio   = row.find('.precio input').val(),
			cantidad = row.find('.cantidad input').val();

			let sum = precio * cantidad;

			total_price = total_price + sum;

			row.find('.total').text( sum.toFixed(2) );  

		});
		let total = (total_price).toFixed(2);
        //let cambio = (precioTotal / moneda).toFixed(2);
    	// if(isNaN(cambio) || moneda == 0){
    	// 	cambio = "0"
    	// }

		$('#montos').text(`Total: ${total}`)
		//$('#cambio').text(`Al cambio: ${cambio}`)
		$('#monto').val(total)

	}

	$('#ASD').on('keyup', 'input', function(){
		calculate();
	})

	const newRow = `<tr>
					<td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
					<td width='20%'> 
					<select class="select-productos select-asd" name="productos">
						<option></option>
					</select>
					</td>
					<td width='10%' class="lote"><input class="select-asd" type="number" value=""/></td>
					<td width='10%' class="cantidad"><input class="select-asd" type="number" value=""/></td>
					<td width='10%' class="precio"><input class="select-asd" type="number" value="" /></td>
					<td width='10%'class="vencimiento"><input class="select-asd" type='text' /></td>
					<td width='10%' class="total"></td>
				</tr>`;

	$('.vencimiento input').inputmask('fecha');
	$('.cantidad input').inputmask('cantidad');

	function validarValores(){
		$('.cantidad input').keyup(function(){validarCantidad($(this)) });
		$('.precio input').keyup(function(){validarPrecio($(this)) });
		$('.lote input').keyup(function(){validarLote($(this)) });
		$('.monto input').keyup(function(){validarMonto($(this)) });

	}
	validarValores();
		
	function validarCantidad(input){
		let valor = input.val();
		if(valor <= 0 || isNaN(valor)){
			$('#error').text('Cantidad invalido.');
			input.css({'border': 'solid 2px', 'border-color':'red'})
			input.attr('valid','false')
			return false
		}else{
			$('#error').text('');
			input.css({'border':'none'});
			input.attr('valid','true');
			return true;
		}
	}

	function validarPrecio(input){
		let valor = input.val();
		if(valor <= 0 || isNaN(valor)){
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

	function validarLote(input){
		let valor = input.val();
		if(valor <= 0 || isNaN(valor)){
			$('#error').text('Lote invalido.');
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

	const validFecha = () => {
        let validacion = [];
        $("input.select-asd").each(function () {
            if (this.value === "" || this.value === null) {
                $(this).addClass('input-error')
                validacion.push(false);
            } else if ($(this).hasClass('vencimiento')) {
                validarFecha($(this), $('#error'), 'Error de fecha,');
            } else {
                $(this).removeClass('input-error')
                validacion.push(true);
            }
        })
        return !validacion.includes(false);
    }

	function validarMonto(input){
		let valor = input.val();
		if(valor <= 0 || isNaN(valor)){
			$('#error').text('Monto invalido.');
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

	let productosRepetidos, productos;
    const validarProductosRepetidos = (status = true) => {
        let validacion = [];
        let $select = document.querySelectorAll('.select-productos');
        if ($select.length < 1) {
            $('.filaProductos').html('No hay filas.');
            return false
        } else {
            $('.filaProductos').html('');
        }
        productos = Object.values(document.querySelectorAll('.select-productos')).map(item => {
            return item.value;
        });
        productosRepetidos = productos.filter((elemento, index) => productos.indexOf(elemento) !== index);
        $(".select-productos").each(function () {
            if (this.value === "" || this.value === null) {
                if (status != true) {
                    $(this).closest('td').find('div.chosen-container').addClass('select-error')
                }
                validacion.push(false);
            } else if (productosRepetidos.includes(this.value)) {
                $(this).closest('td').find('div.chosen-container').addClass('select-error')
                validacion.push(false);
            } else {
                $(this).closest('td').find('div.chosen-container').removeClass('select-error')
                validacion.push(true);
            }
        })
        return !validacion.includes(false);
    }
    
	function filaN(){
		$('#ASD').append(newRow);
		selectProducto();
		validarProductosRepetidos();
		validarValores();
		$('.vencimiento input').inputmask('fecha')
		$('.cantidad input').inputmask('cantidad')		
	}

	$('.newRow').on('click',function(e){
		filaN()
	});

	$('body').on('click','.removeRow',function(e){
		$(this).closest('tr').remove();
		validarProductosRepetidos();
		calculate();
	});

	$(document).on('change','.select-productos',function(){
		validarProductosRepetidos();
	});

	const getProductos = () => {
        return Object.values(document.querySelectorAll('.select-productos')).map(item => {
			let lote = $(item).closest('tr').find('.lote input').val();
			let cantidad = $(item).closest('tr').find('.cantidad input').val();
			let precio = $(item).closest('tr').find('.precio input').val();
            let fecha_vencimiento = $(item).closest('tr').find('.vencimiento input').val();
			
            return { id_producto: item.value, lote, cantidad, precio ,fecha_vencimiento }; 
        });
    }

	$('#proveedor').change(()=>{ validarSelect($('#proveedor'),$("#error1"),"Error de proveedor")})
	$('#orden').keyup(()=>{ validarNumero($('#orden'), $('#error2'), "Error de Orden,")})
	$('#fecha').change(()=>{ validarFecha($('#fecha'), $('#error3'), "Error de Fecha,") })
	

	$('#registrar').click((e)=>{
		e.preventDefault();

		let proveedor =  validarSelect($('#proveedor'),$("#error1"),"Error de proveedor");
		let orden = validarNumero($('#orden'), $('#error2'), "Error de Orden,");
		let fecha =  validarFecha($('#fecha'), $('#error3'), "Error de Fecha,");
		let monto = validarNumero($('#monto'), $('#error4') , 'Error de monto');

		let validarProductos = validarProductosRepetidos(false);
		let validFillFecha = validFecha();
		
		$('.lote input').each(function(){validarLote($(this)) });
		$('.precio input').each(function(){validarPrecio($(this)) });
		$('.cantidad input').each(function(){validarCantidad($(this)) });

		let lote = true, precio = true, cantidad = true;

		if($('.lote input').is('[valid = "false"]')){
			$('#error').text('Lote invalido.');
			lote = false;
		}
		if($('.precio input').is('[valid = "false"]')){
			$('#error').text('Precio invalido');
			precio = false;
		}
		if($('.cantidad input').is('[valid= "false"]')){
			$('#error').text('Cantidad invalida.');
			cantidad = false;
		}

		if(proveedor && orden && fecha && validarProductos && lote && precio && cantidad && validFillFecha && monto){
			
			let proveedor = $('#proveedor').val();
			let orden = $('#orden').val();
			let fecha = $('#fecha').val();
			let monto = $('#monto').val();
			let productos = getProductos();
			

			$.ajax({
				type: "POST",
				url:"",
				dataType:"json",
				data:{
					proveedor,
					orden,
					fecha,
					monto,
				    productos
				},
				success(data){
					if(data.resultado === 'Registrado con exito'){
						tablaMostrar.destroy();
						rellenar();
						$('#agregarform').trigger('reset');
						$('.cerrar').click();
						$('.removeRow').click(); 
						fechaHoy($('#fecha'));
						Toast.fire({ icon: 'success', title: 'Compra registrada' })
						filaN()
					}else if(data.resultado === "Error de orden"){
						$("#error").text(data.error);
						$("#orden").attr("style","border-color:red;")
						$("#orden").attr("style","border-color:red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
						throw new Error('Orden de compra repetida.');
					}
				}

			}).fail((e)=>{
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
				throw new Error(e.responseJSON.msg);
			})
			
		}
	})
	
	let id;
	$(document).on('click', '.borrar',function (){
		id = this.id;
	})
	$("#eliminar").click((e)=>{
		e.preventDefault();
		$.ajax({
			type:"POST",
			url:'',
			dataType:'json',
			data:{
				borrar:'cualquiera',
				id

			},
			success(data){
				mostrar.destroy();
				$(".cerrar").click();
				Toast.fire({icon: 'success', title:'compra eliminada'})
				rellenar();
			}
		})
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



