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
            <div class="form-group text-right">
              <a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a>
            </div>
            <div class="card">
              <div class="card-body">
                <table class="table" id="dtDatatable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Documento</th>
                      <th scope="col">Dirección</th>
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

    <!-- Modal Add Novelty-->
    <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Agregar Paciente</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form action="<?php echo base_url(); ?>index.php/Doctor/PacientesSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-md-6 col-12">
                    <select class="form-control" name="type_doc" id="type_doc">
                      <?php foreach ($typedocs as $tdc): ?>
                        <option value="<?php echo $tdc->id; ?>"<?php echo (set_value('type_doc', @$type_doc) == $tdc->id ? ' selected': ''); ?>><?php echo $tdc->name; ?></option>
                      <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback">
                      Por favor seleccione un tipo de documento
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-12">
                    <input class="form-control" type="text" placeholder="Documento" name="document" id="document" data-id="false" value="">
                    <div class="invalid-feedback">
                      Por favor escriba su numero documento
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <div class="">
                      <input class="form-control" type="text" id="patienname" name="patienname" placeholder="Nombre Completo del Paciente" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre del paciente
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
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Dirección</label>
                    <div class="">
                      <input class="form-control" type="text" id="address" name="address" value="">
                      <div class="invalid-feedback">
                        Por favor escriba la dirección
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row px-2">
                  <div class="form-group col-lg-12">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveFormation">Guardar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var epses = <?php echo json_encode(((array)$epses) );?>;
  var cities = <?php echo json_encode(((array)$cities) );?>;
  //var countries = <?php //echo json_encode(((array)$countries) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>