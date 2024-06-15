$(document).ready(function () {
	let tablaM


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
	let cambio, fecha, timeout
	function mostrar(bitacora = false) {
		$.ajax({
			type: "POST",
			url: "",
			dataType: 'json',
			data: { datos: 'Moneda', bitacora },
			success(data) {
				data.forEach(row => {
					cambio = (row.fecha == null) ? "" : row.cambio
					fecha = (row.fecha == null) ? "" : row.fecha
					tablaM += `
					
						<tr>
							<td>${row.nombre} </td>
							<td>${cambio} </td>
							<td>${fecha} </td>
							<td class="d-flex justify-content-center">
							<button type="button" ${editarPermiso} class="btn btn-view history mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#editHistory"><i class="bi bi-clock-history"></i></button>
							<button type="button" ${editarPermiso} class="btn btn-registrar update mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
							<button type="button" ${eliminarPermiso} class="btn btn-danger delete mx-2" id="${row.id_moneda}" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash3"></i></button>
								</td>
						</tr>`;
				});
				$('#tbody1').html(tablaM);
				tablaM = $('#tablaM').DataTable({
					responsive: true,
				});

			},
			error(e) {
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
				throw new Error(e.responseJSON.msg);
			}
		})
	}

	let click = 0;
	setInterval(() => { click = 0; }, 1500);

	let name;
	$('#moneda').keyup((e) => {
		if (e.which === 13) return clearTimeout(timeout);
		let valid = validarNombre($('#moneda'), $('#ms'), "Error de moneda,")
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { validarM($('#moneda'), $('#ms')) }
		}, 700)
	});
	$('#registrarM').submit((e) => {
		e.preventDefault();
		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

		let moneda = validarNombre($('#moneda'), $('#ms'), "Error de moneda");
		if (moneda) {
			validarM($('#moneda'), $('#ms')).then(() => {
				$("#registrar").prop('disabled', true);

				name = $('#moneda').val();

				$.ajax({
					type: 'POST',
					url: '',
					dataType: 'JSON',
					data: { moneda: 'xd', name },
					success(data) {
						console.log(data);
						tablaM.destroy();
						$('#cerrarMonR').click();
						mostrar();
						Toast.fire({ icon: 'success', title: 'Moneda registrada', showCloseButton: true });
					},
					error(e) {
						Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
						throw new Error(e.responseJSON.msg);
					},
					complete() {
						$("#registrar").prop('disabled', false);
					}
				})
			})

		}
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

	$('#editMon').keyup((e) => {
		if (e.which === 13) return clearTimeout(timeout);
		let valid = validarNombre($('#editMon'), $('#ms2'), "Error de moneda")
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { validarM($('#editMon'), $('#ms2'), id) }
		}, 700)
	});

	$('#editarMon').submit((e) => {
		e.preventDefault();

		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}
		let editN = validarNombre($('#editMon'), $('#ms2'), "Error de moneda");

		if (editN) {
			validarM($('#editMon'), $('#ms2'), id).then(() => {

				$("#editar").prop('disabled', true);
				nameEdit = $('#editMon').val();

				$.ajax({
					type: 'POST',
					url: '',
					dataType: 'JSON',
					data: { nameEdit, id },
					success(data) {
						tablaM.destroy();
						$('#cerrarMonA').click();
						mostrar();
						Toast.fire({ icon: 'success', title: 'Moneda actualizada', showCloseButton: true  });
					},
					error(e) {
						Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
						throw new Error(e.responseJSON.msg);
					},
					complete() {
						$("#editar").prop('disabled', false);
					}
				})
			})
		}
	})

	$(document).on('click', '.delete', function () {
		id = this.id;
	})


	$('#eliminar').click((e) => {
		e.preventDefault()

		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true  });
			throw new Error('Permiso denegado.');
		}
		$("#eliminar").prop('disabled', true);
		validarM($('#editMona'), $('#ms2'), id).then(() => {

			$.ajax({
				type: "POST",
				url: '',
				dataType: 'json',
				data: {
					delete: 'eliminar',
					id
				},
				success(data) {
					tablaM.destroy();
					$('#cerrarMonE').click();
					mostrar();
					Toast.fire({ icon: 'success', title: 'Moneda eliminada', showCloseButton: true  });
				},
				error(e) {
					Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
					console.error(e.responseJSON.msg);
				},
				complete() {
					$("#eliminar").prop('disabled', false);
				}
			})
		})
	})


	let tabla, idHistory

	async function rellenar(idHistory) {

		if (typeof tabla !== 'undefined') tabla.destroy();
		$('#selectMoneda').val(idHistory)
		await $.ajax({
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
						<button type="button" ${editarPermiso} class="btn btn-registrar editar mx-2" id="${row.id_cambio}" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
						<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.id_cambio}" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
						</td>
						</tr>`;
				});
				$('#tbody').html(tabla);
				tabla = $("#tabla").DataTable({
					responsive: true,
					"order": [[1, "desc"]]
				});
			},
			error(e) {
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
				throw new Error(e.responseJSON.msg);
			}
		})
		// mostrar()


	}

	$(document).on('click', '.history', async function () {
		$(".history").prop('disabled', true);
		idHistory = this.id;
		await rellenar(idHistory)
	})

	$('#editHistory').on('hidden.bs.modal', function () {
		$(".history").prop('disabled', false);
	});


	selectMoneda();
	let selectOp
	async function selectMoneda() {
		await $.ajax({
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
	$(document).on('click', '#agregarMoneda', async function () {
		await selectMoneda();
		$('#selectMoneda').val(idHistory)
	})

	let select, vcambio;

	$("#selectMoneda").change(function () {
		select = validarSelect($("#selectMoneda"), $("#error"), "Error de Tipo de Moneda,")
	})
	$("#cambio").keyup((e) => {
		if (e.which === 13) return
		validarNumero($("#cambio"), $("#error"), "Error de Valor de Moneda,")
	});


	$("#registrarC").submit((e) => {
		e.preventDefault();
		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true  });
			throw new Error('Permiso denegado.');
		}

		let vcambio = validarNumero($("#cambio"), $("#error"), "Error de Valor de Moneda,");
		let select = validarSelect($("#selectMoneda"), $("#error"), "Error de Tipo de Moneda,");

		if (select && vcambio) {
			$("#enviar").prop('disabled', true);
			$.ajax({

				type: "POST",
				url: "",
				dataType: "json",
				data: {
					cambio: $("#cambio").val(),
					tipo: $("#selectMoneda").val()
				},
				success(data) {
					tablaM.destroy();
					$("#close").click();
					Toast.fire({ icon: 'success', title: 'Cambio de Moneda Registrado', showCloseButton: true  })
					mostrar()
					rellenar(idHistory)
				},
				error(e) {
					Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
					throw new Error(e.responseJSON.msg);
				},
				complete() {
					$("#enviar").prop('disabled', false);
				}
			})
		}

	})


	$(document).on('click', '.borrar', function () {
		id = this.id;
	})
	$("#delete").click((e) => {
		e.preventDefault()
		$("#delete").prop('disabled', true);
		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true  });
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
				tablaM.destroy();
				$("#closeDel").click();
				Toast.fire({ icon: 'error', title: 'Cambio de moneda eliminado', showCloseButton: true  })
				mostrar()
				rellenar(idHistory)
			},
			error(e) {
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
				throw new Error(e.responseJSON.msg);
			},
			complete() {
				$("#delete").prop('disabled', false);
			}
		})



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

			}, error(e) {
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
				throw new Error(e.responseJSON.msg);
			}

		})

	})

	$("#monedaEdit").keyup(() => { validarSelect($("#monedaEdit"), $("#error2"), "Error de Tipo de Moneda,") });
	$("#cambioEdit").keyup(() => { validarNumero($("#cambioEdit"), $("#error2"), "Error de Valor de Moneda,") });

	let etipo, ecambio;
	$("#editarCambio").submit((e) => {
		e.preventDefault()

		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true  });
			throw new Error('Permiso denegado.');
		}

		etipo = validarSelect($("#monedaEdit"), $("#error2"), "Error de Tipo de Moneda,");
		ecambio = validarNumero($("#cambioEdit"), $("#error2"), "Error de Valor de Moneda,");

		if (etipo && ecambio) {
			$("#enviarEdit").prop('disabled', true);
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
					tablaM.destroy();
					$("#closeEdit").click();
					Toast.fire({ icon: 'success', title: 'Cambio de Moneda editado' , showCloseButton: true })
					mostrar()
					rellenar(idHistory)
				},
				error(e) {
					Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error.", showCloseButton: true })
					throw new Error(e.responseJSON.msg);
				},
				complete() {
					$("#enviarEdit").prop('disabled', false);
				}
			})
		}

	})

	// Vacio de Modales para Moneda
	$(document).on('click', '#cerrarMonA', function () {
		$('#editModal p').text(" ");
		$("#editModal input, select").removeClass('input-error')
	})
	$(document).on('click', '#cerrarMonR', function () {
		$('#registrarMoneda p').text(" ");
		$("#registrarMoneda input, select").removeClass('input-error')
	})
	$(document).on('click', '#cerrarMonE', function () {
		$("#deleteModal p").text(" ");
	})

	// Vacio de Modales para Cambio
	$(document).on('click', '#close', function () {
		$('#editarModal p').text(" ");
		$("#editarModal input, select").removeClass('input-error')
	})
	$(document).on('click', '#closeEdit', function () {
		$('#registrarModal p').text(" ");
		$("#registrarModal input, select").removeClass('input-error')
	})
	$(document).on('click', '#closeDel', function () {
		$("#delModal p").text(" ");
	})

	// Validacion de Moneda
	async function validarM(input, div, id = " ") {
		let inputVal
		inputVal = (input.val() == undefined) ? " " : input.val()
		let resultado = false
		await $.getJSON('', { nomMoneda: inputVal, id, validar: 'xd' }) .done(function (res) {
			div.text("");
			input.removeClass('input-error')
			resultado = true;
		}).fail(function (e) {
			div.text("Error de Moneda, " + e.responseJSON.e);
			input.addClass('input-error')
			resultado = false;

		})
		return resultado
	}



})