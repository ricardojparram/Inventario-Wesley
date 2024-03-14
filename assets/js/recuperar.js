$(document).ready(function(){


  let click = 0;
  setInterval(()=>{ click = 0 }, 5000);

  $("#boton").click((e)=>{
    e.preventDefault()
    if(click >= 1) throw new Error('Spam de clicks');
    click++;
    
    $.ajax({
      type: "post",
      url: '',
      dataType: 'json',
      data: {
        email: $("#email").val(),
      },
      success(data){
        let vemail
        validarCorreo($("#email"),$("#error") ,"Error de correo,")

        if(data.resultado === "Error de email"){
          $("#error").text(data.error);
          $("#cedula").attr("style","border-color: red;")
          $("#cedula").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);"); 
        }else{
          vemail = true;
        }

        if(data.resultado != "Correo enviado"){
          Swal.fire({
            title: 'Ha ocurrido un error!',
            text: 'El envío del correo falló',
            icon: 'error',
          })
          throw new Error('Error: El correo no se envió.')
        }

        if(vemail){

          Swal.fire({
            title: 'Correo enviado!',
            text: 'La contraseña se ha enviado a su correo.',
            icon: 'success',
          })
          setTimeout(function(){
            location = '?url=login'
          }, 1600);

        }


      }

    })
  })

  if('carrito' in localStorage != false){
    let car = (localStorage.getItem('carrito') === '') ? '' : JSON.parse(localStorage.getItem('carrito'));
    let cantidad = Object.keys(car).length;
    $('#carrito_badge').html(cantidad);
  };

});
