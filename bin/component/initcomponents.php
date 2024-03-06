<?php 

  namespace component;
   

   class initcomponents{
    

    public static function header(){

    $varHeader = '<link rel="icon" href="'._URL_.'assets/img/Logo_Medi.png">
              <!-- Vendor CSS Files -->
              <link href="'._URL_.'assets/sw2/sweetalert2.min.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/quill/quill.snow.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/quill/quill.bubble.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/remixicon/remixicon.css" rel="stylesheet">
              <link href="'._URL_.'assets/vendor/simple-datatables/style.css" rel="stylesheet">
              <link href="'._URL_.'assets/css/dataTables.bootstrap5.min.css"rel="stylesheet" type="text/css" >

              <!-- Template Main CSS File -->
              <link href="'._URL_.'assets/css/style.css" rel="stylesheet">';

    echo $varHeader;

    }

    public function footer(){
    $footer = '
         <footer class="py-0 pt-5 footer">
            <!-- <section> begin ============================-->

          <div class="container">
            <div class="row">
              <div class="col-6 col-lg-2 mb-3">
                <h5 class="lh-lg fw-bold text-white">Enlaces Utiles</h5>
                <ul class="list-unstyled mb-md-4 mb-lg-0">
                  <li class="lh-lg"><a class="text-white link" href="?url=inicio">Inicio</a></li>
                  <li class="lh-lg"><a class="text-white link" href="#!">Nosotros</a></li>
                  <li class="lh-lg"><a class="text-white link" href="#!">Catalogo</a></li>
                   <li class="lh-lg"><a class="text-white link" href="#!">contactos</a></li>
                </ul>
              </div>
              <div class="col-6 col-lg-2 mb-3">
                <h5 class="lh-lg fw-bold text-white">Ofrecemos</h5>
                <ul class="list-unstyled mb-md-4 mb-lg-0">
                  <li class="lh-lg"><a class="text-white" href="#!">Medicamentos </a></li>
                  <li class="lh-lg"><a class="text-white" href="#!">Asistencia medica</a></li>
                  <li class="lh-lg"><a class="text-white" href="#!">Productos de higiene personal</a></li>
                </ul>
              </div>
              <div class="col-6 col-lg-2 mb-3">
                <h5 class="lh-lg fw-bold text-white">Horarios de Trabajo</h5>
                <ul class="list-unstyled mb-md-4 mb-lg-0">
                  <li class="lh-lg"><a class="text-white" href="#!">Lunes a viernes</a></li>
                  <li class="lh-lg"><a class="text-white" href="#!">Sabados y Domingos</a></li>
                </ul>
              </div>
              <div class="col-sm-6 col-lg-auto ms-auto">
                <h5 class="lh-lg fw-bold text-white">Contactos</h5>
                    <p class="text-800">
                      <i class="bi bi-telephone text-white"></i><span class="text-white"> 04120502369</span>
                    </p>
                    <p class="text-800">
                      <i class="bi bi-envelope text-white"></i> <span class="text-white"> MediSalud@gmail.com</span>
                    </p>
                    <p class="text-800">
                      <i class="ri-map-pin-2-line text-white"></i> <span class="text-white"> Av. Principal José Félix Ribas<br> entre calle Ayacucho y Maracay</span>
                    </p>
                </div>
              
            <div class="border-bottom border-3"></div>
            <div class="row flex-center my-3">
              <div class="col-md-6 order-1 order-md-0">
                <p class="my-2 text-white text-center text-md-start"> 
                  <strong><span>MediSalud C.A</span></strong> &copy;Todos los derechos reservados 2023
                </p>
              </div>
              <div class="col-md-6">
                <div class="text-center text-md-end">
                  <a href="#!"><i class="bi bi-facebook m-2 fs-3 text-white"></i></a>
                  <a href="https://instagram.com/farmacia.medisalud?igshid=OGY3MTU3OGY1Mw=="><i class="bi bi-instagram m-2 fs-3 text-white"></i></a>
                  <a href="#!"><i class="bi bi-twitter m-2 fs-3 text-white"></i></a>
                </div>
              </div>
            </div>
          </div>
          <!-- end of .container-->
      </footer>';

      echo $footer;
  }
  
    
    public static function js(){
     $varJs = '
              <script src="'._URL_.'assets/js/jquery-3.6.0.js"></script>  
              <script src="'._URL_.'assets/sw2/sweetalert2.all.min.js"></script>
              <script src="'._URL_.'assets/vendor/apexcharts/apexcharts.min.js"></script>
              <script src="'._URL_.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
              <script src="'._URL_.'assets/vendor/chart.js/chart.min.js"></script>
              <script src="'._URL_.'assets/vendor/echarts/echarts.min.js"></script>
              <script src="'._URL_.'assets/vendor/quill/quill.min.js"></script>
              <script src="'._URL_.'assets/vendor/simple-datatables/simple-datatables.js"></script>
              <script src="'._URL_.'assets/vendor/tinymce/tinymce.min.js"></script>
              <script src="'._URL_.'assets/vendor/php-email-form/validate.js"></script>

              <!-- Template Main JS File -->

              <script src="'._URL_.'assets/js/main.js"></script>

              <!-- Page level plugins -->
              <script src="'._URL_.'assets/vendor/datatables/jquery.dataTables.min.js"></script>
              <script src="'._URL_.'assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

              <!-- Page level custom scripts -->
              <script src="'._URL_.'assets/js/datatables-demo.js"></script>
              <script src="'._URL_.'assets/js/validar.js"></script> 
              <script src="'._URL_.'assets/js/notificaciones.js"></script>
              <script src="'._URL_.'assets/js/tiempoLimitePago.js"></script>';

      echo $varJs;

    }
  }

?>