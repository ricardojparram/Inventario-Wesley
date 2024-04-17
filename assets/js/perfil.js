$(document).ready(function () {
	rellenar();
	function rellenar() {
		$.ajax({
			method: "post",
			url: '',
			dataType: 'JSON',
			data: {
				mostrar: 'lol'
			},
			success(dato) {
				$('.nombreCompleto').text(dato[0].nombre + ' ' + dato[0].apellido);
				$('#name').text(dato[0].nombre + ' ' + dato[0].apellido);
				$('#nivel').text(dato[0].nivel);
				$('#cedula').text(dato[0].cedula);
				$('#email').text(dato[0].correo);

				$('#nameEdit').val(dato[0].nombre);
				$('#apeEdit').val(dato[0].apellido);
				$("#cedulaEdit").val(dato[0].cedula.slice(2));
				$("#preDocument").val(dato[0].cedula.charAt(0));
				$('#emailEdit').val(dato[0].correo);
			}
		})
	}

	mostrarUsuarios();
	function mostrarUsuarios() {
		$.post('', { lista: 'mostrar', usuarios: 'usuarios' },
			function (response) {
				let usuarios = JSON.parse(response);
				let imgDefault = 'assets/img/profile_photo.jpg';
				let lista = '';
				usuarios.forEach(fila => {
					if (fila.img == null) fila.img = imgDefault

					lista += ` <li class="list-group-item">
			                    <img src="${fila.img}" alt="Profile" class="imgUser">
			                    <p>${fila.nombre}</p>
			                  </li>`
				})
				$('#users').html(lista);
			})
	}

	let timeout, click = 0;
	setInterval(() => { click = 0; }, 2000);

	$("#nameEdit").keyup(() => { validarNombre($("#nameEdit"), $("#errorNom"), "Error de nombre,") });
	$("#apeEdit").keyup(() => { validarNombre($("#apeEdit"), $("#errorApe"), "Error de apellido,") });
	$("#cedulaEdit").keyup(() => {
		let valid = validarCedula($("#cedulaEdit"), $("#errorCedu"), "Error de cedula,", $("#preDocument"))
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) { validarCedulaBD($("#cedulaEdit"), $("#errorCedu"), $("#preDocument")) }
		}, 700)
	});
	$("#emailEdit").keyup(() => {
		let valid = validarCorreo($("#emailEdit"), $("#errorEmail"), "Error de email,")
		clearTimeout(timeout)
		timeout = setTimeout(function () {
			if (valid) {
				console.log(validarCorreoBD($("#emailEdit"), $("#errorEmail")))
			}
		}, 700)
	});
	let name, lastname, id, email;

	$('#borrarFoto').click(() => {
		$('#imgEditar').attr('src', 'assets/img/profile_photo.jpg');
	})

	let imagen = document.getElementById('imgModal')
	let imgPreview = document.getElementById('imgEditar')
	let input = document.getElementById('foto')

	input.addEventListener('change', function (e) {
		var files = e.target.files;
		var done = function (url) {
			// input.value = '';
			imagen.src = url;
			$('#fotoModal').modal('show');
		};
		var reader;
		var file;
		var url;

		if (files && files.length > 0) {
			file = files[0];

			if (URL) {
				done(URL.createObjectURL(file));
			} else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
					done(reader.result);
				};
				reader.readAsDataURL(file);
			}
		}
	});

	let cropper;
	$('#fotoModal').on('shown.bs.modal', function () {
		cropper = new Cropper(imagen, {
			aspectRatio: 1,
			viewMode: 3,
		});
	}).on('hidden.bs.modal', function () {
		cropper.destroy();
		cropper = null;
	});

	let canvas;
	$('#aceptar').click(function () {
		if (!cropper) throw new Error('Error al recortar');

		canvas = cropper.getCroppedCanvas({
			width: 200,
			height: 200,
		});

		imgPreview.src = canvas.toDataURL();
		$('#fotoModal').modal('hide')

	})

	$("#enviarDatos").click((e) => {

		e.preventDefault();

		if (click >= 1) throw new Error('Spam de clicks');

		name = validarNombre($("#nameEdit"), $("#errorNom"), "Error de nombre,");
		lastname = validarNombre($("#apeEdit"), $("#errorApe"), "Error de apellido,");
		id = validarCedula($("#cedulaEdit"), $("#errorCedu"), "Error de cedula,", $("#preDocument"));
		email = validarCorreo($("#emailEdit"), $("#errorEmail"), "Error de email,");

		if (!name && !lastname && !id && !email) {
			throw new Error('Datos inválidos');
		}

		validarCedulaBD($("#cedulaEdit"), $("#errorCedu"), $("#preDocument")).then(() => {

			validarCorreoBD($("#emailEdit"), $("#errorEmail")).then(() => {

				let form = new FormData($('#formEditar')[0]);
				form.append("cedula", $("#preDocument").val() + "-" + $("#cedulaEdit").val());
				let borrar = $('#imgEditar').is('[src="assets/img/profile_photo.jpg"]');
				let foto = $('#foto').val();

				if (foto !== "") {
					canvas.toBlob(function (blob) {
						form.set('foto', blob, 'avatar.png')
						editarImagen(form);
					});
				} else if (borrar) {
					form.append("borrar", "");
					editarImagen(form);
				} else {
					editarImagen(form);
				}

			})
		})
		click++

	})
	function editarImagen(form) {
		$.ajax({
			type: "POST", url: '', dataType: 'JSON', data: form, contentType: false, processData: false,
			xhr: () => loading(),
			success(data) {
				$('#displayProgreso').hide();
				if (data.foto.respuesta == 'Error') {
					$('#error').text(data.foto.error);
					throw new Error('Error de foto.');
				}
				if (data.foto.respuesta === 'ok') {
					$('.fotoPerfil').attr('src', data.foto.url);
				}
				if (data.edit.respuesta == "Editado correctamente") {
					$('#formEditar').trigger('reset');
					rellenar();
					mostrarUsuarios();
					Toast.fire({ icon: 'success', title: 'Usuario Actualizado' });
					$("#perfil").click();
				} if (data.edit.respuesta == 'Error') {
					$('#error').text(data.edit.respuesta + ", " + data.edit.error);
				}
			},
			error(data) {
				$('#error').text(data.responseJSON.msg);
				$('#displayProgreso').hide();
				Toast.fire({ icon: 'error', title: data.responseJSON.msg });
				throw new Error('Error de foto.');
			}
		})
	}

	async function validarContraseñaBD(input, div) {
		let password = input.val();
		let resultado = false;
		await $.post('', { password, validarContraseña: '' }, function (response) {
			div.text(" ");
			input.removeClass('input-error');
			resultado = true;
		}, "json").fail(e => {
			div.text(e.responseJSON.msg);
			input.addClass('input-error')
			resultado = false;
		})
		return resultado;
	}

	$("#password").keyup(() => {
		let valid = validarContraseña($("#password"), $("#error2"), "Error de Contraseña Actual,")
		clearTimeout(timeout);
		timeout = setTimeout(function () {
			if (valid) validarContraseñaBD($("#password"), $("#error2"));
		}, 700);
	});
	$("#newPassword").keyup(() => { validarContraseña($("#newPassword"), $("#error2"), "Error de Contraseña Nueva,") });
	$("#rePassword").keyup(() => { validarRepContraseña($("#rePassword"), $("#error2"), $("#newPassword")) });
	let contra, reContra;
	$("#editContra").click(function (e) {
		e.preventDefault()
		reContra = validarRepContraseña($("#rePassword"), $("#error2"), $("#newPassword"));
		contra = validarContraseña($("#newPassword"), $("#error2"), "Error de Contraseña Nueva,");

		if (!actContra || !contra || !reContra)
			throw new Error("Datos invalidos");

		const body = {
			passwordAct: $("#password").val(),
			passwordNew: $("#newPassword").val(),
		}
		$(this).prop('disabled', true);
		$.post("", body, function (des) {
			if (des.resultado === 'Editada Contraseña') {
				Toast.fire({ icon: 'success', title: 'Contraseña Actualizada' });
				$("#perfil").click();
				$("#profile-change-password input").val("")
			}
		}, "json")
			.always(() => $(this).prop('disabled', false))
			.fail((e) => {
				Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
				throw new Error(e.responseJSON.msg);
			})

	})

	async function validarCedulaBD(input, div, prefijo) {
		let cedula = prefijo.val() + "-" + input.val()
		let resultado = false;
		await $.getJSON('', { cedula, validarCedula: '' }, function (valid) {
			div.text("");
			input.removeClass('input-error');
			resultado = true;
		}).fail(e => {
			div.text(e.responseJSON.msg);
			input.addClass('input-error')
			resultado = false;
		})
		return resultado;
	}

	async function validarCorreoBD(input, div) {
		let resultado = false;
		await $.getJSON('', { correo: input.val(), validarCorreo: '' }, function (res) {
			div.text("");
			input.removeClass('input-error')
			resultado = true;
		}).fail(e => {
			div.text(e.responseJSON.msg);
			input.addClass('input-error')
			resultado = false;
		})
		return resultado;
	}

	function loading() {
		let xhr = new window.XMLHttpRequest();
		$('#displayProgreso').show();
		xhr.upload.addEventListener("progress", function (event) {
			if (event.lengthComputable) {
				let porcentaje = parseInt((event.loaded / event.total * 100), 10);
				$('#progressBar').data("aria-valuenow", porcentaje)
				$('#progressBar').css("width", porcentaje + '%')
				$('#progressBar').html(porcentaje + '%')
			}
		}, false)
		return xhr;
	}

})