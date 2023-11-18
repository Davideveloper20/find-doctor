<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
    <!-- Main Content -->
    <div id="content-wrapper" class="d-flex flex-column pt-3">

      <!-- Main Content -->
      <div id="content">
        <div class="container-fluid">
          <div class="form-group mb-0 d-flex justify-content-between">
            <h3 class="mb-0">
              <?php echo trans('hi');?>, Doctor!
            </h3>
            <div class="section-header-breadcrumb d-flex my-auto">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item">Mis Pacientes</div>
            </div>
          </div>
          <div class="section-body mt-3">
            <div class="card">
            <div class="card-body">
              <table class="table" id="dtDatatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Documento</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Correo Electronico</th>
                    <th scope="col">Celular</th>
                    <th scope="col"><i class="fas fa-settings"></i></th>
                  </tr>
                </thead>
                <tbody>
              <?php
              foreach ($mypatientes as $k => $item) {
              ?>
                <tr>
                  <th scope="row"><?php echo $k+1;?></th>
                  <td><?php echo $item->name;?></td>
                  <td><?php echo $item->type_doc.' '.$item->document;?></td>
                  <td><?php echo $item->address.(!empty($item->city) ? ', '.$item->city.', '.$item->state.', '.$item->country : '');?></td>
                  <td><?php echo $item->email;?></td>
                  <td><?php echo $item->phonenumber;?></td>
                  <td></td>
                </tr>
              <?php 
                }
              ?>
                </tbody>
              </table>
            </div>
            </div>
          </div>
        </section>
      </div>
<script>
  //var epses = <?php //echo json_encode(((array)$epses) );?>;
  //var cities = <?php //echo json_encode(((array)$cities) );?>;
  //var countries = <?php //echo json_encode(((array)$countries) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>