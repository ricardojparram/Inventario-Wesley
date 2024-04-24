$(document).ready(function(){


  getNotificaciones();
  setInterval(getNotificaciones, 600000);

  function getNotificaciones(){
    $.ajax({ type : 'POST', url: '?url=notificaciones', dataType: 'json', data: {notificaciones: 'consultar'},
      success(data){
        notificaciones = data;
        mostrarNotificacion(notificaciones);
      },
      error: function(xhr, status, error){
        console.log(xhr.responseText);
      }
    })
  }

  function mostrarNotificacion(notificaciones){
     let mostrar = '';
     let notifications = 0
     
     notificaciones.forEach((row) => {
    
      mostrar += `
      <div class='divNotificacion'>
        <li id="${row.id}" class="notification-item notificacion w-100"  data-bs-toggle="modal" data-bs-target="#notificacion">
          <div class="row">
           <div class="col-md-12">
            <div class="d-flex justify-content-center">
              <div class="text-center mt-3">
                <i class="bi bi-exclamation-circle text-warning"></i>
              </div>
              <div class="mx-2">
                <a>${row.titulo}</a>
                <p></p>
                <p class='tiempo'>Tiempo activo: ${row.fecha} minutos</p>
              </div>
              </div>
            </div>
          </div>
          </li> 

          <div class="col-12 me-5 pe-4 fs-5 text-end NotiButton">
               <a id="${row.id}" class="leido btn-sm" href="#">Marcar como leido</a>
            </div>

          <li class='divisor'>
            <hr class="dropdown-divider">
          </li>
      </div>

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

$(document).on('click', '.leido', function(e) {
  e.stopPropagation();
  
  const notificationId = $(this).attr('id');

  // Realizar la solicitud AJAX para marcar como leído
  $.ajax({ 
    type: 'POST', 
    url: '?url=notificaciones', 
    dataType: 'json', 
    data: { notificacionVista: '', notificationId },
    success: function(data) {
      // Actualizar el contador de notificaciones
      let total_notificaciones = parseInt($('.contador').text()) - 1;
      $('.contador').text(total_notificaciones);
      $('.numNoti').text(total_notificaciones);

      $(`#${notificationId}`).closest('.divNotificacion').fadeOut(500, function() {
        $(this).empty();
      });
    },
    error: function(xhr, status, error) {
      console.log(error);
    }
  });
});

   
    
})
