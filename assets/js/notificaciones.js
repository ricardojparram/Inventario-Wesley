$(document).ready(function(){

  let tiempo_para_repetir_peticion = 1800000; 

  getNotificaciones()
  setInterval(getNotificaciones , tiempo_para_repetir_peticion);


  function getNotificaciones(){
    $.ajax({ type : 'POST', url: '?url=notificaciones', dataType: 'json', data: {notificaciones: 'consultar'},
      success(data){
        notificaciones = data;
        mostrarNotificacion(notificaciones);
      }
    })
  }

  function mostrarNotificacion(notificaciones){
     let mostrar = '';
     let notifications = 0
     
     notificaciones.forEach((row) => {
    
      let minutos = 0;
      let tiempoMinutos = 60000;
      const actualizaMinutos = function() {
        minutos++;
        $('.notification-item .tiempo').text(`Tiempo activo: ${minutos} minutos`);
      };
      setInterval(actualizaMinutos, tiempoMinutos);
      mostrar += `
      <li id="${row.id}" class="notification-item notificacion w-100"  data-bs-toggle="modal" data-bs-target="#notificacion">
      <div class="row">
       <div class="col-md-12">
        <div class="d-flex justify-content-center">
          <div class="text-center mt-3">
            <i class="bi bi-exclamation-circle text-warning"></i>
          </div>
          <div class="mx-2">
            <h4>${row.titulo}</h4>
            <p></p>
            <p class='tiempo'>Tiempo activo: ${minutos} minutos</p>
          </div>
          </div>
        </div>
         <div class="col fs-5 text-end NotiButton">
           <a id="${row.id}" class="leido btn-sm" href="#">Marcar como leido</a>
        </div>
      </div>
      </li>

      <li class='divisor'>
        <hr class="dropdown-divider">
      </li>

      `;
      notifications++;
   })

    let total_notificaciones = notifications 

    $('.notifications .item').html('');
    $('.notifications .item').append(mostrar);
    $('.contador').text(total_notificaciones);
    $('.numNoti').text(total_notificaciones);
 
}


  $(document).on('click' , '.notificacion' , function(e){
    const notificationId = $(this).attr('id');

    $.getJSON('?url=notificaciones', {detalleNotificacion: 'detalle' , notificationId}, function(data) {
        $('.titulo').html(`<b>Titulo</b>: ${data[0].titulo}.`);
        $('.fecha').html(`<b>Fecha</b>: ${data[0].fecha}.`);
        $('.mensaje').html(`<b>Descripción</b>:<br> ${data[0].mensaje}.`);
    });

  })


   $(document).on('click' , '.leido' , function(e){
      e.stopPropagation();
      
      const notificationId = $(this).attr('id');
      const selectedNotification = $(this).closest('.notification-item');
      let divisor = selectedNotification.next('.divisor');

        $.ajax({ type : 'POST', url: '?url=notificaciones', dataType: 'json', data: {notificacionVista: '' , notificationId},
        success(data){
          selectedNotification.add(divisor).fadeOut('slow', function() {
          $(this).remove();
         });
          total_notificaciones = $('.contador').text() - 1;
          $('.contador').text(total_notificaciones);
          total_notificaciones = $('.numNoti').text() - 1;
          $('.numNoti').text(total_notificaciones)
        }
     })

    })
   
    
})
