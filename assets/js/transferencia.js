$(document).ready(function(){
	let mostrar
	let registrarPermiso, editarPermiso, eliminarPermiso;
	$.getJSON("getPermisos", function(permisos){
			registrarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : ''; 
			editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
			eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
	}).then(() => rellenar(true));

	function rellenar(bitacora = false){ 
	    $.post("",{mostrar: "", bitacora},function(data){
	        let tabla;
	        JSON.parse(data).forEach(row => {
	            tabla += `
	                <tr>
	                    <td>${row.rif}</th>
	                    <td scope="col">${row.razon_social}</td>
	                    <td scope="col">${row.direccion}</td>                      
	                    <td scope="col">${row.telefono}</td>
	                    <td scope="col">${(row.contacto == null) ? '' : row.contacto}</td>
	                    <td >
	                    	<span class="d-flex justify-content-center">
	                    		<button type="button" ${editarPermiso} class="btn btn-success editar mx-2" id="${row.cod_lab}" data-bs-toggle="modal" data-bs-target="#Editar"><i class="bi bi-pencil"></i></button>
	                    		<button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.cod_lab}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
	                    	</span>
	                    </td>
	                </tr>`;
	        });
	        $('#tableMostrar tbody').html(tabla);
	        mostrar = $('#tableMostrar').DataTable({resposive: true});
	    }).fail((e)=>{
	    	Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
	    	throw new Error('Error al mostrar listado: '+e);
	    })
    }
})
