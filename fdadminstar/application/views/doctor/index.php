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
              <div class="breadcrumb-item"><?php echo trans('profile');?></div>
            </div>
          </div>
          <div class="section-body">
            <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate="" id="profile-form">
              <p class="section-lead">
                <?php echo trans('update_profile_msg1');?>
              </p>
              <?php 
              if(( null != $this->session->flashdata('message_errro')) || validation_errors() != '') { ?>
                <div class="row mt-sm-4">
                <div class="col-12">
                <div class="alert alert-warning">
                  <?php 
                    echo ( null != $this->session->flashdata('message_errro')) ? $this->session->flashdata('message_errro') : '';
                    echo validation_errors();
                  ?>
                </div>
                </div>
                </div>
              <?php 
              }
              ?>
              <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                  <div class="card profile-widget shadow-lg">
                    <div class="profile-widget-header">
                      <div class="controls col-md-12">
                        <div class="fileupload fileupload-new rounded-circle profile-widget-picture" data-provides="fileupload">
                          <label for="uploadThumb" class="fileupload-new thumbnail">
                            <img src="<?php echo (isset($profileimage) && !empty($profileimage) ? $profileimage : base_url().'assets/images/silueta.png'); ?>" alt="<?php echo $fullname;?>" class="img img-fluid img-responsive rounded-circle profile-widget-picture" id="thumbnailPreview"/>
                          </label>
                          <input type="file" id="uploadThumb" name="uploadThumb" class="default" style="display: none;"/>
                        </div>
                      </div>
                      <input type="text" class="form-class sr-only hidden profile-widget-item-label" name="profileimage" id="profileimage" value="<?php echo $profileimage;?>">
                    </div>

                    <div class="profile-widget-description py-2">
                      <div class="profile-widget-name"><?php echo @$titulo.' '.@$fullname; ?> <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> <?php echo @$especiality; ?></div></div>
                      <?php echo @$description; ?>
                    </div>
                    <div class="card-footer text-center pb-3" style="padding: 0 1rem;">
                      <button class="btn btn-primary mr-1"> Email:
                        <i class="fas fa-envelope-f"></i><?php echo @$email; ?>
                      </button>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-12 col-12">
                          <label>ReTHUS</label>
                          <input type="text" class="form-control" name="rethus" value="<?php echo set_value('rethus', @$rethus);?>" required="">
                          <div class="invalid-feedback">
                            Por favor escriba su Registro Único Nacional del Talento Humano en Salud (<b>ReTHUS</b>)
                          </div>
                        </div>
                        <div class="form-group col-md-12 col-12">
                          <label>Número de habilitación</label>
                          <input type="text" class="form-control" name="numhabi" value="<?php echo set_value('numhabi', @$numhabi);?>" required="">
                          <div class="invalid-feedback">
                            Por favor escriba su número de habilitación
                          </div>
                        </div>
                        <div class="form-group col-md-12 col-12">
                          <label>Especialidad</label>
                          <input type="text" class="form-control" name="especiality" value="<?php echo set_value('especiality', @$especiality);?>" required="">
                          <div class="invalid-feedback">
                            Por favor escriba su especialidad principal
                          </div>
                        </div>
                        <div class="form-group col-12">
                          <label>Enfermedades Tratadas</label>
                          <textarea name="enferme_trat" class="form-control summernote-simple"><?php echo set_value('enferme_trat', @$enferme_trat);?></textarea>
                          <div class="invalid-feedback">
                              Por favor escriba las enfermedades que trata
                            </div>
                        </div>
                        <div class="form-group col-12">
                          <label>Reseña</label>
                          <textarea name="description" class="form-control summernote-simple"><?php echo set_value('description', @$description);?></textarea>
                          <div class="invalid-feedback">
                              Por favor escriba una reseña sobre usted
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7">
                  <div class="card">
                    
                      <div class="card-header">
                        <h4><?php echo trans('edit_profile'); ?></h4>
                        <small class="text-muted"><?php echo $status; ?></small>
                      </div>
                      <div class="card-body">
                        <div class="row">        
                          <div class="form-group col-md-3 col-12">
                            <label>Título</label>
                            <input type="text" class="form-control" name="titulo" value="<?php echo set_value('titulo', @$titulo);?>" required="">
                            <div class="invalid-feedback">
                              Por favor escriba su título
                            </div>
                          </div>
                          <div class="form-group col-md-9 col-12">
                            <label>Nombre completo</label>
                            <input type="text" class="form-control" name="fullname" value="<?php echo set_value('fullname', @$fullname);?>" required="">
                            <div class="invalid-feedback">
                              Por favor escriba su nombre completo
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6 col-12">
                            <label class="">Tipo de Documento</label>
                            <select class="form-control" name="type_doc" id="type_doc">
                              <option value=""<?php echo (set_value('type_doc', @$type_doc) == '' ? ' selected': ''); ?>>Seleccione</option>
                              <?php foreach ($typedocs as $tdc): ?>
                                <option value="<?php echo $tdc->id; ?>"<?php echo (set_value('type_doc', @$type_doc) == $tdc->id ? ' selected': ''); ?>><?php echo $tdc->name; ?></option>
                              <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback">
                              Por favor seleccione un tipo de documento
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12">
                            <label class="">Documento</label>
                            <input class="form-control" type="text" name="document" id="document" value="<?php echo set_value('document', @$document);?>">
                            <div class="invalid-feedback">
                              Por favor escriba su numero documento
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6 col-12">
                            <label>Genero</label>
                            <select class="form-control" name="gender" id="gender">
                              <option value=""<?php echo (set_value('gender', @$gender) == '' ? ' selected': ''); ?>>Seleccione</option>
                              <option value="1"<?php echo (set_value('gender', @$gender) == '1' ? ' selected': ''); ?>>Masculino</option>
                              <option value="2"<?php echo (set_value('gender', @$gender) == '2' ? ' selected': ''); ?>>Femenino</option>
                            </select>
                            <div class="invalid-feedback">
                              Por favor seleccione su genero
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12">
                            <label>Fecha de Nacimiento</label>
                            <div class='input-group date' id="birthdate_cnt">
                              <input type='text' class="form-control" style="text-align: center;" name="birthdate" id="birthdate" required readonly="" placeholder="Seleccione fecha" value="<?php echo (!empty(set_value('birthdate', @$birthdate)) ? date('d/m/Y', strtotime(set_value('birthdate', @$birthdate))): date('d/m/Y')) ;?>"/>
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
                          <div class="form-group col-md-6 col-12">
                            <label>Celular</label>
                            <div class="">
                              <input class="form-control intlTelInput" data-telvalue="#phonenumber" type="tel" name="phone2" id="phone2" value="<?php echo set_value('phonenumber', @$phonenumber); ?>">
                              <input type="tel" class="form-control hidden sr-only" placeholder="Celular" name="phonenumber" id="phonenumber" value="<?php echo set_value('phonenumber', @$phonenumber); ?>">
                            </div>
                            <div class="invalid-feedback">
                              Por favor escriba su número de celular
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12">
                            <label class="">País</label>
                            <div class="">
                              <input class="form-control" type="text" id="idcountry" name="country" value="<?php echo set_value('country', @"$country"); ?>">
                              <input class="form-control hidden sr-only" type="text" name="idcountry" id="idcountryval" value="<?php echo set_value('idcountry', @$idcountry); ?>">
                            </div>
                            <div class="invalid-feedback">
                              Por favor seleccione su país
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
                        </div>
                        <div class="row">
                          <div class="form-group col-md-12 col-12">
                            <label class="">Dirección</label>
                            <div class="">
                              <input class="form-control" type="text" id="address" name="address" value="<?php echo set_value('address', @"$address"); ?>">
                            </div>
                            <div class="invalid-feedback">
                              Por favor escriba su dirección
                            </div>
                          </div>
                        </div>
                        <div class="row mapDoctor">
                        	<div class="form-group col-6">
                        		<label for="">Lat</label>
                        		<input type="text" id="latitude" name="latitude" readonly="" class="form-control" value="<?php echo set_value('latitude', @"$latitude"); ?>">
                        	</div>
                        	<div class="form-group col-6">
                        		<label for="">Lon</label>
                        		<input type="text" id="longitude" name="longitude" readonly="" class="form-control" value="<?php echo set_value('longitude', @"$longitude"); ?>">
                        	</div>
                        	<div class="mb-0 col-12">
                        		<div id="map" class="rflex form-control">
                        			<i class="fa fa-spinner fa-spin fa-1x"></i>
                        		</div>
                        	</div>
                        </div>
                        <div class="row">
                          <div class="form-group mb-0 col-12">
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" name="newsletter" <?php echo !in_array((set_value('newsletter', @((isset($newsletter) && !empty($newsletter)? $newsletter : $user->newsletter)))), ['', '1','on']) ? '': 'checked';?> class="custom-control-input" id="newsletter">
                              <label class="custom-control-label" for="newsletter">Suscribirse a las notificaciones</label>
                              <div class="text-muted form-text">
                                Le enviaremos información de nuestras promociones y actulizaciones
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                      </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
<script>
  var cities = <?php echo json_encode(((array)$cities) );?>;
  var countries = <?php echo json_encode(((array)$countries) );?>;
</script>
<?php 
echo $this->uri->segment(0); $this->load->view('_partials/footer'); ?>