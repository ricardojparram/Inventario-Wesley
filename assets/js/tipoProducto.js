$(document).ready(function(){

    let mostrar;
    let permiso , editarPermiso , eliminarPermiso, registrarPermiso;

     $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
        success(data){ permiso = data; }

      }).then(function(){
        rellenar(true);
        registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
        $('.agregarModal').attr(registrarPermiso, '');
    })

        function rellenar(bitacora = false){
        $.ajax({
            type: "post",
            url: "",
            dataType: "json",
            data: {mostrar: "labs" , bitacora},
            success(data){
              let tabla;
              data.forEach(row =>{
                  editarPermiso = (permiso.editar != 1)?  'disable' : '';
                  eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';

                tabla += `
                <tr>
                <td>${row.nombrepro}</td>
                <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.id_tipoprod}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.id_tipoprod}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
                </td>
                </tr>
                `;
              })
              $('#tbody').html(tabla ? tabla: "");
                mostrar = $('#tabla').DataTable({
                  resposive : true
                })
            }
        })
    }

    function validarTipo(input , div , id = false){
        return new Promise((resolve , reject)=>{
          $.post('' ,{tipoProducto : input.val(), validarTipo: "tipo de producto" , id},
            function(data){
              let mensaje = JSON.parse(data);
              if(mensaje.resultado === "error"){
                div.text(mensaje.msg);
                input.attr("style","border-color: red;")
                input.attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
                return reject(false);
              }else{
                div.text(" ");
                input.attr("style","border-color: none"); 
                return resolve(true);
              }
            })
        })
      }
    
     $("#tipoProducto").keyup(()=> {  
        let valid = validarNombre($("#tipoProducto"),$("#error") ,"Error del tipo de Producto,");
        if(valid){
            validarTipo($("#tipoProducto"), $("#error"));
        }
    });






$("tipoProducto").keyup(()=> {  validarNombre($("#tipoProducto"),$("#error"), "Error de tipoProducto,") });
let EtipoProducto
$("#enviar").click((e)=>{
    e.preventDefault();
    EtipoProducto = validarNombre($("#tipoProducto"),$("#error") , "Error de tipoProducto,");
    if (EtipoProducto){
    $.ajax({
        type: "post",
        url: '',
        dataType: 'json',
        data: {
            tipoProducto: $("#tipoProducto").val()
        },
        success(data){
            if (data.resultado === 'Registrado correctamente.') {
                mostrar.destroy(); 
                rellenar();  
                  $('#close').click(); 
                Toast.fire({ icon: 'success', title: 'Tipo de producto registrada' }) 
            }else if(data.resultado === 'error'){
                $("#error").text(data.msg);
                $("#tipoProducto").attr("style","border-color: red;")
                $("#tipoProducto").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
              }
        }
    })
}
})

$("#cerrarRegist").click(()=>{
 $("#basicModal input").attr("style","borde-color:none; backgraund-image: none;");
 $("#error").text("");
})

let id
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
                Toast.fire({ icon: 'error', title: 'Tipo de producto eliminado' })
            }
        })
    })



let idedit;

$(document).on('click', '.editar', function() {
    idedit = this.id; 
    
           $.ajax({
               method: "post",
               url: "",
               dataType: "json",
            data: {item: "lol", idedit}, 
            success(data){
                $("#tipoProductoEdit").val(data[0].nombrepro);
            }

        })

});

$("#tipoProductoEdit").keyup(()=> {  validarNombre($("#tipoProductoEdit"),$("#error2"), "Error de tipo de producto,") });
let ZtipoProductoEdit
$("#enviarEdit").click((e)=>{
 e.preventDefault();
 ZtipoProductoEdit = validarNombre($("#tipoProductoEdit"),$("#error2") , "Error de tipo de producto,");
    if(ZtipoProductoEdit){
    $.ajax({
        type: "post",
        url: '',
        dataType: 'json',
        data: {
            tipoProductoEdit: $("#tipoProductoEdit").val(),idedit
        },
        success(data){
            if (ZtipoProductoEdit) {
                mostrar.destroy();  
                  $('#cerraar').click(); 
                Toast.fire({ icon: 'success', title: 'Tipo de producto actualizada' })
                rellenar(); 
            }else{
                e.preventDefault()
            }
        }
})
}
})



















})