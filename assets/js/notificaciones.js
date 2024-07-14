$(document).ready(function(){

    const ws = new WebSocket('ws://localhost:8080');

   ws.onopen = function() {
       console.log('Connected to the WebSocket server');
    };
  
    ws.onmessage = function(event) {
      const data = JSON.parse(event.data);
      console.log(data);
      mostrarNotificacion(data);
    };
  
    ws.onerror = function(error) {
        console.log('WebSocket Error: ' + error);
    };
  
    ws.onclose = function() {
       console.log('Disconnected from the WebSocket server');
    };
  

  getNotificaciones();

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
    
    const $notificationButton = $(this);
    const notificationId = $notificationButton.attr('id');
  
    // Deshabilitar el botón para evitar múltiples clics
    $notificationButton.prop('disabled', true);
  
    $.ajax({ 
      type: 'POST', 
      url: '?url=notificaciones', 
      dataType: 'json', 
      data: { notificacionVista: '', notificationId },
      success: function(data) {
        let total_notificaciones = parseInt($('.contador').text());
  
        // Solo disminuir el contador si es mayor que 0
        if (total_notificaciones > 0) {
          total_notificaciones--;
        }
  
        // Actualizar los elementos de la UI
        $('.contador').text(total_notificaciones);
        $('.numNoti').text(total_notificaciones);
  
        // Eliminar la notificación visualmente
        $(`#${notificationId}`).closest('.divNotificacion').fadeOut(500, function() {
          $(this).remove();  
        });
        getNotificaciones();
      },
      error: function(xhr, status, error) {
        console.log(error);
        $notificationButton.prop('disabled', false);
      }
    });
  });
  

   
    
})
