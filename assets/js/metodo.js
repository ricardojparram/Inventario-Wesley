$(document).ready(function(){

    let mostrar;
    let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

     $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:''},
        success(permisos){
          registrarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : ''; 
          editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
          eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
        }
    }).then(() => rellenar(true));

 
  function rellenar(bitacora = false){
   $.ajax({
    type: "POST",
    url: "",
    dataType: "json",
    data: {mostrar: "metodo" , bitacora},
    success(data){
      let tabla;
      data.forEach(row =>{           
        tabla += `
        <tr>
        <td>${row.tipo_pago}</td>
        <td class="d-flex justify-content-center">
        <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
        <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
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

  function validarTipoPago(input , div , id = false){
    return new Promise((resolve , reject)=>{
      $.post('' ,{tipoPago: input.val(), validarTipoPago: "metodo" , id},
        function(data){
          let mensaje = JSON.parse(data);
          if(mensaje.resultado === "error"){
            div.text(mensaje.error);
            input.attr("style","border-color: red;")
            input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
            return reject(false);
          }else{
            div.text(" ");
            return resolve(true);
          }
        })
    })
  }

  let ytipo;
  let click = 0;
  setInterval(()=>{ click = 0; }, 2000);

  $('#tipo').keyup(()=>{ validarString($("#tipo"),$("#error"),"Error de tipo de pago") })

  $("#enviar").click((e)=>{
    e.preventDefault()

    if(registrarPermiso === 'undefined'){
      Toast.fire({ icon: 'error', title: 'No tienes permisos para esta acción.' });
      throw new Error('Permiso denegado.');
    }

    if(click >= 1) throw new Error('Spam de clicks');

    ytipo = validarString($("#tipo"),$("#error"),"Error de tipo de pago");

    if (ytipo) {


      $.ajax({

        type:"POST",
        url:"",
        dataType:"json",
        data:{
          metodo:$("#tipo").val()
        },
        success(data){
          console.log(data.resultado);
          if (data.resultado == 'registrado correctamente') {
            mostrar.destroy();
            $("#close").click();
            Toast.fire({ icon: 'success', title: 'metodo de pago registrado' });
            rellenar();
          }
        }
      })
    }
    click++;
  })
  

$("#cerrarRegis, #cerrar").click(()=>{
  $("#registrarModal input").attr("style","borde-color:none; backgraund-image: none;");
  $("#error").text("");
 })

  let id;
  $(document).on('click', '.borrar', function(){
    id = this.id;
  })

  $("#deletes").click((e)=>{
    e.preventDefault();

    if(click >= 1) throw new Error('Spam de clicks');

    $.ajax({
      type:"POST",
      url:'',
      dataType:'json',
      data:{
        eliminar:'eliminar',
        id
      },
      success(data){
        if (data.resultado === "Eliminado"){
          $("#closeModal").click();
          mostrar.destroy();
          Toast.fire({icon: 'error', title: 'Tipo de pago eliminado'})
          rellenar();
        }
      }
    })
    click++;
  })
 

  $(document).on('click', '.editar', function(){
    id = this.id;

    $.ajax({
      type:"POST",   
      url:'',
      dataType:'json',
      data:{
        editar:'editar metodo de pago',
        id
      },
      success(data){
        $("#tipoEdit").val(data[0].tipo_pago);
      }
    })
  })

  $("#tipoEdit").keyup(()=> {  validarString($("#tipoEdit"),$("#error2") ,"Error de Tipo de Moneda,") });


  let ctipo; 
  $("#enviarEdit").click((e)=>{
    e.preventDefault()
    
    if(click >= 1) throw new Error('Spam de clicks');

    ctipo = validarString($("#tipoEdit"),$("#error2") ,"Error de Tipo de Moneda,");
    if (ctipo){
      $.ajax({

        type:"POST",
        url:"",
        dataType:"json",
        data:{
          tipoEdit: $("#tipoEdit").val(),
          id

        },
        success(data){
          if (data.resultado == 'Editado') {
            mostrar.destroy();
            $("#closeEdit").click();
            Toast.fire({ icon: 'success', title: 'Tipo de pago registrado'});
            rellenar();
          }
        }
      })
    }
    click++;

  })

});
