$(document).ready(function(){

let mostrar;
let permiso, editarPermiso, eliminarPermiso, registrarPermiso;

$.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
	  success(data){ permiso = data; }
 
    }).then(function(){
        rellenar(true);
        registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
        $('.agregarModal').attr(registrarPermiso, '');
       })
  

















    
})