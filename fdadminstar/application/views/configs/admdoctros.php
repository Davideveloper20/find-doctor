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
            <h1><?php echo $title;?></h1>
            <div class="section-header-breadcrumb d-flex my-auto">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item"><?php echo $title;?></div>
            </div>
          </div>
          <div class="section-body mt-3">
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
                      <th scope="col">Status</th>
                      <th scope="col"><i class="fas fa-cog"></i></th>
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
  <!-- Modal Add Novelty-->
  <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=""><i class="fa fa-edit fa-fw"></i>Editar Doctor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-panel">
            <form id="editDoctor-frm" action="<?php echo base_url(); ?>Admin/Doctor-Save" method="post" accept-charset="utf-8">
              <div class="row px-2">
                <div class="form-group col-mg-3 col-lg-3 col-sm-4">
                  <label>Título</label>
                  <input type="text" class="form-control" name="titulo" id="titulo" value="" required="">
                  <div class="invalid-feedback">
                    Por favor escriba su título. p.e.: Dr.
                  </div>
                </div>
                <div class="form-group col-md-9 col-lg-9 col-sm-8">
                  <label>Nombre completo</label>
                  <input type="text" class="form-control" name="fullname" id="fullname" value="" required="">
                  <div class="invalid-feedback">
                    Por favor escriba su nombre completo
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                  <label class="">Tipo de Documento</label>
                  <select class="form-control" name="type_doc" id="type_doc" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($typedocs as $tdc): ?>
                      <option value="<?php echo $tdc->id; ?>"><?php echo $tdc->name; ?></option>
                    <?php endforeach ?>
                  </select>
                  <div class="invalid-feedback">
                    Por favor seleccione un tipo de documento
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                  <label class="">Documento</label>
                  <input class="form-control" type="text" name="document" id="document" value="" required>
                  <div class="invalid-feedback">
                    Por favor escriba su numero documento
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                  <label>ReTHUS</label>
                  <input type="text" class="form-control" id="rethus" name="rethus" value="" required="">
                  <div class="invalid-feedback">
                    Por favor escriba su Registro Único Nacional del Talento Humano en Salud (<b>ReTHUS</b>)
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                  <label>Número de Habilitación</label>
                  <input type="text" class="form-control" id="numhabi" name="numhabi" value="" required="">
                  <div class="invalid-feedback">
                    Por favor escriba su número de habilitación
                  </div>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                  <label>Genero</label>
                  <select class="form-control" name="gender" id="gender" required>
                    <option value="">Seleccione</option>
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                  </select>
                  <div class="invalid-feedback">
                    Por favor seleccione su genero
                  </div>
                </div>
                <div class="form-group col-md-4 col-lg-4 col-sm-12">
                  <label>Fecha de Nacimiento</label>
                  <div class='input-group date' id="birthdate_cnt">
                    <input type='text' class="form-control" style="text-align: center;" name="birthdate" id="birthdate" required readonly="" placeholder="Seleccione fecha" value=""/>
                    <span class="input-group-addon input-group-append">
                      <span class="input-group-text" id="birthdate-addon">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </span>
                  </div>
                  <div class="invalid-feedback">
                    Por favor seleccione su fecha de nacimiento
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-4">
                  <label>Celular</label>
                  <div class="">
                    <input class="form-control intlTelInput" type="tel" name="phonenumber" id="phonenumber" value="" >
                  </div>
                  <div class="invalid-feedback">
                    Por favor escriba su número de celular
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-4">
                  <label class="">País</label>
                  <div class="">
                    <input class="form-control" type="text" id="idcountry" name="country" value="" required>
                  </div>
                  <div class="invalid-feedback">
                    Por favor seleccione su país
                  </div>
                </div>
                <div class="form-group col-md-6 col-lg-8">
                  <label class="">Ciudad</label>
                  <div class="">
                    <input class="form-control" type="text" id="idcity" name="city" value="" required>
                  </div>
                  <div class="invalid-feedback">
                    Por favor seleccione su ciudad
                  </div>
                </div>
                <div class="form-group col-md-12 col-12">
                  <label class="">Dirección</label>
                  <div class="">
                    <input class="form-control" type="text" id="address" name="address" value="" required>
                  </div>
                  <div class="invalid-feedback">
                    Por favor escriba su dirección
                  </div>
                </div>
                <div class="form-group col-md-12 col-lg-12 col-sm-12">
                  <label>Especialidad</label>
                  <input type="text" class="form-control" name="especiality" id="especiality" value="" required="">
                  <div class="invalid-feedback">
                    Por favor escriba su especialidad principal
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                  <label>Enfermedades Tratadas</label>
                  <textarea name="enferme_trat" id="enferme_trat" class="form-control summernote-simple" required rows="2"></textarea>
                  <div class="invalid-feedback">
                      Por favor escriba las enfermedades que trata
                    </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                  <label>Reseña</label>
                  <textarea name="description" id="description" class="form-control summernote-simple" required="" rows="3"></textarea>
                  <div class="invalid-feedback">
                      Por favor escriba una reseña sobre usted
                    </div>
                </div>
              </div>
              <div class="row mapDoctor sr-only hidden">
                <div class="form-group mb-0 col-6">
                  <label for="">Lat</label>
                  <input type="text" id="latitude" name="latitude" readonly="" class="form-control" value="<?php echo set_value('latitude', @"$latitude"); ?>">
                </div>
                <div class="form-group mb-0 col-6">
                  <label for="">Lon</label>
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
            <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveDoctor"><i class="fa fa-save fa-fw"></i>Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  var countries = <?php echo json_encode(((array)$countries) );?>;
  var cities = <?php echo json_encode(((array)$cities) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>