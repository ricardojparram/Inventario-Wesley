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
            data: { mostrar: "xd", bitacora },
            success(data){
                data.forEach(row => {
                    tabla +=`
                    <tr>
                        <td>${row.cedula}</td>
                        <td>${row.nombres}</td>
                        <td>${row.apellidos}</td>
                        <td>${row.empleado}</td>
                        <td>${row.sede} </td>
                        <td class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary datos mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                            <button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.cedula}" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
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
    $("#cedu").keyup(() => { 
        let valid = validarCedula($("#cedu"), $("#errorCedu"), "Error de cédula,")
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {console.log("cedu");}
        },700)
    })
    $("#email").keyup(() => {
        let valid = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,")
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {console.log("email");}
        },700)
    })
    $("#nom").keyup(() => {validarNombre($("#nom"), $("#errorNom"), "Error de Nombre,")})
    $("#ape").keyup(() => {validarNombre($("#ape"), $("#errorApe"), "Error de Apellido,")})
    $("#edad").keyup(() => {validarNumero($("#edad"), $("#errorEdad"), "Error de Edad,")})
    $("#direc").keyup(() => {validarDireccion($("#direc"), $("#errorDirec"), "Error de Direccion,")})
    $("#tele").keyup(() => {validarTelefono($("#tele"), $("#errorTele"), "Error de Telefono,")})
    $("#sede").change(() => {validarSelect($("#sede"), $("#errorSede"), "Error de Sede,")})
    $("#tipo").change(() => {validarSelect($("#tipo"), $("#errorTipo"), "Error de Tipo,")})

    $("#enviar").click((e) => {
        e.preventDefault();
        console.log("hola");
		if (click >= 1) throw new Error('Spam de clicks');
		// if (typeof permisos.Registrar === 'undefined') {
		// 	Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
		// 	throw new Error('Permiso denegado.');
		// }

        let nombre = validarNombre($("#nom"), $("#errorNom"), "Error de Nombre,")
        let apellido = validarNombre($("#ape"), $("#errorApe"), "Error de Apellido,")
        let edad = validarNumero($("#edad"), $("#errorEdad"), "Error de Edad,")
        let direccion = validarDireccion($("#direc"), $("#errorDirec"), "Error de Direccion,")
        let telefono = validarTelefono($("#tele"), $("#errorTele"), "Error de Telefono,")
        let sede = validarSelect($("#sede"), $("#errorSede"), "Error de Sede,")
        let tipo = validarSelect($("#tipo"), $("#errorTipo"), "Error de Tipo,")
        let cedula = validarCedula($("#cedu"), $("#errorCedu"), "Error de Cédula,")
        let correo = validarCorreo($("#email"), $("#errorEmail"), "Error de Correo,")
        if (cedula) {
            validarC(" ", $("#cedu"), $("#errorCedu")).then(() => {
                if (correo) {
                    validarE(" ", $("#email"), $("#errorEmail")).then(() => {

                        if (nombre && apellido && edad && direccion  && telefono && sede && tipo) {
                            $.ajax({
                                type: 'POST',
                                url: '',
                                dataType: "json",
                                data: {
                                    dni: $("#cedu").val(),
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
                                    if (result.resultado === 'Registrado correctamente.') {
                                        // tabla.destroy();
                                        $("#cerrarRegis").click();
                                        Toast.fire({ icon: 'success', title: 'Personal Registrado', showCloseButton: true})
                                        // rellenar();
                                    } else {
                                        tabla.destroy();
                                        $("#error").text(result.resultado + ", " + result.error);
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

    let cedulaId 
    $(document).on('click', '.editar', function () {
		cedulaId = this.id;
        $.ajax({
			method: "post",
			url: '',
			dataType: "json",
			data: { select: "xd", cedulaId },
			success(data) {

				$("#cedu").val(data[0].cedula);
				$("#nom").val(data[0].nombres);
				$("#ape").val(data[0].apellidos);
				$("#email").val(data[0].correo);
				$("#edad").val(data[0].edad);
				$("#tele").val(data[0].telefono);
				$("#direc").val(data[0].direccion);
				$("#sede").val(data[0].sede);
				$("#tipo").val(data[0].tipo);
			}
		})
	});

    //Validaciones de Evento Editar
    $("#ceduEdit").keyup(() => { 
        let valid = validarCedula($("#ceduEdit"), $("#errorCeduEdit"), "Error de cédula,")
        clearTimeout(timeout)
        timeout = setTimeout(function(){
            if (valid) {validarC(cedulaId, $("#ceduEdit"), $("#errorCeduEdit"))}
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
    $("#edadEdit").keyup(() => {validarNumero($("#edadEdit"), $("#errorEdadEdit"), "Error de Edad,")})
    $("#direcEdit").keyup(() => {validarDireccion($("#direcEdit"), $("#errorDirecEdit"), "Error de Direccion,")})
    $("#teleEdit").keyup(() => {validarTelefono($("#teleEdit"), $("#errorTeleEdit"), "Error de Telefono,")})
    $("#sedeEdit").change(() => {validarSelect($("#sedeEdit"), $("#errorSedeEdit"), "Error de Sede,")})
    $("#tipoEdit").change(() => {validarSelect($("#tipoEdit"), $("#errorTipoEdit"), "Error de Tipo,")})


    $("#editar").click((e) => {
        e.preventDefault()
		if (click >= 1) throw new Error('Spam de clicks');
		// if (typeof permisos.Editar === 'undefined') {
		// 	Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
		// 	throw new Error('Permiso denegado.');
		// }

        let nombre = validarNombre($("#nomEdit"), $("#errorNomEdit"), "Error de Nombre,")
        let apellido = validarNombre($("#apeEdit"), $("#errorApeEdit"), "Error de Apellido,")
        let edad = validarNumero($("#edadEdit"), $("#errorEdadEdit"), "Error de Edad,")
        let direccion = validarDireccion($("#direcEdit"), $("#errorDirecEdit"), "Error de Direccion,")
        let telefono = validarTelefono($("#teleEdit"), $("#errorTeleEdit"), "Error de Telefono,")
        let sede = validarSelect($("#sedeEdit"), $("#errorSedeEdit"), "Error de Sede,")
        let tipo = validarSelect($("#tipoEdit"), $("#errorTipoEdit"), "Error de Tipo,")
        let cedula = validarCedula($("#ceduEdit"), $("#errorCeduEdit"), "Error de Cédula,")
        let correo = validarCorreo($("#emailEdit"), $("#errorEmailEdit"), "Error de Correo,")
        if (cedula) {
            validarC(cedulaId, $("#ceduEdit"), $("#errorCeduEdit")).then(() => {
                if (correo) {
                    validarE(cedulaId, $("#emailEdit"), $("#errorEmailEdit")).then(() => {

                        if (nombre && apellido && edad && direccion  && telefono && sede && tipo) {
                            $.ajax({
                                type: 'POST',
                                url: '',
                                dataType: "json",
                                data: {
                                    dniEdit: $("#ceduEdit").val(),
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
                                    // if (result.resultado === 'Editado correctamente.') {
                                    //     tabla.destroy();
                                    //     $("#cerrarEdit").click();
                                    //     Toast.fire({ icon: 'success', title: 'Personal Registrado', showCloseButton: true })
                                    //     rellenar();
                                    // } else {
                                    //     tabla.destroy();
                                    //     $("#error").text(result.resultado + ", " + result.error);
                                    //     rellenar();
                                    // }
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
		// if (typeof permisos.Eliminar === 'undefined') {
		// 	Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.', showCloseButton: true });
		// 	throw new Error('Permiso denegado.');
		// }
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
						$("#cerrarModalDel").click();
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
	function validarC(valor, input, div) {
		val = (input.val() == undefined) ? " " : input.val()
		return new Promise((resolve, reject) => {
			$.getJSON('', {
				cedula: val,
				idVal: valor,
				validar: 'xd'
			},
				function (valid) {
					console.log(valid)
					if (valid.resultado === "Error") {
						div.text("Error de Cedula, " + valid.msj);
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
