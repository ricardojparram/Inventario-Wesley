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
		<td>${row.nombre_t}</td>
		<td class="d-flex justify-content-center">
		<button type="button" ${editarPermiso} id="${row.id_tipo}" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
		<button type="button" ${eliminarPermiso} id="${row.id_tipo}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
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



 $("#tipNom").keyup(()=> {  validarNombre($("#tipNom"),$("#error") ,"Error del Tipo de Producto,") });


let ytipo
$("#enviar").click((e)=>{
	e.preventDefault();
	ytipo =  validarNombre($("#tipNom"),$("#error") ,"Error del Tipo de Producto, ");
 	if(ytipo){
 $.ajax({
 	type:"POST",
 	url:"",
 	dataType:"json",
 	data:{

 		tipo: $("#tipNom").val(),

 	},
 	success(data){
 	
 			mostrar.destroy()
 		
 		$("#closeAg").click();
 		Toast.fire({ icon: 'success', title:' Tipo de Producto registrado'})
       rellenar();


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
		success(tipoE){
			if (tipoE.resultado === "Eliminado"){
				mostrar.destroy();
				$("#cerrar").click();
				Toast.fire({icon: 'success', title:'Tipo de Producto eliminado'})
				rellenar();
			}else{
				console.log("No se elimino");
			}
		}
	})
})
let tipoedit
$(document).on('click', '.editar', function(){
	tipoedit= this.id;
	console.log(tipoedit);
	$.ajax({
		type:"POST",
		url:'',
		dataType:'json',
		data:{
			editar:'si puede ser ',
			tipoedit
		},

		success(log){
			console.log(log);
			$("#tipNomEdit").val(log[0].nombre_t);
		}


	})
})
$("#tipNomEdit").keyup(()=>{  validarNombre($("#tipNomEdit"),$("#error2"),"Error de Tipo de Producto") });

let jtipo;
$("#enviarEditar").click((e)=>{
	e.preventDefault();
jtipo = validarNombre($("#tipNomEdit"),$("#error2"),"Error de Tipo de Producto");
if(jtipo){
$.ajax({
	type:"POST",
	url:"",
	dataType:"json",
	data:{
		tipoEditar: $("#tipNomEdit").val(),
		tipoedit
	},success(){
		if (jtipo) {
        mostrar.destroy();
        $("#closeEditar").click();
        Toast.fire({ icon: 'success', title: 'Tipo de cambio registrado'})
        rellenar();
      }else{
        e.preventDefault()
      }
	}
})
}
})














})