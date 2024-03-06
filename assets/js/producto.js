$(document).ready(function(){

    fechaHoy($('#fecha'));

  let tablaMostrar;
  let permiso , eliminarPermiso, registrarPermiso;

  $.ajax({method: 'POST', url: "", dataType: 'json', data: {getPermisos : "permiso"},
    success(data){ permiso = data; }

  }).then(function(){
    rellenar(true);
    registrarPermiso = (permiso.registrar != 1)? 'disable' : '';
    $('#agregarModal').attr(registrarPermiso, '');
  })

       function rellenar(bitacora = false){
         $.ajax({
          method: "POST",
          url: " ",
          dataType: "json",
          data:{mostrar: "produ" , bitacora},
          success(data){
           let tabla;
            data.forEach(row =>{
              editarPermiso = (permiso.editar != 1)?  'disable' : '';
              imagenPermiso = (permiso.imagen != 1)? 'disable' : '';
              eliminarPermiso = (permiso.eliminar != 1)? 'disable' : '';

              tabla += `
              <tr>
              <td>${row.descripcion}</td>
              <td>${row.stock}</td>
              <td>${row.venta}</td>
              <td>${row.des_tipo}</td>
              <td>${row.vencimiento}</td>
              <td class="d-flex justify-content-center">
              <button type="button" ${editarPermiso} id="${row.cod_producto}" class="btn btn-success editar mx-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
              <button type="button" ${imagenPermiso} id="${row.cod_producto}" class="btn btn-success infoImg mx-2" data-bs-toggle="modal" data-bs-target="#infoImg"> <i class="bi bi-eye icon-24 t" width="20"></i></button>
              <button type="button" ${eliminarPermiso} id="${row.cod_producto}" class="btn btn-danger borrar mx-2"  data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
              
             
              </td>
              </tr>
              `
            })
            $('#tbody').html(tabla);
            tablaMostrar = $('#tableMostrar').DataTable({
              resposive : true
            })
          }
        })
      }



       $('#codigo').keyup(()=> {validarStringLength($("#codigo"),$("#error2"),"Error de nombre",50) });
       $('#descripcion').keyup(()=> {validarStringLength($("#descripcion"),$("#error3"),"Error de nombre", 200) });

      $('#fecha').change(function(){
      validarFechaHoy($("#fecha"),$("#error4"),"Error de fecha"); 
     })
      $('#composición').keyup(()=> {validarStringLength($("#composición"),$("#error5"),"Error de Composición", 50) });

      $('#posologia').keyup(()=> {validarStringLength($("#posologia"),$("#error6"),"Error de posologia",400) });

       $('#ubicación').change(function(){
        validarSelect($("#ubicación"),$("#error7"),"Error, elige ubicación");
      })
      $('#laboratorio').change(function(){
        validarSelect($("#laboratorio"),$("#error8"),"Error, elige laboratorio");
      })
      $('#presentación').change(function(){
       validarSelect($("#presentación"),$("#error9"),"Error, elige presentación");
     })
      $('#tipoP').change(function(){
        validarSelect($("#tipoP"),$("#error10"),"Error, elige tipo producto");
      })
      $('#clase').change(function(){
       validarSelect($("#clase"),$("#error11"),"Error, elige clase");
     })
      $('#contraIn').keyup(()=> {validarStringLength($("#contraIn"),$("#error12"),"Error elige ubicación",400) });
      $('#cantidad').keyup(()=> {validarNumero($("#cantidad"),$("#error13"),"Error de cantidad") });
      $('#precioV').keyup(()=> {validarVC($("#precioV"),$("#error14"),"Error de precio venta") });

      let click = 0;
      setInterval(()=>{click = 0}, 2000);

    /* --- AGREGAR --- */

    $("#boton").click((e)=>{

      if(click >= 1) throw new Error('Spam de clicks');
    
      let codigo = validarStringLength($("#codigo"),$("#error2"),"Error de nombre",50);
      let descripcion = validarStringLength($("#descripcion"),$("#error3"),"Error de descripcion",200);
      let fecha = validarFechaHoy($("#fecha"),$("#error4"),"Error de fecha");
      let composición = validarStringLength($("#composición"),$("#error5"),"Error de Composición", 50);
      let posologia = validarStringLength($("#posologia"),$("#error6"),"Error de posologia",400);
      let ubicación = validarSelect($("#ubicación"),$("#error7"),"Error ubicación");
      let laboratorio = validarSelect($("#laboratorio"),$("#error8"),"Error laboratorio");
      let presentación = validarSelect($("#presentación"),$("#error9"),"Error presentación");
      let tipo = validarSelect($("#tipoP"),$("#error10"),"Error tipo producto");
      let clase = validarSelect($("#clase"),$("#error11"),"Error clase");
      let contraIn = validarStringLength($("#contraIn"),$("#error12"),"Error contraindicaciones",400);
      let cantidad = validarNumero($("#cantidad"),$("#error13"),"Error de cantidad");
      let precioV = validarVC($("#precioV"),$("#error14"),"Error de precio venta");

      if(codigo && descripcion && fecha && composición && posologia && ubicación && laboratorio && presentación && tipo && clase && contraIn  && cantidad && precioV){
        
        $descripcionP = $('#descripcion');
        $fechaVP = $('#fecha');
        $composicionP = $('#composición');
        $posologiaP = $('#posologia');
        $ubicaciónP = $('#ubicación');
        $laboratorioP = $('#laboratorio');
        $presentaciónP = $('#presentación');
        $tipoP = $('#tipoP');
        $clase = $('#clase');
        $contraInP = $('#contraIn');
        $cantidadP = $('#cantidad');
        $precioVenP = $('#precioV');
        $codigo = $('#codigo');


        //  ENVÍO DE DATOS
        $.ajax({
          type: "POST",
          url: '',
          dataType: 'json',
          data: {
            "descripcion" : $descripcionP.val(),
             "codigo" : $codigo.val(),
            "fechaV" : $fechaVP.val(),
            "composicionP" : $composicionP.val(),
            "posologia" : $posologiaP.val(),
            "ubicación" : $ubicaciónP.val(),
            "laboratorio" : $laboratorioP.val(),
            "presentación" : $presentaciónP.val(),
            "tipoP" : $tipoP.val(),
            "clase" : $clase.val(),           
            "contraIn" : $contraInP.val(),
            "cantidad" : $cantidadP.val(),
            "precioV" : $precioVenP.val(),

          },
          success(data){

          tablaMostrar.destroy()  
          rellenar();  // FUNCIÓN PARA RELLENAR
          $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
          $('.cerrar').click();
          fechaHoy($('#fecha'));
          Toast.fire({ icon: 'success', title: 'Producto registrada' });
        }
        
      })
        
      }else{
        e.preventDefault();
      }
      click++;
    });

  /* --- CERRAR REGISTRAR --- */

  $('#cerrar').click(()=>{
     $('#agregarform').trigger('reset'); // LIMPIAR EL FORMULARIO
     $('#basicModal input').attr("style","borden-color:none;","borden-color:none;");
     $('#basicModal select').attr("style","borden-color:none;","borden-color:none;");
     $('.error').text('');
     fechaHoy($('#fecha'));
   })

   /* --- CERRAR MODIFICAR --- */

   $('#Cancelar').click(()=>{
     $('#editModal input').attr("style","borden-color:none;","borden-color:none;");
     $('#editModal select').attr("style","borden-color:none;","borden-color:none;");
     $('#error').text('');
     fechaHoy($('#fecha'));
   })

   /* --- EDITAR --- */ 
   let id;

   $(document).on('click', '.editar', function() {
        id = this.id; // se obtiene el id del botón, previamente le puse de id el codigo en rellenar()
          // RELLENA LOS INPUTS
          $.ajax({
            method: "POST",
            url: "",
            dataType: "json",
            data: {select: "edit", id},
            success(data){
             
              $("#descripcionEd").val(data[0].descripcion);
               $("#codigoEd").val(data[0].codigo);
              $("#fechaEd").val(data[0].vencimiento);
              $("#composicionEd").val(data[0].composicion);
              $("#posologiaEd").val(data[0].posologia); 
              $("#ubicaciónEd").val(data[0].ubicacion);
              $("#laboratorioEd").val(data[0].cod_lab);
              $("#presentaciónEd").val(data[0].cod_pres);
              $("#tipoEd").val(data[0].cod_tipo);
              $("#claseEd").val(data[0].cod_clase);
              $("#contraInEd").val(data[0].contraindicaciones);
              $("#cantidadEd").val(data[0].stock);
              $("#VentaEd").val(data[0].p_venta);
              
            }

          })
      });
      
      $('#codigoEd').keyup(() => validarStringLength($("#codigoEd"),$("#errorE2"),"Error de nombre"))
      $('#descripcionEd').keyup(()=> {validarStringLength($("#descripcionEd"),$("#errorE3"),"Error de descripcion") });
       
       $('#fechaEd').change(function(){
       validarFecha($('#fechaEd'),$("#errorE4"),"Error de fecha");
      })

      $('#composicionEd').keyup(()=> {validarStringLength($("#composicionEd"),$("#errorE5"),"Error de Composición") });
     
      $('#posologiaEd').keyup(()=> {validarStringLength($("#posologiaEd"),$("#errorE6"),"Error de posologia") });
      $('#ubicaciónEd').change(function(){
        validarSelect($("#ubicaciónEd"),$("#errorE7"),"Error ubicación");
      })
      $('#laboratorioEd').change(function(){
        validarSelect($("#laboratorioEd"),$("#errorE8"),"Error laboratorio");
      })
      $('#presentaciónEd').change(function(){
       validarSelect($("#presentaciónEd"),$("#errorE9"),"Error presentación");
     })
      $('#tipoEd').change(function(){
        validarSelect($("#tipoEd"),$("#errorE10"),"Error tipo producto");
      })
      $('#claseEd').change(function(){
       validarSelect($("#claseEd"),$("#errorE11"),"Error clase");
     })
         
      $('#contraInEd').keyup(()=> {validarStringLength($("#contraInEd"),$("#errorE12"),"Error de contraindicaciones") });
      $('#cantidadEd').keyup(()=> {validarNumero($("#cantidadEd"),$("#errorE13"),"Error de cantidad") });
      $('#VentaEd').keyup(()=> {validarVC($("#VentaEd"),$("#errorE14"),"Error de precio venta") });

     
      

      $("#actualizar").click((e)=>{
          //VALIDACIONES
          if(click >= 1) throw new Error('Spam de clicks');
          
          let codigoEd = validarStringLength($("#descripcionEd"),$("#errorE2"),"Error de descripcion", 50);
          let descripcionE = validarStringLength($("#descripcionEd"),$("#errorE3"),"Error de descripcion", 200);        
          let fechaE = validarFecha($('#fechaEd'),$("#errorE4"),"Error de fecha")
          let composicionE = validarStringLength($("#composicionEd"),$("#errorE5"),"Error de Composición", 50) ;
          let posologiaE = validarStringLength($("#posologiaEd"),$("#errorE6"),"Error de posologia",400) ;
          let ubicaciónE = validarSelect($("#ubicaciónEd"),$("#errorE7"),"Error ubicación");
          let laboratorioE = validarSelect($("#laboratorioEd"),$("#errorE8"),"Error laboratorio");
          let presentaciónE = validarSelect($("#presentaciónEd"),$("#errorE9"),"Error presentación");
          let tipoE = validarSelect($("#tipoEd"),$("#errorE10"),"Error tipo producto");
          let claseE = validarSelect($("#claseEd"),$("#errorE11"),"Error clase");
          let contraInE = validarStringLength($("#contraInEd"),$("#errorE12"),"Error de contraindicaciones", 400);
          let cantidadE = validarNumero($("#cantidadEd"),$("#errorE13"),"Error de cantidad");
          let VentaE = validarVC($("#VentaEd"),$("#errorE14"),"Error de precio venta");


          if(codigoEd && descripcionE && fechaE && composicionE && posologiaE && ubicaciónE && laboratorioE && presentaciónE && tipoE && claseE  && contraInE && cantidadE && VentaE){

      //  ENVÍO DE DATOS
      $.ajax({
        type: "POST",
        url: '',
        dataType: "json",
        data: {
          
          
          descripcionEd : $('#descripcionEd').val(),
          codigoEd : $('#codigoEd').val(),
          fechaEd : $('#fechaEd').val(),
          composicionEd : $('#composicionEd').val(), 
          posologiaEd: $('#posologiaEd').val(),
          ubicaciónEd : $('#ubicaciónEd').val() ,
          laboratorioEd : $('#laboratorioEd').val() ,
          presentaciónEd : $('#presentaciónEd').val() ,
          tipoEd : $('#tipoEd').val() ,
          claseEd : $('#claseEd').val(),    
          contraInEd : $('#contraInEd').val() ,
          cantidadEd : $('#cantidadEd').val() ,
          VentaEd : $('#VentaEd').val() ,
          id 
        },
        success(data){
         tablaMostrar.destroy();
          rellenar();  // FUNCIÓN PARA RELLENAR
          $('#editarform').trigger('reset');
          $('.cerrar').click();
          Toast.fire({ icon: 'success', title: 'Producto Actualizado' });
        } 

      }) 

    }else{
      e.preventDefault();
    }
    click++;

  })


    /* --- ELIMINAR --- */


    $(document).on('click','.borrar', function(){
     id = this.id;

   })

    $('#delete').click(()=>{
      if(click >= 1){ throw new Error('Spam de clicks');}
      console.log(id);
      $.ajax({
        type: 'POST',
        url: '',
        data: {delete : 'delete' , id},
        success(data){
          tablaMostrar.destroy();
          rellenar();
          $('.cerrar').click();
          Toast.fire({ icon: 'success', title: 'producto anulado' }) // ALERTA 
        }
      })
      click++;
    })

    $(document).on('click', '.infoImg', function () {
      id = this.id;
      $.ajax({
        method: "post",
        url: "",
        dataType: "json",
        data: { select1: true, id },
        success(data) {
          $("#imgEditar").attr('src', data[0].img);
        }
      });
    });

    let imagen = document.getElementById('imgModal')
    let imgPreview = document.getElementById('imgEditar')
    let input = document.getElementById('img')
    let default_img = 'assets/img/productos/producto_imagen.png';

    $('#borrarFoto').click(()=>{
      imgPreview.src = default_img;
    })

    input.addEventListener('change', function (e) {
      var files = e.target.files;
      var done = function (url) {
      // input.value = '';
        imagen.src = url;
        $('#fotoModal').modal('show');
      };
      var reader;
      var file;
      var url;

      if (files && files.length > 0) {
        file = files[0];

        if (URL) {
          done(URL.createObjectURL(file));
        } else if (FileReader) {
          reader = new FileReader();
          reader.onload = function (e) {
            done(reader.result);
          };
          reader.readAsDataURL(file);
        }
      }
    });

    let cropper;
    $('#fotoModal').on('shown.bs.modal', function () {
      cropper = new Cropper(imagen, {
        aspectRatio: 1,
        viewMode: 3,
      });
    }).on('hidden.bs.modal', function () {
      cropper.destroy();
      cropper = null;
    });

    let canvas;
    $('#aceptarCroppedImg').click(function(){
      if(!cropper) throw new Error('Error al recortar');

      canvas = cropper.getCroppedCanvas({
        width: 500,
        height: 500,
      });
      imgPreview.src = canvas.toDataURL();
      $('#fotoModal').modal('hide')

    })


    $("#actualizarImg").click((e)=>{
      e.preventDefault();
      if (click >= 1) throw new Error('Spam de clicks');

      let form = new FormData($('#formEditar')[0]);
          form.append('id', id);
      let borrar = $('#imgEditar').is(`[src="${default_img}"]`);

      if(borrar != true){
        if(typeof canvas === "undefined" || typeof canvas == null){
          Toast.fire({ icon: 'warning', title: 'No ha cambiado la imagen.' });
          throw Error('Canvas no tiene ninguna imagen cortada');
        }else{
          canvas.toBlob(function(blob){
            form.set('foto', blob, 'avatar.png')
            editarImagen(form);
          });
        }
      }

      if(borrar){
        form.append("borrar", "borrarImg");
        editarImagen(form);
      }

      click++
    })

    function editarImagen(form){
      form.append('editarImg', '');
      $.ajax({
          type: "POST",
          url: '',
          dataType: 'JSON',
          data: form,
          contentType: false,
          processData: false,
          xhr: () => loading(),
          success(data){
            $('#displayProgreso').hide();
            if(data.respuesta == 'error'){
              $('#error').text(data.error);
              throw new Error('Error de foto.');
            }
            if (data.respuesta == "ok") {
              $('#formEditar').trigger('reset');
              imgPreview.src = "#";
              Toast.fire({ icon: 'success', title: 'Foto del producto actualizada' });
              $(".cerrar").click();
            }
          },
          error(data){
            $('#displayProgreso').hide();
            Toast.fire({ icon: 'error', title: 'Ha ocurrido un error al subir la imágen.' });
            console.log(data);
          }
        })
    }

    function loading(){
    let xhr = new window.XMLHttpRequest();
    $('#displayProgreso').show();
    xhr.upload.addEventListener("progress", function(event){

      if(event.lengthComputable){
        let porcentaje = parseInt( (event.loaded / event.total * 100), 10);
        $('#progressBar').data("aria-valuenow",porcentaje)
        $('#progressBar').css("width",porcentaje+'%')
        $('#progressBar').html(porcentaje+'%')
      }

    },false)
    xhr.addEventListener("progress", function(e){

      if (e.lengthComputable) {
        percentComplete = parseInt( (e.loaded / e.total * 100), 10);
        $('#progressBar').data("aria-valuenow",percentComplete);
        $('#progressBar').css("width",percentComplete+'%');
        $('#progressBar').html(percentComplete+'%');
      }else{
        $('#progressBar').html("Upload");
      }

    }, false);

    return xhr;
  }

});