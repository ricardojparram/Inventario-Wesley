<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>¿Necesitas Ayuda?</title>
	<?php $VarComp->header(); ?>
	<link rel="stylesheet" href="assets/css/estiloInterno.css">
</head>
<body>
	<!-- ======= Header ======= -->

	<?php 

	$header->Header();

	?>

	<!-- End Header -->


	<!-- ======= Sidebar ======= -->

	<?php
      
         $menu->Menu();
                
      ?>

	<!-- End Sidebar-->
	<main class="main" id="main">
		<div class="pagetitle">
			<h1>Guia Para El Usuario</h1>
		</div>

		<section class="section">
			<div class="row">

				<div class="col-lg-6">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title">Guia Para el usuario</h5>
							</div>
							<p>
								En este apartado podra algunas de las preguntas que le puedan dar solucion a sus problemas respondiendo con instrucciones de lo que debe hacer en cada uno de los modulos, esto le permitira al usuario una mejor navegacion y utilizacion del sistema 
							</p>

						</div>
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title">Preguntas Frecuntes</h5>
							</div>
							<div>
								<ul>
									<a href="#principal"><li >Pagina Principal</li></a><br>
									<a href="#tablaCrud"><li style="text-decoration: none;">Tablas CRUD´S</li></a><br>
									<a href="#ventaCompras"><li style="text-decoration: none;">Registro de ventas y compras</li></a>
								</ul>
							</div>
							<p id="principal"></p>
						</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title" >Pagina Principal</h5>
							</div>
							<div class="accordion accordion-flush" id="accordionFlushExample">
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expInicio" aria-expanded="false" aria-controls="expInicio">
											¿Que se encuentra aqui?
										</button>
									</h2>
									<div id="expInicio" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En esta seccion encontrara un resumen de lo que se encuentra registrado en el sistema como las ventas registradas hasta el momento, productos disponible en stock y se podra encontra con un grafico que muestra las ventas y compras producidas durante los ultimos 7 dias
											</p>
											<div>
												<img class="col-10" src="assets/img/ResumenCard.png">
											</div>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Ventas" aria-expanded="false" aria-controls="Ventas">
											Recuadros de Ventas y Compras
										</button>
									</h2>
									<div id="Ventas" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En esta pagina se encuantran dos recuadros en la parte superior, estos recuadros muestran la cantidad de ventas y de compras realizadas durante un periodo de tiempo el cual es seleccionables entre "Hoy" que muestra lo registrado entre las 12 de la media noche hasta la hora que se encuentre al momento de precionar la opcion, tambien se encuentra mes y año el cual muestra lo regsitrado entre el primer dia del mes o el primer dia del año hasta la fecha que se encuentre.
											</p>
											<img class="col-6" src="assets/img/CardVentas.png">
										</div>

									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingTwo">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Grafico" aria-expanded="false" aria-controls="Grafico">
											Grafico
										</button>
									</h2>
									<div id="Grafico" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En el grafio puede encontar la canditad de ventas y de compras registradas de los ultimos 7 dias, estos se mostraran con dos colores diferentes, verde para ventas y marron para compras, en la parte inferior del recuadro podra encontar una pequeña leyenda, al presionar sobre el nombre de uno de los dos se ocultara del grafico permitiendo visualizar unicamente la cantidad de ventas o de compras dependiendo de cual haya presionado, este volvera a mostrarse si vuele a presionar el mismo nombre.
											</p>
											<img class="col-12" src="assets/img/Grafico.png">
										</div>
									</div>
								</div>

							</div>
							<p id="tablaCrud"></p>

						</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title">Tablas CRUD´S</h5>
							</div>
							<div class="accordion accordion-flush" id="accordionFlushExample">
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expoTabla" aria-expanded="false" aria-controls="expoTabla">
											¿Que Puedes Hacer?
										</button>
									</h2>
									<div id="expoTabla" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En la mayoria de las paginas encontaras una tablas el cual mostrara informacion con respecto al modulo que te encuentras, por ejemplo en modulo de clientes encontraras los datos de los clientes registrados, en el modulo de productos veras la cantidad de productos registrados con sus respectivos datos, dentro y fuera encontrara botones el cual le permiten realizar acciones como registro, modificacion y eliminacion de datos
											</p>
											<img class="col-12" src="assets/img/TablaCrud.png">
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingTwo">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Registro" aria-expanded="false" aria-controls="Registro">
											Registro de datos 
										</button>
									</h2>
									<div id="Registro" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En la parte superior derecha de la tabla encontraras un boton <img class="col-1" src="assets/img/Agregar.png"> al darle click aparecera un recuadro que te perdira los datos que debes ingresar para realizar el registro, en caso de no saber que datos puedes ingresar, de lado izquierdo de cada input podras ver un icono al presionarlo te dira que datos debes ingresar en es input 
											</p>
											<img class="col-12" src="assets/img/RegisModal.png">
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingThree">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Edit" aria-expanded="false" aria-controls="Edit">
											Edicion de Datos
										</button>
									</h2>
									<div id="Edit" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												Dentro de la tabla al final de cada fila se encuentra un boton de edicion <img class="col-xm-1" src="assets/img/Editar.png"> al darle click aparecera un recuadro que te mostrara los datos que previamente ya habias registrado y podras hacer la modificion que desees 
											</p>
											<img class="col-12" src="assets/img/EditModal.png">
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="Eliminar">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Eliminar" aria-expanded="false" aria-controls="Eliminar">
											Eliminacion de Datos
										</button>
									</h2>
									<div id="Eliminar" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												Dentro de la tabla al final de cada fila se encuentra un boton de borrado <img class="col-xm-1" src="assets/img/Borrar.png"> al darle click aparecera un recuadro que te preguntara si deseas borrar los datos de la fila, al darle borrar se desapareceran los datos de la tabla 
											</p>
											<img class="col-8" src="assets/img/EliminModal.png">
										</div>
									</div>
								</div>
							</div>
							<p id="ventaCompras"></p>
						</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title">Registro de Ventas y Compras</h5>
							</div>
							<div class="accordion accordion-flush" id="accordionFlushExample">
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
											¿Como Registrar algo aqui?
										</button>
									</h2>
									<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En estos modulos podras llevar un registro a detalle de las  ventas y de las compras realizadas hasta la fecha por ejemplo que poductos vendieron cuales compraron y a quien se los compraron pero a diferencia de las otras tablas crud´s (se recomienda ver el apartado <a href="#">Tablas CRUD´S</a>), aqui podras hacer una multiple seleccion de productos gracias a sus multifilas y de lado derecho de cada fila podra ver el boton de anulacion de compra <img class="col-xm-1" src="assets/img/Borrar.png">
											</p>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingTwo">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
											Registro Multifilas
										</button>
									</h2>
									<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												Al precionar el boton de <img class="col-1" src="assets/img/Agregar.png"> aparecera un recuadro con campos para rellenar con la informacion solicitada, en parte superior podra ver una casilla que marca el  porcentaje del IVA (en caso de una compra), este porcentaje se le aplicara al SubTotal, mas abajo de los inputs podra selecionar lo diferentes productos que decidira vender en caso de una venta o comprar en caso de una compra 
											</p>
											<img class="col-8" src="assets/img/MultiFila.png">
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingThree">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
											Detalles
										</button>
									</h2>
									<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En la tabla podra ver unos botones en cada una de las filas <img class="col-2" src="assets/img/Btndetalles.png"> si los preciona podra ver el nombre de los productos, la cantidad y su precio e incluyendo el numero de factura o el numero de orden de compra.
											</p>
											<img class="col-8" src="assets/img/Detalles.png">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="col-lg-6">
					<div class="card ">
						<div class="card-body">
							<div class="col-6">
								<h5 class="card-title">Reportes</h5>
							</div>
							<div class="accordion accordion-flush" id="accordionFlushExample">
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingOne">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
											Generar Reportes
										</button>
									</h2>
									<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">
											<p>
												En esta pagina encontrara unas campos para que selecione el tipo de reporte a escoger y un rango entre fechas que seleccione al completar los 
											</p>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingTwo">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
											Accordion Item #2
										</button>
									</h2>
									<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header" id="flush-headingThree">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
											Accordion Item #3
										</button>
									</h2>
									<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
										<div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

			</div>
		</section>

	</main>
</body>
<?php $VarComp->js();?>

</html>