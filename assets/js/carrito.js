$(document).ready(function(){

	/* Obtiene los detalles de los productos del carrito */
	function getProductosBD(){
		$.post('?url=catalogo',{mostrarCatalogo: ''}, function(res){
				let productos = JSON.parse(res).reduce((acc, curr) => {
					acc[curr.cod_producto] = curr;
					return acc;
				}, {});
	            localStorage.setItem('productos', JSON.stringify(productos));
			})
	}

	getPrecioDolar();
	async function getPrecioDolar(){
		await $.post('?url=carrito',{precioDolar: ''}, function(res){
				let dolar = JSON.parse(res)[0]
	            localStorage.setItem('dolar', dolar.cambio);
			})
	}

	let params = new URLSearchParams(document.location.search);
	if(params.get("url") === "carrito") getProductosBD();
	
	const getDolar = () => Number(localStorage.getItem('dolar'))

	/* Para la manipulacion de los detalles del producto en localStorage */
	const getProductos = () => JSON.parse(localStorage.getItem('productos'));
	const getProdDetalle = (id) => { let prod = getProductos(); return prod[`${id}`] || ""; }

	/* Para la manipulación del carrito en el localStorage */
	const setCar = (prods) => localStorage.setItem('carrito', JSON.stringify(prods));
	const getCar = () => {
		return (localStorage.getItem('carrito') === "") ? "" : JSON.parse(localStorage.getItem('carrito'));
	};
	const insertIntoCar = (id,prod) => {
		let car = getCar();
		car[`${id}`] = prod;
		setCar(car);
	}
	const updateProd = (id, cantidad) => {
		let car = getCar();
		car[`${id}`].cantidad = cantidad;
		setCar(car);
	}
	const deleteProd = (id) => {
		let car = getCar(); 
		delete car[`${id}`];
		setCar(car);
	}
	const deleteCar = () => localStorage.setItem('carrito', []);

	let session = true;

	consultarCarrito().then((res) =>{
		if(res != true) session = false;

		mostrarCarrito();
	})

	/* Fusiona el carrito entregado con el carritoStorage */
	function mergeCarts(carrito, carritoStorage){

		if(Object.keys(carritoStorage).length === 0){
			setCar(carrito);
			carritoStorage = getCar();
		}

		if(JSON.stringify(carritoStorage) === JSON.stringify(carrito)) return;

		if(Object.keys(carritoStorage).length >= 1){
			Object.entries(carrito).forEach((prod) => {
				if (carritoStorage[prod[1].cod_producto]) {
					if(carritoStorage[prod[1].cod_producto].cantidad != prod[1].cantidad){
						carritoStorage[prod[1].cod_producto].cantidad = parseInt(carritoStorage[prod[1].cod_producto].cantidad) + parseInt(prod[1].cantidad);
					}
				} else {
					carritoStorage[prod[1].cod_producto] = prod[1];
				}
			});
			setCar(carritoStorage);
		}
	}

	/* Consulta la sesión y el carrito registrado en la BD */
	async function consultarCarrito(){
		let res;
		let productosCarrito = [];
		if('carrito' in localStorage){
			productosCarrito = getCar();
		}else{
			localStorage.setItem('carrito', []);
		}

		await $.ajax({method: 'POST',dataType: 'json',url: '?url=carrito',
			data: {consultarCarrito:''},
			success(response){
				if(response.resultado != 'ok'){
					res = false;
					return;
				}

				res = true;

				if(response.carrito.length < 1){
					if(Object.keys(productosCarrito).length >= 1){
						añadirCarrito(productosCarrito, true);
					}
					return;
				}

				if(response.carrito.length >= 1){
					añadirCarrito(response.carrito);
				}
			},
			error(response){
				res = false;
				throw new Error("Ha ocurrido un error al consultar carrito: "+response);
			}
		})
		return res;
	}
	
	function mostrarCarrito(){
		carrito = Object.entries(getCar());
		let div = '';
		if(carrito.length === 0){
			div = `<div class="alert alert-secondary w-75" role="alert">
						<h3>Su carrito está vacío.</h3>
		            </div>`;
		    $('.carrito-container').html(div);
		    $('.regresar').parent().toggleClass('col-6')
			$('.cardTotal').hide();
			$('.vaciar').hide();
		}
		let productos = [];

		if(carrito.length > 0){
			let precioTotal = 0;
			let flexDirection = ($('.carrito-container').width() < 400) ? 'item-carrito-tienda' : '';
			carrito.forEach((row, i) => {
				let prod = getProdDetalle(row[1].cod_producto);
					prod.cantidad = row[1].cantidad;
				let precioUnidadTotal = parseFloat(prod.p_venta) * parseFloat(prod.cantidad);
				precioTotal += precioUnidadTotal;
				let hr = (i == carrito.length - 1) ? '' : '<hr class="my-2">';
				let img = (prod.img == null) ? '' : prod.img;
				let precio_dolar = (Number(prod.p_venta) / getDolar()).toFixed(2);
				div += `
				<div class="item-carrito ${flexDirection} p-2">
	                <img class="" src="${img}">
	                <div class="descripcion ">
	                  <h3>${prod.nombre}</h3>
	                  <p>${prod.descripcion}</p>
	                  <div class="opciones position-relative">
	                    <input type="number" id="${prod.cod_producto}" value="${prod.cantidad}" class="form-control cantidad" placeholder="Cant.">
	                    <a class="eliminar" prod="${prod.cod_producto}" >Eliminar</a>
	                    <div class="invalid-tooltip">Cantidad no disponible.</div>
	                  </div>
	                </div>
	                <div class="precio">
	                  <h6 class="text-muted fs-7">C/U: </br> Bs. <span class="precioUnidad">${prod.p_venta}</span> </br> <span>$. ${precio_dolar}</span></h6>
	                  <h6 class="fs-6">Total: Bs. <span class="precioUnidadTotal">${precioUnidadTotal}</span></h6>
	                </div>
	            </div>
	            ${hr}
	            `
	            productos.push({cod_producto: prod.cod_producto, cantidad: prod.cantidad});		
			})
			
			actualizarBadge(productos.length);
			$('.carrito-container').html(div);
			actualizarTotalCarrito();
			validarStock(productos);
			editarCantidad();
			confirmarEliminar();
		}

	}

	async function validarStock(productos, tooltipClass = '.invalid-tooltip', inputParam = false){
		let res;
		await $.ajax({ method: 'POST', url: '?url=carrito', dataType: 'json',
			data: {validar:'', productos},
			success(response){ 
				let resultado = [];
				response.forEach(row => {
					let input = (inputParam === false) ? $(`#${row.cod_producto}`) : inputParam;
					let tooltip = input.closest('.opciones').find(tooltipClass);
					if(row.info.resultado){
						$(`#${row.cod_producto}`).attr("style","border-color: none;")
						tooltip.hide();
					}else{
						$(`#${row.cod_producto}`).attr("style","border-color: red;")
						tooltip.text('Cantidad no disponible.')
						tooltip.show();
					}
					resultado.push(row.info.resultado);
				})
				res = !resultado.includes(false);
			}
		})
		return res;
	}

	let id;
	$(document).on('click','.mostrarCatalogo', function(){id = this.attributes.id_prod.value;})

	async function añadirCarrito(productsAdded, inicio = false){
		let res;
		let carrito;
		let carritoStorage = getCar();	
		if(inicio === false){
			/* El array sacado del json lo covierte en un formato {cod_producto: {info}} */
			carrito = productsAdded.reduce((acc, curr) => {
				acc[curr.cod_producto] = curr;
				return acc;
			}, {});
			mergeCarts(carrito, carritoStorage);
		}
		
		if(session === false){
			res = true
			mergeCarts(carrito, carritoStorage);
		}

		if(session === true){
			carrito = getCar();
			await $.ajax({ url:'?url=carrito', type: 'post', dataType: 'json',
				data: {añadirCarrito:'', productos: carrito},
				success(response){
					res = response.resultado
				},
				error: (e) => {
					deleteProd(productsAdded.cod_producto);
					$('.cerrar').click();
					Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.'});
				}

			})
		}
		return res;
	}

	function actualizarTotalModalProducto(){
		let prod = getProdDetalle(id)
		let input = $('#cantidad_añadir_producto');
		let total = Number(input.val()) * Number(prod.p_venta); 
		$('.precio_bs').html(total);
	}
	/* validaciones para el input de cantidad en el modal de producto */
	let timeoutId, validCantidad;
	$('.cantidad_producto button').click(function(){
		let input = $('#cantidad_añadir_producto');
		let cantidad = Number(input.val());
		if($(this).hasClass('mas')) cantidad++;
		if($(this).hasClass('menos')) cantidad--;
		cantidad = (cantidad < 1) ? 1 : cantidad;
		input.val(cantidad);
		let valid = validarInputCantidadCarrito(input, '.invalid-tooltip-prod');
		clearTimeout(timeoutId);
		timeoutId = setTimeout(function() {
			if(valid){
				validarStock([{cod_producto: id, cantidad: input.val()}], '.invalid-tooltip-prod', input).then((res) =>{
					if(!res) return;
					validCantidad = true;
					actualizarTotalModalProducto();
				});
			};
		}, 700);
	})
	$('#cantidad_añadir_producto').on('keyup', function(){
		let input = $(this);
		let valid = validarInputCantidadCarrito(input, '.invalid-tooltip-prod');
		clearTimeout(timeoutId);
		timeoutId = setTimeout(function() {
			if(valid){
				validarStock([{cod_producto: id, cantidad: input.val()}], '.invalid-tooltip-prod', input).then((res) =>{
					if(!res) return;
					validCantidad = true;
					actualizarTotalModalProducto();
				});
			};
		}, 700);
	})

	/* Añadir productos */
	$('#añadir_al_carrito').click(function(){
		let input = $('#cantidad_añadir_producto');
		let cantidad = Number(input.val());
		validCantidad = validarInputCantidadCarrito(input, '.invalid-tooltip-prod')
		if(validCantidad != true) return;

		let prod = [{cod_producto: id, cantidad}]
		validarStock(prod, '.invalid-tooltip-prod', input).then((res) => {
			if(!res) return;
			actualizarTotalModalProducto();
			añadirCarrito(prod).then((res) => {
				if(res === true){
					$('.cerrar').click();
					mostrarCarrito();
					Toast.fire({ icon: 'success', title: 'Se ha añadido el producto al carrito.'});
				}else{
					$('.cerrar').click();
					Toast.fire({ icon: 'error', title: res.msg});
					return;
				}
			})
			
		})

	})

	/* Actualiza el total del carrito completo */
	function actualizarTotalCarrito(){
		let total = 0;
		$('.carrito-container').find('.precioUnidadTotal').each(function(){
			let precio = Number($(this).text());
			total += precio;
		})
		let total_dolar = (Number(total) / getDolar()).toFixed(2);
		$('#precioTotal').html(total);
		$('#precioDolar').html(total_dolar);
	}

	/* Edita la cantidad de los productos ya agregados al carrito */
	let precioUnidad, cantidadProducto, precioUnidadTotal, input;
	function editarCantidad(){
		let timeoutId;
		$('.opciones .cantidad').on('keyup change',function(){
			clearTimeout(timeoutId);
			input = $(this);
			let {id: cod_producto, value: cantidad} = this;
			let productoEditado = [{cod_producto, cantidad}];
			timeoutId = setTimeout(function() {
				if(validarInputCantidadCarrito(input) != true) return;
				validarStock(productoEditado).then((res) => {
					if(!res) return;

					if(session === false){
						updateProd(cod_producto, cantidad);
						let { p_venta } = getProdDetalle(cod_producto);
						precioUnidadTotal = parseFloat(p_venta) * parseInt(cantidad);
						input.closest('.item-carrito').find('.precioUnidadTotal').html(precioUnidadTotal);
						input.closest('.item-carrito').find('.precioUnidad').html(precioUnidad);
						actualizarTotalCarrito()
						return;
					}

					$.post('?url=carrito',{editar:'', cod_producto, cantidad}, function(response){
						let result = JSON.parse(response);

						if(!result.resultado){
							Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
						}else{
							updateProd(cod_producto, cantidad);
							precioUnidad = Number(result.info.precioActual);
							cantidadProducto = Number(result.info.cantidad);
							precioUnidadTotal = precioUnidad * cantidadProducto;
							input.closest('.item-carrito').find('.precioUnidadTotal').html(precioUnidadTotal);
							input.closest('.item-carrito').find('.precioUnidad').html(precioUnidad);
							actualizarTotalCarrito()
						}
					}).fail(()=>{
						Toast.fire({ icon: 'error', title: 'Ha ocurrido un error al editar la cantidad.' });
						throw new Error("Ha ocurrido un error al editar la cantidad.");
					})

				})
			}, 700);
		})
	}

	/* Actualiza el número del carrito del nav */
	function actualizarBadge($cantidad){
		badge = $('#carrito_badge');
		badge.html($cantidad)
	}

	/* Abre modal para confimar el eliminar */
	function confirmarEliminar(){
		$('.opciones .eliminar').on('click', function(e){
			e.preventDefault();
			let prodTitle = $(this).closest('.descripcion').find('h3').text();
			$('#delProductTitle').html(prodTitle);
			$('#delModal').modal('show')

			id = $(this).attr('prod');
		})
	}

	/* Elimina productos del carrito en localStorage y BD */
	$('#delProductFromCar').click(function(){
		if(session === false){
			deleteProd(id);
			Toast.fire({ icon: 'success', title: 'Producto eliminado del carrito.' })
			$('.carrito-container').empty();
			$('#delModal').modal('hide');
			mostrarCarrito();
			return;
		}

		$.ajax({type : 'post', url : '?url=carrito', data : {eliminar:'', id}, dataType: 'json',
			success(data){
				if(data.resultado === true){
					deleteProd(id);
					Toast.fire({ icon: 'success', title: 'Producto eliminado del carrito.' })
					$('.carrito-container').empty();
					$('#delModal').modal('hide');
					mostrarCarrito();
				}else{
					Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' })
					$('#delModal').modal('hide');
				}
			},
			error(){
				Toast.fire({ icon: 'error', title: 'Ha ocurrido un error al eliminar producto.' });
				throw new Error("Ha ocurrido un error al eliminar producto.");
			}
		})

	})

	/* Abre modal confirmar el vaciar carrito */
	$('.vaciar').click(function(e){
		e.preventDefault();
		$('#vaciarCarritoModal').modal('show');
	})
	/* Vaciar carrito */
	$('#vaciarCarritoConfirm').click(() => {

		if(session === false){
			deleteCar();
			$('#vaciarCarritoModal').modal('hide');
			$('.carrito-container').empty();
			Toast.fire({ icon: 'success', title: 'Se ha vaciado su carrito, correctamente.'});
			mostrarCarrito();
			return;
		}

		$.post('?url=carrito', {vaciarCarrito: ''}, function(response){
			let res = JSON.parse(response);
			if(res.resultado){
				deleteCar();
				$('#vaciarCarritoModal').modal('hide');
				$('.carrito-container').empty();
				Toast.fire({ icon: 'success', title: 'Se ha vaciado su carrito, correctamente.'});
				mostrarCarrito();
			}else{
				$('#vaciarCarritoModal').modal('hide');
				Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.'});
			}
		})
	})

	/* Envia a facturacion */
	$('#realizarFacturacion').click(function(){
		if(session === false){
			Toast.fire({ icon: 'error', title: 'Necesita iniciar sesión.'});
			setTimeout(function(){
				window.location = '?url=login'
			}, 1600);
			return;
		}
		let productos = [];
		$('.cantidad').each(function(){
			let input = $(this);
			if(validarInputCantidadCarrito(input) != true) throw new Error('Input inválido.');
			let {id: cod_producto, value: cantidad} = this;
			productos.push({cod_producto, cantidad});
		})
		validarStock(productos).then(res => {
			if(!res) return;
			window.location = "?url=pago";
		});
	})


})