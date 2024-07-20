$(document).ready(function () {

  notificaciones()
  function notificaciones() {
    $.ajax({
      type: 'GET',
      url: '',
      dataType: 'json',
      data: { consultar: "notificaciones" },
      success(data) {
        data ? data : '';
        mostrar = $('#tabla').DataTable({
          responsive: true,
          data: data,
          order: [[3, 'desc']],
          columns: [
            { data: 'titulo' },
            { data: 'fecha' },
            { data: 'mensaje' },
            {
              data: null,
              render: function (data, type, row) {
                let status = Number(row.status); // Convierte a número
                let checked = status === 1 ? '' : 'checked';
                let statusText = status === 1 ? 'Nuevo' : 'Leído ';
                return `
								<div class="form-check">
                                    <input class="form-check-input status-check" type="checkbox" id="${row.id}" ${checked}>
                                    <label class="form-check-label">${statusText}</label>
                                </div>				
							`;
              }
            }
          ],
          drawCallback: function () {
            $('.status-check').off('change').on('change', handleStatusChange)
          }
        })
      }
    })
  }

  function handleStatusChange(event) {
    let checkbox = event.target;
    let id = checkbox.id;
    let newStatus = checkbox.checked ? 0 : 1;
    let label = checkbox.nextElementSibling;

    checkbox.disabled = true;

    $.ajax({
      type: 'POST',
      url: '',
      dataType: 'json',
      data: {
        id: id,
        status: newStatus
      },
      success(data) {
        if (data.resultado === 'ok') {
          label.textContent = newStatus === 1 ? 'Nuevo' : 'Leído';
        } else {
          Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        }
      }
    }).fail(function (e) {
      Toast.fire({ icon: "error", title: e.responseJSON.msg || "Ha ocurrido un error." });
      throw new Error(e.responseJSON.msg);
    }).always(function () {
      checkbox.disabled = false;
    });
  }

})
