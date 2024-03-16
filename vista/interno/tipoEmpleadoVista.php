<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Método</title>
  <?php $VarComp->header(); ?>
  <link rel="stylesheet" href="assets/css/estiloInterno.css">
  <link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap5.min.css">
</head>
<body>
  <!-- ======= Header ======= -->

  <?php 

  $header->Header();

  ?>

  <!-- End Header -->


  <!-- ======= Sidebar ======= -->

  <?php 

  $menu->Menu();

  ?>

  <!-- End Sidebar-->


  <main class="main" id="main">
    <div class="pagetitle">

      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><h1>Gestionar Tipo Empleado</h1></li>
        </ol>
      </nav>

    </div>

    <div class="card">
      <div class="card-body">

        <div class="row">
          <div class="col-6">
            <h5 class="card-title">Tipo de Empleado</h5>
          </div>

          <div class="col-6 text-end mt-3">
            <button type="button" class="btn btn-registrar" data-bs-toggle="modal" data-bs-target="#registrarModal">Agregar</button>

          </div>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered" id="tabla" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th scope="col">Tipos de Empleados</th>

                <th scope="col">Opciones</th>

              </tr>

            </thead>
            <tbody id ="tbody">
             <tr>
              <td>Medico</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>
            <tr>
              <td>Medico</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>
            <tr>
              <td>Medico</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>
            <tr>
              <td>Medico</td>
              <td class="d-flex justify-content-center">
                <button type="button" ${editarPermiso} id="${row.id_forma_pago}" class="btn btn-registrar editar mx-2" data-bs-toggle="modal" data-bs-target="#editarModal"><i class="bi bi-pencil"></i></button>
                <button type="button" ${eliminarPermiso} id="${row.id_forma_pago}" class="btn btn-danger borrar mx-2" data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi bi-trash3"></i></button>
              </td>
            </tr>

           </tbody>
      </table>
    </div>
    <!-- End Table with stripped rows -->

  </div>
</div>


</main>

  <!-- Footer -->

  <?php $footer->Footer();?>
    
  <!-- End Footer -->
  
  <?php $VarComp->js(); ?>   
  <script src="assets/js/tipoEmpleado.js"></script> 

  <!-- Development version -->
  <script src="assets/js/popper.js"></script>

  <!-- Production version -->
  <script src="assets/js/popper.min.js"></script>


</html>
