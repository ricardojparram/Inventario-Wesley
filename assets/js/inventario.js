$(document).ready(function(){

 let mostrar
  let permisos, editarPermiso, eliminarPermiso;
    $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos:''},
        success(data){ permisos = data; }
    }).then(() => rellenar(true));

  function rellenar(bitacora = false){ 
        $.ajax({ type: "post", url: "", dataType: "json", data: {mostrar: "inv", bitacora},
            success(data){
                let tabla;
                editarPermiso = (typeof permisos.Editar === 'undefined') ? 'disabled' : '';
                eliminarPermiso = (typeof permisos.Eliminar === 'undefined') ? 'disabled' : '';
                data.forEach(row => {
                    tabla += `
                        <tr>
                            <td>${row.fecha}</th>
                            <td scope="col">${row.id_producto_sede}</td>
                            <td scope="col">${row.entrada}</td>                      
                            <td scope="col">${row.salida}</td>
                            <td scope="col">${row.id_sede}</td>
                            <td scope="col">${row.cantidad}</td>
                            
                        </tr>`;
                });
                $('#tableMostrar tbody').html(tabla);
                mostrar = $('#tableMostrar').DataTable({
                    resposive: true
                });
            },
            error(e){
                Toast.fire({ icon: 'error', title: 'Ha ocurrido un error.' });
                throw new Error('Error al mostrar listado: '+e);
            }
        })

    }




})

