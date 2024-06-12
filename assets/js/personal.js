$(document).ready(function() {

    let tabla
    //Consulta de Permisos
    let permisos, editarPermiso, eliminarPermiso, registrarPermiso;
	$.ajax({
		method: 'POST', url: "", dataType: 'json', data: { getPermisos: 'a' },
		success(data) { permisos = data; }
	}).then(function () {
		rellenar(true);
		registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
		editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
		eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		$('#agregarModalButton').attr(registrarPermiso, '');
	});

    //Rellenar Tabla
    function rellenar(bitacora = false){
        $.ajax({
            method: "post",
            url: "",
            dataType: "json",
            data: { mostrar: "xd", bitacora },
            success(data){
                data.forEach(row => {
                    tabla +=`
                    <tr>
                        <td>${row.cedula}</td>
                        <td>${row.nombres}</td>
                        <td>${row.apellidos}</td>
                        <td>${row.tipo}</td>
                        <td>${row.sede} </td>
                        <td class="d-flex justify-content-center">
                            <button type="button" class="btn btn-view datos mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#datosModal"><i class="bi bi-eye"></i></button>
                            <button type="button" ${editarPermiso} class="btn btn-registrar editar mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                            <button type="button" ${eliminarPermiso} class="btn btn-danger eliminar mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
                        </td>
					</tr>
                    `;
                });
                $('#tbody').html(tabla);
				tabla = $("#tabla").DataTable({
					responsive: true,
				});
            }
        })
    }

    
	let click = 0;
	setInterval(() => { click = 0; }, 2000);
    let timeout
    //Validaciones de Evento Registrar
    $("#preDocument").change(() => {
        let valid = validarCedula($("#cedu"), $("#errorCedu"), "Error de Documento,", $("#preDocument"))
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarC(" ",$("#cedu") , $("#errorCedu"), $("#preDocument")) }
        },700)
    })
    $("#cedu").keyup(() => { 
        let valid = validarCedula($("#cedu"), $("#errorCedu"), "Error de Documento,", $("#preDocument"))
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) { validarC(" ", $("#cedu"), $("#errorCedu"), $("#preDocument")) }
        },700)
    })
    $("#email").keyup(() => {
        let valid = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,")
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarE(" ", $("#email"), $("#errorEmail"))}
        },700)
    })
    $("#nom").keyup(() => {validarNombre($("#nom"), $("#errorNom"), "Error de Nombre,")})
    $("#ape").keyup(() => {validarNombre($("#ape"), $("#errorApe"), "Error de Apellido,")})
    $("#edad").change(() => {validarFechaAyer($("#edad"), $("#errorEdad"), "Error de Fecha,")})
    $("#direc").keyup(() => {validarDireccion($("#direc"), $("#errorDirec"), "Error de Direccion,")})
    $("#tele").keyup(() => {validarTelefono($("#tele"), $("#errorTele"), "Error de Telefono,")})
    $("#sede").change(() => {validarSelect($("#sede"), $("#errorSede"), "Error de Sede,")})
    $("#tipo").change(() => {validarSelect($("#tipo"), $("#errorTipo"), "Error de Tipo,")})

    $("#enviar").click((e) => {
        e.preventDefault();
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Registrar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

        let nombre = validarNombre($("#nom"), $("#errorNom"), "Error de Nombre,")
        let apellido = validarNombre($("#ape"), $("#errorApe"), "Error de Apellido,")
        let edad = validarFechaAyer($("#edad"), $("#errorEdad"), "Error de Fecha,")
        let direccion = validarDireccion($("#direc"), $("#errorDirec"), "Error de Direccion,")
        let telefono = validarTelefono($("#tele"), $("#errorTele"), "Error de Telefono,")
        let sede = validarSelect($("#sede"), $("#errorSede"), "Error de Sede,")
        let tipo = validarSelect($("#tipo"), $("#errorTipo"), "Error de Tipo,")
        let cedula = validarCedula($("#cedu"), $("#errorCedu"), "Error de Cédula,", $("#preDocument"))
        let correo = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,")
        if (cedula) {
            validarC(" ", $("#cedu"), $("#errorCedu"), $("#preDocument")).then(() => {
                if (correo) {
                    validarE(" ", $("#email"), $("#errorEmail")).then(() => {

                        if (nombre && apellido && edad && direccion  && telefono && sede && tipo) {
                            $.ajax({
                                type: 'POST',
                                url: '',
                                dataType: "json",
                                data: {
                                    dni: $("#preDocument").val()+"-"+$("#cedu").val(),
                                    name: $("#nom").val(),
                                    lastName: $("#ape").val(),
                                    email: $("#email").val(),
                                    age: $("#edad").val(),
                                    adress: $("#direc").val(),
                                    phone: $("#tele").val(),
                                    sede: $("#sede").val(),
                                    tipo: $("#tipo").val()
                                },
                                success(result) {
                                    console.log(result);
                                    if (result.resultado === 'Registrado') {
                                        tabla.destroy();
                                        $("#cerrarRegis").click();
                                        Toast.fire({ icon: 'success', title: 'Personal Registrado', showCloseButton: true})
                                        rellenar();
                                    } else {
                                        tabla.destroy();
                                        $("#errorRegis").text(result.resultado + ", " + result.error);
                                        rellenar();
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

    
    $(document).on('click', '.editar', function () {
		cedulaId = this.id;
        $.ajax({
			method: "post",
			url: '',
			dataType: "json",
			data: { select: "xd", cedulaId },
			success(data) {

				$("#ceduEdit").val(data[0].cedula.slice(2));
                $("#preDocumentEdit").val(data[0].cedula.charAt(0));
				$("#nomEdit").val(data[0].nombres);
				$("#apeEdit").val(data[0].apellidos);
				$("#emailEdit").val(data[0].correo);
				$("#edadEdit").val(data[0].fecha);
				$("#teleEdit").val(data[0].telefono);
				$("#direcEdit").val(data[0].direccion);
				$("#sedeEdit").val(data[0].sede);
				$("#tipoEdit").val(data[0].tipo);
			}
		})
	});

    // Mostrar Datos Unicos para 
    $(document).on('click', '.datos', function () {
		cedulaId = this.id;
        $.ajax({
			method: "post",
			url: '',
			dataType: "json",
			data: { select: "xd", cedulaId },
			success(data) {

                $("#ceduDatos").text(data[0].cedula);
				$("#nomDatos").text(data[0].nombres);
				$("#apeDatos").text(data[0].apellidos);
				$("#emailDatos").text(data[0].correo);
				$("#edadDatos").text(data[0].edad+" Años");
				$("#teleDatos").text(data[0].telefono);
				$("#direcDatos").text(data[0].direccion);
				$("#sedeDatos").text(data[0].nomSede);
				$("#tipoDatos").text(data[0].nomTipo);
			}
		})
	});

    //Validaciones de Evento Editar
    $("#preDocumentEdit").change(() => {
        let valid = validarCedula($("#ceduEdit"), $("#errorCeduEdit"), "Error de Documento,", $("#preDocumentEdit"))
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarC(cedulaId, $("#ceduEdit"), $("#errorCeduEdit"), $("#preDocumentEdit")) }
        },700)
    })
    $("#ceduEdit").keyup(() => { 
        let valid = validarCedula($("#ceduEdit"), $("#errorCeduEdit"), "Error de Documento,", $("#preDocumentEdit"))
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarC(cedulaId, $("#ceduEdit"), $("#errorCeduEdit"), $("#preDocumentEdit"))}
        },700)
    })
    $("#emailEdit").keyup(() => {
        let valid = validarCorreo($("#emailEdit"), $("#errorEmailEdit"), "Error de Correo,")
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarE(cedulaId, $("#emailEdit"), $("#errorEmailEdit"))}
        },700)
    })
    $("#nomEdit").keyup(() => {validarNombre($("#nomEdit"), $("#errorNomEdit"), "Error de Nombre,")})
    $("#apeEdit").keyup(() => {validarNombre($("#apeEdit"), $("#errorApeEdit"), "Error de Apellido,")})
    $("#edadEdit").change(() => {validarFechaAyer($("#edadEdit"), $("#errorEdadEdit"), "Error de Fecha,")})
    $("#direcEdit").keyup(() => {validarDireccion($("#direcEdit"), $("#errorDirecEdit"), "Error de Direccion,")})
    $("#teleEdit").keyup(() => {validarTelefono($("#teleEdit"), $("#errorTeleEdit"), "Error de Telefono,")})
    $("#sedeEdit").change(() => {validarSelect($("#sedeEdit"), $("#errorSedeEdit"), "Error de Sede,")})
    $("#tipoEdit").change(() => {validarSelect($("#tipoEdit"), $("#errorTipoEdit"), "Error de Tipo,")})


    $("#editar").click((e) => {
        e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Editar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}

        let nombre = validarNombre($("#nomEdit"), $("#errorNomEdit"), "Error de Nombre,")
        let apellido = validarNombre($("#apeEdit"), $("#errorApeEdit"), "Error de Apellido,")
        let edad = validarFechaAyer($("#edadEdit"), $("#errorEdadEdit"), "Error de Fecha,")
        let direccion = validarDireccion($("#direcEdit"), $("#errorDirecEdit"), "Error de Direccion,")
        let telefono = validarTelefono($("#teleEdit"), $("#errorTeleEdit"), "Error de Telefono,")
        let sede = validarSelect($("#sedeEdit"), $("#errorSedeEdit"), "Error de Sede,");
        let tipo = validarSelect($("#tipoEdit"), $("#errorTipoEdit"), "Error de Tipo,");
        let cedula = validarCedula($("#ceduEdit"), $("#errorCeduEdit"), "Error de Documento,", $("#preDocumentEdit"))
        let correo = validarCorreo($("#emailEdit"), $("#errorEmailEdit"), "Error de Correo,")
        if (cedula) {
            validarC(cedulaId, $("#ceduEdit"), $("#errorCeduEdit"), $("#preDocumentEdit")).then(() => {
                if (correo) {
                    validarE(cedulaId, $("#emailEdit"), $("#errorEmailEdit")).then(() => {

                        if (nombre && apellido && edad && direccion  && telefono && sede && tipo) {
                            $.ajax({
                                type: 'POST',
                                url: '',
                                dataType: "json",
                                data: {
                                    dniEdit: $("#preDocumentEdit").val()+"-"+$("#ceduEdit").val(),
                                    nameEdit: $("#nomEdit").val(),
                                    lastNameEdit: $("#apeEdit").val(),
                                    emailEdit: $("#emailEdit").val(),
                                    ageEdit: $("#edadEdit").val(),
                                    adressEdit: $("#direcEdit").val(),
                                    phoneEdit: $("#teleEdit").val(),
                                    sedeEdit: $("#sedeEdit").val(),
                                    tipoEdit: $("#tipoEdit").val(),
                                    cedulaId
                                },
                                success(result) {
                                    console.log(result);
                                    if (result.resultado === 'Editado') {
                                        tabla.destroy();
                                        $("#cerrarEdit").click();
                                        Toast.fire({ icon: 'success', title: 'Personal Registrado', showCloseButton: true })
                                        rellenar();
                                    } else {
                                        tabla.destroy();
                                        $("#errorEdit").text(result.resultado + ", " + result.error);
                                        rellenar();
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

    $(document).on('click', '.eliminar', function () {
		cedulaId = this.id;
	});

    $("#delete").click((e) => {
        e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		if (typeof permisos.Eliminar === 'undefined') {
			Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
			throw new Error('Permiso denegado.');
		}
        validarC(cedulaId, $("Noa"), $("#errorDel")).then(() => {
			$.ajax({
				type: "POST",
				url: '',
				dataType: 'json',
				data: {
					eliminar: 'eliminar',
					cedulaId
				},
				success(data) {
					console.log(data);
					if (data.resultado === "Eliminado") {
						tabla.destroy();
						$("#cerrarDel").click();
						Toast.fire({ icon: 'error', title: 'Personal Eliminado', showCloseButton: true })
						rellenar();
					} else {
						tabla.destroy();
						$("#errorDel").text("El Personal no Pudo Ser Eliminado");
						rellenar();
					}
				}
			})
		})
		click++
    })

    $(document).on('click', '#cerrarEdit', function () {
		$('#editModal p').text(" ");
		$("#editModal input, select").attr("style", "border-color: none;")
		$("#editModal input, select").attr("style", "backgraund-image: none;");
	})

	$(document).on('click', '#cerrarRegis', function () {
		$('#basicModal p').text(" ");
		$("#basicModal input, select").attr("style", "border-color: none;")
		$("#basicModal input, select").attr("style", "backgraund-image: none;");
	})

	$(document).on('click', '#cerrarDel', function () {
		$("#delModal p").text(" ");
	})

    //Validacion de Existencia para la Cedula 
	let val
	function validarC(valor, input, div, prefijo) {
		val = (input.val() == undefined) ? " " : prefijo.val()+"-"+input.val()
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				cedula: val,
				idVal: valor,
				validar: 'xd'
			},
				function (valid) {
					console.log(valid)
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
	function validarE(valor, input, div) {
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				correo: input.val(),
				idVal: valor,
				validarE: 'lol'
			},
				function (valid) {
					console.log(valid)
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
