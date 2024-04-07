$(document).ready(function(){
	rellenar();
	function rellenar(){
		$.ajax({
			method: "post",
			url: '',
			dataType: 'JSON',
			data: {
				mostrar: 'lol'
			},
			success(dato){
				$('.nombreCompleto').text(dato[0].nombre+' '+dato[0].apellido);
				$('#name').text(dato[0].nombre+' '+dato[0].apellido);
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
	function mostrarUsuarios(){
		$.post('', {lista: 'mostrar', usuarios: 'usuarios'},
			function(response){
				let usuarios = JSON.parse(response);
				let imgDefault = 'assets/img/profile_photo.jpg';
				let lista = '';
				usuarios.forEach(fila =>{
					if(fila.img == null) fila.img = imgDefault

					lista += ` <li class="list-group-item">
			                    <img src="${fila.img}" alt="Profile" class="imgUser">
			                    <p>${fila.nombre}</p>
			                  </li>`
				})
				$('#users').html(lista);
			})
	}

	let timeout,click = 0;
	setInterval(() => { click = 0; }, 2000);

	$("#nameEdit").keyup(()=> {  validarNombre($("#nameEdit"),$("#errorNom") ,"Error de nombre,") });
	$("#apeEdit").keyup(()=> {  validarNombre($("#apeEdit"),$("#errorApe") ,"Error de apellido,") });
	$("#cedulaEdit").keyup(()=> {	
		let valid = validarCedula($("#cedulaEdit"),$("#errorCedu") ,"Error de Documento,", $("#preDocument")) 
		clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) { validarC($("#cedulaEdit"), $("#errorCedu"), $("#preDocument")) }
        },700)
	});
	$("#emailEdit").keyup(()=> {  
		let valid = validarCorreo($("#emailEdit"),$("#errorEmail") ,"Error de email,") 
		clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) { validarE($("#emailEdit"), $("#errorEmail")) }
        },700)
	});
	let name, lastname, id, email;

	$('#borrarFoto').click(()=>{
		$('#imgEditar').attr('src', 'assets/img/profile_photo.jpg');
	})

	let imagen = document.getElementById('imgModal')
	let	imgPreview = document.getElementById('imgEditar')
	let	input = document.getElementById('foto')

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
	$('#aceptar').click(function(){
		if(!cropper) throw new Error('Error al recortar');

		canvas = cropper.getCroppedCanvas({
			width: 200,
			height: 200,
		});

		imgPreview.src = canvas.toDataURL();
		$('#fotoModal').modal('hide')

	})

	$("#enviarDatos").click((e)=>{

		e.preventDefault();

		if (click >= 1) throw new Error('Spam de clicks');

		name = validarNombre($("#nameEdit"),$("#errorNom") ,"Error de nombre,");
		lastname = validarNombre($("#apeEdit"),$("#errorApe") ,"Error de apellido,");
		id = validarCedula($("#cedulaEdit"),$("#errorCedu") ,"Error de Documento,", $("#preDocument"));
		email = validarCorreo($("#emailEdit"),$("#errorEmail") ,"Error de email,");

		if(!name && !lastname && !id && !email) {
			throw new Error('Datos inválidos');
		}
		let form = new FormData($('#formEditar')[0]);
		form.append("cedula", $("#preDocument").val()+"-"+$("#cedulaEdit").val());
		let borrar = $('#imgEditar').is('[src="assets/img/profile_photo.jpg"]');
		console.log(canvas);
		validarC($("#cedulaEdit"), $("#errorCedu"), $("#preDocument")).then(() => {
			
			
			validarE($("#emailEdit"), $("#errorEmail")).then(() => {
				
				
				
				if(borrar != true){
					if(typeof canvas === "undefined" || typeof canvas == null){
						Toast.fire({ icon: 'warning', title: 'No ha cambiado la imagen.' });
						throw Error('Canvas no tiene ninguna imagen cortada');
					}else{
						canvas.toBlob(function(blob){
							form.set('foto', blob, 'avatar.png')
							editarImagen(form);
						});
					}
				}
				
				if(borrar){
					form.append("borrar", "borrarImg");
					editarImagen(form);
				}
				
			})
		})
		click++
		
	})
	function editarImagen(form){
		$.ajax({
			type: "POST",
			url: '',
			dataType: 'JSON',
			data: form,
			contentType: false,
			processData: false,
			xhr(){
				let xhr = new window.XMLHttpRequest();
				$('#displayProgreso').show();
				xhr.upload.addEventListener("progress", function(event){

					if(event.lengthComputable){
						let porcentaje = parseInt( (event.loaded / event.total * 100), 10);
						$('#progressBar').data("aria-valuenow",porcentaje)
						$('#progressBar').css("width",porcentaje+'%')
						$('#progressBar').html(porcentaje+'%')
					}

				},false)
				xhr.addEventListener("progress", function(e){

					if (e.lengthComputable) {
						percentComplete = parseInt( (e.loaded / e.total * 100), 10);
						$('#progressBar').data("aria-valuenow",percentComplete);
						$('#progressBar').css("width",percentComplete+'%');
						$('#progressBar').html(percentComplete+'%');
					}else{
						$('#progressBar').html("Upload");
					}

				}, false);

				return xhr;
			},
			success(data){
				$('#displayProgreso').hide();
				if(data.foto.respuesta == 'Error'){
					$('#error').text(data.foto.error);
					throw new Error('Error de foto.');
				}
				if(data.foto.respuesta == 'Imagen guardada.' || data.foto.respuesta == 'Imagen eliminada.'){
					$('.fotoPerfil').attr('src', data.foto.url);
				}
				if (data.edit.respuesta == "Editado correctamente") {
					$('#formEditar').trigger('reset');
					rellenar();
					mostrarUsuarios();
					Toast.fire({ icon: 'success', title: 'Usuario Actualizado' });
					$("#perfil").click();
				}if(data.edit.respuesta == 'Error'){
					$('#error').text(data.edit.respuesta+", "+data.edit.error);
				}
				
			},
			error(data){
				$('#displayProgreso').hide();
				Toast.fire({ icon: 'error', title: 'Ha ocurrido un error al subir la imágen.' });
			}
		})
	}

	let actContra;
	function validarContra(input, div){
		let password = input.val();
		$.post('', {password, validarContraseña : 'asd'},
			function(response){
				data = JSON.parse(response);
				if(data.resultado == 'Error de contraseña'){
					actContra = false;
					div.text(data.error);
					input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
				}else{
					actContra = true;
				}
			});
	}

	$("#password").keyup(()=> {  
		let valid = validarContraseña($("#password"),$("#error2") ,"Error de Contraseña Actual,") 
		clearTimeout(timeout);
    	timeout = setTimeout(function() {
      		if(valid) validarContra($("#password"),$("#error2"));
    	}, 700);
	});
	$("#newPassword").keyup(()=> {  validarContraseña($("#newPassword"),$("#error2") ,"Error de Contraseña Nueva,") });
	$("#rePassword").keyup(()=> {  validarRepContraseña($("#rePassword"),$("#error2") ,$("#newPassword")) });
	let contra, reContra;
	$("#editContra").click((e)=> {
		e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		reContra = validarRepContraseña($("#rePassword"),$("#error2") ,$("#newPassword"));
		contra = validarContraseña($("#newPassword"),$("#error2") ,"Error de Contraseña Nueva,");

		if (actContra && contra && reContra) {
			$.ajax({
				url:'',
				method: 'post',
				dataType: 'JSON',
				data: {
					passwordAct: $("#password").val(),
					passwordNew: $("#newPassword").val(),
					passwordNewR: $("#rePassword").val()
				},success(des){
					// console.log(des);
					if (des.resultado === 'Error de contraseña') {
						$("#error2").text(des.error);
						  $("#password").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
					}
					if (des.resultado === 'Error de repetida') {
						$("#error2").text(des.error);
						$("#newPassword").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
					}
					if (des.resultado === 'Editada Contraseña') {
						Toast.fire({ icon: 'success', title: 'Contraseña Actualizada' });
						$("#perfil").click();
						$("#profile-change-password input").val("")
					}
				}
			})
		}
		click++
	})

	//Validacion de Existencia para la Cedula 
	let val
	function validarC(input, div, prefijo) {
		val = prefijo.val()+"-"+input.val()
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				cedula: val,
				validar: 'xd'
			},
				function (valid) {
					if (valid.resultado === "Error") {
						div.text("Error de Documento, " + valid.msj);
						input.attr("style", "border-color: red;");
						input.attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
						return reject(false);
					} else {
						div.text("");
						return resolve(true);
					}
				}
			)
		})
	}

	//Validacion de Existencia para Correo
	function validarE(input, div) {
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				correo: input.val(),
				validarE: 'lol'
			},
				function (valid) {
					if (valid.resultado === "Error") {
						div.text("Error de Correo, " + valid.msj);
						input.attr("style", "border-color: red;");
						input.attr("style", "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
						return reject(false);
					} else {
						div.text("");
						return resolve(true);
					}
				}
			)
		})
	}

})