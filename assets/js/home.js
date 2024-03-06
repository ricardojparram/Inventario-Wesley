$(document).ready(function(){

	clientes();
	ventasHoy();
	ventas();
	comprasHoy();
	compras();

	function clientes(){


		$.ajax({
			method: 'POST',
			url: '',
			dataType: 'json',
			data: {clien: 'lol'},
			success(clin){
				$('#usuarios').text(clin[0].usuario);
				$('#proveedores').text(clin[0].proveedor);
				$('#clientes').text(clin[0].cliente);
				$('#producto').text((clin[0].producto == null) ? '0' : clin[0].producto);
			}
		})
	}
	

	function ventas(){
			let opcionV;

		$(document).on('click', '.ventas', function(){
			opcionV = this.id;

		switch(opcionV){
			case 'hoy':
			$('#ventas').text("| Dia");
			break;

			case 'mensual':
			$('#ventas').text("| Mes");
			break;

			case 'anual':
			$('#ventas').text("| Año");
			break;
			default: $('#ventas').text('Tipo de reporte inválido.');	
		}

		$.ajax({
			method: 'POST',
			url: '',
			dataType: 'json',
			data: {
				ventas: 'lalo', opcionV
			},
			success(ven){
				$('#valorV').text(ven[0].venta);
			}
		})
		})
	}

	function ventasHoy(){
		$.ajax({
			method: 'POST',
			url: '',
			dataType: 'json',
			data: {
				ventas: 'lalo', 
				opcionV: 'hoy'
			},
			success(ven){
				$('#valorV').text(ven[0].venta);
			}
		})
	}

	function compras(){
		let opcionC;

		$(document).on('click', '.compras', function(){
			opcionC = this.id;

			switch(opcionC){
				case 'hoy':
				$('#compras').text("| Dia");
				break;

				case 'mensual':
				$('#compras').text("| Mes");
				break;

				case 'anual':
				$('#compras').text("| Año");
				break;
				default: $('#compras').text('Tipo de reporte inválido.');	
			}

			$.ajax({
				method: 'POST',
				url: '',
				dataType: 'json',
				data: {
					compras: 'lalo', opcionC
				},
				success(com){
					$('#valorC').text(com[0].compra);
				}

			});

		})
	}

	function comprasHoy(){
		$.ajax({
			method: 'POST',
			url: '',
			dataType: 'json',
			data: {
				compras: 'lalo', 
				opcionC: 'hoy'
			},
			success(com){
				$('#valorC').text(com[0].compra);
			}
		})
	}

	let ctx = document.getElementById('reportsChart').getContext('2d');

	let gradient = ctx.createLinearGradient(0, 0, 0, 400);
		gradient.addColorStop(0, 'rgba(94, 166, 48, 0.7)');
		gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

	let gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
		gradient2.addColorStop(0, 'rgba(166, 74, 40, 0.7)');
		gradient2.addColorStop(1, 'rgba(255, 255, 255, 0)');

	let ventasG, comprasG, fechas;

	$.post('', {grafico: 'xd'}, function(response){
		data = JSON.parse(response);
		
		new Chart(ctx, {
			type: "line",
			xAxisID: [0,5,10,15,20,25],
			data: {
				labels: data.fechas,
				datasets: [{
					label: "Ventas",
					data: data.ventas,
					borderColor: "#5EA630",
					borderRadius: 5,
					backgroundColor:gradient,
					pointBackgroundColor: "#5EA630",
					fill: true
				},
				{
					label: "Compras",
					data: data.compras,
					borderColor: "#A64A28",
					pointBackgroundColor: "#A64A28",
					backgroundColor: gradient2,
					fill: true
				}]
			},
			options: {
				plugins: {
					legend: {
						display: true,
						position: 'bottom',
						labels: {
							color: "black",
							usePointStyle: true,
							pointStyle: "circle",

						}

					}
				},
				interaction: {
					intersect: false,
					mode: 'nearest',
				},
				pointRadius: 4,
				pointHoverRadius: 6,
				pointBorderColor: "white",
				pointBorderWidth: 2,
				tension: 0.2,
				borderWidth: 2.5,
				borderCapStyle: "round",
				responsive: true,
				scales: {
					x: {
						grid: {
							display: false
						}
					},
					y: {
						suggestedMin: 0,
						suggestedMax: 15,
						type: 'linear',
						position: 'left'
					}
				},
			}
		});
	});


});