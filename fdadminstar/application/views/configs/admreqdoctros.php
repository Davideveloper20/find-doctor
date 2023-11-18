<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?php echo $title;?></h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item"><?php echo $title;?></div>
            </div>
          </div>
          <div class="section-body">
            <h2 class="section-title">Gestion de <?php echo $title;?></h2>
            <div class="card">
              <div class="card-body">
                <table class="table table-striped table-hover" id="dtDatatable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Imagen</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Documento</th>
                      <th scope="col">Contacto</th>
                      <th scope="col">Especialdad</th>
                      <th scope="col">Monto</th>
                      <th scope="col"><i class="fas fa-settings"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </div>
<?php $this->load->view('_partials/footer'); ?>