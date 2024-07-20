$(document).ready(function(){

	mostrarCatalogo()
	const getProductos = () => JSON.parse(localStorage.getItem('productos'));
	const getProdDetalle = (id) => {let prod = getProductos(); return prod[`${id}`] }

	const getDolar = () => Number(localStorage.getItem('dolar'))

	function mostrarCatalogo(){
		$.ajax({type: 'POST',url: '',dataType: 'json',data:{mostraC: ''},
			success(data){
				let productos = data.reduce((acc, curr) => {
					acc[curr.cod_producto] = curr;
					return acc;
				}, {});
	            localStorage.setItem('productos', JSON.stringify(productos));
				let mostrar = '';
	            data.forEach(row =>{
	            	let precio_dolar = (Number(row.p_venta) / Number(getDolar())).toFixed(2);
	             	mostrar += `
		            <div class="product-container">
		              <div class="card-product position-relative">
		                <div class="card-body">
		                  <div class="text-center position-relative">
		                    <img src="${row.img}" alt="Imagen del producto" class="mb-3 img-fluid">
		                    <small class="text-warning position-absolute bottom-0 end-0">
		                      <i class="bi bi-star-fill"></i>
		                      <i class="bi bi-star-fill"></i>
		                      <i class="bi bi-star-fill"></i>
		                      <i class="bi bi-star-fill"></i>
		                      <i class="bi bi-star-half"></i>
		                    </small>
		                  </div>
		                  <div class="text-small mb-1">
		                    <a href="#!" class="text-decoration-none text-muted" tabindex="0"><small>${row.des_clase}</small></a>
		                  </div>
		                  <h2 class="fs-6">${row.nombre}</h2>
		                  <!-- rating -->

		                  <div class="d-flex justify-content-between align-items-center mt-3">
		                    <div>
		                      <span class="text-dark">Bs. ${row.p_venta}</span>&nbsp;&nbsp;<span class="text-muted"> ${precio_dolar}$ </span>
		                    </div>
		                    <!-- btn -->
		                    <button id_prod="${row.cod_producto}" class="btn btn-sm btn-success text-white mostrarCatalogo" data-bs-toggle="modal" data-bs-target="#AñadirCarritoModal">
		                      <strong>Comprar </strong><i class="fs-7 text-white bi bi-cart-plus-fill"></i> 
		                    </button>
		                  </div>
		                </div>
		              </div>
		            </div>
	             	`;
	            })
	            $('#catalogo').html(mostrar);
			}
		})
	}

	let id, producto;

	$(document).on('click' , '.mostrarCatalogo' , function(){
		id = this.attributes.id_prod.value;
		let producto = getProdDetalle(id);
		let precio_dolar = (Number(producto.p_venta) / getDolar()).toFixed(2);

		$('.tipo_medicamento').html(producto.des_clase).attr('href', `catalogo&filtro=${producto.des_clase}`)	
		$('.nombre_medicamento').html(producto.nombre);
		$('.descripcion_medicamento').html(producto.descripcion);
		$('.precio_bs').html(producto.p_venta);
		$('.precio_dolar').html(precio_dolar);
		$('.producto_imagen_modal').attr('src', producto.img);
		$('.codigo_producto').html(producto.cod_producto);
		$('.tipo_producto').html(producto.des_clase);
		$('.contraindicaciones').html(producto.contraindicaciones);

	})

	$.ajax({type: 'POST',url: '',dataType: 'json',data:{ mostrarCategorias:''},
		success(res){
			let mostrar = "";
			let contador = 1;
			res.forEach((row, i) => {
				let active = (i === 0) ? 'active' : "";
				let principioSlide = (contador === 1) ? `<div class="carousel-item ${active} pt-2 pb-2">` : "";
				let finalSlide = (contador === 3) ? "</div>" : "";
				mostrar += `
				${principioSlide}
					<div class="categoria-item">
	                    <div class="card-body">
	                      <a href="catalogo&filtro=${row.des_clase}" class="text-dark">
	                        <div class="mt-1 mb-0">
	                          <img src="${row.img}" alt='Imagen de un producto tipo "${row.des_clase}"' class="img-fluid">
	                        </div>
	                        <h2 class="text-center fw-bold">${row.des_clase}</h2>
	                      </a>
	                    </div>
	                </div>
				${finalSlide}
				`
				if(contador % 3 === 0) contador = 0; 
				contador++
			})
			$('#categoriaSlider .carousel-inner').html(mostrar);
			const myCarouselElement = document.querySelector('#categoriaSlider')

			const carousel = new bootstrap.Carousel(myCarouselElement, {
				interval: 2000,
				touch: false
			})
		}
	})


})