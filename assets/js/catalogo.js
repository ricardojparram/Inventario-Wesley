$(document).ready(function(){

	async function getProductosBD(){
		await $.post('',{mostrarCatalogo: ''}, function(res){
				let productos = JSON.parse(res).reduce((acc, curr) => {
					acc[curr.cod_producto] = curr;
					return acc;
				}, {});
	            localStorage.setItem('productos', JSON.stringify(productos));
			})
	}
	const getProductos = () => JSON.parse(localStorage.getItem('productos'));
	const getProdDetalle = (id) => {let prod = getProductos(); return prod[`${id}`] }	

	const getDolar = () => Number(localStorage.getItem('dolar'))

	getProductosBD().then(() => {
		mostrarCategorias().then(() => {
			validarCategoria();
			$(".categorias_check").click(function(){ filtrarProductos(this.id) })
		})
		mostrarCatalogo(getProductos())
	})


	function mostrarCategorias(){
		return $.ajax({type: 'POST',url: '',dataType: 'json',data:{mostrarCategorias: ''},
			success(res){
				let categorias = `
				<span>
					<input type="radio" class="btn-check categorias_check" name="options-outlined" id="todas_las_categorias">
					<label class="btn btn-sm btn-outline-dark mb-1" for="todas_las_categorias">Todos</label>
				</span>
				`;
				res.forEach((categoria) => {
					categorias += `
					<span>
						<input type="radio" class="btn-check categorias_check" name="options-outlined" id="${categoria.des_clase}">
						<label class="btn btn-sm btn-outline-dark mb-1" for="${categoria.des_clase}">${categoria.des_clase}</label>
					</span>
					`
				})
				$('.categorias_catalogo').html(categorias);
			}
		})
	}

	function mostrarCatalogo(products){
		let mostrar = '';
		if($.isEmptyObject(products)){
			mostrar +=`
				<h3 class="text-center">No se han encontrado productos.</h3>
			`
			$('.catalogoProductos').html(mostrar);
			return;
		}
    for(let i in products){
    	let precio_dolar = (Number(products[i].p_venta) / getDolar()).toFixed(2);
     	mostrar += `
        <div class="product-container">
          <div class="card-product position-relative">
            <div class="card-body">
              <div class="text-center position-relative">
                <img src="${products[i].img}" alt="Imagen del producto" class="mb-3 img-fluid">
                <small class="text-warning position-absolute bottom-0 end-0">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-half"></i>
                </small>
              </div>
              <div class="text-small mb-1">
                <a href="#!" class="text-decoration-none text-muted" tabindex="0"><small>${products[i].des_clase}</small></a>
              </div>
              <h2 class="fs-6">${products[i].nombre}</h2>
              <!-- rating -->

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                  <span class="text-dark">Bs. ${products[i].p_venta}</span><span class="text-muted"> ${precio_dolar}$</span>
                </div>
                <!-- btn -->
                <button id_prod="${products[i].cod_producto}" class="btn btn-sm btn-success text-white mostrarCatalogo" data-bs-toggle="modal" data-bs-target="#AñadirCarritoModal">
                  <strong>Comprar </strong><i class="fs-7 text-white bi bi-cart-plus-fill"></i> 
                </button>
              </div>
            </div>
          </div>
        </div>
     	`;
    }
    $('.catalogoProductos').html(mostrar);
	}


	let id, producto;

	$(document).on('click' , '.mostrarCatalogo' , function(){
		id = this.attributes.id_prod.value;
		let producto = getProdDetalle(id);
		let precio_dolar = (Number(producto.p_venta) / getDolar()).toFixed(2);

		$('.tipo_medicamento').html(producto.des_clase).attr('href', `?url=catalogo&filtro=${producto.des_clase}`)
		$('.nombre_medicamento').html(producto.nombre);
		$('.descripcion_medicamento').html(producto.descripcion);
		$('.precio_bs').html(producto.p_venta);
		$('.precio_dolar').html(precio_dolar);
		$('.producto_imagen_modal').attr('src', producto.img);
		$('.codigo_producto').html(producto.cod_producto);
		$('.tipo_producto').html(producto.des_clase);
		$('.contraindicaciones').html(producto.contraindicaciones);

	})

	let params = new URLSearchParams(document.location.search)
	function filtrarProductos(filtro){
		if(filtro === "todas_las_categorias"){
			params.delete("filtro")
		}else{
			params.set('filtro', filtro)
		}
		window.history.pushState(null, "", `?${params.toString()}`);
		let products = getProductos();

    if(filtro === "todas_las_categorias") return mostrarCatalogo(products);

		let busqueda = normalizar(filtro.toUpperCase());

		let result = Object.fromEntries(Object.entries(products).filter(row => { 
			return (
				normalizar(row[1].nombre).toUpperCase().indexOf(busqueda) > -1 ||
				normalizar(row[1].des_clase).toUpperCase().indexOf(busqueda) > -1  
			)
		}));
		mostrarCatalogo(result)
	}
	
	// Quita tildes de las palabras. ej: Acetominafén => Acetominafen
	function normalizar(str){
		return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	}
	
	function validarCategoria(){ 
		let tipo_catalogo = params.get("filtro");

		if(tipo_catalogo == null || tipo_catalogo == ""){
			$('#todas_las_categorias').attr("checked", "");
			return filtrarProductos("todas_las_categorias");
		}

		filtrarProductos(tipo_catalogo)

		if(document.getElementById(tipo_catalogo) == null){
			$('.buscarProducto').val(tipo_catalogo);
			return;
		}
		$(`#${tipo_catalogo}`).attr("checked", "");
		
	}

	$('.buscarProducto').on('keyup', function(){
		filtrarProductos(this.value)
	})


})
