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

  // const tipoAcciones = {
  // 	venta : () => {
  // 		thead = `<tr>
  // 					<th scope="col">Factura N°</th>
  // 					<th scope="col">Cédula</th>
  // 					<th scope="col">Cliente</th>
  // 					<th scope="col">Fecha</th>
  // 					<th scope="col">Total divisa</th>
  // 					<th scope="col">Total Bs</th>
  // 				 </tr>`;
  // 		columns = [{data : 'num_fact'}, {data : 'cedula'},
  // 				   {data: 'nombre'}, {data : 'fecha'},
  // 				   {data : 'total_divisa'},{data : 'monto_total'}];
  // 		$('#reporteLista thead').html(thead);
  // 		$('#error').text('')
  // 	},
  // 	compra : () => {
  // 		thead = `<tr>
  // 					<th scope="col">Orden de Compra</th>
  // 					<th scope="col">Proveedor</th>
  // 					<th scope="col">Fecha</th>
  // 					<th scope="col">Cantidad de Productos</th>
  // 					<th scope="col">Total divisa</th>
  // 					<th scope="col">Total Bs</th>
  // 				 </tr>`;
  // 		columns = [{data : 'orden_compra'}, {data : 'razon_social'},
  // 				   {data : 'fecha'}, {data : 'cantidad'},
  // 				   {data : 'total_divisa'}, {data : 'monto_total'}];
  // 		$('#reporteLista thead').html(thead);
  // 		$('#error').text('')
  // 	},
  // 	'error' : () => {
  // 		$('#error').text('Seleccione un tipo de reporte.');
  // 		throw new Error('Seleccione un tipo de reporte.');
  // 	}
  // }

  let click = 0;
  setInterval(() => {
    click = 0;
  }, 1000);

  $("#generar").click(function () {
    if (click >= 1) throw new Error("Spam de clicks");

    generarGrafico();
    // generarReporte();

    click++;
  });

  $("#exportar").click(function () {
    if (click >= 1) throw new Error("Spam de clicks");

    generarGrafico();
    // exportarReporte();

    click++;
  });

  // $('#exportarEstadistico').click(function(){
  // 	if(click >= 1) throw new Error('Spam de clicks');
  //
  // 	exportarReporteEstadistico();
  //
  // 	click++;
  // })

  function generarReporte() {
    tipo = $("#tipoReporte").val();
    fechaInicio = $("#fecha").val();
    fechaFinal = $("#fecha2").val();

    if ($("#reporteLista tbody tr").length >= 1) tabla.destroy();

    // if(!tipoAcciones.hasOwnProperty(tipo)) tipoAcciones.error();
    // tipoAcciones[tipo]();

    $.ajax({
      type: "post",
      url: "",
      dataType: "json",
      data: { mostrar: "reporte", tipo, fechaInicio, fechaFinal },
      success(res) {
        reporte = res;
        tabla = $("#reporteLista").DataTable({
          responsive: true,
          data: reporte,
          columns: columns,
        });
        $("#reporte").removeClass("d-none");
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al generar reporte: " + e);
      },
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

    $.ajax({
      method: "POST",
      url: "",
      dataType: "json",
      data: { exportar: "reporte", tipo, fechaInicio, fechaFinal },
      xhr: () => loading(),
      success(data) {
        $("#displayProgreso").hide();
        if (data.Error == "Reporte vacío.") {
          Toast.fire({
            icon: "error",
            title: "No se puede exportar un reporte vacío.",
          });
          throw new Error("Reporte vacío.");
        }

        if (data.respuesta == "Archivo guardado") {
          Toast.fire({ icon: "success", title: "Exportado correctamente." });
          descargarArchivo(data.ruta);
        } else {
          Toast.fire({
            icon: "error",
            title: "No se pudo exportar el reporte.",
          });
        }
      },
      error(e) {
        $("#displayProgreso").hide();
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al exportar el reporte: " + e);
      },
    });
  }

  function exportarReporteEstadistico() {
    if (reporte.length < 1) {
      Toast.fire({
        icon: "error",
        title: "No se puede exportar un reporte vacío.",
      });
      throw new Error("Reporte vacío.");
    }

    $.ajax({
      method: "POST",
      url: "",
      dataType: "json",
      data: { estadistico: "reporte", tipo, fechaInicio, fechaFinal },
      xhr: () => loading(),
      success(data) {
        $("#displayProgreso").hide();
        if (data.Error == "Reporte vacío.") {
          Toast.fire({
            icon: "error",
            title: "No se puede exportar un reporte vacío.",
          });
          throw new Error("Reporte vacío.");
        }

        if (data.respuesta == "Archivo guardado") {
          Toast.fire({ icon: "success", title: "Exportado correctamente." });
          descargarArchivo(data.ruta);
        } else {
          Toast.fire({
            icon: "error",
            title: "No se pudo exportar el reporte.",
          });
        }
      },
      error(e) {
        Toast.fire({ icon: "error", title: "Ha ocurrido un error." });
        throw new Error("Error al exportar el reporte: " + e);
      },
    });
  }

  function descargarArchivo(ruta) {
    let link = document.createElement("a");
    link.href = ruta;
    link.download = ruta.substr(ruta.lastIndexOf("/") + 1);
    link.click();
  }

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

  let chartDonaciones = "",
    chartProductos = "";
  function generarGrafico() {
    let canvas_donaciones = document
      .getElementById("grafico_donaciones")
      .getContext("2d");
    let canvas_productos = document
      .getElementById("grafico_productos")
      .getContext("2d");
    if (!!chartDonaciones) chartDonaciones.destroy();

    let gradient = canvas_donaciones.createLinearGradient(0, 0, 0, 500);
    gradient.addColorStop(0, "rgba(94, 166, 48, 0.7)");
    gradient.addColorStop(1, "rgba(255, 255, 255, 0)");

    let gradient2 = canvas_donaciones.createLinearGradient(0, 0, 0, 500);
    gradient2.addColorStop(0, "rgba(128, 36, 170, 0.7)");
    gradient2.addColorStop(1, "rgba(255, 255, 255, 0)");

    let gradient3 = canvas_donaciones.createLinearGradient(0, 0, 0, 500);
    gradient3.addColorStop(0, "rgba(156, 234, 18, 0.7)");
    gradient3.addColorStop(1, "rgba(255, 255, 255, 0)");

    fechaInicio = $("#fecha").val();
    fechaFinal = $("#fecha2").val();
    $.post(
      "",
      { grafico: "", fechaInicio, fechaFinal },
      function (response) {
        $("#reporte").removeClass("d-none");
        const donaciones = response.donaciones;
        const productos = response.productos;
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

        chartDonaciones = new Chart(canvas_donaciones, {
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
            responsive: true,
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
        });

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
        const config = {
          type: "bar",
          data: data_productos,
          options: {
            responsive: true,
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
        chartProductos = new Chart(canvas_productos, config);
      },
      "json",
    );
  }
});
