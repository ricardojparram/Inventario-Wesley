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
          
          <td>${row.fecha}</td>
          <td>${row.des_tipo_pago}</td>
          <td>${row.total_divisa}</td>
          <td>${row.monto}</td>
          <td><button class="btn btn-success detalleV" id="${row.num_fact}" data-bs-toggle="modal" data-bs-target="#detalleVenta">Ver detalles</button></td>
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


     // FUNCION CALCULATE PARA PRECIOS
  selectMoneda()
  var iva = parseFloat($('#config_iva').val());
  var money;
  $(document).on("change", '.select2M', function(){
    money = $(this).children(":selected").attr("id");
    calculate();
  })


  selectOptions();
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
          $('.select2M').each(function(){
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
    })
 

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

    function filaN(){
      $('#ASD').append(newRow);
      selectOptions();
      cambio();
      validarRepetido()
    }

    // Agregar fila para insertar producto
     $('.newRow').on('click',function(e){
       filaN();
     });

    // ELiminar fila
     $('body').on('click','.removeRow', function(e){
        $(this).closest('tr').remove();
        calculate()
     });

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
                  $('#error').text('No pueden haber productos repetidos');
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
         $('#error').text('');
       }
       
     })
      
    }

    function validarCedula(input, select2, div){
      $.post('',{cedula : input.val(), validar: "cedula"}, function(data){
        let mensaje = JSON.parse(data);
        if(mensaje.resultado === "Error de cedula"){
          div.text(mensaje.error);
          select2.attr("style","border-color: red;")
          select2.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
        }
      })
    }
    

     // REGISTRAR VENTA
    let vmetodo, vmoneda;
    $('#metodo').change(function(){
      vmetodo = validarNumero($("#metodo"),$("#error2"),"Error de metodo de pago");
    })
    $('#moneda').change(function(){
      vmoneda =  validarSelect($('#moneda'),$("#error5"),"Error de moneda")
    })
     $('.iva').keyup(()=> {validarNumero($(".iva"),$("#error4"),"Error de iva") });

     $('.select2').change(function(){
      let cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula");
      if(cedula){
       validarCedula($(".select2"),$(".select2-selection") ,$("#error1"));
      }
     })

     let click = 0;
     setInterval(()=>{click = 0}, 2000);
    
    // REGISTRAR VENTA INICIA
     $("#registrar").click((e)=>{
       e.preventDefault();

       if(click >= 1){ throw new Error('Spam de clicks');}

       let cedula = validarSelec2($(".select2"),$(".select2-selection"),$("#error1"),"Error de Cedula");

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
       vmetodo = validarNumero($("#metodo"),$("#error2"),"Error de metodo de pago");
       let montoT = validarNumero($("#monto"),$("#error3"),"Error de monto");
       let iva = validarNumero($(".iva"),$("#error4"),"Error de iva");
       let selectM = validarSelect($('#moneda'),$("#error5"),"Error de moneda");
       let vproductos = true;

       $('.table-body tbody tr').each(function(){
        let producto = $(this).find('.select-productos').val();
        if(producto == "" || producto == null){
         vproductos = false;
         $('#error').text('No debe haber productos vacíos.')
       }
     })

       let repetidos = true 
       if($('.select-productos').is('[valid="false"]')){
        repetidos = false
       }else if(!$('.select-productos').is('[valid="false"]')){
        repetidos = true
       }

       let vstock = true;
       if($('.stock').is('[valid="false"]')){
        vstock  = false
        $('#error').text('Cantidad inválida.')
      }else if($('.stock').val() == "" || $('.stock').val() === '0'){
        vstock = false
        $('#error').text('Seleccione un producto');
      } 

       

      if(cedula && vmetodo && vmoneda && montoT && vproductos && vstock && iva && selectM && repetidos){

       console.log("Enviando ...");

       $.post('',{
        cedula: $('#cedula').val(),
        metodo: $('#metodo').val(),
        montoT: $('#monto').val(),
        moneda: $('#moneda').val()
      },
      function(data){
       let idVenta = JSON.parse(data);
       console.log(idVenta);
       enviarProductos(idVenta.id);
       mostrar.destroy();
            rellenar();  // FUNCIÓN PARA RELLENAR
            $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
            $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
            $('.cerrar').click(); // CERRAR EL MODAL
            $('.removeRow').click(); 
            $('#error').text('');
            filaN()
            Toast.fire({ icon: 'success', title: 'Venta registrada' }) // ALERTA 

          })
     }
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

  $(document).on('click', '.factura' , function(){
    id = this.id;
    $.ajax({
      type: "POST",
      url: '',
      dataType: 'json',
      data: {id, factura: "factura"},
      success(data){

      }
    })
  })

  $('#cerrar').click(()=>{
     $('.select2').val(0).trigger('change'); // LIMPIA EL SELECT2
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('#Agregar select').attr("style","borden-color:none;","borden-color:none;");
     $('#Agregar input').attr("style","borden-color:none;","borden-color:none;");
     $('.error').text(" ");
     $('.removeRow').click(); // LIMPIAR FILAS 
     filaN() // 
  })

  // ELIMNINAR VENTA

       $(document).on('click', '.borrar', function(){
        id = this.id;
        
      })   

       function validarEliminar() {
        return new Promise((resolve, reject) => {
          $.ajax({
            type: "POST",
            url: '',
            dataType: "json",
            data: { validarCI: "existe", id },
            success(data) {
              console.log(data);
              if (data.resultado === "Error de venta") {  
                Toast.fire({ icon: 'error', title: 'Esta venta ya esta anulada' }); // ALERTA 
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
        validarEliminar().then(() => {
            // Si la promesa se resuelve, la ejecución continúa
            $.ajax({
              type: "POST",
              url: '',
              data: { eliminar: "asd", id },
              success(data) {
                mostrar.destroy();
                rellenar();
                $('.cerrar').click();
                Toast.fire({ icon: 'success', title: 'Venta eliminada' }); // ALERTA 
              }
            });
          }).catch(() => {
      // Si la promesa se rechaza, la ejecución se detiene
      console.log('No se puede eliminar la venta');
      });
      click++;
    });


});