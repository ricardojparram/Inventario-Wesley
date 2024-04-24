$(document).ready(function () {

	let tabla;
	//Consulta de Permisos
	let permisos, editarPermiso, eliminarPermiso, registrarPermiso;
	$.ajax({
		method: 'POST', url: "", dataType: 'json', data: { getPermisos: 'a' },
		success(data) { permisos = data; }
	}).then(function () {
		refrescar(true);
		registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
		editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
		eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		$('#agregarModalButton').attr(registrarPermiso, '');
	});

	//Rellenar Tabla
	function refrescar(bitacora = false) {
		$.ajax({
			method: "post",
			url: "",
			dataType: "json",
			data: { mostrar: "user", bitacora },
			success(data) {
				data.forEach(row => {
					tabla += `
						<tr>
							<td>${row.cedula}</td>
							<td>${row.nombre}</td>
							<td>${row.apellido}</td>
							<td>${row.correo}</td>
							<td>${row.rol} </td>
							<td class="d-flex justify-content-center">
							<button type="button" ${editarPermiso} class="btn btn-registrar editar mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
							<button type="button" ${eliminarPermiso} class="btn btn-danger eliminar mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
								</td>
						</tr>`;
				});
				$('#tbody').html(tabla);
				tabla = $("#tabla").DataTable({
					responsive: true,
				});
			}
		})
	}


	let timeout, click = 0;
	setInterval(() => { click = 0; }, 2000);

	$("#name").keyup(() => { validarNombre($("#name"), $("#errorNom"), "Error de Nombre,") });
	$("#apellido").keyup(() => { validarNombre($("#apellido"), $("#errorApe"), "Error de Apellido,") });
	$("#password").keyup(() => { validarContraseña($("#password"), $("#errorContra"), "Error de Contraseña,") });
	$("#select").change(() => { validarSelect($("#select"), $("#errorNivel"), "Error de Nivel,") })
	$("#preDocument").change(() => {
		let valid = validarCedula($("#cedula"), $("#errorCed"), "Error de Documento,", $("#preDocument"))
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { 
				validarC(" ", $("#cedula"), $("#errorCed"), $("#preDocument"))
				rellenarPersonal($("#cedula"), $("#preDocument"), $("#errorCed"))
		}
		}, 700)
	})
	$("#cedula").keyup(() => {
		let valid = validarCedula($("#cedula"), $("#errorCed"), "Error de Documento,", $("#preDocument"))
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) {
				validarC(" ", $("#cedula"), $("#errorCed"), $("#preDocument"))
				rellenarPersonal($("#cedula"), $("#preDocument"), $("#errorCed"))
			}
		}, 700)
	});
	$("#email").keyup(() => {
		let valid = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,")
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { validarE(" ", $("#email"), $("#errorEmail")) }
		}, 700)
	});


	$("#enviar").click((e) => {
		e.preventDefault();
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

		let tipo, contra, correo, lastName, nombre, dni, vcorreo
		tipo = validarSelect($("#select"), $("#errorNivel"), "Error de Nivel,");
		contra = validarContraseña($("#password"), $("#errorContra"), "Error de Contraseña,");
		correo = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,");
		lastName = validarNombre($("#apellido"), $("#errorApe"), "Error de Apellido,");
		nombre = validarNombre($("#name"), $("#errorNom"), "Error de Nombre,");
		dni = validarCedula($("#cedula"), $("#errorCed"), "Error de Cedula,", $("#preDocument"));
		if (dni) {
			validarC(" ", $("#cedula"), $("#errorCed"), $("#preDocument")).then(() => {
				if (correo) {
					validarE(" ", $("#email"), $("#errorEmail")).then(() => {

						if (tipo && contra && lastName && nombre) {
							$.ajax({
								type: 'POST',
								url: '',
								dataType: "json",
								data: {
									cedula: $("#preDocument").val() + "-" + $("#cedula").val(),
									name: $("#name").val(),
									apellido: $("#apellido").val(),
									email: $("#email").val(),
									password: $("#password").val(),
									tipoUsuario: $("#select").val(),
								},
								success(result) {
									if (result.resultado === 'Registrado correctamente.') {
										tabla.destroy();
										$("#cerrarRegis").click();
										Toast.fire({ icon: 'success', title: 'Usuario Registrado', showCloseButton: true })
										refrescar();
									} else {
										tabla.destroy();
										$("#error").text(result.resultado + ", " + result.error);
										refrescar();
									}
								}
							})
						}
					})
				}
			})
		}
		click++
	})


	var cedulaDel;
	$(document).on('click', '.eliminar', function () {
		cedulaDel = this.id;

	});

	$("#delete").click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');

		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

		validarC(cedulaDel, $("Noa"), $("#errorDel")).then(() => {
			$.ajax({
				type: "POST",
				url: '',
				dataType: 'json',
				data: {
					eliminar: 'eliminar',
					cedulaDel
				},
				success(data) {
					if (data.resultado === "Eliminado") {
						tabla.destroy();
						$("#cerrarModalDel").click();
						Toast.fire({ icon: 'error', title: 'Usuario Eliminado', showCloseButton: true })
						refrescar();
					} else if (data.resultado === "Error") {
						$("#errorDel").text(data.msj);
					} else {
						tabla.destroy();
						$("#errorDel").text("El Usuario no Pudo Ser Eliminado");
						refrescar();
					}
				}
			})
		})
		click++
	})


	var id
	$(document).on('click', '.editar', function () {
		id = this.id;
		$("#nameEdit").prop('disabled', true);
		$("#apellidoEdit").prop('disabled', true);
		$("#emailEdit").prop('disabled', true);
		$("#passwordEdit").prop('disabled', true);
		$("#selectEdit").prop('disabled', true);

		$.ajax({
			method: "post",
			url: '',
			dataType: "json",
			data: { select: "user", id },
			success(data) {

				$("#cedulaEdit").val(data[0].cedula.slice(2));
				$("#preDocumentEdit").val(data[0].cedula.charAt(0))
				$("#nameEdit").val(data[0].nombre);
				$("#apellidoEdit").val(data[0].apellido);
				$("#emailEdit").val(data[0].correo);
				$("#selectEdit").val(data[0].rol);
			}
		})
	})


	$("#preDocumentEdit").change(() => {
		let valid = validarCedula($("#cedulaEdit"), $("#errorCedEdit"), "Error de Documento,", $("#preDocumentEdit"))
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { 
				validarC(id, $("#cedulaEdit"), $("#errorCedEdit"), $("#preDocumentEdit"))
				rellenarPersonal($("#cedulaEdit"), $("#preDocumentEdit"), $("#errorCedEdit"), "Edit")
			}
		}, 700)
	})
	$("#cedulaEdit").keyup(() => {
		let valid = validarCedula($("#cedulaEdit"), $("#errorCedEdit"), "Error de Documento,", $("#preDocumentEdit"))
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { 
				validarC(id, $("#cedulaEdit"), $("#errorCedEdit"), $("#preDocumentEdit")) 
				rellenarPersonal($("#cedulaEdit"), $("#preDocumentEdit"), $("#errorCedEdit"), "Edit")
				
			}
		}, 700)
	});
	$("#emailEdit").keyup(() => {
		let valid = validarCorreo($("#emailEdit"), $("#errorEmailEdit"), "Error de Correo,")
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { validarE(id, $("#emailEdit"), $("#errorEmailEdit")) }
		}, 700)
	});
	$("#nameEdit").keyup(() => { validarNombre($("#nameEdit"), $("#errorNomEdit"), "Error de Nombre,") });
	$("#apellidoEdit").keyup(() => { validarNombre($("#apellidoEdit"), $("#errorApeEdit"), "Error de Apellido,") });
	$("#passwordEdit").keyup(() => {
		if ($("#passwordEdit").val() == "") {
			$("#passwordEdit").attr("style", "border-color: none; background-image: none"), $("#errorContraEdit").text(" ")
		}
		else { validarContraseña($("#passwordEdit"), $("#errorContraEdit"), "Error de Contraseña,"); }
	})
	$("#selectEdit").keyup(() => { validarSelect($("#selectEdit"), $("#errorNivelEdit"), "Error de Nivel,") });

	$("#enviarEdit").click((e) => {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

		let tipo = validarSelect($("#selectEdit"), $("#errorNivelEdit"), "Error de Nivel,");
		let contra = ($("#passwordEdit").val() == "") ? true : validarContraseña($("#passwordEdit"), $("#errorContraEdit"), "Error de Contraseña,");
		let correo = validarCorreo($("#emailEdit"), $("#errorEmailEdit"), "Error de Correo,");
		let lastName = validarNombre($("#apellidoEdit"), $("#errorApeEdit"), "Error de Apellido,");
		let nombre = validarNombre($("#nameEdit"), $("#errorNomEdit"), "Error de Nombre,");
		let dni = validarCedula($("#cedulaEdit"), $("#errorCedEdit"), "Error de Documento,", $("#preDocumentEdit"));
		if (dni) {
			validarC(id, $("#cedulaEdit"), $("#errorCedEdit"), $("#preDocumentEdit")).then(() => {
				if (correo) {
					validarE(id, $("#emailEdit"), $("#errorEmailEdit")).then(() => {

						if (tipo && contra && lastName && nombre) {
							$.ajax({
								type: 'POST',
								url: '',
								dataType: 'json',
								data: {
									cedulaEdit: $("#preDocumentEdit").val() + "-" + $("#cedulaEdit").val(),
									nameEdit: $("#nameEdit").val(),
									apellidoEdit: $("#apellidoEdit").val(),
									emailEdit: $("#emailEdit").val(),
									passwordEdit: $("#passwordEdit").val(),
									tipoUsuarioEdit: $("#selectEdit").val(),
									id
								},
								success(edit) {
									if (edit.resultado === "Editado") {
										tabla.destroy();
										$("#cerrarRegisEdit").click();
										Toast.fire({ icon: 'success', title: 'Usuario Actualizado', showCloseButton: true })
										refrescar();
									} else {
										tabla.destroy();
										$("#error2").text(edit.resultado + ", " + edit.error)
										refrescar();
									}
								}
							})
						}
					})
				}
			})
		}
		click++
	})

	$(document).on('click', '#cerrarRegisEdit', function () {
		$('#editModal p').text(" ");
		$("#editModal select").removeClass('select-error')
		$("#editModal input").removeClass('input-error')
	})

	$(document).on('click', '#cerrarRegis', function () {
		$('#basicModal p').text(" ");
		$("#basicModal input").removeClass('input-error')
		$("#basicModal select").removeClass('select-error')
	})

	$(document).on('click', '#cerrarModalDel', function () {
		$("#delModal p").text(" ");
	})

	$("#agregarModalButton").click(() => {
		$("#name").prop('disabled', true);
		$("#apellido").prop('disabled', true);
		$("#email").prop('disabled', true);
		$("#password").prop('disabled', true);
		$("#select").prop('disabled', true);

	})


	//Validacion para la Cedula 
	let val
	function validarC(valor, input, div, prefijo) {
		val = (input.val() == undefined) ? " " : prefijo.val() + "-" + input.val()
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				cedula: val,
				idVal: valor,
				validar: 'xd'
			},
				function (valid) {
					if (valid.resultado === "Error") {
						div.text("Error de Cedula, " + valid.msj);
						input.addClass('input-error')
						return reject(false);
					} else {
						div.text("");
						input.removeClass('input-error')
						return resolve(true);
					}
				}
			)
		})
	}

	//Validacion para correo
	function validarE(valor, input, div) {
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				correo: input.val(),
				idVal: valor,
				validarE: 'lol'
			},
				function (valid) {
					if (valid.resultado === "Error") {
						div.text("Error de Correo, " + valid.msj);
						input.addClass('input-error')
						return reject(false);
					} else {
						div.text("");
						input.removeClass('input-error')
						return resolve(true);
					}
				}
			)
		})
	}

	async function rellenarPersonal(cedula, prefijo, error, edit = "") {
		await $.getJSON('', {
			cedula: prefijo.val() + "-" + cedula.val(),
			persona: "xd"
		},
			function (data) {
				console.log(data);
				$("#name"+edit).val(data[0].nombres);
				$("#apellido"+edit).val(data[0].apellidos);
				$("#email"+edit).val(data[0].correo);

				$("#password"+edit).prop('disabled', false);
				$("#select"+edit).prop('disabled', false);
				return true
				}).fail(e => {

				error.text("Error de Cedula, " + e.responseJSON.msg);
				input.addClass('input-error')
				return false
			})

	}
}) 