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
              <div class="breadcrumb-item">Consultorios</div>
            </div>
          </div>
          <div class="section-body mt-3">
            
            <a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm mb-3"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a>
            <div class="card">
              <div class="card-body">
                <table class="table" id="dtDatatable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Direccion</th>
                      <th scope="col">Email</th>
                      <th scope="col">Telefono</th>
                      <th scope="col"><i class="fas fa-cog"></i></th>
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

    <!-- Modal Add Novelty-->
    <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id=""><i class="fa fa-plus i fa-fw"></i>Agregar Consultorio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addConsultingRoom-frm" action="<?php echo base_url(); ?>index.php/ConsultoriosSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Nombre del Consultorio</label>
                    <div class="">
                      <input class="form-control" type="text" id="name" name="name" value="">
                      <input class="form-control hidden sr-only" type="text" name="idconsult" id="idconsult" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-12">
                    <label class="">Ciudad</label>
                    <div class="">
                      <input class="form-control" type="text" id="idcity" name="city" value="<?php echo set_value('city', @"$city".(!empty($state)?", $state":"")); ?>">
                      <input class="form-control hidden sr-only" type="text" name="idcity" id="idcityval" value="<?php echo set_value('idcity', @$idcity); ?>">
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione su ciudad
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-6 col-sm-12 ">
                    <label class="">Dirección</label>
                    <div class="">
                      <input class="form-control" type="text" id="address" name="address" value="">
                      <div class="invalid-feedback">
                        Por favor escriba La Dirección
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Telefono 1</label>
                    <div class="">
                      <input class="form-control" type="text" id="phone1" name="phone1" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el telefono
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Telefono 2</label>
                    <div class="">
                      <input class="form-control" type="text" id="phone2" name="phone2" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el telefono
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Correo Electrónico</label>
                    <div class="">
                      <input class="form-control" type="email" id="email" name="email" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el Correo Electrónico
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Sitio Web</label>
                    <div class="">
                      <input class="form-control" type="url" id="url" name="url" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el Sitio Web
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mapDoctor">
                  <div class="form-group mb-0 col-6">
                    <label for="">Latitud</label>
                    <input type="text" id="latitude" name="latitude" readonly="" class="form-control" value="<?php echo set_value('latitude', @"$latitude"); ?>">
                  </div>
                  <div class="form-group mb-0 col-6">
                    <label for="">Longitud</label>
                    <input type="text" id="longitude" name="longitude" readonly="" class="form-control" value="<?php echo set_value('longitude', @"$longitude"); ?>">
                  </div>
                  <div class="mb-0 col-12">
                    <div id="map" class="rflex form-control">
                      <i class="fa fa-spinner fa-spin fa-1x"></i>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group col-lg-12">
              <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal"><i class="fa fa-times fa-fw"></i>Cerrar</button>
              <button type="button" class="btn btn-success pull-right" data-reqid="" id="saveFormation"><i class="fa fa-save fa-fw"></i>Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var cities = <?php echo json_encode(((array)$cities) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>