$(document).ready(function () {
  $("#recuperar").submit((e) => {
    e.preventDefault();

    let vemail = validarCorreo($("#email"), $("#error"), "Error de correo,");
    if (!vemail) throw new Error("Email invalido");

    $(this).find('button[type="submit"]').prop("disabled", true);
    $(this).find('button[type="submit"]').html(`
<div class="spinner-border spinner-border-sm text-light"></div>
      `);
    $.post(
      "",
      {
        email: $("#email").val(),
      },
      function (data) {
        Swal.fire({
          title: "Correo enviado!",
          text: "La contraseña se ha enviado a su correo.",
          icon: "success",
        });
        setTimeout(function () {
          location = "login";
        }, 1600);
      },
      "json"
    )
      .fail((e) => {
        Swal.fire({
          title: "Ha ocurrido un error!",
          text: e.responseJSON || "El envío del correo falló",
          icon: "error",
        });
      })
      .always((e) => {
        $(this).find('button[type="submit"]').prop("disabled", false);
        $(this).find('button[type="submit"]').html(`Enviar`);
      });
  });
});
