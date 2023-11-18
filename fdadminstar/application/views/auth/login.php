<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="<?php echo base_url(); ?>assets/images/icon.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Iniciar Sesión</h4></div>
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
              <div class="card-body">
                <form method="POST" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Por favor escriba tu correo electrónico
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Contraseña</label>
                      <div class="float-right">
                        <a href="<?php echo base_url(); ?>index.php/Auth/Forgot-Password" class="text-small">
                          Olvido su contraseña?
                        </a>
                      </div>
                    </div>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
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

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Ingresar
                    </button>
                  </div>
                </form>
                <!--div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">Login With Social</div>
                </div>
                <div class="row sm-gutters">
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-facebook">
                      <span class="fab fa-facebook"></span> Facebook
                    </a>
                  </div>
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-twitter">
                      <span class="fab fa-twitter"></span> Twitter
                    </a>                                
                  </div>
                </div-->
                <div class="mt-3 text-muted text-center">
                  No tiene una cuenta? <a href="<?php echo base_url(); ?>index.php/Auth/Register">Crear una</a>
                </div>
                <div class="simple-footer">
                  Copyright &copy; <a href="https://solucionesstar.com" target="_blank">Soluciones Star SAS</a> 2019
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('_partials/js'); ?>