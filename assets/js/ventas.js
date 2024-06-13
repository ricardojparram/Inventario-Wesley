 $(document).ready(function(){


  let mostrar;
  let permiso , eliminarPermiso, registrarPermiso;

    $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
      success(data){ permiso = data; }

    }).then(function(){
      rellenar(true);
      registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
        $('#agregarModal').attr(registrarPermiso, '');
    })         

  function rellenar(bitacora = false){
    $.ajax({
      method: "POST",
      url: " ",
      dataType: "json",
      data:{mostrar: "venta" , bitacora},
      success(data){
        let tabla;
        data.forEach(row =>{
          eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';
          tabla +=`
          <tr>
          <td>${row.cedula_cliente}</td>
          <td><button class="btn btn-success detalleV" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleVenta">Ver Detalles</button></td>
          <td>${row.fecha}</td>
          <td><button class="btn btn-success detalleTipo" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleTipoPago">Ver Metodos Pago</button></td>
          <td>${row.total_divisa}</td>
          <td>${row.total}</td>
          <td class="d-flex justify-content-center">
          <button type="button" ${eliminarPermiso} class="btn btn-danger borrar mx-2" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#Borrar"><i class="bi bi-trash3"></i></button>
          </td>
          </tr>
          `
        })
        $('#tbody').html(tabla);
        mostrar = $('#tableMostrar').DataTable({
          resposive : true
        })
      }
    })
  }

  let id;

  $(document).on('click', '.detalleV' , function(){

       id = this.id; // id = id
       $.post('',{detalleV : 'detV' , id}, function(data){
        let lista = JSON.parse(data);
        let tabla;

        lista.forEach(row=>{
          tabla += `
          <tr>
          <td>${row.descripcion}</td>
          <td>${row.cantidad}</td>
          <td>${row.precio_actual}</td>                      
          </tr>
          `  
        })
        $('#ventaNombre').text(`Numero de Factura #${lista[0].num_fact}.`);
        $('#bodyDetalle').html(tabla);
        $('.factura').attr("id", id);
      })
     })

      $(document).on('click', '.detalleTipo' , function(){

       id = this.id; // id = id
       $.post('',{detalleTipo : 'detTipoPago' , id}, function(data){
        let lista = JSON.parse(data);
        let tabla;

        lista.forEach(row=>{
          tabla += `
          <tr>
          <td>${row.des_tipo_pago}</td>
          <td>${row.monto_pago}</td>                    
          </tr>
          `  
        })
        $('#ventaNombreTipoPago').text(`Numero de Factura #${lista[0].num_fact}.`);
        $('#bodyDetalleTipo').html(tabla);
      })
     })


     // FUNCION CALCULATE PARA PRECIOS
  selectMoneda()
  var iva = parseFloat($('#config_iva').val());
  var money;
  $(document).on("change", '.select2M', function(){
    money = $(this).children(":selected").attr("id");
    calculate();
  })


  selectOptions();
  selectTipoPago()
  calculate();

  function calculate(){
    let total_price = 0,
    total_tax = 0;

    $('.table-body tbody tr').each( function(){
      let row = $(this),
      rate = row.find('.rate input').val(),
      amount = row.find('.amount input').val();

      let sum = rate * amount;
      let tax = ("0."+iva)*sum;

      total_price = total_price + sum;
      total_tax = total_tax + tax;

      row.find('.sum').text( sum.toFixed(2) );
      row.find('.tax').text( tax.toFixed(2) );

    });

    let precioTotal = (total_price + total_tax).toFixed(2);
    let ivatotal = total_tax.toFixed(2);
    let total = total_price.toFixed(2);
    let cambio = (precioTotal / money).toFixed(2);
    if(isNaN(cambio) || money == 0){
      cambio = "0"
    }
      $('#montos').text(`IVA: ${ivatotal} - subTotal: ${total}`)
      $('#montos2').text(`Total + IVA: ${precioTotal}`)
      $('#cambio').text(`Al cambio: ${cambio}`)
      $('#monto').val(precioTotal)
      manejarCantidadXTipoP(precioTotal);

     }
 //  rellena los select de moneda
     
     function selectMoneda(){
       $.ajax({
        url: '',
        method: 'POST',
        dataType: 'json',
        data: {
          selectM : 'moneda'
        },
        success(data){
          let option = ""
          data.forEach((row)=>{
            let alcambio = row.cambio;
            if(row.cambio == 0) alcambio = "" 
           option += `<option id="${alcambio}" class="option" value="${row.id_cambio}" >${row.nombre} ${alcambio}</option>`
         })
          $('.cedula').each(function(){
            if(this.children.length == 1){
              $(this).append(option);
              
            }
          })
        }
      })
     }



 //  rellena los select de las filas de productos
    function selectOptions(){
      $.ajax({
        url: '',
        method: 'POST',
        dataType: 'json',
        data: {
          select: "Prod"
        },
        success(data){
          let option = ""
          data.forEach((row)=>{
            option += `<option value="${row.cod_producto}">${row.descripcion}</option>`
          })
          $('.select-productos').each(function(){
             if(this.children.length == 1){
               $(this).append(option);
               $(this).chosen({
                width: '25vw',
                placeholder_text_single: "Selecciona un producto",
                search_contains: true,
                allow_single_deselect: true,
                });
               calculate();
             }
          })

        }
      })
    } 

     //  rellena los select de las filas de productos
    function selectTipoPago(){
      $.ajax({
        url: '',
        method: 'POST',
        dataType: 'json',
        data: {
          selectTipo: "tipo de pago"
        },
        success(data){
          let option = ""
          data.forEach((row)=>{
            option += `<option value="${row.id_tipo_pago}">${row.des_tipo_pago}</option>`
          })
          $('.select-tipo').each(function(){
             if(this.children.length == 1){
               $(this).append(option);
               $(this).chosen({
                width: '25vw',
                placeholder_text_single: "Selecciona un metodo",
                search_contains: true,
                allow_single_deselect: true,
                });
             }
          })

        }
      })
    } 

    let producto, select, cantidad, stock;
    //Selecciona cada producto 
    cambio();
    function cambio(){
      $('.select-productos').change(function(){
        select = $(this);
        producto = $(this).val();
        cantidad = select.closest('tr').find('.amount input');
        fillData();

      })
    }
    //  Rellena los inputs con el precio y cantidad de cada producto
    function fillData(){
      $.getJSON('',{producto, fill: "data"}, function(data){

        let precio = select.closest('tr').find('.rate input');
        stock = data[0].stock;
        cantidad.val(data[0].stock);
        cantidad.attr("placeholder", stock);
        precio.val(data[0].p_venta);
        calculate();
        validarStock(cantidad, stock);

      })
    }

    function validarStock(input, max){
      $(input).keyup(()=>{
        stock = Number(max);
        num = Number(input.val());
        if(num > stock || num == 0 || num < 1 || !Number.isInteger(num)){
          input.css({"border" : "solid 1px", "border-color" : "red"})
          input.attr("valid", "false")
        }else{
          input.css({'border': 'none'})
          input.attr("valid", "true")
        }
      })
    }
   
    
    //  SELECT2 CON BOOTSTRAP-5 
 $(".select2").select2({
  theme: 'bootstrap-5',
  dropdownParent: $('#Agregar .modal-body'),
  width: '80%' 
});
    
    // fila de tipo pago
    let newRowTipo = ` <tr>
                        <td width="1%"><a class="removeRowPagoTipo a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
                        <td width='30%'> 
                          <select class="select-tipo select-asd" name="TipoPago">
                            <option></option>
                          </select>
                        </td>
                        <td width='15%' class="precioPorTipo"><input class="select-asd precio-tipo" type="number" value=""/></td>
                      </tr>`;
    
    // Caracteriticas de la fila Tipo Pago
      function filaTipoN(){
        $('#FILL').append(newRowTipo);
        selectTipoPago();
        validarRepetidoTipoPago();
        validarValores();
        selectMultifila($('.select-tipo'), $('.filaTipoPago'), '.table-body-tipo' ,'No debe haber tipo pago vacíos.');
        validarFila($('#FILL') ,$('.filaTipoPago'));
      }
 

     //  fila que se inserta
     let newRow = `<tr>
          <td width="1%"><a class="removeRow a-asd" href="#"><i class="bi bi-trash-fill"></i></a></td>
          <td width='40%'> 
          <select class="select-productos select-asd">
            <option></option>
          </select>
          </td>
          <td width='10%' class="amount"><input class="select-asd stock" type="number" value=""/></td>
          <td width='10%' class="rate"><input class="select-asd" type="number" disabled value="" /></td>
          <td width='10%'class="tax"></td>
          <td width='10%' class="sum"></td>
          </tr>`;
    
    // Caracteriticas de la fila Producto
    function filaN(){
      $('#ASD').append(newRow);
      selectOptions();
      cambio();
      validarRepetido();
      selectMultifila($('.select-productos'), $('.filaProductos'),'.table-body' ,'No debe haber productos vacíos.');;     
      validarFila($('#ASD') ,$('.filaProductos'));
    }

    // Agregar fila para insertar producto
     $('.newRow').on('click',function(e){
       filaN();       
     });

     // Agregar fila para insertar tipo de pago
     $('.newRowPago').on('click',function(e){
       filaTipoN();    
     });

    // ELiminar fila
     $('body').on('click','.removeRow', function(e){
        $(this).closest('tr').remove();
        validarFila($('#ASD') ,$('.filaProductos'));
        calculate()
     });

     // ELiminar fila Tipo de Pago
     $('body').on('click','.removeRowPagoTipo', function(e){
        $(this).closest('tr').remove();
        validarFila($('#FILL') ,$('.filaTipoPago'));
     });

     //Calcular Cantidad por tipo de pago

     function manejarCantidadXTipoP(montoT){
       let montoMax = parseFloat(montoT);
       let precioXTipo = $('.precio-tipo');
       let cantidadFilas = precioXTipo.length;
       let precioPorFila = montoMax / cantidadFilas;

       let totalAsignado = 0;

       precioXTipo.each(function(){

        if (totalAsignado + precioPorFila <= montoMax){
          $(this).val(precioPorFila.toFixed(2));
          totalAsignado += precioPorFila;

        }else if(totalAsignado < montoMax){
          $(this).val((montoMax - totalAsignado).toFixed(2));
          totalAsignado = montoMax;
          
        }else{
          $(this).val("0.00");
        }

       })
     }

     //Calcular Cantidad por tipo de pago por cambio del usuario

    $(document).on('keyup', '.precio-tipo', () => {
      let montoMax = parseFloat($('#monto').val());
      let preciosPorTipo = parseFloat($('.precio-tipo'));
      let numFilas = preciosPorTipo.length;

      if (numFilas === 1) {
        preciosPorTipo.val(montoMax.toFixed(2));
      } else if(numFilas === 2){
        let precio1 = parseFloat(preciosPorTipo.eq(0).val());
        let precio2 = parseFloat(preciosPorTipo.eq(1).val());
        if (precio1 + precio2 > montoMax) {
          preciosPorTipo.eq(1).val((montoMax - precio1).toFixed(2));
        } else {
          preciosPorTipo.eq(1).val((montoMax - precio1).toFixed(2));
        }
      }

      let totalAsignado = 0;
      preciosPorTipo.each(function(){
        totalAsignado += parseFloat($(this).val());
      });

      if (totalAsignado > montoMax || totalAsignado < montoMax || totalAsignado < 1) {
        preciosPorTipo.css({"border": "solid 1px", "border-color": "red"});
        $(preciosPorTipo).attr('valid', 'false');
      } else {
        preciosPorTipo.css({"border": "none"});
        $(preciosPorTipo).attr('valid', 'true');
      }
    })

    function validarTipoPorPrecio(montoM , precioXtipo){
      let montoMax = parseFloat(montoM.val());
      let preciosPorTipo = precioXtipo;

      let totalAsignado = 0;
      preciosPorTipo.each(function(){
        totalAsignado += parseFloat($(this).val());
      });

       resto = montoMax - totalAsignado

      if (totalAsignado > montoMax) {
        preciosPorTipo.css({"border": "solid 1px", "border-color": "red"});
        $(preciosPorTipo).attr('valid', 'false');
        $('#pValid').text('Excede el monto máximo por ' + resto.toFixed(2) + ' bs');

      }else if(totalAsignado < montoMax){
        preciosPorTipo.css({"border": "solid 1px", "border-color": "red"});
        $(preciosPorTipo).attr('valid', 'false');
        $('#pValid').text('Falta ' + resto.toFixed(2) + ' bs para alcanzar el monto máximo');

      } else if(totalAsignado < 1 ){
        preciosPorTipo.css({"border": "solid 1px", "border-color": "red"});
        $(preciosPorTipo).attr('valid', 'false');
        $('#pValid').text('');

      }else if(totalAsignado == montoMax){
         preciosPorTipo.css({"border": "none"});
        $(preciosPorTipo).attr('valid', 'true');
      }

    }

    function validarPrecio(input){
      let valor = input.val();
      if(valor <= 0 || isNaN(valor)){
        $('#error').text('Precio inválido.');
        input.css({'border': 'solid 1px', 'border-color':'red'})
        input.attr('valid','false')
        return false;
      }else{
        $('#error').text('');
        input.css({'border': 'none'});
        input.attr('valid','true');
        return true;
      }
    }

    validarValores();
    function validarValores(){
      $('.precioPorTipo input').keyup(function(){ validarPrecio($(this)) });
    }


    //Evento keyup para que funcione calculate()
    $('.table-body').on('keyup','input',function(){
      calculate();
    })

    //configuracion de IVA
     $('#config_iva').on('keyup', function(){
      iva = parseFloat($(this).val())
       
      if(iva < 0 || iva > 100){
       iva = 0
      }
      console.log('valor de iva cambio a : '+iva);
      calculate();

     })

     //configuracion de Moneda
     $('.select2M').on('change', function(){
       moneda = parseFloat($(this).val())
       calculate()
      })
       
    //Validar que no se repita 
    function validarRepetido(){
      $('.select-productos').change(function(){
        let producto;

        $('.select-productos').each(function(){

          producto = $(this).val();
          let count = 0;
          $('.select-productos').each(function(){

            if(producto != ''){
              if(producto == $(this).val()){
                count++
                if(count >=2){
                  $(this).closest('tr').attr('style', 'border-color: red;')
                  $(this).attr('valid', 'false');
                  $('#pValid').text('No pueden haber productos repetidos');
                }else{
                  $(this).attr('valid', 'true');
                }
              }
            }

          });

        })
        $('.select-productos').each(function(){
          if($(this).is('[valid="true"]')){
            $(this).closest('tr').attr('style', 'border-color: none;');
          }
          
        })
        if(!$('.select-productos').is('[valid="false"]')){
         $('#pValid').text('');
       }
       
     })
      
    }
    
    //Validar que no se repita tipo de pago
    function validarRepetidoTipoPago() {
       $('.select-tipo').change(function(){
        let tipoPago;

        $('.select-tipo').each(function(){

          tipoPago = $(this).val();
          let count = 0;
          $('.select-tipo').each(function(){

            if(tipoPago != ''){
              if(tipoPago == $(this).val()){
                count++
                if(count >=2){
                  $(this).closest('tr').attr('style', 'border-color: red;')
                  $(this).attr('valid', 'false');
                  $('#pValid').text('No pueden haber Tipo de Pagos repetidos');
                }else{
                  $(this).attr('valid', 'true');
                }
              }
            }

          });

        })
        $('.select-tipo').each(function(){
          if($(this).is('[valid="true"]')){
            $(this).closest('tr').attr('style', 'border-color: none;');
          }
          
        })
        if(!$('.select-tipo').is('[valid="false"]')){
         $('#pValid').text('');
       }
       
     })
    }

    function validarCedula(input, select2, div){
      return new Promise((resolve , reject)=>{
        $.post('',{cedula : input.val(), validar: "cedula"}, function(data){
          let mensaje = JSON.parse(data);
          if(mensaje.resultado === "Error de cedula"){
            div.text(mensaje.error);
            select2.attr("style","border-color: red;")
            select2.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
            return reject(false); 
          }else{
            div.text(" ");
            return resolve(true);
          }
        })
      })
    }

    function validarFila(filas , error){
      let filaExiste = false;
      $(filas).each(function(){
        let fila = $(this).find('tr');
        if(fila.length > 0){
          filaExiste = true;
          $(error).text('');
          return false
        }
      })
      if(!filaExiste){
        $(error).text('No debe haber filas vacias.');
      }
      return filaExiste;
    }

      function selectMultifila(select , error , table , mensaje){
       $(select).change(()=>{
       $(`${table} tbody tr`).each(function(){
          let selectFila = $(this).find(select).val();
          if(selectFila == "" || selectFila == null){
           $(error).text(mensaje);
         }else{
          $(error).text('');
        }
       }) 
      })
     }
    

     // REGISTRAR VENTA
    let vmoneda;


     $('#moneda').change(()=>{ vmoneda =  validarSelect($('#moneda'),$("#error5"),"Error de moneda")  })
     $('.iva').keyup(()=> {validarNumero($(".iva"),$("#error4"),"Error de iva") });

     $('.select2').change(function(){
      let cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula");
      if(cedula){
       validarCedula($(".select2"),$(".select2-selection") ,$("#error1"));
      }
     })


     selectMultifila($('.select-tipo'), $('.filaTipoPago'), '.table-body-tipo' ,'No debe haber tipo pago vacíos.');
     selectMultifila($('.select-productos'), $('.filaProductos'),'.table-body' ,'No debe haber productos vacíos.');

     let click = 0;
     setInterval(()=>{click = 0}, 2000);
    
    // REGISTRAR VENTA INICIA
    $("#registrar").click((e)=>{
     e.preventDefault();

     if(click >= 1){ throw new Error('Spam de clicks');}

     let cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula");


      if(cedula) validarCedula($(".select2"),$(".select2-selection") ,$("#error1")).then(()=>{

        let filaProductos;
        let filaTipoPago;

       filaProductos = validarFila($('#ASD') ,$('.filaProductos'));
       filaTipoPago = validarFila($('#FILL') ,$('.filaTipoPago'));

      $('.select2').change(function(){
       let select2 = $(this).val() 
       if(select2 == " " || select2 == null ){
        return cedula = false
      }else{
       $('#error1').text(" ");
       $(".select2-selection").attr("style","border-color: none;")
       $(".select2-selection").attr("style","backgraund-image: none;");
       return cedula = true;
     }
   })
      vmoneda =  validarSelect($('#moneda'),$("#error5"),"Error de moneda")
      let montoT = validarNumero($("#monto"),$("#error3"),"Error de monto");
      let iva = validarNumero($(".iva"),$("#error4"),"Error de iva");
      let vproductos = true;
      let vtipoPago = true;
      let vprecio = true;
      let totalTipo = true

   //Validar Productos
         $('.table-body tbody tr').each(function(){
          let producto = $(this).find('.select-productos').val();
          if(producto == "" || producto == null){
           vproductos = false;
           $('.filaProductos').text('No debe haber productos vacíos.')
         }else{
          $('.filaProductos').text('');
          vproductos = true;
        }
      })
     
     //Validar Tipo Producto
       $('.table-body-tipo tbody tr').each(function(){
        let tipoPago = $(this).find('.select-tipo').val();
        if(tipoPago == "" || tipoPago == null){
         vtipoPago = false; 
         $('.filaTipoPago').text('No debe haber tipo pago vacíos.');
       }else{
        $('.filaTipoPago').text('');
        vtipoPago = true;
      }
    })

      $('.precioPorTipo input').each(function(){ validarPrecio($(this)) });

      if($('.precioPorTipo input').is('[valid="false"]')){
        $('#pValid').text('Precio inválido.');
        vprecio = false;
      }
      
      
      //Validar repetidos productos
      let repetidos = true 
      if($('.select-productos').is('[valid="false"]')){
        repetidos = false
      }else if(!$('.select-productos').is('[valid="false"]')){
        repetidos = true
      }
      
      //Validar repetidos tipo pago
      let repetidosTipoPago = true 
      if($('.select-tipo').is('[valid="false"]')){
        repetidosTipoPago = false
      }else if(!$('.select-tipo').is('[valid="false"]')){
        repetidosTipoPago = true
      }

      validarTipoPorPrecio($('#monto') , $('.precio-tipo'))
      if($('.precio-tipo').is('[valid="false"]')){
       totalTipo = false
      }else if(!$('.precio-tipo').is('[valid="false"]')){
       totalTipo = true
      }

      let vstock = true;
      if($('.stock').is('[valid="false"]')){
        vstock  = false
        $('#pValid').text('Cantidad inválida.')
      }else if($('.stock').val() == "" || $('.stock').val() === '0'){
        vstock = false
        $('#pValid').text('Cantidad inválida.');
      } 



      if(cedula && vmoneda && montoT && vproductos && vtipoPago && vstock && vprecio && totalTipo && iva && repetidos && repetidosTipoPago && filaProductos && filaTipoPago){

       $.post('',{
        cedula: $('#cedula').val()
      },
      function(data){
       let idVenta = JSON.parse(data);
       console.log(idVenta);
       enviarProductos(idVenta.id);
       datosPago(idVenta.id).then((idPago)=>{

       datosPagoPorTipo(idPago);
       mostrar.destroy();
            rellenar();  // FUNCIÓN PARA RELLENAR
            $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
            $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
            $('.cerrar').click(); // CERRAR EL MODAL
            $('.removeRow').click(); 
            $('.removeRowPagoTipo').click(); 
            $('#error').text('');
            filaN()
            filaTipoN()
            Toast.fire({ icon: 'success', title: 'Venta registrada' }) // ALERTA   

          }).catch(()=>{
            throw new Error('Error');
          })       

        })
     }
   }).catch(()=>{
        throw new Error('Error');
   })
   click++;
 })

  //función para enviar productos uno por uno
  function enviarProductos(id){
    $('.table-body tbody tr').each(function(){
     let producto = $(this).find('.select-productos').val();
     let cantidad = $(this).find('.amount input').val();
     let precio = $(this).find('.rate input').val();

     $.post("",{producto, precio , cantidad, id})
       
    })
  }

  function datosPago(id){
    montoT = $('#monto').val();

    return new Promise(function(resolve, reject) {
      $.post("",{montoT , id} , function(data){
        let idPago = JSON.parse(data);
        resolve(idPago.id);
      }).fail(function() {
        reject(new Error('Error'));
      });
    });
  }


  function datosPagoPorTipo(id){
    let moneda = $('#moneda').val();

   $('.table-body-tipo tbody tr').each(function(){
    let tipoPago = $(this).find('.select-tipo').val();
    let montoPorTipo = $(this).find('.precioPorTipo input').val();

    $.post("",{tipoPago , montoPorTipo , moneda ,id});

   })

  }

  $(document).on('click', '.factura' , function(){
    id = this.id;
    validarId().catch(()=>{
      throw new Error('No existe');
    });
    $.ajax({
      type: "POST",
      url: '',
      dataType: 'json',
      data: {id, factura: "factura"},
      success(data){
       if(data.respuesta === 'Archivo guardado'){
        Toast.fire({ icon: 'success', title: 'Exportado correctamente.' });
        descargarArchivo(data.ruta);
       }else{
        Toast.fire({ icon: 'error', title: 'Error de Exportado.' });
       }
      }
    })
  })

  function descargarArchivo(ruta){
    let link = document.createElement('a');
    link.href = ruta;
    link.target = '_black';
    link.click();
  }

  $('#cerrar').click(()=>{
     $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('#Agregar select').attr("style","borden-color:none;","borden-color:none;");
     $('#Agregar .select2-selection').attr("style","borden-color:none;","borden-color:none;");
     $('#Agregar input').attr("style","borden-color:none;","borden-color:none;");
     $('.error').text(" ");
     $('.removeRow').click(); // LIMPIAR FILAS
     $('.removeRowPagoTipo').click(); // LIMPIAR FILAS TIPO PAGO
     filaN() // 
     filaTipoN()
  })

  // ELIMNINAR VENTA

       $(document).on('click', '.borrar', function(){
        id = this.id;
        
      })   

       function validarId() {
        return new Promise((resolve, reject) => {
          $.ajax({
            type: "POST",
            url: '',
            dataType: "json",
            data: { validarCI: "existe", id },
            success(data) {
              console.log(data);
              if (data.resultado === "Error de venta") {  
                Toast.fire({ icon: 'error', title: 'Esta venta esta anulada' }); // ALERTA 
                mostrar.destroy();
                rellenar();
                $('.cerrar').click();
                reject(); // Rechaza la promesa si la condición se cumple
              } else {
                resolve(); // Resuelve la promesa si la condición no se cumple
              }
            }
          });
        });
      }

      $("#delete").click(() => { 
        if (click >= 1) {
          throw new Error('Spam de clicks');
        }
        validarId().then(() => {
            // Si la promesa se resuelve, la ejecución continúa
            $.ajax({
              type: "POST",
              url: '',
              data: { eliminar: "asd", id },
              success(data) {
                mostrar.destroy();
                rellenar();
                $('.cerrar').click();
                Toast.fire({ icon: 'success', title: 'Venta anulada' }); // ALERTA 
              }
            });
          }).catch(() => {
      // Si la promesa se rechaza, la ejecución se detiene
      console.log('No se puede eliminar la venta');
      });
      click++;
    });


});