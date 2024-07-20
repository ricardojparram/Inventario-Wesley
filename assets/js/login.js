$(document).ready(function () {
  let timeoutId;
  $("#cedula").keyup(() => {
    let valid = validarCedula(
      $("#cedula"),
      $("#error"),
      "Error de cedula,",
      $("#preDocument")
    );
    debounce(() => {
      if (valid) validarCedulaBD();
    }, 700);
  });
  $("#preDocument").change(() => {
    let valid = validarCedula(
      $("#cedula"),
      $("#error"),
      "Error de cedula,",
      $("#preDocument")
    );
    debounce(() => {
      if (valid) validarCedulaBD();
    }, 700);
  });

  $("#user").submit((e) => {
    e.preventDefault();

    let vcedula, vpassword;
    vcedula = validarCedula(
      $("#cedula"),
      $("#error"),
      "Error de cedula,",
      $("#preDocument")
    );
    vpassword = validarContraseña(
      $("#pass"),
      $("#error"),
      "Error de contraseña,"
    );
    vsede = validarNumero($("#sede"), $("#error"), "Error de sede,");

    if (!vcedula || !vpassword || !vsede) throw new Error("Datos invalidos");

    $(this).find('button[type="submit"]').prop("disabled", true);
    $.post(
      "",
      {
        login: "",
        sede: $("#sede").val(),
        cedula: $("#preDocument").val() + "-" + $("#cedula").val(),
        password: $("#pass").val(),
      },
      function () {
        Swal.fire({
          title: "Iniciando sesión!",
          text: "Los datos son correctos.",
          icon: "success",
        });
        setTimeout(function () {
          window.location = "login";
        }, 1600);
      },
      "json"
    )
      .fail((e) => {
        $("#error").text(e.responseJSON?.msg);
        console.error(e);
      })
      .always(() => {
        $(this).find('button[type="submit"]').prop("disabled", false);
      });
  });

  function validarCedulaBD() {
    $.getJSON(
      "",
      {
        cedula: $("#preDocument").val() + "-" + $("#cedula").val(),
        validar: "xd",
      },
      function (data) {
        $("#error").text("");
        $("#cedula").removeClass("input-error");
      }
    ).fail((e) => {
      $("#error").text(e.responseJSON?.msg);
      $("#cedula").addClass("input-error");
      console.error(e);
    });
  }
});
