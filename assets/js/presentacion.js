$(document).ready(function(){

  /* --- FUNCIÓN PARA RELLENAR LA TABLA --- */
  rellenar();
  let mostrar
  function rellenar(){ 
    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: {mostrar: "pres" },
      success(data){
        mostrar = $('#tableMostrar').DataTable({
          responsive: true,
          data: data
        })
      }
    })

  }

  /* --- AGREGAR --- */

  // VALIDACIONES
  $("#medida").keyup(()=> {  validarString($("#medida"),$("#error") ,"Error de  Medida,") });
  $("#cantidad").keyup(()=> {  validarNumero($("#cantidad"),$("#error") , "Error en Cantidad,") });
  $("#peso").keyup(()=> {  validarNumero($("#peso"),$("#error") ,"Error en Peso,") });

  $("#registrar").click((e)=>{

    let vmedida = validarString($("#medida"),$("#error") ,"Error de Medida,");
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
              $("#medEdit").val(data[0].medida);
              $("#cantEdit").val(data[0].cantidad);
              $("#pesEdit").val(data[0].peso);
              
            }

        })

  });



    // VALIDACIONES
  $("#medEdit").keyup(()=> {  validarString($("#medEdit"),$("#error") ,"Error de  Medida,") });
  $("#cantEdit").keyup(()=> {  validarNumero($("#cantEdit"),$("#error") , "Error en Cantidad,") });
  $("#pesEdit").keyup(()=> {  validarNumero($("#pesEdit"),$("#error") ,"Error en Peso,") });

  // FORMULARIO DE EDITAR

  $("#editar").click((e)=>{
      //VALIDACIONES
      let vmedida = validarString($("#medEdit"),$("#error") ,"Error de Medida,");
      let vcantidad = validarNumero($("#cantEdit"),$("#error") , "Error en Cantidad,");
      let vpeso = validarNumero($("#pesEdit"),$("#error") ,"Error en Peso,");


    
    if(vmedida ==true && vcantidad ==true && vpeso ==true){
      //console.log("entrando");

      //  ENVÍO DE DATOS
      $.ajax({

        type: "post",
        url: '',
        data: {
          medEdit : $("#medEdit").val(),
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

    }else{
      e.preventDefault();
    }   

  })


    $(document).on('click', '.borrar', function() {
      id = this.id;
     }); 
      $('#borrar').click(()=>{
        $.ajax({
          type : 'post',
          url : '',
          data : {eliminar : 'asd', id},
          success(data){
            mostrar.destroy();
            $('.cerrar').click();
            rellenar();
            Toast.fire({ icon: 'success', title: 'Tipo de Presentación eliminado' })
          }
        })
      })

      
    

})

