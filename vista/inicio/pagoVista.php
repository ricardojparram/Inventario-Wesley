<!DOCTYPE html>
<html lang="en">

<head>
  <title>Pago</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <?php  $VarComp->header();?>
    <link rel="stylesheet" href="assets/css/tienda.css">
    <link rel="stylesheet" href="assets/css/estiloPago.css">
    <link rel="stylesheet" href="assets/css/chosen.min.css">


</head>


<body class="bg-gradient-primary" id="body">

<header class="">
    
  <!-- Barra navegadora -->
  <?php $tiendaComp->nav(); ?>

</header>
  <main class="main mx-auto pt-5 col-10 row">
    <div class="col-sm-12 col-md-10 col-lg-7 mx-md-auto mx-lg-0">
        <div class="card">
                <div class="">
                    <form action="" method="post" class="f1">
                        <div class="row">
                            <h3 class="col-6">Registrar</h3>
                            <h3 id="temporizador" class="col-6 text-end"></h3>
                        </div>
                        <div class="f1-steps">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
                            </div>
                            <div class="f1-step active">
                                <div class="f1-step-icon"><i class="ri-user-fill"></i></div>
                                <p>Datos Personales</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="ri-map-pin-2-line"></i></div>
                                <p>Direccion de Entrega</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="bi bi-credit-card"></i></div>
                                <p>Metodo de Pago</p>
                            </div>
                        </div>


                        <!--paso 1 -->
                        <fieldset>
                                <br>
                    			<div class="row form-group col-md-12">
                                    <div class="col-lg-6">
                                        <label for="inputText" class="col-12 col-form-label"><strong>Nombre</strong></label>
                                        <div class="input-group ">
                                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener 3 o más letras(a-z, A-Z)"><i class="bi bi-people-fill"></i></button>
                                            <input type="text" class="form-control" disabled id="nomClien">
                                        </div>
                                        <p class="m-0" id="errorNomApe" style="color:#ff0000;text-align: center;"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="inputText" class="col-12 col-form-label"><strong>Cedula</strong></label>
                                        <div class="input-group">
                                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La cédula debe tener 6 o más carácteres, solo números(0-9)." aria-describedby="popover809249"><i class="bi bi-person-fill"></i></button>
                                            <input type="text" class="form-control" disabled id="cedClien">
                                        </div>
                                        <p class="m-0" id="errorCed" style="color:#ff0000;text-align: center;"></p>
                                    </div>
                                </div>
                                <div class="row form-group col-md-12">
                                    <div class="col-lg-6">
                                        <label for="inputText" class="col-12 col-form-label"><strong>Telefono</strong></label>
                                        <div class="input-group ">
                                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover"data-bs-placement="top" data-bs-content="Introduzca su numerico telefonico."><i class="bi bi-telephone"></i></button>
                                            <input type="text" class="form-control" placeholder="Telefono" id="teleClien">
                                        </div>
                                        <p class="m-0" id="errorTele" style="color:#ff0000;text-align: center;"></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="inputText" class="col-12 col-form-label"><strong>Correo Electronico</strong></label>
                                        <div class="input-group">
                                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca un correo electrónico válido. Ej: usuario@mail.es"><i class="ri-at-line"></i></button>
                                            <input type="text" class="form-control" placeholder="ejemplo@email.com" id="emailClien">
                                        </div>
                                        <p class="m-0" id="errorEmail" style="color:#ff0000;text-align: center;"></p>
                                    </div>
                                </div>
                                <div class="row form-group col-md-12">
                                    <label for="inputText" class="col-12 col-form-label"><strong>Direccion Fiscal</strong></label>
                                    <div class="input-group">
                                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Ingrese su direccion para la factura"><i class="bi bi-map"></i></button>
                                        <input type="text" class="form-control" id="direcClien" placeholder="Direccion">
                                    </div>
                                    <p class="m-0" id="errorDirec" style="color:#ff0000;text-align: center;"></p>
                                </div>
                                <p class="nota col-11 mx-auto small mt-2">*Si encuentras algún dato personal incorrecto, corrígelo en la sección de perfil antes de seguir con el registro</p>
                                <p class="m-0" id="error1" style="color:#ff0000;text-align: center;"></p>
                                <div class="f1-buttons mt-3">
                                    <button type="button" class="btn btn-danger" id="cancelar" data-bs-toggle="modal" data-bs-target="#cancelarModal">Cancelar</button>
                                    <button type="button" class="btn btn-success btn-next" id="1">Siguiente</button>
                                </div>
                        </fieldset>
                        <!-- fin del paso 1 -->

                        <!---paso 2 -->
                        <fieldset>
                            <br>
                            <div class="mb-4">
                                <div class="col-lg-12 col-md-12 mx-auto row justify-content-center"> 
                                    <button type="button" class="col-lg-3 col-6 mx-3 mb-3 botEntre align-middle btn btn-success" id="repartidor">Delivery</button>
                                    <button type="button" class="col-lg-3 col-6 mx-3 mb-3 botEntre align-middle btn btn-success" id="nacional">Envio Nacional</button>
                                    <button type="button" class="col-lg-3 col-6 mx-3 mb-3 botEntre align-middle btn btn-success" id="persona">Retirar en Persona</button>
                                </div>
                                <p id="errorBot" style="color:#ff0000;text-align: center;"></p>
                            </div>
                            <div class="row form-group mb-1 col-md-12 m-auto">
                                <div class="glass" id="delivery">
                                    <div class="row form-group col-md-12">
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label"><strong>Calle</strong></label>
                                            <div class="input-group ">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover"data-bs-placement="top" data-bs-content="Introduzca el Numero de Calle."><i class="bi bi-signpost"></i></button>
                                                <input type="text" class="form-control" placeholder="Calle ##" id="calle">
                                            </div>
                                            <p class="m-0" id="errorCalle" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label"><strong>Avenida/Carrera</strong></label>
                                            <div class="input-group">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca el Numero o Nombre de la Avenida."><i class="bi bi-signpost-split"></i></button>
                                                <input type="text" class="form-control" placeholder="Avenida ##" id="numAv">
                                            </div>
                                            <p class="m-0" id="errorNumAv" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group col-md-12">
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label"><strong>Numero de Casa</strong></label>
                                            <div class="input-group ">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover"data-bs-placement="top" data-bs-content="Introduzca el Numero de la Casa o Departamento."><i class="bi bi-house"></i></button>
                                                <input type="text" class="form-control" placeholder="Numero de Casa" id="numCasa">
                                            </div>
                                            <p class="m-0" id="errorNumCasa" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label"><strong>Referencia</strong></label>
                                            <div class="input-group">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Introduzca una Referencia o Instrucciones Especificas para Llegar al Lugar."><i class="bi bi-card-checklist"></i></button>
                                                <input type="text" class="form-control" placeholder="A lado de los Bomberos" id="ref">
                                            </div>
                                            <p class="m-0" id="errorRef" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="glass" id="envio">
                                    <div class="row form-group col-md-12 ">
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label "><strong>Estado</strong></label>
                                            <div class="input-group">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone el estadoa donde se le va a enviar su pedido."><i class="bi bi-truck"></i></button>
                                                <select class="form-select" id="estado">
                                                    <option selected="" disabled="">Nombre</option>
                                                </select>
                                            </div>
                                            <p class="m-0" id="errorEstado" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="inputText" class="col-12 col-form-label "><strong>Nombre de Sede</strong></label>
                                            <div class="input-group">
                                                <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone la sede de la empresa."><i class="bi bi-box-seam"></i></button>
                                                <select class="form-select" id="sede">
                                                    <option selected="" disabled="">Nombre</option>
                                                </select>
                                            </div>
                                            <p class="m-0" id="errorSede" style="color:#ff0000;text-align: center;"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group col-md-12">
                                        <label for="inputText" class="col-12 col-form-label"><strong>Direccion de Sede</strong></label>
                                        <div class="input-group">
                                            <textarea type="text" class="form-control" id="ubicacion" disabled placeholder="Direccion"></textarea>
                                        </div>
                                        <p class="m-0" id="errorUbicacion" style="color:#ff0000;text-align: center;"></p>
                                    </div>
                                </div>
                                <div class="glass" id="retirar">
                                    <p>
                                        Puede ir a retirar su compra una vez se aprobada a esta direccion <strong>Av. Principal José Félix Ribas entre Calles Ayacucho y Maracay,</strong> con solo decir su nombre o su numero de cedula podra retirar su compra
                                    </p>
                                </div>
                            </div>
                            <p class="mt-2" id="error2" style="color:#ff0000;text-align: center;"></p>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <button type="button" class="btn btn-secondary btn-previous ">Atrás</button>
                                </div>
                                <div class="f1-buttons col-8">
                                    <button type="button" class="btn btn-danger" id="cancelar" data-bs-toggle="modal" data-bs-target="#cancelarModal">Cancelar</button>
                                    <button type="button" class="btn btn-success btn-next" id="2">Siguiente</button>
                                </div>
                            </div>
                            
                        </fieldset>
                        <!--fin del paso 2 -->

                        <!---paso 3 -->
                        <fieldset>
                            <br>
                            <div class="form-group col-md-12">  
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="table-responsive form-group col-12">

                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tipo Pago</th>
                                                <th>Precio</th>
                                            </tr>
                                            </thead>
                                            <tbody id="FILL">
                                            <tr>
                                                <td width="1%"><a class="removeRowPagoTipo a-asd"><i class="bi bi-trash-fill"></i></a></td>
                                                <td width='30%'> 
                                                <select class="select-tipo select-asd" name="TipoPago">
                                                    <option></option>
                                                </select>
                                                </td>
                                                <td width='15%' class="precio"><input class="select-asd precio-tipo" type="number" placeholder="0,00"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <a class="newRowPago a-asd" ><i class="bi bi-plus-circle-fill"></i> Nueva fila</a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2" id="error3" style="color:#ff0000;text-align: center;"></p>
                            <p class="mt-2" id="errorMonto" style="color:#ff0000;text-align: center;"></p>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <button type="button" class="btn btn-secondary btn-previous ">Atrás</button>
                                </div>
                                <div class="f1-buttons col-8">
                                    <button type="button" class="btn btn-danger" id="cancelar" data-bs-toggle="modal" data-bs-target="#cancelarModal">Cancelar</button>
                                    <button type="button" class="btn btn-success enviar" id="3">Registar Datos</button>
                                </div>
                            </div>
                        </fieldset>
                        <!--fin del paso 3 -->

                    </form>
                </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-10 col-lg-5 mx-md-auto mx-lg-0">

        <!-- Precio y Cambio -->
        <div class="card">
            <div class="f1" id="precios">

                <div class="row">
                    <h5 class="col-6"><strong>Subtotal: </strong></h5>
                    <h5 class="col-6 text-end" id="valorBs"></h5>
                </div>
                <hr class="mt-1">
                <div class="row">
                    <h5 class="col-6"><strong>Costo de Envio: </strong></h5>
                    <h5 class="col-6 text-end" id="pEnvio"></h5>
                </div>
                <hr class="mt-1">
                <div class="row">
                    <h5 class="col-6"><strong>I.V.A: </strong></h5>
                    <h5 class="col-6 text-end" id="impuesto"></h5>
                </div>
                <hr class="mt-1">
                <div class="row">
                    <h5 class="col-6"><strong>Total: </strong></h5>
                    <h5 class="col-6 text-end" id="total"></h5>
                </div>
                <hr class="mt-1">
                <div class="row">
                    <h5 class="col-6"><strong>Al Cambio: </strong></h5>
                    <h5 class="col-6 text-end" id="valorUsd"></h5>
                </div>
            </div>
        </div>

        <!-- Metodo de Pago -->
        <div class="card">
            
            <form action="" method="post" class="f1" id="fade">
                <div class=" col-8 mx-auto text-center">
                    <h4 class="m-0">Elija un Metodo a Pagar</h4>
                </div>
                <div class="row form-group col-md-12 movil mt-4">
                    <h6 class="text-center">Datos para Pago Movil</h6>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label "><strong>Nombre de Banco</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone un banco"><i class="bi bi-bank"></i></button>
                            <select  class="form-select" id="bancTipoM">
                                <option selected disabled>Nombre</option>
                            </select>
                        </div>
                        <p class="m-0" id="errorBancTipo" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label"><strong>Cedula/Rif</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Documento de la Cuenta."><i class="bi bi-card-text"></i></button>
                            <input type="text" class="form-control" disabled id="cedBancM">
                        </div>
                    </div>
                    
                </div>
                <div class="row form-group col-md-12 movil">
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label"><strong>Telefono</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Telefono Para Pago Movil"><i class="bi bi-telephone"></i></button> 
                            <input type="text" id="teleMovil" disabled class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label"><strong>Codigo</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Codigo del Banco"><i class="bi bi-hash"></i></button> 
                            <input type="text" id="codBanc" disabled class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row form-group col-md-12 mt-4 movil">
                    <h6 class="text-center">Datos para Verificación de Pago</h6>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label"><strong>Referencia</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Referencia para pago"><i class="bi bi-hash"></i></button> 
                            <input type="text" id="referenciaMovil" class="form-control">
                        </div>
                        <p class="m-0" id="errorReferenciaMovil" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label "><strong>Nombre de Banco</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone el banco de Donde Hizo la Transferencia"><i class="bi bi-bank"></i></button>
                            <select  class="form-select" id="bancTipoRM">
                                <option selected disabled>Nombre</option>
                            </select>
                        </div>
                        <p class="m-0" id="errorbancTipoRM" style="color:#ff0000;text-align: center;"></p>
                    </div>
                </div>
                <div class="row form-group col-md-12 trans mt-4">
                    <h6 class="text-center">Datos para Transferencia</h6>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label "><strong>Nombre de Banco</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone un banco"><i class="bi bi-bank"></i></button>
                            <select  class="form-select" id="bancTipoT">
                                <option selected disabled>Nombre</option>
                            </select>
                        </div>
                        <p class="m-0" id="errorBancTipoT" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="col-xl-6 col-md-12 trans">
                        <label for="inputText" class="col-12 col-form-label"><strong>Cedula/Rif</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Documento de la Cuenta."><i class="bi bi-card-text"></i></button>
                            <input type="text" class="form-control" disabled  id="cedBancT">
                        </div>
                    </div>
                </div>
                <div class="row form-group col-md-12 trans">
                    <label for="inputText" class="col-12 col-form-label"><strong>Numero de Cuenta</strong></label>
                    <div class="input-group">
                        <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Banco para Depositar"><i class="ri-hashtag"></i></button> 
                        <input type="text" id="numCuen" disabled class="form-control">
                    </div>
                </div>
                <p class="nota col-11 mx-auto small mt-2">*Al momento de realizar el registro de los datos, el banco seleccionado en esta sección facilitará la verificación del depósito realizado.</p>
                
                <div class="row form-group col-md-12 mt-4 trans">
                    <h6 class="text-center">Datos para Verificación de Pago</h6>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label"><strong>Referencia</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Numero de Referencia para pago"><i class="bi bi-hash"></i></button> 
                            <input type="text" id="referenciaTrans" class="form-control">
                        </div>
                        <p class="m-0" id="errorReferenciaTrans" style="color:#ff0000;text-align: center;"></p>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label for="inputText" class="col-12 col-form-label "><strong>Nombre de Banco</strong></label>
                        <div class="input-group">
                            <button type="button" class="iconos btn btn-secondary" data-bs-trigger="hover focus"data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Selecicone el banco de Donde Hizo la Transferencia"><i class="bi bi-bank"></i></button>
                            <select  class="form-select" id="bancTipoRT">
                                <option selected disabled>Nombre</option>
                            </select>
                        </div>
                        <p class="m-0" id="errorBancTipoRT" style="color:#ff0000;text-align: center;"></p>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal Eliminar-->
    <div class="modal fade" id="cancelarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="staticBackdropLabel">¿Desea Cancelar su Compra?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Perdera el Progreso y los Datos de su Compra</h5>
                <p></p>
                <p class="m-0" id="errorDel" style="color:#ff0000;text-align: center;"></p>
            </div>
            <div class="modal-footer" id="divEli">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarModal">Cerrar</button>
                <button type="button" class="btn btn-danger" id="cancel">Confirmar</button>
            </div>
            </div>
        </div>
    </div>


  </main>


  

  <?php $VarComp->js();?>   
  <script src="assets/js/pagoTab.js"></script>
  <script src="assets/js/chosen.jquery.min.js"></script>
  <script src="assets/js/carrito.js"></script>



</body>

</html>

