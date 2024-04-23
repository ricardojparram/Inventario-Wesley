$(document).ready(function () {
  fechaHoy($("#fecha"), $("#fecha2"));
  let tabla, tipo, fechaInicio, fechaFinal, thead, columns, reporte;

  $('input[type="date"]').on("change", function () {
    if ($("#fecha").val() > $("#fecha2").val()) {
      $("#error").text("La fecha de inicio no puede ser mayor a la final.");
      $("#fecha").attr("style", "border-color: red;");
      return false;
    } else {
      $("#error").text("");
      $("#fecha").attr("style", "border-color: none;");
    }

    validarFechaAyer($(this), $("#error"), "La fecha");
  });

  const tipoAcciones = {
    donaciones: () => {
      thead = `<tr>
  					<th scope="col">Fecha</th>
  					<th scope="col">Tipo de donación</th>
  					<th scope="col">Identifación</th>
  					<th scope="col">Nombre</th>
  				 </tr>`;
      columns = [
        { data: "fecha" },
        { data: "tipo_donacion" },
        { data: "id" },
        { data: "nombre" },
      ];
      $("#reporteLista thead").html(thead);
      $("#error").text("");
    },
    productos: () => {
      thead = `<tr>
  					<th scope="col">Sede</th>
  					<th scope="col">Producto</th>
  					<th scope="col">Lote</th>
  					<th scope="col">Cantidad</th>
  					<th scope="col">Fecha de vencimiento</th>
  					<th scope="col">Estado</th>
  					<th scope="col">Días</th>
  				 </tr>`;
      columns = [
        { data: "nombre_sede" },
        { data: "presentacion_producto" },
        { data: "lote" },
        { data: "cantidad" },
        { data: "fecha_vencimiento" },
        { data: "estado_producto" },
        { data: "dias" },
      ];
      $("#reporteLista thead").html(thead);
      $("#error").text("");
    },
    error: () => {
      $("#error").text("Seleccione un tipo de reporte.");
      throw new Error("Seleccione un tipo de reporte.");
    },
  };

  $("#generar").click(function () {
    generarReporte(this);
  });

  $("#exportar").click(function () {
    exportarReporte();
  });

  function generarReporte(el) {
    tipo = $("#tipoReporte").val();
    fechaInicio = $("#fecha").val();
    fechaFinal = $("#fecha2").val();

    if ($("#reporteLista tbody tr").length >= 1) {
      console.log($("#reporteLista tbody tr").length >= 1);
      tabla.destroy();
    }

    if (!tipoAcciones.hasOwnProperty(tipo)) tipoAcciones.error();
    tipoAcciones[tipo]();

    $(el).prop("disabled", true);
    $.post(
      "",
      { mostrar: "reporte", tipo, fechaInicio, fechaFinal },
      function (res) {
        reporte = res.reporte;
        tabla = $("#reporteLista").DataTable({
          responsive: true,
          data: reporte,
          columns: columns,
        });
        generarGrafico(res.grafico, tipo);
        $("#reporte").removeClass("d-none");
      },
      "json",
    )
      .fail((e) => {
        Toast.fire({
          icon: "error",
          title: e.responseJSON.msg || "Ha ocurrido un error.",
        });
        console.error(e);
      })
      .always(() => {
        $(el).prop("disabled", false);
      });
  }

  function exportarReporte() {
    if (reporte.length < 1) {
      Toast.fire({
        icon: "error",
        title: "No se puede exportar un reporte vacío.",
      });
      throw new Error("Reporte vacío.");
    }
    let grafico = document.querySelector("#grafico").toDataURL();

    $.ajax({
      method: "POST",
      url: "",
      dataType: "json",
      data: { exportar: "", tipo, fechaInicio, fechaFinal, grafico },
      xhr: () => loading(),
      success(data) {
        $("#displayProgreso").hide();
        Toast.fire({ icon: "success", title: "Exportado correctamente." });
        descargarArchivo(data.ruta);
      },
      error(e) {
        Toast.fire({
          icon: "error",
          title: e.responseJSON.msg || "Ha ocurrido un error.",
        });
        console.error("Error al exportar el reporte: " + e);
      },
    });
  }

  function descargarArchivo(ruta) {
    let link = document.createElement("a");
    link.href = ruta;
    link.download = ruta.substr(ruta.lastIndexOf("/") + 1);
    link.click();
  }

  const canvas = document.getElementById("grafico").getContext("2d");
  let gradient = canvas.createLinearGradient(0, 0, 0, 600);
  gradient.addColorStop(0, "rgba(94, 166, 48, 0.7)");
  gradient.addColorStop(1, "rgba(255, 255, 255, 0)");

  let gradient2 = canvas.createLinearGradient(0, 0, 0, 600);
  gradient2.addColorStop(0, "rgba(128, 36, 170, 0.7)");
  gradient2.addColorStop(1, "rgba(255, 255, 255, 0)");

  let gradient3 = canvas.createLinearGradient(0, 0, 0, 600);
  gradient3.addColorStop(0, "rgba(156, 234, 18, 0.7)");
  gradient3.addColorStop(1, "rgba(255, 255, 255, 0)");
  const accionesGrafico = {
    donaciones: (donaciones) => {
      const data_donaciones = {
        labels: donaciones.fechas,
        datasets: [
          {
            label: "Instituciones",
            data: donaciones.donativos_int,
            borderColor: "#558500",
            borderRadius: 5,
            backgroundColor: gradient,
            pointBackgroundColor: "#558500",
            fill: true,
          },
          {
            label: "Pacientes",
            data: donaciones.donativos_pac,
            borderColor: "#af74c9",
            borderRadius: 5,
            backgroundColor: gradient2,
            pointBackgroundColor: "#af74c9",
            fill: true,
          },
          {
            label: "Personal",
            data: donaciones.donativos_per,
            borderColor: "#92E500",
            borderRadius: 5,
            backgroundColor: gradient3,
            pointBackgroundColor: "#92E500",
            fill: true,
          },
        ],
      };
      return {
        type: "line",
        xAxisID: [0, 5, 10, 15, 20, 25],
        data: data_donaciones,
        options: {
          plugins: {
            legend: {
              display: true,
              position: "bottom",
              labels: {
                color: "black",
                usePointStyle: true,
                pointStyle: "circle",
              },
            },
            title: {
              display: true,
              text: "Donaciones",
            },
          },
          interaction: {
            intersect: false,
            mode: "nearest",
          },
          pointRadius: 4,
          pointHoverRadius: 6,
          pointBorderColor: "white",
          pointBorderWidth: 2,
          tension: 0.2,
          borderWidth: 2.5,
          borderCapStyle: "round",
          scales: {
            x: {
              grid: {
                display: false,
              },
            },
            y: {
              suggestedMin: 0,
              suggestedMax: 15,
              type: "linear",
              position: "left",
            },
          },
        },
      };
    },
    productos: (productos) => {
      const data_productos = {
        labels: productos.labels,
        datasets: [
          {
            label: "Vencidos",
            data: productos.vencidos,
            borderColor: "#558500",
            backgroundColor: gradient,
          },
          {
            label: "Vigentes",
            data: productos.vigentes,
            borderColor: "#af74c9",
            backgroundColor: gradient2,
          },
        ],
      };
      return {
        type: "bar",
        data: data_productos,
        options: {
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: true,
              text: "Estado de los productos",
            },
          },
        },
      };
    },
    error: () => {
      $("#error").text("Seleccione un tipo de reporte.");
      throw new Error("Seleccione un tipo de reporte.");
    },
  };
  let chart = "";
  function generarGrafico(datos, tipo) {
    if (!!chart) chart.destroy();
    chart = new Chart(canvas, accionesGrafico[tipo](datos));
  }
});

function loading() {
  let xhr = new window.XMLHttpRequest();
  $("#displayProgreso").show();
  xhr.upload.addEventListener(
    "progress",
    function (event) {
      if (event.lengthComputable) {
        let porcentaje = parseInt((event.loaded / event.total) * 100, 10);
        $("#progressBar").data("aria-valuenow", porcentaje);
        $("#progressBar").css("width", porcentaje + "%");
        $("#progressBar").html(porcentaje + "%");
      }
    },
    false,
  );
  xhr.addEventListener(
    "progress",
    function (e) {
      if (e.lengthComputable) {
        percentComplete = parseInt((e.loaded / e.total) * 100, 10);
        $("#progressBar").data("aria-valuenow", percentComplete);
        $("#progressBar").css("width", percentComplete + "%");
        $("#progressBar").html(percentComplete + "%");
      } else {
        $("#progressBar").html("Upload");
      }
    },
    false,
  );
  return xhr;
}
