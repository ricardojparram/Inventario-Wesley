$(document).ready(function(){

	let mostrar;
	let comprobarEnvioPermiso;
	$.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:''},
		success(permisos){
			comprobarPagoPermiso = (typeof permisos['Comprobar pago'] === "undefined") ? 'disabled' : '';
		}
	}).then(() => rellenar(true));

	const estados = {
		"1" : `<button type="button" class="btn btn-success estadoEnvio" data-bs-toggle="modal" data-bs-target="#comprobacion">Entregado</button>`,
		"2" : `<button type="button" class="btn btn-warning estadoEnvio" data-bs-toggle="modal" data-bs-target="#comprobacion">En camino</button>`,
		"3" : `<button type="button" class="btn btn-dark estadoEnvio" data-bs-toggle="modal" data-bs-target="#comprobacion">En proceso</button>`
 	}

	function rellenar(bitacora = false){ 
        $.ajax({ type: "post", url: "", dataType: "json", data: {mostrar: "", bitacora},
            success(data){
                let tabla;
                data.forEach(row => {
                	let fecha_envio = (row.fecha_envio == null) ? "" : row.fecha_envio;
                	let fecha_entrega = (row.fecha_entrega == null) ? "" : row.fecha_entrega;
                    tabla += `
                        <tr>
	                        <td scope="row">${row.id_envio}</td>
	                        <td >${row.cedula}</td>
	                        <td >${row.nombre_cliente}</td>
	                        <td title="${row.sede_empresa}">
	                        	<span style="display:block ruby; overflow:hidden;width:200px; text-overflow: ellipsis;">${row.sede_empresa}</span>
	                        </td>
	                        <td >${fecha_envio}</td>
	                        <td >${fecha_entrega}</td>
	                        <td envio_id=${row.id_envio}>
	                        	<span class="d-flex justify-content-center">${estados[row.status]}</span>
	                        </td>
                        </tr>`;
                });
                $('#tablaEnvios tbody').html(tabla);
                mostrar = $('#tablaEnvios').DataTable({
                    resposive: true,
                    order: [[0, "desc"]]
                });
            },
            error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
        })

    }

    let id;
    $(document).on('click','.estadoEnvio',function(){
    	id = this.closest('td').attributes.envio_id.value;
    })

    $('#enviarEstadoDeEnvio').click(function(){
    	let estado = $('.asignacionDeEstado input:radio[name=options-outlined]:checked').val();
    	$.ajax({url:"", dataType:'json', type:'post', data:{ id_envio:id, estado},
    		success(res){
    			console.log(res);
    			if(res.resultado != "ok"){
    				Toast.fire({ icon: 'error', title: res.msg });
    				throw new Error('Error: '+res.msg);
    			}

    			mostrar.destroy(); 
				rellenar(); 
				$('.cerrar').click(); 
				Toast.fire({ icon: 'success', title: res.msg }) 

    		},
    		error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al enviar estado de envío: '+e);
            }
    	})
    })

})