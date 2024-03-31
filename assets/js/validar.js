
	const expresiones = {
		nombre: /^[a-zA-ZÀ-ÿ ]{0,30}$/,
		correo: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
		direccion: /^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#/,.-]){7,160}$/,
		cedula: /^[0-9]{7,10}$/,
		fecha: /^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/,
		numero: /^([0-9]+\.+[0-9]|[0-9])+$/,
		string: /^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9/#\s,.-]){3,50}$/,
		stringAnyLength: /^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9/#\s(),.-]){1,5000}$/,
		cuentaBank: /^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$/
	}

	function validarNombre(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.nombre.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");															
			return false
		}else if (!valid) {
			div.text(mensaje+" el nombre debe ser solo letras")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");						
			return false
		}else if (parametro.length<3) {
			div.text(mensaje+" el nombre debe tener mínimo 3 carácteres.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");									
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");	
			return true
		}			             
	}	

	function validarDireccion(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.direccion.test(parametro);
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir la dirección.")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");								
			return false
		}else if(!valid){
			div.text(mensaje+" debe intruducir una dirección válida")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");		
			return false
		}else if(parametro.length>158){
			div.text(mensaje+" direccion demasiada larga")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");		
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");
			return true
		}	
	}

	function validarStringLong(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.string.test(parametro);
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (!isNaN(parametro)) {
			div.text(mensaje+" debe llevar letras")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(parametro.length<4){
			div.text(mensaje+" debe introducir mínimo 4 carácteres.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
		}else if(!valid){
			div.text(mensaje+" carácteres no validos")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			input.removeClass('input-error');
			return true
		}			             
	}

	function validarStringLength(input, div, mensaje, length){
		parametro = input.val();
		let valid = expresiones.stringAnyLength.test(parametro);
		console.log(valid, parametro)
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (!isNaN(parametro)) {
			div.text(mensaje+" debe llevar letras")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(parametro.length<4){
			div.text(mensaje+" debe introducir mínimo 4 carácteres.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
		}else if(parametro.length>length){
			div.text(mensaje+" carácteres no validos")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
		}else if(!valid){
			div.text(mensaje+" carácteres no validos")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}

	function validarString(input, div, mensaje){
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (!isNaN(parametro)) {
			div.text(mensaje+" debe ser solo letras")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");
			return true
		}			             
	}

	function validarNumero(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.numero.test(parametro)
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(!valid){
			div.text(mensaje+" debe ser positivo.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}

	function validarPostal(input, div, mensaje){
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(parametro.length != 4) {
			div.text(mensaje+" codigo postal invalido.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");		
			return false					
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}


	function validarVC(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.numero.test(parametro)
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(parametro == 0) {
			div.text(mensaje+" no puede ser 0.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
		}else if(!valid){
			div.text(mensaje+" debe ser positivo.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}


	function validarTelefono(input, div, mensaje){
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false
		}else if (isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if (parametro.length<10){
			div.text(mensaje+" debe introducir maximo 10 carácteres.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");														
			return false	
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");
			return true
		}			             
	}						

	function validarCedula(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.cedula.test(parametro)
		if (parametro == null || parametro == "") {
			div.text(mensaje+" debe introducir datos.") 
			input.attr("style","border-color: red;") 
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");              
			return false
		}else if (parametro.length < 7 || parametro.length > 10) {
			div.text(mensaje+" debe entre 7 y 10 caracteres.")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");        
			return false
		}else if (!valid){
			div.text(mensaje+" dato inválido.") 
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");              
			return false 
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}                
	}	

	function validarContraseña(input, div, mensaje){
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")	
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");								
			return false
		}else if (parametro.length<8) {
			div.text(mensaje+" debe tener un mínimo de 8 caracteres.")	
			input.attr("style","border-color: red;")			
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");				
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");
			return true
		}			             
	}

	function validarRepContraseña(input, div, inputDos){
		parametro = input.val();
		parametroDos = inputDos.val();
		if (parametro==null||parametro=="") {
			div.text("Debe introducir datos.")	
			input.attr("style","border-color: red;")	
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");								
			return false
		}else if(parametro!=parametroDos){
			div.text("Las contraseñas deben coincidir.");
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;");
			return true
		}
	} 

	function validarCorreo(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.correo.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")	
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");								
			return false
		}
		if(!valid){
			div.text(mensaje+" debe introducir un correo válido.")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
			return false

		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","background-image: none;")
			return true
		}
	}	


	function validarCorreoOp(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.correo.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;")
			return true
		}
		if(!valid){
			div.text(mensaje+" debe introducir un correo válido.")
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
			return false

		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;")
			return true
		}
	}

	function validarTelefonoOp(input, div, mensaje){
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true                           
		}else if (isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.") 
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");             
			return false
		}else if (parametro.length<10){
			div.text(mensaje+" debe introducir mínimo 10 carácteres.")  
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                           
			return false  
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}
	}

	function validarCodBank(input, div, mensaje){    
		parametro = input.val();
		let valid = expresiones.numero.test(parametro)
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(isNaN(parametro)) {
			div.text(mensaje+" debe ser solo números.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(!valid){
			div.text(mensaje+" debe ser positivo.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
		}else if(parametro.length < 4){
           div.text(mensaje+" debe ser mayor que 3.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");	
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}

		function validarBanco(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.cuentaBank.test(parametro)
		if (parametro==null||parametro=="") {
            div.text(mensaje+" debe introducir datos.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
			return false
		}else if(!valid){
			div.text(mensaje+" debe ser un banco valido.")	
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");							
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}			             
	}

	function validarSelect(input, div, mensaje){ 
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" seleccione una opción") 
			input.attr("style","border-color: red;")
			input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                           
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			input.attr("style","backgraund-image: none;");
			return true
		}
	}

	function validarSelec2(input, select , div, mensaje){ 
		parametro = input.val();
		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir datos.") 
			select.attr("style","border-color: red;")
			select.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");                           
			return false
		}else{
			div.text(" ");
			select.attr("style","border-color: none;")
			select.attr("style","backgraund-image: none;");
			return true
		}
	}


	function validarFecha(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.fecha.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir una fecha")
			input.attr("style","border-color: red;")
			return false
		}else if (!valid) {
			div.text(mensaje+" introdusca una fecha")	
			input.attr("style","border-color: red;")
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")	
			return true
		}			             
	}

	Date.prototype.toDateInputValue = (function() {
		var local = new Date(this);
		local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
		return local.toJSON().slice(0,10);
	});

	const fechaActual = new Date().toDateInputValue();

	function fechaHoy(...input){
		input.forEach((n) =>{
			n.val(fechaActual)
		})
	}

	function validarFechaHoy(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.fecha.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir una fecha")
			input.attr("style","border-color: red;");															
			return false
		}else if (!valid) {
			div.text(mensaje+" introduzca una fecha")	
			input.attr("style","border-color: red;");					
			return false
		}else if (fechaActual > parametro) {
			div.text(mensaje+" la fecha es menor")	
			input.attr("style","border-color: red;")
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			return true
		}			             
	}

	function validarFechaAyer(input, div, mensaje){
		parametro = input.val();
		let valid = expresiones.fecha.test(parametro);

		if (parametro==null||parametro=="") {
			div.text(mensaje+" debe introducir una fecha");
			input.attr("style","border-color: red;");															
			return false
		}else if (!valid) {
			div.text(mensaje+" introduzca una fecha")	
			input.attr("style","border-color: red;");					
			return false
		}else if (fechaActual < parametro) {
			div.text(mensaje+" no puede ser mayor a la actual.")	
			input.attr("style","border-color: red;")
			return false
		}else{
			div.text(" ");
			input.attr("style","border-color: none;")
			return true
		}			             
	}

	function validarInputCantidadCarrito(input, tooltipClass = '.invalid-tooltip'){
		parametro = input.val();
		let valid = /^([0-9]+\.+[0-9]|[0-9])+$/.test(parametro)
		let tooltip = input.closest('.opciones').find(tooltipClass);
		if (parametro == null || parametro =="" || parametro == 0) {
			tooltip.text("Debe introducir datos.");
			tooltip.show();	
			input.attr("style","border-color: red;")
			return false
		}else if (isNaN(parametro)) {
			tooltip.text("Debe ser solo números.");
			tooltip.show();	
			input.attr("style","border-color: red;")
			return false
		}else if(!valid){
			tooltip.text("Debe ser positivo.");
			tooltip.show();	
			input.attr("style","border-color: red;")
		}else{
			tooltip.hide();
			input.attr("style","border-color: none;")
			return true 
		}			             
	}

		// Alertas personalizadas.

	const Toast = Swal.mixin({ 
		toast: true,  
		position: 'top-end', 
		showConfirmButton: false, 
		timer: 4000, timerProgressBar: true, 
		didOpen: (toast) => { 
			toast.addEventListener('mouseenter', Swal.stopTimer) 
			toast.addEventListener('mouseleave', Swal.resumeTimer) } 
	}) 

		// INICIALIZADOR DE POPPERS

	const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
	const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

// Alerta permiso denegado
const validarPermiso = permiso => {
	if(!permiso){
		Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
		throw new Error('Permiso denegado.');
	}
}