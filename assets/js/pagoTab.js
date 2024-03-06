
function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if ($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({ scrollTop: scroll_to }, 0);
	}
}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if (direction == 'right') {
		new_value = now_value + (100 / number_of_steps);
	}
	else if (direction == 'left') {
		new_value = now_value - (100 / number_of_steps);
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

$(document).ready(function () {
	var data
	let next_step = false;
	let tipo, select, memas, cambio, calTipo, valorT
	let pTarifas = 0

	temporizador();
	DatosP();
	precio();
	selectTipoPago();
	banco();
	tarifa();

	let click = 0;
	setInterval(() => { click = 0; }, 2000);




	// Datos Personales
	function DatosP() {
		$.ajax({
			type: "post",
			url: "",
			dataType: "json",
			data: { datos: 'xd' },
			success(data) {
				memas = data;

				$("#nomClien").val(data[0].nombre + ' ' + data[0].apellido);
				$("#cedClien").val(data[0].cedula);
				$("#emailClien").val(data[0].correo);
				$("#teleClien").val(data[0].celular);
				$("#direcClien").val(data[0].direccion);
			}
		});
	}


	let impuesto
	let total, pTotal
	let precioUsd
	// Precio y Cambio
	function precio() {
		$.ajax({
			type: "post",
			url: "",
			dataType: "json",
			data: { mostrarP: "hola" },
			success(pre) {
				precioUsd = parseFloat(pre[0].cambio)
				cambio = parseFloat(pre[0].id_cambio)
				pTotal = parseFloat(pre[0].total)
				if (pre[0].cuenta > 0) {
					$("#valorBs").html(parseFloat(pTotal).toFixed(2) + " Bs");
					impuesto = pTotal * 0.16;
					$("#impuesto").html(parseFloat(impuesto).toFixed(2) + " Bs");
					$('#pEnvio').html(parseFloat(pTarifas).toFixed(2) + " Bs");
					total = pTotal + impuesto + parseFloat(pTarifas);
					$("#total").html(parseFloat(total).toFixed(2) + " Bs");
					$("#valorUsd").html(parseFloat(total / precioUsd).toFixed(2) + " $");

				} else {
					let div = `
						<div class="col-8 mx-auto text-center">
                    		<h3>Su Carrito Esta Vacio</h3>
                		</div>`;
					$("#precios").html(div);
				}
			}
		})
	}

	// Mostrar Estado y Sede de Envio
	estado();
	function estado() {
		let selEm
		$.ajax({
			type: "post",
			url: "",
			dataType: "json",
			data: { mostrarE: "est" },
			success(empre) {
				var estados = empre
				estados.forEach(row => {
					selEm += `
					<option value="${row.id_estado}">${row.nombre}</option>
					`;
				})
				$('#estado').html('<option selected disabled>Nombre</option>' + selEm);
			}
		});


	};

	function tarifa() {
		$.ajax({
			type: "post",
			url: '?url=envios',
			dataType: 'JSON',
			data: {
				precio_envio: "ss"
			},
			success(data) {
				valorT = parseFloat(data.precio_solo)
			}
		})
	}

	let nomEstado, sede, sedes, sedeU
	$("#estado").change(() => {
		let selEnvi
		nomEstado = $("#estado").val();

		$.ajax({
			type: "post",
			url: "",
			dataType: "json",
			data: { mostrarS: "xd", nomEstado },
			success(sed) {
				sedes = sed
				sed.forEach(row => {
					selEnvi += `
						<option value="${row.id_sede}">${row.nombre}</option>
						`;
				})
				$('#sede').html('<option selected disabled>Nombre</option>' + selEnvi);

			}
		});
	})

	$("#sede").change(() => {
		sede = $("#sede").val()
		sedeU = sedes.find(item => item.id_sede == sede)
		$("#ubicacion").val(sedeU.ubicacion);

	})


	/*
		Form
	*/
	$('.f1 fieldset:first').fadeIn('slow');


	// Validicaiones Primer Step

	$("#direcClien").keyup(() => { validarDireccion($("#direcClien"), $("#errorDirec"), "Error de Direccion,") });
	$("#emailClien").keyup(() => { validarCorreo($("#emailClien"), $("#errorEmail"), "Error de Correo,") });
	$("#teleClien").keyup(() => { validarTelefono($("#teleClien"), $("#errorTele"), "Error de Telefono,") });


	$("#1").click((e) => {

		let direccion = validarDireccion($("#direcClien"), $("#errorDirec"), "Error de Direccion,");
		let correo = validarCorreo($("#emailClien"), $("#errorEmail"), "Error de Correo,");
		let telefono = validarTelefono($("#teleClien"), $("#errorTele"), "Error de Telefono,");
		let cedula = validarCedula($("#cedClien"), $("#errorCed"), "Error de Cedula,");
		let nombre = validarNombre($("#nomClien"), $("#errorNomApe"), "Error de Nombre,");

		if (nombre && direccion && telefono && cedula && correo) {
			next_step = true;
		} else {
			next_step = false;
		}
	});
	let idGlass
	$(".glass").fadeOut(0);

	$(".botEntre").on('click', function () {
		$("#errorBot").text("");
		idGlass = this.id;
		next_step = false;
		switch (idGlass) {
			case "repartidor":
				$(".glass").fadeOut(0);
				$("#delivery").fadeIn(300);
				pTarifas = precioUsd * 1
				precio()
				break;
			case "nacional":
				$(".glass").fadeOut(0);
				$("#envio").fadeIn(300);
				calcularTipo().then((res) => calTipo = res)
				// calTipo = calcularTipo();

				pTarifas = valorT
				precio();
				break;
			case "persona":
				$(".glass").fadeOut(0);
				$("#retirar").fadeIn(300);
				pTarifas = 0
				precio();
				break;

			default:
				break;
		}
	})

	async function calcularTipo() {
		let valid = false
		await $.ajax({
			url: '',
			type: 'post',
			dataType: 'JSON',
			data: { calculaT: 'vv' },
			success(data) {
				if (data.resultado == 'cuenta superior') {
					$("#errorBot").html("No puedes exceder las 12 unidades (no más de tres del mismo tipo) por envio nacional")
					valid = false
				} else {
					$("#errorBot").html("")
					valid = true
				}
			}

		})
		return valid
	}

	// Validicaiones Segundo Step


	$("#calle").keyup(() => { validarString($("#calle"), $("#errorCalle"), "Error de Calle,") });
	$("#numAv").keyup(() => { validarString($("#numAv"), $("#errorNumAv"), "Error de Avenida,") });
	$("#numCasa").keyup(() => { validarString($("#numCasa"), $("#errorNumCasa"), "Error de Casa,") });
	$("#ref").keyup(() => { validarString($("#ref"), $("#errorRef"), "Error de Referencia,") });
	$("#estado").change(() => { validarSelect($("#estado"), $("#errorEstado"), "Error de Estado,") });
	$("#sede").change(() => { validarSelect($("#sede"), $("#errorSede"), "Error de Sede,") });

	$("#2").click((e) => {
		let estado, sedeV, calle, avenida, numCasa, referencia
		if (typeof idGlass == 'undefined') {
			$("#errorBot").text("Elija una Opcion de Entrega");
		} else {
			$("#errorBot").text("");
		}

		switch (idGlass) {
			case "repartidor":
				calle = validarString($("#calle"), $("#errorCalle"), "Error de Calle,");
				avenida = validarString($("#numAv"), $("#errorNumAv"), "Error de Avenida,");
				numCasa = validarString($("#numCasa"), $("#errorNumCasa"), "Error de Casa,");
				referencia = validarString($("#ref"), $("#errorRef"), "Error de Referencia,");

				if (calle && avenida && numCasa && referencia) {
					next_step = true;
				} else {
					next_step = false;
				}
				break;
			case "nacional":
				estado = validarSelect($("#estado"), $("#errorEstado"), "Error de Estado,");
				sedeV = validarSelect($("#sede"), $("#errorSede"), "Error de Sede,");
				calcularTipo();
				if (estado && sedeV && calTipo) {
					next_step = true;
				} else {
					next_step = false;
				}
				break;
			case "persona":

				next_step = true;
				break;

			default:
				next_step = false;
				break;
		}

	});
	//////////////////////////////////////////////////////////////////////////////////////////////////////

	fechaHoy($('#fecha'));

	// fila de tipo pago
	let newRowTipo = ` <tr>
						  <td width="1%"><a class="removeRowPagoTipo a-asd" ><i class="bi bi-trash-fill"></i></a></td>
						  <td width='30%'> 
							<select class="select-tipo select-asd" name="TipoPago">
							  <option></option>
							</select>
						  </td>
						  <td width='15%' class="precio"><input class="select-asd precio-tipo" type="number" placeholder="0,00"></td>
						</tr>`;

	// Caracteriticas de la fila Tipo Pago

	function filaTipoN() {
		$('#FILL').append(newRowTipo);
		selectTipoPago();
		validarRepetido();
		validarValores();

	}
	// Agregar fila para insertar tipo de pago
	$('.newRowPago').on('click', function (e) {
		filaTipoN();
	});

	// Caracteriticas de la fila Tipo Pago
	function borrarFilaTipoN() {
		validarRepetidoB();
		//validarPrecio($(this));
	}
	// ELiminar fila Tipo de Pago
	$('body').on('click', '.removeRowPagoTipo', function (e) {
		$(this).closest('tr').remove();
		borrarFilaTipoN();
	});

	let datosTrans, datosMovil

	//  rellena los select de las filas de tipos
	function selectTipoPago() {
		$.ajax({
			url: '',
			method: 'POST',
			dataType: 'json',
			data: {
				selectTipo: "tipo de pago"
			},
			success(data) {
				let option = ""
				data.forEach((row) => {
					option += `<option value="${row.id_tipo_pago}">${row.des_tipo_pago}</option>`
				})
				$('.select-tipo').each(function () {
					if (this.children.length == 1) {
						$(this).append(option);
						$(this).chosen({
							width: '25vw',
							placeholder_text_single: "Selecciona un tipo de pago",
							search_contains: true,
							allow_single_deselect: true,
						});
					}
				})

			}
		})
		let move
		$(".select-tipo").on('change', function () {
			move = $(this).val()
			let contadorM = 0, contadorT = 0
			$('.select-tipo').each(function () {
				contadorM = ($(this).val() == 4) ? contadorM + 1 : contadorM
				contadorT = ($(this).val() == 5) ? contadorT + 1 : contadorT
				if (contadorM < 1) {
					$(".movil").fadeOut(0);
				}
				if (contadorT < 1) {
					$(".trans").fadeOut(0);
				}
				if (contadorM < 1 && contadorT < 1) {
					$("#fade").fadeOut(0);
				}
			})
			tipoPago(move);

		})
	}

	$(".trans, .movil").fadeOut(0);

	function tipoPago(tipo) {
		if (tipo == 4 || tipo == 5) {
			let campos
			let resultado
			let muvi
			$("#fade").fadeIn(400);

			$("#fade input").val('');
			$.ajax({
				url: '',
				method: 'POST',
				dataType: 'json',
				data: {
					mostrarT: 'xd', tipo
				},
				success(data) {
					data
					let option = ""
					switch (tipo) {
						case '4':
							option = ""
							data.forEach((row) => {
								option += `<option value="${row.id_datos_cobro}">${row.nombre}</option>`
							})
							$("#bancTipoM").html("<option selected disabled>Nombre</option>" + option)
							$(".movil").fadeIn(300);
							// $(".trans").fadeOut(0);
							$("#bancTipoM").on('change', function () {

								muvi = $("#bancTipoM").val()
								resultado = data.find(item => item.id_datos_cobro == muvi);
								$("#cedBancM").val(resultado.rif_cedula)
								$("#teleMovil").val(resultado.telefono)
								$("#codBanc").val(resultado.codigo)
								datosMovil = resultado.id_datos_cobro
							})

							break;
						case '5':
							option = ""
							data.forEach((row) => {
								option += `<option value="${row.id_datos_cobro}">${row.nombre}</option>`
							})
							$("#bancTipoT").html("<option selected disabled>Nombre</option>" + option)
							$(".trans").fadeIn(300);
							// $(".movil").fadeOut(0);

							$("#bancTipoT").on('change', function () {
								muvi = $("#bancTipoT").val()
								resultado = data.find(item => item.id_datos_cobro == muvi);
								$("#cedBancT").val(resultado.rif_cedula)
								$("#numCuen").val(resultado.num_cuenta)
								datosTrans = resultado.id_datos_cobro
							})
							break;

						default:
							break;

					}
				}
			})
		}

		return 0
	}

	function banco() {
		let opu
		$.ajax({
			url: '',
			method: 'POST',
			dataType: 'json',
			data: {
				mostrarB: "xd"
			}, success(data) {
				data.forEach(row => {
					opu += `<option value="${row.id_banco}">${row.nombre}</option>`;
				})
				$("#bancTipoRT, #bancTipoRM").html("<option selected disabled>Nombre</option>" + opu)

			}
		})
	}



	function validarRepetido() {
		let selects
		$(".select-tipo").change(function () {
			let valores = [];
			let repetidos = false
			selects = $(this)

			$('.select-tipo').each(function () {
				// $(this).attr('valid', 'true');
				var valor = $(this).val();
				valores.push(valor);
				if ($(this).val() == 4 && $(this).val() == 4) {
					tipoPago($(this).val())
				}
			});

			for (var i = 0; i < valores.length; i++) {
				var contador = 0;

				for (var j = 0; j < valores.length; j++) {
					if (valores[i] === valores[j]) {
						contador++;
						cuenta();
					}

				}

			}


			function cuenta() {

				if (contador >= 2) {
					selects.closest('tr').attr('style', 'border-color: red;')
					selects.attr('valid', 'false');
					$('#error3').text('No pueden haber tipos de pagos repetidos');
				} else {
					selects.attr('valid', 'true');
					selects.closest('tr').attr('style', 'border-color: none;')
				}
			}

			$('.select-tipo').each(function () {
				if ($(this).is('[valid="true"]')) {
					$(this).closest('tr').attr('style', 'border-color: none;');
				}
				if (!$('.select-tipo').is('[valid="false"]')) {
					$('#error3').text('');
				}

			})
		})
	}
	let contadorB
	function validarRepetidoB() {
		let valores = [];
		$('.select-tipo').each(function () {
			selects = $(this)
			// $(this).attr('valid', 'true');
			var valor = $(this).val();
			valores.push(valor);
		});

		for (var i = 0; i < valores.length; i++) {
			contadorB = 0;

			for (var j = 0; j < valores.length; j++) {
				if (valores[i] === valores[j]) {
					contadorB++;
					cuentaB();
				}

			}

		}


		function cuentaB() {

			if (contadorB >= 2) {
				selects.closest('tr').attr('style', 'border-color: red;')
				selects.attr('valid', 'false');
				$('#error3').text('No pueden haber tipos de pagos repetidos');
			} else {
				selects.attr('valid', 'true');
				selects.closest('tr').attr('style', 'border-color: none;')
			}
		}

		$('.select-tipo').each(function () {
			if ($(this).is('[valid="true"]')) {
				$(this).closest('tr').attr('style', 'border-color: none;');
			}
			if (!$('.select-tipo').is('[valid="false"]')) {
				$('#error3').text('');
			}

		})
	}



	function validarPrecio(input) {
		let valor = input.val();
		if (valor <= 0 || isNaN(valor)) {
			$('#errorMonto').text('Precio inválido.');
			input.css({ 'border': 'solid 1px', 'border-color': 'red' })
			input.attr('valid', 'false')
			return false;
			// }else if (valor == null || valor == '') {
			// 	$('#errorMonto').text('Rellene los campos vacios.');
			//   	input.css({'border': 'solid 1px', 'border-color':'red'})
			//   	input.attr('valid','false')
		} else {
			$('#errorMonto').text('');
			input.css({ 'border': 'none' });
			input.attr('valid', 'true');
			return true;
		}
	}
	validarRepetido();
	validarValores();
	function validarValores() {
		$('.precio input').keyup(function () { validarPrecio($(this)) });
	}

	$("#tipoP").change(() => { validarSelect($("#tipoP"), $("#errorTipoP"), "Error de Tipo,") });
	$("#bancTipo").change(() => { validarSelect($("#bancTipo"), $("#errorBancTipo"), "Error de Banco,") });

	$("#bancTipoT").change(() => { validarSelect($("#bancTipoT"), $("#errorBancTipoT"), "Error de Banco,") });
	$("#referenciaTrans").change(() => { validarNumero($("#referenciaTrans"), $("#errorReferenciaTrans"), "Error de Referencia,") });
	$("#bancTipoRT").change(() => { validarSelect($("#bancTipoRT"), $("#errorBancTipoRT"), "Error de Banco,") });

	$("#bancTipoM").change(() => { validarSelect($("#bancTipoM"), $("#errorBancTipo"), "Error de Banco,") })
	$("#referenciaMovil").change(() => { validarNumero($("#referenciaMovil"), $("#errorReferenciaMovil"), "Error de Referencia,") })
	$("#bancTipoRM").change(() => { validarSelect($("#bancTipoRM"), $("#errorbancTipoRM"), "Error de Banco,") })
	let direcE


	$('#3').on('click', function () {
		if (click >= 1) throw new Error('Spam de clicks');
		let vmonto
		let monto = 0
		let valid = false
		let vtipoPago = true
		let vprecio = true
		let estado, sede, calle, avenida, numCasa, referencia

		let direccion = validarDireccion($("#direcClien"), $("#errorDirec"), "Error de Direccion,");
		let correo = validarCorreo($("#emailClien"), $("#errorEmail"), "Error de Correo,");
		let telefono = validarTelefono($("#teleClien"), $("#errorTele"), "Error de Telefono,");
		let cedula = validarCedula($("#cedClien"), $("#errorCed"), "Error de Cedula,");
		let nombre = validarNombre($("#nomClien"), $("#errorNomApe"), "Error de Nombre,");

		switch (idGlass) {
			case "repartidor":
				calle = validarString($("#calle"), $("#errorCalle"), "Error de Calle,");
				avenida = validarString($("#numAv"), $("#errorNumAv"), "Error de Avenida,");
				numCasa = validarString($("#numCasa"), $("#errorNumCasa"), "Error de Casa,");
				referencia = validarString($("#ref"), $("#errorRef"), "Error de Referencia,");

				if (calle && avenida && numCasa && referencia) {
					valid = true;
				} else {
					valid = false;
				}
				$("#estado, #sede").val(" ")
				break;
			case "nacional":
				estado = validarSelect($("#estado"), $("#errorEstado"), "Error de Estado,");
				sedeV = validarSelect($("#sede"), $("#errorSede"), "Error de Sede,");
				

				if (estado && sedeV && calTipo) {
					valid = true;
				} else {
					valid = false;
				}
				$("#calle, #numAv, #numCasa, #ref").val(" ")
				break;

			case "persona":

				valid = true;
				$("#estado, #sede").val(" ")
				$("#calle, #numAv, #numCasa, #ref").val(" ")
				break;

			default:
				valid = false;
				break;
		}

		$('#FILL tr').each(function () {
			let tipoPago = $(this).find('.select-tipo').val();
			if (tipoPago == "" || tipoPago == null) {
				$(this).attr('style', 'border-color: red;')
				$(this).attr('valid', 'false');
				vtipoPago = false;
				$('#error3').text('No debe haber tipo de pagos vacíos.')
			}
		})
		let value = 0
		$('.precio input').each(function () {
			let valPre = validarPrecio($(this))
			value = parseFloat($(this).val());
			if (!isNaN(value)) {
				monto = parseFloat(monto + value)
			}

			if (!valPre) {
				vprecio = false
			}

		});

		let repetido = false
		if ($('.select-tipo').is('[valid="false"]')) {
			repetido = false
		} else if (!$('.select-tipo').is('[valid="false"]')) {
			repetido = true
		}

		// monto = (typeof monto === 'undefined') ? 0 : monto;

		if (monto == total) {
			vmonto = true;
			$("#errorMonto").text('')
		} else if (monto > total) {
			vmonto = false;
			$("#errorMonto").text('Error en el monto, la Cantidad es Mayor')
			$('.precio input').css({ 'border': 'solid 1px', 'border-color': 'red' })
		} else if (monto < total) {
			vmonto = false;
			$("#errorMonto").text('Error en el monto, la Cantidad es Menor')
			$('.precio input').css({ 'border': 'solid 1px', 'border-color': 'red' })
		} else {
			vmonto = false;
			$("#errorMonto").text('Error en el monto, la Cantidad es Invalida')
		}

		let push = []

		if (idGlass == "repartidor") {
			direcE = $("#calle").val() + " " + $("#numAv").val() + " " + $("#numCasa").val() + " " + $("#ref").val()
		} else {
			direcE = ""
		}



		let tipVal
		let movil = true
		let trans = true

		$(".trans input, .trans select").attr('style', 'border-coler: none');
		$(".movil input, .movil select").attr('style', 'border-coler: none');
		$(".trans p").text("");
		$(".movil p").text("");

		$('.select-tipo').each(function (i) {
			tipVal = $(this).closest('tr')
			switch (tipVal.find("select").val()) {
				case "4":
					let cep = validarSelect($("#bancTipoM"), $("#errorBancTipo"), "Error de Banco,");
					let nao = validarNumero($("#referenciaMovil"), $("#errorReferenciaMovil"), "Error de Referencia,");
					let nin = validarSelect($("#bancTipoRM"), $("#errorbancTipoRM"), "Error de Banco,");
					if (!cep || !nao || !nin) {
						movil = false
					}

					push[i] = { tipo: tipVal.find("select").val(), monto: tipVal.find(".precio-tipo").val(), bancoReceptor: datosMovil, referencia: $("#referenciaMovil").val(), bancoEmisor: $("#bancTipoRM").val(), cambio: cambio };
					break;
				case "5":
					let poa = validarSelect($("#bancTipoT"), $("#errorBancTipoT"), "Error de Banco,");
					let bro = validarNumero($("#referenciaTrans"), $("#errorReferenciaTrans"), "Error de Referencia,");
					let nen = validarSelect($("#bancTipoRT"), $("#errorBancTipoRT"), "Error de Banco,");
					if (!poa || !bro || !nen) {
						trans = false
					}

					push[i] = { tipo: tipVal.find("select").val(), monto: tipVal.find(".precio-tipo").val(), bancoReceptor: datosTrans, referencia: $("#referenciaTrans").val(), bancoEmisor: $("#bancTipoRT").val(), cambio: cambio };
					break;

				default:

					push[i] = { tipo: tipVal.find("select").val(), monto: tipVal.find(".precio-tipo").val(), bancoReceptor: " ", referencia: " ", bancoEmisor: " ", cambio: cambio };
					break;
			}



		})



		if (nombre && direccion && telefono && cedula && correo && movil && trans && valid && vtipoPago && vprecio && vmonto && repetido) {


			$.ajax({
				url: '',
				method: 'POST',
				dataType: 'json',
				data: {
					cedula: memas[0].cedula,
					nombre: memas[0].nombre,
					apellido: memas[0].apellido,
					direccionF: $("#direcClien").val(),
					telefono: $("#teleClien").val(),
					correo: $("#emailClien").val(),

					sede: $("#sede").val(),

					direccionE: direcE,
					detalles: push


				},
				success(final) {
					if (final.resultado === "Registrado Pedido") {
						clearInterval(intervalo)
						Swal.fire({
							title: 'Compra Realizada',
							text: 'Espere un Maximo de 24 Horas para la Revision de su Pago',
							icon: 'success',
						})
						actualizarNotificacion(final).then(() => {
						   localStorage.clear()
							setTimeout(function () {
								location = '?url=inicio'
							}, 2500); 
						  }
						);	
					} else {
						Toast.fire({ icon: 'error', title: 'No fue Posible Realizar la Compra' })
					}
				}
			})
			

		} else {

		}

		click++
	})



	function actualizarNotificacion(mensaje) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: '?url=notificaciones',
				dataType: 'json',
				method: 'POST',
				data: {
					mensaje: JSON.stringify(mensaje),
					nombreNotificacion: 'compra_realizada'
				},
				success: () => {
					resolve();
				},
				error: () => {
					reject();
				}
			});
		});
	}


	// next step
	$('.f1 .btn-next').on('click', function () {
		let parent_fieldset = $(this).parents('fieldset');

		// navigation steps / progress steps
		let current_active_step = $(this).parents('.f1').find('.f1-step.active');
		let progress_line = $(this).parents('.f1').find('.f1-progress-line');

		if (next_step) {
			parent_fieldset.fadeOut(400, function () {
				// change icons
				current_active_step.removeClass('active').addClass('activated').next().addClass('active');
				// progress bar
				bar_progress(progress_line, 'right');
				// show next step
				$(this).next().fadeIn();
				// scroll window to beginning of the form
				scroll_to_class($('.f1'), 20);
			});
		}

	});

	// previous step
	$('.f1 .btn-previous').on('click', function () {
		// navigation steps / progress steps
		let current_active_step = $(this).parents('.f1').find('.f1-step.active');
		let progress_line = $(this).parents('.f1').find('.f1-progress-line');

		$(this).parents('fieldset').fadeOut(400, function () {
			// change icons
			current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
			// progress bar
			bar_progress(progress_line, 'left');
			// show previous step
			$(this).prev().fadeIn();
			// scroll window to beginning of the form
			scroll_to_class($('.f1'), 20);
		});
	});

	let intervalo
	function temporizador() {
		let color = true
		$.ajax({
			method: 'post',
			url: '',
			dataType: 'JSON',
			data: {
				tiempo: "val"
			}, success(data) {
				let segundos = (data)
				intervalo = setInterval(() => {

					let fecha = new Date(segundos);
					let fechaActual = new Date();
					let diferenciaEnSegundos = (fecha - fechaActual) / 1000;

					let hora = 3600 + diferenciaEnSegundos

					if (hora <= 0) {
						return eliminarVenta("tiempo")


					}
					let horas = Math.floor(hora / 3600).toString().padStart(2, '0')
					let minutos = Math.floor((hora % 3600) / 60).toString().padStart(2, '0')
					let segundosRestantes = Math.floor(hora % 60).toString().padStart(2, '0')
					if (hora <= 600) {
						if (color) {
							color = false;
							$("#temporizador").css("color", "black");
						} else {
							$("#temporizador").css("color", "red");
							color = true
						}
					}
					$("#temporizador").text(horas + ":" + minutos + ":" + segundosRestantes);
					//$("#temporizador").text(hora);


				}, 1000)
			}
		})
	}

	function eliminarVenta(elimina) {
		let eliminar = (elimina == "tiempo" || elimina == "cancelar") ? "sumar" : "contar"
		$.ajax({
			url: '',
			dataType: 'JSON',
			method: 'POST',
			data: { eliminar: eliminar },
			success(asd) {
				if (asd.resultado == "Eliminado" && elimina == "tiempo") {
					Swal.fire({
						title: 'Tiempo Limite Superdado!',
						text: 'Acaba de Superar el Tiempo Limite de 1 Hora',
						icon: 'error',
					})

				}
				if (asd.resultado == "Eliminado" && elimina == "cancelar") {
					Swal.fire({
						title: 'Compra Cancelada',
						text: 'Ha cancelado su compra',
						icon: 'error',
					})

				}
				clearInterval(intervalo)
				setTimeout(function () {
					location = '?url=carrito'
				}, 1400);
			}
		})
	}

	$('#cancel').on('click', function () {
		if (click >= 1) throw new Error('Spam de clicks');
		eliminarVenta("cancelar")
		click++;
	})

});