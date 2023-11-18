<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <img src="<?php echo base_url(); ?>assets/images/icon.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Registrarse</h4></div>
                <?php 
                if(( null != $this->session->flashdata('message_errro')) || validation_errors() != '') { ?>
                  <div class="row">
                    <div class="alert alert-warning">
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
                    <div class="alert alert-info">
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
                <form method="POST" id="register-form">
                  <div class="row">
                    <div class="form-group col-12">
                      <label for="full_name">Nombre Completo</label>
                      <input id="full_name" type="text" class="form-control" name="fullname" autofocus placeholder="Escriba su nombre completo" value="<?php echo set_value('fullname', @"$fullname");?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="Escriba su email" value="<?php echo set_value('email', @"$email");?>">
                    <div class="invalid-feedback">
                    </div>
                  </div>

                  <div class="row" id="pwd-container">
                    <div class="form-group col-6">
                      <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" minlength="8" maxlength="23" placeholder="Escriba una contraseña">
                      <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-sm-offset-2" style="padding-top: 30px;">
                      <div class="pwstrength_viewport_progress">
                      </div>
                      <div class="pwstrength_viewport_verdict">
                      </div>
                    </div>
                    <div class="col-sm-12" id="message"></div>
                  </div>
                  <div class="form-divider">
                    Dirección
                  </div>
                  <div class="row">
                    <div class="form-group col-12">
                      <label for="idcity" class="d-block">Ciudad</label>
                      <input class="form-control" type="text" id="idcity" value="<?php echo set_value('city', @"$city".(isset($state) && !empty($state) ? ", $state" : '') ); ?>" placeholder="Seleccione su ciudad">
                      <input class="form-control hidden sr-only" type="text" name="idcity" id="idcityval" value="<?php echo set_value('idcity', @$idcity); ?>">
                    </div>
                    <div class="form-group col-12">
                      <label for="address" class="d-block">Dirección</label>
                      <input type="text" class="form-control" id="address" name="address" placeholder="Escriba su dirección" value="<?php echo set_value('address', @"$address");?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                      <label class="custom-control-label" for="agree">Estoy de acuerdo con los <a href="#modalTerms" data-toggle="modal">términos y condiciones</a></label>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Registrar
                    </button>
                  </div>
                </form>
              </div>
              <div class="mt-3 text-muted text-center">
                Ya tiene una cuenta? <a href="<?php echo base_url(); ?>index.php/Auth/Login">Inicie Sesión</a>
              </div>
              <div class="simple-footer">
                Copyright &copy; <a href="https://solucionesstar.com" target="_blank">Soluciones Star SAS</a> 2019
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- Modal Terminos y Condiciones-->
    <div class="modal fade" id="modalTerms" tabindex="-1" role="dialog" aria-labelledby="modalmodalTermsTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Términos y Condiciones</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times" aria-hidden="true"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row px-2">
              Texto Pendiente
            </div>
          </div>
          <div class="modal-footer">
            <center>
              <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Cerrar</button>
            </center>
          </div>
        </div>
      </div>
    </div>

<script>
  var cities = <?php echo json_encode(((array)$cities) );?>;
</script>
<?php $this->load->view('_partials/js'); ?>