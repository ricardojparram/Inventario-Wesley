$(document).ready(function(){

    let mostrar;
    let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

     $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
        success(data){ permiso = data; }

      }).then(function(){
        rellenar(true);
        registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
        $('#agregarModal').attr(registrarPermiso, '');
    })

 
  function rellenar(bitacora = false){
   $.ajax({
    type: "POST",
    url: "",
    dataType: "json",
    data: {mostrar: "metodo" , bitacora},
    success(data){
      let tabla;
      data.forEach(row =>{
          editarPermiso = (permiso.editar != 1)?  'disable' : '';
          eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';
          check = (row.online == 1)? 'checked' : '';
           
        tabla += `
        <tr>
        <td>${row.des_tipo_pago}</td>
        <td> <input class="form-check-input boton" type="checkbox" ${check} id="${row.id_tipo_pago}"></td>
        <td class="d-flex justify-content-center">
        <button type="button" ${editarPermiso} id="${row.id_tipo_pago}" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
        <button type="button" ${eliminarPermiso} id="${row.id_tipo_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
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
  let ytipo;
  $("#enviar").click((e)=>{

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
          }else{
            e.preventDefault();
          }
        }
      })
    }
  })
  
$("#cerrarRegis, #cerrar").click(()=>{
  $("#registrarModal input").attr("style","borde-color:none; backgraund-image: none;");
  $("#error").text("");
 })

  let id;
  $(document).on('click', '.borrar', function(){
    id = this.id;
    console.log(id);
  })
  $("#deletes").click((e)=>{
    $.ajax({
      type:"POST",
      url:'',
      dataType:'json',
      data:{
        eliminar:'eliminar',
        id
      },
      success(elba){
        if (elba.resultado === "Eliminado"){
          $("#closeModal").click();
          mostrar.destroy();
          Toast.fire({icon: 'error', title: 'Tipo de pago eliminado'})
          rellenar();

        }else{
          console.log("No se elimino")
        }
      }
    })
  })
 
  let checkbox , value;

  $(document).on('click', '.boton' , function(){
    
    checkbox = this.id;
    isChecked = this.checked;

    check = isChecked ? 1 : 0;

    $.ajax({
      type: 'POST',
      url: '',
      dataType: 'json',
      data:{check , id: checkbox},
      success(data){
        if (data.resultado == 'check editado'){
          mostrar.destroy();
          Toast.fire({ icon: 'success', title: 'online editado'});
          rellenar();
        }
      }
    })

  })



  let unicas;
  $(document).on('click', '.editar', function(){
    unicas = this.id;

    $.ajax({
      type:"POST",   
      url:'',
      dataType:'json',
      data:{
        editar:'siloserick',
        unicas
      },
      success(uno){
        console.log(uno);
        $("#tipoEdit").val(uno[0].des_tipo_pago);
      }
    })
  })

  $("#tipoEdit").keyup(()=> {  validarString($("#tipoEdit"),$("#error2") ,"Error de Tipo de Moneda,") });


  let ctipo; 
  $("#enviarEdit").click((e)=>{
    ctipo = validarString($("#tipoEdit"),$("#error2") ,"Error de Tipo de Moneda,");
    if (ctipo){
    $.ajax({

      type:"POST",
      url:"",
      dataType:"json",
      data:{
        tipoEdit: $("#tipoEdit").val(),
        unicas

      },
      success(data){
        console.log(ctipo);
        if (data.resultado == 'Editado') {
          mostrar.destroy();
          $("#closeEdit").click();
          Toast.fire({ icon: 'success', title: 'Tipo de cambio registrado'});
          rellenar();
        }else{
          e.preventDefault()
        }
      }
    })
}


  })

});
