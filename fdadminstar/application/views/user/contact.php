<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?php echo base_url(); ?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Soluciones star sas">

  <title><?php echo NOMBRE_PROYECTO; ?> - Pagos</title>


  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.css?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>" rel="stylesheet">
  <link href="assets/css/custom.v2.css?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>" rel="stylesheet">
  <link rel="icon" href="includes/img/favicon.png" />

</head>

<body class="bg-forma1 position-absolute h-100 w-100 p-2">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-5 col-md-6">

        <div class="card shadow my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-12 d-none d-lg-block"></div>
              <div class="col-12">
                <div class="p-5">
                  <div class="text-center mb-3">
                    <img src="assets/images/logo.png" width="200">
                  </div>
                  <?php if($this->session->flashdata('msj')): ?>
                    <div class="text-center">
                      <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('msj'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php 
                  if($allowed):
                    if(isset($order)): ?>
                      <script>
                        var plan_name = "<?php echo "Carga en saldo FindDoctor"; ?>",
                          plan_desc = "<?php echo "Carga de saldo FindDoctor"; ?>",
                          pay_t = "<?php echo $order->order_token; ?>",
                          pay_c = "<?php echo $order->order_total; ?>",
                          pay_p = "",
                          pay_i = "<?php echo $order->id_orders; ?>",
                          pay_u = "<?php echo $order->id_user; ?>",
                          pay_link = "<?php echo site_url("payments/transactions") ?>";
                      </script>
                      <div class="text-center text-primary">
                        <h4 class="mb-4 text-primary mB">Proceso de pago</h4>
                        <p class="mt-3 mL">
                          En unos minutos se abrirá la ventana de pagos de Epayco. Al finalizar el proceso, será redirigido a la aplicación nuevamente
                        </p>
                        <p class="mL">Monto total del pedido: <span class="price mB">
                          $ <?php echo number_format($order->order_total,2); ?></span>
                        </p>
                        <a href="javascript:window.close()">Volver a la app</a>
                      </div>
                    <?php else: ?>
                      <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Ha ocurrido un problema</h1>
                      </div>
                      <p class="mt-3">Estamos presentado inconvenientes en nuestra plataforma para realizar el cambio correcto de sus datos de contacto, intente de nuevo en unos minutos o comuníquese con nuestro equipo de soporte para darle seguimiento y la posterior solución al problema.</p>
                      <p class="mt-3">
                        <button class="btn btn-lg btn-secondary">Reintentar</button>
                        <button class="btn btn-lg btn-secondary">Iniciar chat</button>
                      </p>
                  <?php endif; 
                  else: ?>
                    <h5 class="text-center form-group mB text-dark">
                      Descarga la aplicación
                    </h5>
                    <div class="form-group text-center">
                      <img src="includes/img/appgallery.png" class="img-fluid" width="200" />
                      <img src="includes/img/applestore.png" class="img-fluid" width="200" />
                      <img src="includes/img/playstore.png" class="img-fluid" width="200" />
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/libs/jquery/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Page level custom scripts -->
  <?php 
    if(isset($order) || isset($payment)) {
  ?>
      <script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
  <?php
    }
  ?>
  <script src="assets/js/solstar/solstar.js?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>"></script>
  <script src="assets/js/solstar/conf.js?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>"></script>
  <?php 
    if(isset($order) || isset($payment)) {
  ?>
      <script type="text/javascript" src="assets/js/payments.js?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>"></script>
  <?php
    }
  ?>
</body>

</html>
