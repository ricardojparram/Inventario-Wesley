$(document).ready(function(){
	
	let mostrar;
	let permiso , eliminarPermiso, registrarPermiso;

	$.ajax({method: 'POST' , url: '' , dataType: 'json' , data: {getPermiso: '' },
		success(permisos){
			registrarPermiso = (typeof permisos.Registrar === 'undefined') ? 'disabled' : '';
			eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
		}
	}).then(()=> { rellenar(true) });

	function rellenar(bitacora = false){
		$.ajax({
			type: 'POST',
			url: "",
			dataType: 'json',
			data: {mostrar : 'DonativoPersonal', bitacora},
			success(data){
				data ? data : '';
				mostrar = $('#tabla').DataTable({
					resposive: true,
					data: data,
					columns:[
					{ data: 'cedula' },
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
							<button type="button" ${eliminarPermiso} id="${row.id_donaciones}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#borrar"><i class="bi bi-trash3"></i></button>
							`;
						}
					}
					]
				})

			}
		})
	}

	let id;

  // Ver Detalles de donaciones

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
       		</tr>
       		`  
       	})
       	$('#DonacionTitle').text(`Numero de Donacion #${lista[0].id_donaciones}.`);
       	$('#bodyDetalle').html(tabla);
       })

   })


        // Funcion select Personal
        selectPersonal();
        function selectPersonal() {
            $.ajax({
                type: 'POST',
                url: '',
                dataType: 'json',
                data: { selectPersonal: 'personal' },
                success(data) {
                    $('#cedula').select2({
                        data: data.map(function(item) {
                            return { id: item.cedula, text: item.cedula + ' ' + item.nombres + ' ' + item.apellidos};
                        }),
                        theme: 'bootstrap-5',
                        dropdownParent: $('#agregar .modal-body'),
                        width: '80%'
                    });
                    $('#cedula').on('change', function() {
                        let selectedCedula = $(this).val();
                        let personal = data.find(item => item.cedula === selectedCedula)
                        if (personal) {
                            let nombreApellido = personal.nombres + ' ' + personal.apellidos;
                            $('#beneficiario').val(nombreApellido);
                        }
                    });
                }
            });

        }


    const newRow = `
      <tr>
        <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
          <td width='25%'> 
             <select class="select-productos select-asd" name="productos">
               <option></option>
             </select>
           </td>
          <td width='15%' class="cantidad"><input class="select-asd stock" type="number" value=""/></td>
      </tr>`;


    // Funcion de select para productos 
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
                let option = data.map(row => `<option value="${row.id_producto_sede}">${row.id_producto_sede} - ${row.producto} ${row.lote}</option>`).join('');

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



    // Validar producto repetidos

        let selectRepetidos, select_t;
    const validarSelectRepetidos = (selected, error, status = true) => {
        let validacion = [];
        let $select = document.querySelectorAll(selected);
        if ($select.length < 1) {
            $(error).html('No hay filas.');
            return false
        } else {
            $(error).html('');
        }
        select_t = Object.values(document.querySelectorAll(selected)).map(item => {
            return item.value;
        });
        selectRepetidos = select_t.filter((elemento, index) => select_t.indexOf(elemento) !== index);
        $(selected).each(function () {
            if (this.value === "" || this.value === null) {
                if (status != true) {
                    $(this).closest('td').find('div.chosen-container').addClass('select-error')
                }
                validacion.push(false);
            } else if (selectRepetidos.includes(this.value)) {
                $(this).closest('td').find('div.chosen-container').addClass('select-error')
                validacion.push(false);
            } else {
                $(this).closest('td').find('div.chosen-container').removeClass('select-error')
                validacion.push(true);
            }
        })
        return !validacion.includes(false);
    }


    const mostrarInventarioProducto = (item) => {
        let $cantidad = $(item).closest('tr').find('.cantidad input');
        let producto_inventario = item.value;
        $.getJSON('', { producto : producto_inventario , filas : 'select fila'}, function (data) {
            $cantidad.val(data[0].cantidad);
            $cantidad.attr('placeholder', data[0].cantidad);
            validarStock($cantidad , data[0].cantidad);
        })
    }

    function validarStock(input, max){
        $(input).keyup(()=>{
            stock = Number(max);
            num = Number(input.val());
            if(num > stock || num == 0 || num < 1 || !Number.isInteger(num)){
                input.css({"border" : "solid 1px", "border-color" : "red"})
                input.attr("valid", "false");
                $('#pValid').text('Cantidad inválida.');
            }else{
                input.css({'border': 'none'})
                input.attr("valid", "true");
                $('#pValid').text(' ');
            }
        })
    }

    validCantidad = () => {
        let stockValue = $('.stock').val();
        let isValidStock = !$('.stock').is('[valid="false"]') && stockValue !== "" && stockValue !== '0';

        $('#pValid').text(isValidStock ? '' : 'Cantidad inválida.');
        return isValidStock;
    }



    // Funcion de agregar fila 
     function addNewRow(){
        $('#ASD').append(newRow)
        selectProductos();
     }

     // Agregar fila para insertar producto a donar
     $('.newRow').on('click',function(e){
        addNewRow();   
        validarSelectRepetidos('.select-productos' , '.filaProductos'); 
     });

    // Evento de cambio en los productos 
     $(document).on("change", ".select-productos", function () {
        validarSelectRepetidos('.select-productos' , '.filaProductos'); 
        mostrarInventarioProducto(this);
     });


     // ELiminar fila
     $('body').on('click','.removeRow', function(e){
        $(this).closest('tr').remove();
        validarSelectRepetidos('.select-productos' , '.filaProductos'); 
     });

    
    function validarCedula(input, select2, div){
      return new Promise((resolve , reject)=>{
        $.getJSON('',{cedula : input.val(), tipo: 'personal'}, function(data){
          if(data.resultado === "error"){
            div.text(data.msg);
            select2.addClass('select-error')
            return reject(false); 
          }else{
            div.removeClass('select-error')
            return resolve(true);
          }
        })
      })
    }


     let click = 0;
     let cedula , beneficiario , producto , valid;
     setInterval(()=>{click = 0}, 2000);

     $('#cedula').change(()=>{ 
        valid = validarSelec2($('#cedula'),$(".select2-selection"), $('#error1') , 'Error de cedula'); 
        if (valid) validarCedula($("#cedula"),$(".select2-selection") ,$("#error1"));
    })

     $('#registrar').click(function(e) {
         e.preventDefault()

        if(click >= 1){ throw new Error('Spam de clicks');}

        let repetidos;
        let datos = [];

         cedula = validarSelec2($('#cedula'),$(".select2-selection"), $('#error1') , 'Error de cedula');
         valid_productos = validarSelectRepetidos('.select-productos' , '.filaProductos' , false);
         cantidad = validCantidad();

         if(cedula && valid_productos && cantidad){
            
             $('.table-body tbody tr').each(function(){
                productos = $(this).find('.select-productos').val();
                stock = $(this).find('.stock').val();

                let productoObj = {
                    producto : productos,
                    cantidad : stock
                };

                datos.push(productoObj);
            });

            $.ajax({
                url: '',
                type: 'POST',
                dataType: 'json',
                data: {cedulaPersonal: $('#cedula').val() , datos },
                success(data){
                if(data.resultado == 'registrado con exito'){
                    mostrar.destroy();
                    rellenar();  
                    $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
                    $('#agregar .select2-selection').attr("style","borden-color:none;","borden-color:none;");
                    $('.error').text(" ");
                    $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
                    $('#error').text('');
                    $('.removeRow').click();
                    $('.cerrar').click(); 
                    Toast.fire({ icon: 'success', title: 'Donacion registrada' , showCloseButton: true });
                 }
               }
            })
            .fail(function(e) {
             Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
              throw new Error(e.responseJSON.msg);
            })

         }

        click++;
     });

 $('#cerrar').click(()=>{
    $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
    $('#agregar .select2-selection').attr("style","borden-color:none;","borden-color:none;");
    $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
    $('.removeRow').click();
    $('.error').text(" ");
    $('#error').text('');
     addNewRow();
  })

     function validarExitencia(){
        return new Promise((resolve, reject) =>{
            $.ajax({
                type: "POST",
                url: '',
                dataType: "json",
                data: { validarE: "existe", id},
                success(data) {
                    if (data.resultado === "error") {  
                        mostrar.destroy();
                        rellenar();
                        $('.cerrar').click();
                       Toast.fire({icon: 'error', title: 'Error de Donacion', showCloseButton: true }) // ALERTA 
                       reject(false);
                   }else{
                    resolve(true);
                   }

               }
           })
        })
     }

     $(document).on('click', '.borrar' , function(){
        id = this.id;
     })

     $('#delete').click(function(e) {
        e.preventDefault();

        if(click >= 1){ throw new Error('Spam de clicks');}

        validarExitencia().then(()=>{

            $.ajax({
                url: '',
                type: 'POST',
                dataType: 'json',
                data: {eliminar: 'eliminar', id},
                success(data){
                  if (data.resultado === 'Eliminado'){
                    $("#close").click();
                    mostrar.destroy();
                    Toast.fire({icon: 'success', title: 'Donacion eliminada', showCloseButton: true })
                    rellenar();
                  }
                }

            }).fail(function() {
                console.log("error");
            })

        }).catch(()=>{
            throw new Error('No exite.');
        })

        click++;
     });



 })