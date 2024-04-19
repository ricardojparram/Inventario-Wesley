$(document).ready(function(){

  /* --- FUNCIÓN PARA RELLENAR LA TABLA --- */

  let mostrar;
  let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

  $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
          success(data){ permiso = data; }

        }).then(function(){
          rellenar(true);
          registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
          $('.agregarModal').attr(registrarPermiso, '');
      })

    function rellenar(){
      $.ajax({
        type: "post",
        url: "",
        dataType: "json",
        data: {mostrars: "present"},
        success(data){
            let tabla;
            console.log(data);
            data.forEach(row =>{
                
              tabla += `
              <tr>
              <td>${row.cod_pres}</td>
              <td>${row.cantidad}</td>
              <td>${row.nombre}</td>
              <td>${row.peso}</td>
              <td class="d-flex justify-content-center">
              <button type="button" id="${row.cod_pres}" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
              <button type="button"id="${row.cod_pres}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
              </tr>
              `;
            })

             $('#tbody').html(tabla);
              mostrar = $('#tablas').DataTable({
                responsive : true })
        }
        
      })

    }

    rellenar();
  /* --- AGREGAR --- */

  // VALIDACIONES
  $('#medida').change(function(){ validarSelect($("#medida"),$("#error"),"Error, escoge una medida");})
  $("#cantidad").keyup(()=> {  validarNumero($("#cantidad"),$("#error") , "Error en Cantidad,") });
  $("#peso").keyup(()=> {  validarNumero($("#peso"),$("#error") ,"Error en Peso,") });

  $("#registrar").click((e)=>{

    let vmedida = validarSelect($("#medida"),$("#error"),"Error tipo producto");
    let vcantidad = validarNumero($("#cantidad"),$("#error") , "Error en Cantidad,");
    let vpeso = validarNumero($("#peso"),$("#error") ,"Error en Peso,");


    if(vmedida ==true && vcantidad ==true && vpeso ==true){

      // ENVÍO DE DATOS
      $.ajax({

        type: "post",
        url: '',
        data: {
          med : $("#medida").val(),
          cant : $("#cantidad").val(),
          pes : $("#peso").val()
        },
        success(){

          mostrar.destroy(); // VACÍA LA DATATABLE
          rellenar();  // FUNCIÓN PARA RELLENAR DATATABLE
          $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
            $('.cerrar').click(); // CERRAR EL MODAL
            Toast.fire({ icon: 'success', title: 'Presentación Registrada' }) // ALERTA 
            
        }

      })

      e.preventDefault();

    }else{
      e.preventDefault();
    }   

  })

  /* --- EDITAR --- */
  let id 
    // SELECCIONA ITEM
    $(document).on('click', '.editar', function() {
        id = this.id; // se obtiene el id del botón, previamente le puse de id el codigo en rellenar()
        // RELLENA LOS INPUTS
          $.ajax({
            method: "post",
            url: "",
            dataType: "json",
            data: {select: "pres", id}, // id : id
            success(data){
             // $("#medEdit").val(data[0].medida);
              $("#cantEdit").val(data[0].cantidad);
              $("#pesEdit").val(data[0].peso);
              
            }

        })

  });



    // VALIDACIONES
 // $('#medEdit').change(function(){ validarSelect($("#medida"),$("#error"),"Error, escoge una medida");})
  //$("#cantEdit").keyup(()=> {  validarNumero($("#cantEdit"),$("#error") , "Error en Cantidad,") });
  //$("#pesEdit").keyup(()=> {  validarNumero($("#pesEdit"),$("#error") ,"Error en Peso,") });

  // FORMULARIO DE EDITAR

    $("#editarP").click((e)=>{
      console.log("Editando");

      //VALIDACIONES
     // let vmedida = validarString($("#medidas"),$("#error") ,"Error de Medida,");
      let vcantidad = validarNumero($("#cantEdit"),$("#error") , "Error en Cantidad,");
      let vpeso = validarNumero($("#pesEdit"),$("#error") ,"Error en Peso,");


    

      console.log("entrando");

      //  ENVÍO DE DATOS
      $.ajax({

        type: "post",
        url: '',
        data: {
          medEdit : $("#medidas").val(),
          cantEdit : $("#cantEdit").val(),
          pesEdit : $("#pesEdit").val(),
          id
          
        },
        success(){
          mostrar.destroy();
          rellenar(); 
          $('#editarform').trigger('reset');
          $('.cerrar').click();
          Toast.fire({ icon: 'success', title: 'Presentación modificada' })
        }

      })

      e.preventDefault();

     

  })

  $(document).on('click', '.borrar', function() {
      id = this.id;
    });
      $('#borrar').click((e)=>{
        e.preventDefault();

        $.ajax({
          type : 'post',
          url : '',
          dataType: 'json',
          data : {borrar : 'asd', id},
          success(data){

            
            mostrar.destroy();
            $('#cerrar').click();
            rellenar();
            Toast.fire({ icon: 'success', title: 'Clase eliminada' })
          }
        })
      })

      
    

})

