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
              <div class="card-header"><h4>Restablecer Contraseña</h4></div>
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
                <form method="POST" action="<?php echo base_url(); ?>index.php/Auth/Olvido-Clave" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Por favor escriba tu correo electrónico
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Restablecer
                    </button>
                  </div>
                </form>
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
      </div>
    </section>
  </div>

<?php $this->load->view('_partials/js'); ?>