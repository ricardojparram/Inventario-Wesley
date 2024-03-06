$(document).ready(function(){

	const url_param = (window.location.search === "?url=pago") ? "pago" : "";
	console.log(url_param);
	// const comprobarLimitePago = () => {
		setInterval(() => {
			
			$.ajax({
				url: '?url=pago', 
				dataType:'JSON', 
				method:'POST', 
				data: {comprobarLimitePago:'', url_param},
				success(asd){
					console.log(asd);
					if (asd.resultado == "Eliminado correctamente.") {
						Swal.fire({
							title: 'Tiempo Limite Superdado!',
							text: 'Acaba de Superar el Tiempo Limite de 1 Hora',
							icon: 'error',
						})
						setTimeout(function(){
							location = '?url=carrito'
						}, 1600);
					}
				}
			})
			
		}, 300000);
	
	//}
	// console.log(comprobarLimitePago);
})