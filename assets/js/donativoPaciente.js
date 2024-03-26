$(document).ready(function(){
	
	fechaHoy($('#fecha'));
	let mostrar;
	let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

	$.ajax({method: 'POST' , url: '' , dataType: 'json' , data: {getPermiso: '' },
		success(permisos){
			registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
			editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
			eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		}
	}).then(()=> { rellenar(true) });

	function rellenar(bitacora = false){
		$.ajax({
			type: 'POST',
			url: "",
			dataType: 'json',
			data: {mostrar : 'DonativoPaciente', bitacora},
			success(data){
				data ? data : '';
				mostrar = $('#tabla').DataTable({
					resposive: true,
					data: data,
					columns:[
					{ data: 'ced_pac' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							<button class="btn btn-registrar detalleD" id="${row.id_donaciones}" data-bs-toggle="modal" data-bs-target="#detalleDonacion">Ver Detalles</button>
							`;
						}
					},
					{ data: 'fecha' },
					{ data: 'beneficiario' },
					{
						data: null,
						render: function(data, type, row) {
							return `
							<button type="button" ${editarPermiso} id="${row.id_donaciones}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
							<button type="button" ${eliminarPermiso} id="${row.id_donaciones}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
							`;
						}
					}
					]
				})

			}
		})
	}

	  let id;

  $(document).on('click', '.detalleD' , function(){

       id = this.id; // id = id
       $.post('',{detalleD : 'detalle Donacion' , id}, function(data){
        let lista = JSON.parse(data);
        let tabla;

        lista.forEach(row=>{
          tabla += `
          <tr>
          <td>${row.nombrepro}</td>
          <td>${row.cantidad}</td>
          <td>${row.cantidad}</td>                      
          </tr>
          `  
        })
        $('#DonacionTitle').text(`Numero de Donacion #${lista[0].id_donaciones}.`);
        $('#bodyDetalle').html(tabla);
      })

     })


        selectPacientes();
        function selectPacientes() {
        	$.ajax({
        		type: 'POST',
        		url: '',
        		dataType: 'json',
        		data: { selectPacientes: 'paciente' },
        		success(data) {
        		$('#cedula').select2({
        			data: data.map(function(item) {
        				return { id: item.ced_pac, text: item.ced_pac};
        			}),
        			theme: 'bootstrap-5',
        			dropdownParent: $('#Agregar .modal-body'),
        			width: '80%'
        		});
        		$('#cedula').on('change', function() {
        			let selectedCedula = $(this).val();
        			let paciente = data.find(item => item.ced_pac === selectedCedula)
        			if (paciente) {
        				let nombreApellido = paciente.nombre + ' ' + paciente.apellido;
        				$('#nombre').val(nombreApellido);
        			}
        		});
        	}
        });

    }

    	selectSedes()
	    function selectSedes(){
	    	$.ajax({
	    		type: 'POST',
	    		url: '',
	    		dataType: 'json',
	    		data: { selectSedes: 'sedes' },
	    		success(data) {
	    		 let selectSedes = $('#sedes').select2({
	    			data: data.map(function(item) {
	    				return { id: item.id_sede, text: item.nombre};
	    			}),
	    			theme: 'bootstrap-5',
	    			dropdownParent: $('#Agregar .modal-body'),
	    			width: '50%'
	    		});

	    	}
	    });
	}

      //  fila que se inserta
      let newRow = `<tr>
      <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
      <td width='25%'> 
      <select class="select-productos select-asd" name="productos">
      <option></option>
      </select>
      </td>
      <td width='15%' class="cantidad"><input class="select-asd stock" type="number" value=""/></td>
      <td width='15%' class="cantUnitaria"><input class="select-asd unidad" type="number" value="" /></td>
      </tr>`;

      // Validar filas

      function validarFila(filas, error) {
      	let filaExiste = $(filas).find('tr').length > 0;

      		if (filaExiste) {
      			$(error).text('');
      		} else {
      			$(error).text('No debe haber filas vacías.');
      		}

      		return filaExiste;
      	}

    // Funcion select para productos multifila
      selectProductos()
      function selectProductos(){
	  	$.ajax({
	  		url: '',
	  		method: 'POST',
	  		dataType: 'json',
	  		data: {
	  			selectProductos: "productos"
	  		},
	  		success(data){
	  			let option = data.map(row => `<option value="${row.id_producto_sede}">${row.nombrepro} ${row.lote}</option>`).join('');

	  			$('.select-productos').each(function(){
	  				if(this.children.length == 1){
	  					$(this).append(option);
	  					$(this).chosen({
	  						width: '30vw',
	  						placeholder_text_single: "Selecciona un producto",
	  						search_contains: true
	  					});
	  				}
	  			})

	  		}
	  	})
	}



	$('#ASD').on('change', '.select-productos', function(){
		let selectedValues = [];
		$('.select-productos').each(function(){
			let value = $(this).val();
				if(value !== ''){
					if(selectedValues.includes(value)){
						$(this).closest('tr').attr('style', 'border-color: red;')
						$(this).val('');
					} else {
						$(this).closest('tr').attr('style', 'border-color: none')
						selectedValues.push(value);
					}
				}
			});
		});

	 let producto , select , cantidad ,  stock;

	 function productoSeleccionado(){

	 }

      function addNewRow(){
  	  $('#ASD').append(newRow);
  	  	validarFila($('#ASD') ,$('.filaProductos'));
      }

     // Agregar fila para insertar producto a donar
     $('.newRow').on('click',function(e){
     	addNewRow();
     	selectProductos();       
     });

     // ELiminar fila
     $('body').on('click','.removeRow', function(e){
        $(this).closest('tr').remove();
        validarFila($('#ASD') ,$('.filaProductos'));
     });


})