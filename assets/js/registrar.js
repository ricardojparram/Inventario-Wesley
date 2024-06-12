$(document).ready(function () {
  let timeoutId;
  // $("#cedula").keyup(()=>{
  //   let valid = validarCedula($("#cedula"),$("#error") ,"Error de cedula,");
  //   clearTimeout(timeoutId);
  //   timeoutId = setTimeout(function() {
  //     if(valid) validarCedulaBD();
  //   }, 700);
  // })

  $("#email").keyup(() => {
    let valid = validarCorreo($("#email"), $("#error"), "Error de correo,");
    clearTimeout(timeoutId);
    timeoutId = setTimeout(function () {
      if (valid) validarEmailBD();
    }, 700);
  });

  $("#name").keyup(() => {
    validarNombre($("#name"), $("#error"), "Error de nombre,");
  });
  $("#apellido").keyup(() => {
    validarNombre($("#apellido"), $("#error"), "Error de apellido,");
  });
  $("#password").keyup(() => {
    validarContraseña($("#password"), $("#error"), "Error de contraseña,");
  });
  $("#repass").keyup(() => {
    validarRepContraseña($("#repass"), $("#error"), $("#password"));
  });

  $("#boton").click((e) => {
    e.preventDefault();

    let vcedula, vname, vapellido, vcorreo, vpassword, vrepass;

    // validarCedula($("#cedula"),$("#error") ,"Error de cédula,");
    vname = validarNombre($("#name"), $("#error"), "Error de nombre,");
    vapellido = validarNombre(
      $("#apellido"),
      $("#error"),
      "Error de apellido,",
    );
    validarCorreo($("#email"), $("#error"), "Error de correo,");
    vpassword = validarContraseña(
      $("#password"),
      $("#error"),
      "Error de contraseña,",
    );
    vrepass = validarRepContraseña($("#repass"), $("#error"), $("#password"));

    if (!vname || !vapellido || !vpassword || !vpassword || !vrepass) {
      throw new Error("Error.");
    }

    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: {
        cedula: $("#cedula").val(),
        name: $("#name").val(),
        apellido: $("#apellido").val(),
        email: $("#email").val(),
        password: $("#password").val(),
      },
      success(data) {
        e.preventDefault();

        if (data.resultado === "Error de cedula") {
          $("#error").text(data.error);
          $("#cedula").attr("style", "border-color: red;");
          $("#cedula").attr(
            "style",
            "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
          );
          throw new Error("Cedula ya registrada.");
        }
        if (data.resultado === "Error de email") {
          $("#error").text(data.error);
          $("#email").attr("style", "border-color: red;");
          $("#email").attr(
            "style",
            "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
          );
          throw new Error("Correo ya registrado.");
        }
        if (data.resultado === "error") {
          Swal.fire({
            title: "Registrado correctamente!",
            text: "Los datos serán guardados en la base de datos.",
            icon: "success",
          });
          throw new Error(data.msg);
        }
        vcorreo = true;
        vcedula = true;
        if (vcedula && vname && vapellido && vcorreo && vpassword && vrepass) {
          Swal.fire({
            title: "Registrado correctamente!",
            text: "Los datos serán guardados en la base de datos.",
            icon: "success",
          });
          setTimeout(function () {
            window.location = "?url=login";
          }, 1600);
        }
      },
    });
  });

  // function validarCedulaBD(){
  //   $.getJSON('',{cedula: $("#cedula").val(),validar: 'xd'},
  //     function(data){
  //       if(data.resultado === "Error de cedula"){
  //         $("#error").text(data.error);
  //         $("#cedula").attr("style","border-color: red;")
  //         $("#cedula").attr("style","border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);");
  //     }
  //   })
  // }

  function validarEmailBD() {
    $.getJSON("", { email: $("#email").val(), validar: "xd" }, function (data) {
      if (data.resultado === "Error de email") {
        $("#error").text(data.error);
        $("#email").attr("style", "border-color: red;");
        $("#email").attr(
          "style",
          "border-color: red; background-image: url(assets/img/Triangulo_exclamacion.png); background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);",
        );
      }
    });
  }

  if ("carrito" in localStorage != false) {
    let car =
      localStorage.getItem("carrito") === ""
        ? ""
        : JSON.parse(localStorage.getItem("carrito"));
    let cantidad = Object.keys(car).length;
    $("#carrito_badge").html(cantidad);
  }
});
