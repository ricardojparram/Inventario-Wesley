$(document).ready(function () {
	let tabla2


	//Consulta de Permisos
	let permisos, editarPermiso, eliminarPermiso, registrarPermiso;
	$.ajax({
		method: 'POST', url: "", dataType: 'json', data: { getPermisos: 'a' },
		success(data) { permisos = data; }
	}).then(function () {
		mostrar(true);
		registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
		editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
		eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		$('#agregarMoneda, #agregarCambio').attr(registrarPermiso, '');
	});
	let cambio, fecha
	function mostrar(bitacora = false) {
		$.ajax({
			type: "POST",
			url: "",
			dataType: 'json',
			data: { datos: 'Moneda', bitacora },
			success(data) {
				data.forEach(row => {
					cambio = (row.cambio == null) ? "" : row.cambio
					fecha = (row.fecha == null) ? "" : row.fecha
					tabla2 += `
					
						<tr>
							<td>${row.nombre} </td>
							<td>${cambio} </td>
							<td>${fecha} </td>
							<td class="d-flex justify-content-center">
							<button type="button" ${editarPermiso} class="btn btn-primary history mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#editHistory"><i class="bi bi-clock-history"></i></button>
							<button type="button" ${editarPermiso} class="btn btn-success update mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
							<button type="button" ${eliminarPermiso} class="btn btn-danger delete mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash3"></i></button>
								</td>
						</tr>`;
				});
				$('#tbody1').html(tabla2);
				tabla2 = $('#tabla2').DataTable({
					responsive: true,
				});

			}
		})
	}

	let click = 0;
	setInterval(() => { click = 0; }, 1500);

	let moneda, name;
	$('#moneda').keyup(() => { validarNombre($('#moneda'), $('#ms'), "Error de moneda") });
	$('#registrar').click((e) => {
		e.preventDefault();
		if (click >= 1) throw new Error('Spam de clicks');

		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}



		moneda = validarNombre($('#moneda'), $('#ms'), "Error de moneda");

		if (moneda) {
			name = $('#moneda').val();

			$.ajax({
				type: 'POST',
				url: '',
				dataType: 'JSON',
				data: { moneda: 'registrar', name },
				success(data) {
					tabla2.destroy();
					$('#cerrarR').click();
					mostrar();
					Toast.fire({ icon: 'success', title: 'Moneda registrado' });
				}
			})

		}
		click++
	})

	let id;

	$(document).on('click', '.update', function () {
		id = this.id;

		$.ajax({
			type: 'POST',
			url: '',
			dataType: 'JSON',
			data: { edit: 'Money', id },
			success(data) {
				$('#editMon').val(data[0].nombre);
			}
		})
	})

	$('#editMon').keyup(() => { validarNombre($('#editMon'), $('#ms2'), "Error de moneda") });

	$('#editar').click((e) => {
		e.preventDefault();
		if (click >= 1) throw new Error('Spam de clicks');

		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}
		let editN = validarNombre($('#editMon'), $('#ms2'), "Error de moneda");

		if (editN) {
			nameEdit = $('#editMon').val();

			$.ajax({
				type: 'POST',
				url: '',
				dataType: 'JSON',
				data: { nameEdit, id },
				success(data) {
					console.log(data)
					tabla2.destroy();
					$('#cerrarA').click();
					mostrar();
					Toast.fire({ icon: 'success', title: 'Moneda actualizada' });
				}
			})
		}
		click++
	})

	$(document).on('click', '.delete', function () {
		id = this.id;
	})


	$('#eliminar').click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}

		$.ajax({
			type: "POST",
			url: '',
			dataType: 'json',
			data: {
				delete: 'eliminar',
				id
			},
			success(data) {
				console.log(data)
				tabla2.destroy();
				$('#cerrar').click();
				mostrar();
				Toast.fire({ icon: 'success', title: 'Moneda eliminada' });
			}
		})
		click++
	})


	let tabla
	let idHistory

	$(document).on('click', '.history', function () {

		idHistory = this.id;


		console.log(idHistory)
		rellenar(idHistory)
		tabla.destroy();
	})
	
	function rellenar(idHistory) {
		selectMoneda()
		$.ajax({
			type: "POST",
			url: '',
			dataType: 'json',
			data: { mostrar: 'xd', idHistory },
			success(angeles) {
				
				$("#nomMoneda").text(angeles[0]['nombre'])

				angeles.forEach(row => {
					if (row.cambio == null) return
					
					tabla += `
						<tr>
						<td>${row.cambio} </td>
						<td>${row.fecha} </td>
						<td class="d-flex justify-content-center">
						<button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.id_cambio}" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
						<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.id_cambio}" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
						</td>
						</tr>`;
				});
				$('#tbody').html(tabla);
				tabla = $("#tabla").DataTable({
					responsive: true,
					"order": [[1, "desc"]]
				});
			}
		})
		// mostrar()


	}



	selectMoneda();
	let selectOp
	function selectMoneda() {
		$.ajax({
			type: "POST",
			url: '',
			dataType: 'json',
			data: { select: 'mostrar' },
			success(data) {
				let option = "";
				selectOp = data
				data.forEach((row) => {
					option += `<option value="${row.id_moneda}">${row.nombre}</option>`;
				})
				$('.selectM').html(option);
					
				
			}
		})
	}
	let resultado
	$(document).on('click', '#agregarMoneda', function (){
		
		$('#selectMoneda').val(idHistory)
	})

	let select, vcambio;

	$("#selectMoneda").change(function () {
		select = validarSelect($("#selectMoneda"), $("#error"), "Error de Tipo de Moneda,")
	})
	$("#cambio").keyup(() => { validarNumero($("#cambio"), $("#error"), "Error de Valor de Moneda,") });


	$("#enviar").click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');

		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}

		vcambio = validarNumero($("#cambio"), $("#error"), "Error de Valor de Moneda,");
		select = validarSelect($("#selectMoneda"), $("#error"), "Error de Tipo de Moneda,");

		if (select && vcambio) {
			$.ajax({

				type: "POST",
				url: "",
				dataType: "json",
				data: {

					cambio: $("#cambio").val(),
					tipo: $("#selectMoneda").val()

				},
				success(data) {
					tabla.destroy();
					tabla2.destroy();
					$("#close").click();
					Toast.fire({ icon: 'success', title: 'Tipo de cambio registrado' })
					mostrar()
					rellenar(idHistory)
				}
			})
		}
		click++

	})


	$(document).on('click', '.borrar', function () {
		id = this.id;
	})
	$("#delete").click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}
		$.ajax({
			type: "POST",
			url: '',
			dataType: 'json',
			data: {
				borrar: 'cualquier cosita',
				id
			},
			success(consul) {
				tabla2.destroy();
				tabla.destroy();
				$("#closeDel").click();
				Toast.fire({ icon: 'error', title: 'Tipo de moneda eliminado' })
				mostrar()
				rellenar(idHistory)

			}
		})


		click++

	})

	let unico;
	$(document).on('click', '.editar', function () {
		unico = this.id;

		$.ajax({
			type: "POST",
			url: '',
			dataType: 'json',
			data: {
				editar: 'noloserick',
				unico
			},
			success(uni) {
				console.log(uni)
				$("#monedaEdit").val(uni[0].moneda);
				$("#cambioEdit").val(uni[0].cambio);

			}

		})

	})

	$("#monedaEdit").keyup(() => { validarSelect($("#monedaEdit"), $("#error2"), "Error de Tipo de Moneda,") });
	$("#cambioEdit").keyup(() => { validarNumero($("#cambioEdit"), $("#error2"), "Error de Valor de Moneda,") });

	let etipo, ecambio;
	$("#enviarEdit").click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');

		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
			throw new Error('Permiso denegado.');
		}

		etipo = validarSelect($("#monedaEdit"), $("#error2"), "Error de Tipo de Moneda,");
		ecambio = validarNumero($("#cambioEdit"), $("#error2"), "Error de Valor de Moneda,");

		$.ajax({

			type: "POST",
			url: "",
			dataType: "json",
			data: {
				cambioEdit: $("#cambioEdit").val(),
				tipoEdit: $("#monedaEdit").val(),
				unico

			},
			success(data) {
				tabla.destroy()
				tabla2.destroy();
				if (etipo == true && ecambio == true) {

					$("#closeEdit").click();
					Toast.fire({ icon: 'success', title: 'Tipo de cambio registrado' })
					// rellenar();

				} else {
					e.preventDefault()
				}
				mostrar()
				rellenar(idHistory)

			}
		})

		click++

	})



	// $('#cerrar').click(()=> {
	// 	if (click >= 1) throw new Error('Spam de clicks');
	// 	tabla.destroy()
	// 	click++
	// })

	


})