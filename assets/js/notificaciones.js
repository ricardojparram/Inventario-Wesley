$(document).ready(function(){

  let tiempo_para_repetir_peticion = 1800000; 


  getRegistrarNotificaciones()
  setInterval(getRegistrarNotificaciones, tiempo_para_repetir_peticion);

  function getRegistrarNotificaciones(){
    $.ajax({ type: 'POST', url:  '?url=notificaciones', dataType: 'json', data: {notificacionRegistrar : ''},
      success(data){
        if (data.resultado === 'notificaciones registradas.'){
          throw new Error('notificaciones registradas');
        }
        
      }
    })
  }


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
     let mostrar = '', mostrar1 = '', mostrar2 = '', mostrar3 = '';
     let count_stock_bajo = 0, count_vencidos = 0, count_dia_inventario = 0 , count_pedido_pagos = 0;
     
     notificaciones.forEach((row) => {
    

     if(row.tipo_notificacion === 'productos_stock_bajo'){

      let minutos = 0;
      let tiempoMinutos = 60000;
      const actualizaMinutos = function() {
        minutos++;
        $('.notification-item .tiempo').text(`Tiempo activo: ${minutos} minutos`);
      };
      setInterval(actualizaMinutos, tiempoMinutos);
      mostrar += `
      <li class="notification-item notificacion w-100">
      <div class="row">
       <div class="col-md-12">
        <div class="d-flex justify-content-between">
          <div class="text-center mt-3">
            <i class="bi bi-exclamation-circle text-warning"></i>
          </div>
          <div class="mx-2">
            <h4>Productos por expirar</h4>
            <p>${row.mensaje}</p>
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
      count_stock_bajo++;
     }

    if(row.tipo_notificacion === 'productos_vencidos'){

      let minutos = 0;
      let tiempoMinutos = 60000;
      const actualizaMinutos = function() {
        minutos++;
        $('.notification-item .tiempo').text(`Tiempo activo: ${minutos} minutos`);
      };
      setInterval(actualizaMinutos, tiempoMinutos);
      mostrar1 += `
        <li class="notification-item notificacion">
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex justify-content-between">
                <div class="text-center mt-3">
                  <i class="bi bi-x-circle text-danger"></i>
                </div>
                <div>
                  <h4>Producto vencido</h4>
                  <p>${row.mensaje}</p>
                  <p class='tiempo'>tiempo activo: ${minutos} minutos</p>
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
      count_vencidos++;
    }

     

     if(row.tipo_notificacion === 'dia_de_inventario'){

      let minutos = 0
      let tiempoMinutos = 60000
      const actualizaMinutos = function() {
        minutos++;
        $('.notification-item .tiempo').text(`Tiempo activo: ${minutos} minutos`);
      };
      setInterval(actualizaMinutos, tiempoMinutos);
      mostrar2 += `
      <li class="notification-item notificacion w-100">
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-between">
              <div class="text-center mt-3">
                <i class="bi bi-exclamation-circle text-warning"></i>
              </div>
              <div>
                <h4>Dia de Inventario: ${row.dia_de_inventario}</h4>
                <p>Stock Producto: ${row.stock}</p>
                <p>${row.mensaje}</p>
                <p class='tiempo'>tiempo activo: ${minutos} minutos</p>
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
      count_dia_inventario++;
     }

      if(row.tipo_notificacion === 'compra_realizada'){

      let minutos = 0;
      let tiempoMinutos = 60000;
      const actualizaMinutos = function() {
        minutos++;
        $('.notification-item .tiempo').text(`Tiempo activo: ${minutos} minutos`);
      };
      setInterval(actualizaMinutos, tiempoMinutos);
      mostrar3 += `
        <li class="notification-item notificacion">
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex justify-content-between">
                <div class="text-center mt-3">
                  <i class="bi bi-exclamation-circle text-warning"></i>
                </div>
                <div>
                  <h4>Se realizo una compra online</h4>
                  <p>${row.mensaje}<br> ${row.fecha}</p>
                  <p class='tiempo'>tiempo activo: ${minutos} minutos</p>
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
      count_pedido_pagos++;
    }

   });

    let total_notificaciones = count_stock_bajo + count_vencidos + count_dia_inventario + count_pedido_pagos;

    $('.notifications .item').html('');
    $('.notifications .item').append(mostrar);
    $('.notifications .item').append(mostrar1);
    $('.notifications .item').append(mostrar2);
    $('.notifications .item').append(mostrar3);
    $('.contador').text(total_notificaciones);
    $('.numNoti').text(total_notificaciones);

  }


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
