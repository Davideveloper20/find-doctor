<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

    <!-- Main Content -->
    <div id="content-wrapper" class="d-flex flex-column pt-5">
      <!-- Main Content -->
      <div id="content">
        <div class="container-fluid">
          <div class="form-group mb-0 d-flex justify-content-between">
            <h3 class="mb-0">
              <?php echo $title;?>
              <p class="section-lead title-font-12 mL">Consulta el estado de tus pagos</p>
            </h3>
            <div class="section-header-breadcrumb d-flex my-auto">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item">valoraciones</div>
            </div>
          </div>

          <div class="section-body">
            <div class="card">
              <div class="card-body">
                <table class="table table-striped table-hover" id="dtDatatable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Promedio</th>
                      <th scope="col">Valoraciones</th>
                      <th scope="col">Comentario</th>
                      <th scope="col">Fecha</th>
                      <th scope="col"><i class="fas fa-settings"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('_partials/footer'); ?>