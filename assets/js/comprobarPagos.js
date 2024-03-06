$(document).ready(function(){

	let mostrar;
	let permisos, comprobarPagoPermiso;
	$.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:''},
		success(data){
			permisos = data;
			comprobarPagoPermiso = (typeof permisos['Comprobar pago'] === "undefined") ? 'disabled' : '';
		}
	}).then(() => rellenar(true));

	

	function rellenar(bitacora = false){
		const estados = {
			"1" : `<button type="button" ${comprobarPagoPermiso} class="btn btn-success estadoPago" data-bs-toggle="modal" data-bs-target="#comprobacion">Aprobado</button>`,
			"2" : `<button type="button" ${comprobarPagoPermiso} class="btn btn-warning estadoPago" data-bs-toggle="modal" data-bs-target="#comprobacion">En espera</button>`,
			"3" : `<button type="button" ${comprobarPagoPermiso} class="btn btn-danger estadoPago" data-bs-toggle="modal" data-bs-target="#comprobacion">Negado</button>`
		}
        $.ajax({ type: "post", url: "", dataType: "json", data: {mostrar: "", bitacora},
            success(data){
                let tabla;
                data.forEach(row => {
                    tabla += `
                        <tr>
	                        <td scope="row">${row.num_fact}</td>
	                        <td >${row.cedula}</td>
	                        <td >${row.nombre_cliente}</td>
	                        <td class="d-flex justify-content-center" pago_id=${row.id_pago}>${estados[row.status]}</td>
	                        <td>
	                        	<span class="d-flex justify-content-center">
	                        		<button type="button" pago="${row.id_pago}" title="Detalles del pago" class="btn btn-dark detalle_pago mx-2" data-bs-toggle="modal" data-bs-target="#detallePago">
	                        			<i class="bi bi-card-checklist"></i>
	                        		</button>
	                        	</span>
	                        </td>
                        </tr>`;
                });
                $('#tablaPagos tbody').html(tabla);
                mostrar = $('#tablaPagos').DataTable({
                    resposive: true
                });
            },
            error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
        })

    }

    let id;
    $(document).on('click','.estadoPago',function(){
    	id = this.closest('td').attributes.pago_id.value;
    })

    $('#enviarEstadoDePago').click(function(){
    	let estado = $('.asignacionDeEstado input:radio[name=options-outlined]:checked').val();
    	
    	$.ajax({url:"", dataType:'json', type:'post', data:{ id_pago:id, estado},
    		success(res){
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
                throw new Error('Error al mostrar listado: '+e);
            }
    	})
    })

    $(document).on('click','.detalle_pago',function(){
    	id = this.attributes.pago.value;
		$.ajax({url:"", dataType:'json', type:'post', data:{ id_pago:id, detalle_pago:''},
			success(res){
				console.log(res);
				$('#detallePago h5 strong span').html(res[0].num_fact)
				let tabla;
                res.forEach(row => {
                    tabla += `
                        <tr>
	                        <td >${row.des_tipo_pago}</td>
	                        <td >${row.monto_pago}</td>
	                        <td >${row.cambio}</td>
	                        <td >${row.banco_cobro}</td>
	                        <td >${row.banco_cliente}</td>
                        </tr>`;
                });
                $('#tablaDetallePago tbody').html(tabla);
			},
			error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
		})   	
    })

})