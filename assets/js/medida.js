$(document).ready(function(){
	let mostrar;
	let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

	 $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
	  success(data){ permiso = data; }

	 }).then(function(){
	  rellenar(true);
	  registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
	  $('.agregarModal').attr(registrarPermiso, '');
	 })

function rellenar(bitacora = false){
	 $.ajax({
      type: "POST",
      url: '',
      dataType: 'json',
      data:{mostrar: 'xd' , bitacora},
      success(data){
      	let tabla;
	    data.forEach(row =>{
		editarPermiso = (permiso.editar != 1)?  'disable' : '';
		eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';

		tabla += `
		<tr>
		<td>${row.nombre}</td>
		<td class="d-flex justify-content-center">
		<button type="button" ${editarPermiso} id="${row.id_medida}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
		<button type="button" ${eliminarPermiso} id="${row.id_medida}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
		</td>
		</tr>
		`;
		})
		$('#tbody').html(tabla);
		mostrar = $('#tabla').DataTable({
		resposive : true
		})
      }
    })
}

function validarMedida(input , div , id = false){
    return new Promise((resolve , reject)=>{
      $.post('' ,{medida: input.val(), validarMedida: "medida" , id},
        function(data){
          let mensaje = JSON.parse(data);
          if(mensaje.resultado === "error"){
            div.text(mensaje.msg);
            input.attr("style","border-color: red;")
            input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
            return reject(false);
          }else{
            div.text(" ");
            return resolve(true);
          }
        })
    })
  }

 $("#mediNom").keyup(()=> {  
	let valid = validarNombre($("#mediNom"),$("#error") ,"Error del Medida de Producto,");
	if(valid){
		validarMedida($("#mediNom"), $("#error"));
	}
});


let ymedida
$("#enviar").click((e)=>{
	e.preventDefault();
	ymedida =  validarNombre($("#mediNom"),$("#error") ,"Error del Medida de Producto, ");
 	if(ymedida){
 $.ajax({
 	type:"POST",
 	url:"",
 	dataType:"json",
 	data:{

 		medida: $("#mediNom").val(),

 	},
 	success(data){
		if (data.resultado == 'Registrado con exito') {
            mostrar.destroy();
            rellenar();
            $('#user').trigger('reset'); 
            $("#closeRegis").click();
            $("#mediNom").attr("style","borde-color:none; backgraund-image: none;");
            $("#error").text("");
            Toast.fire({ icon: 'success', title: 'Medida de Producto registrado' , showCloseButton: true });
          }else if(data.resultado === 'error'){
            $("#error").text(data.msg);
            $("#mediNom").attr("style","border-color: red;")
            $("#mediNom").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
          }

   }
})

}

})

$("#closeRegis").click(()=>{
 	$("#basicModal input").attr("style","borde-color:none; backgraund-image: none;");
 	$("#error").text("");
 })


let id;
$(document).on('click', '.borrar',function (){
	id = this.id;
})
$("#delete").click((e)=>{
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:'',
		dataType:'json',
		data:{
			borrar:'cualquiera',
			id

		},
		success(medidaE){
			if (medidaE.resultado === "Eliminado"){
				mostrar.destroy();
				$("#cerrar").click();
				Toast.fire({icon: 'error', title:'medida de Producto eliminado'})
				rellenar();
			}else{
				console.log("No se elimino");
			}
		}
	})
})
let medidaEdit
$(document).on('click', '.editar', function(){
	medidaEdit = this.id;
	console.log(medidaEdit);
	$.ajax({
		type:"POST",
		url:'',
		dataType:'json',
		data:{
			editar:'si puede ser ',
			medidaEdit
		},

		success(log){
			console.log(log);
			$("#medidaNomEdit").val(log[0].nombre);
		}


	})
})
$("#medidaNomEdit").keyup(()=>{  
	let valid = validarNombre($("#medidaNomEdit"),$("#error2"),"Error de  de Medida Producto") 
	if(valid){
		validarMedida($("#medidaNomEdit"), $("#error2") , medidaEdit);
	}
});

let medidaT;
$("#enviarEditar").click((e)=>{
	console.log('hola')
e.preventDefault();
medidaT = validarNombre($("#medidaNomEdit"),$("#error2"),"Error de Medida de Producto");
if(medidaT){
$.ajax({
	type:"POST",
	url:"",
	dataType:"json",
	data:{
		medidaEditar: $("#medidaNomEdit").val(),
		medidaEdit
	},success(data){
		if (data.resultado == 'Editado') {
            mostrar.destroy();
            rellenar();
            $('.editMedida').trigger('reset'); 
            $("#closeEditar").click();
            $("#medidaNomEdit").attr("style","borde-color:none; backgraund-image: none;");
            $("#error2").text("");
            Toast.fire({ icon: 'success', title: 'Medida de Producto registrado' , showCloseButton: true });
          }else if(data.resultado === 'error'){
            $("#error2").text(data.msg);
            $("#medidaNomEdit").attr("style","border-color: red;")
            $("#medidaNomEdit").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
          }
	}
})
}
})














})