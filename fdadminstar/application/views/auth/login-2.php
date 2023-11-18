<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch mt-0">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="logo" width="100%" class="mb-2 mt-2">
            <h4 class="text-dark font-weight-normal">Bienvenido a <span class="font-weight-bold"><span class="blue1">Find</span><span class="blue2">Doctor</span></span></h4>
            <p class="text-muted">Antes de comenzar, debe iniciar sesión o registrarse si aún no tiene una cuenta.</p>
            <?php 
            if(( null != $this->session->flashdata('message_errro')) || validation_errors() != '') { ?>
              <div class="row">
                <div class="alert alert-warning col-12">
                  <?php 
                    echo ( null != $this->session->flashdata('message_errro')) ? $this->session->flashdata('message_errro') : '';
                    echo validation_errors();
                    $this->session->set_flashdata('message_errro', '');
                  ?>
                </div>
              </div>
            <?php 
            }
            if(( null != $this->session->flashdata('message'))) { ?>
              <div class="row">
                <div class="alert alert-info col-12">
                  <?php 
                    echo ( null != $this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
                    $this->session->set_flashdata('message', '');
                  ?>
                </div>
              </div>
            <?php 
            }
            ?>
            <form method="POST" class="needs-validation" novalidate="">
              <div class="form-group">
                <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus placeholder="Escriba su correo electrónico">
                <div class="invalid-feedback">
                  Por favor escriba su correo electrónico
                </div>
              </div>
              <div class="form-group">
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required placeholder="Escriba su contraseña">
                <div class="invalid-feedback">
                  Por favor escriba su contraseña
                </div>
              </div>

              <!--div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                  <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
              </div-->

              <div class="form-group text-right">
                <a href="<?php echo base_url(); ?>index.php/Auth/Forgot-Password" class="float-left mt-3">
                  Olvido su contraseña?
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                  Ingresar
                </button>
              </div>

              <div class="mt-3 text-center">
                No tiene una cuenta? <a href="<?php echo base_url(); ?>index.php/Auth/Register">Crear una</a>
              </div>
            </form>

            <div class="text-center mt-3 text-small">
              Copyright &copy; <a href="https://solucionesstar.com" target="_blank">Soluciones Star SAS</a> 2019
              <div class="mt-2">
                <a href="#privacity-modal">Politicas de Privacidad</a>
                <div class="bullet"></div>
                <a href="#terms-service">Terminos de Servicio</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 position-relative overlay-gradient-bottom hidden-sm hidden-xs backGN rflex" data-background="<?php echo base_url(); ?>assets/images/bg/bg4.jpg">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-5 pb-3" style="text-shadow: 3px 3px 2px rgba(245,245,255, 0.7);">
                <h1 class="mb-2 display-4 font-weight-bold blue1" id="salutationNow"><?php $h = date('H');echo ($h >=0 && 5>$h)? 'Excelente Madrugada': (($h >=5 && 12>$h) ? 'Buenos Días' : (($h >=12 && 19>$h) ? 'Buenas Tardes': 'Buenas Noches')); ?></h1>
                <h5 class="font-weight-normal text-muted-transparent blue2 text-center" id="timeNow"><?php echo date('d/m/Y h:i a');?></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('_partials/js'); ?>