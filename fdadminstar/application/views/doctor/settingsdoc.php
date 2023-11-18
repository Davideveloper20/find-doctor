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
              <div class="breadcrumb-item">Mis Configuraciones</div>
            </div>
          </div>
          <div class="section-body mt-3">
            <div class="row">
              <div class="card col-12 pt-3">
                <div class="form-panel">
                  <form action="<?php echo base_url(); ?>Doctor/SettingsSave" method="post" accept-charset="utf-8">
                    <div class="row px-2">
                      <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <label class="">Jornada Laboral</label>
                        <div class="col-12">
                          <div class="form-group col-md-6 col-12 pull-left">
                            <label>Inicio</label>
                            <div class='input-group datei' id="datei_cnt">
                              <input type='text' class="form-control" style="text-align: center;" name="datei" id="datei" required readonly="" placeholder="Seleccione hora" value="<?php echo set_value('datei', @$datei);?>"/>
                              <span class="input-group-addon input-group-append">
                                <span class="input-group-text" id="datei-addon">
                                  <i class="fas fa-clock"></i>
                                </span>
                              </span>
                            </div>
                            <div class="invalid-feedback">
                              Por favor seleccione la hora de inicio
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12 pull-left">
                            <label>Final</label>
                            <div class='input-group datef' id="datef_cnt">
                              <input type='text' class="form-control" style="text-align: center;" name="datef" id="datef" required readonly="" placeholder="Seleccione hora" value="<?php echo set_value('datef', @$datef);?>"/>
                              <span class="input-group-addon input-group-append">
                                <span class="input-group-text" id="datef-addon">
                                  <i class="fas fa-clock"></i>
                                </span>
                              </span>
                            </div>
                            <div class="invalid-feedback">
                              Por favor seleccione la hora final
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6 col-sm-12 ">
                        <label class="">Acepta Medicina Prepagada</label>
                        <div class="col-12">
                          <label class="custom-switch mt-2">
                          <span class="custom-switch-description">No</span>
                          <input type="checkbox" id="chatstatus" name="med_prepagado" <?php echo (@$user->med_prepagado == 1 ? 'checked': '');?> class="custom-switch-input">
                          <span class="custom-switch-indicator chatt"></span>
                          <span class="custom-switch-description">Si</span>
                        </label>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6 col-sm-12 ">
                        <label class="">Atención Domiciliaria</label>
                        <div class="col-12">
                          <label class="custom-switch mt-2">
                          <span class="custom-switch-description">No  </span>
                          <input type="checkbox" id="chatstatus" name="med_domicilio" <?php echo (@$user->med_domicilio == 1 ? 'checked': '');?> class="custom-switch-input">
                          <span class="custom-switch-indicator chatt"></span>
                          <span class="custom-switch-description">Si</span>
                        </label>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6 col-sm-12 ">
                        <label class="">Lugar de Atención</label>
                        <select name="jobplace" id="jobplace" class="form-control">
                          <option <?php echo (@$jobplace == 'consultorio' ? 'selected': '');?> value="consultorio">Consultorio</option>
                          <option <?php echo (@$jobplace == 'torremedica' ? 'selected': '');?> value="torremedica">Torre Médica</option>
                        </select>
                      </div>
                    </div>
                    <div class="row px-2 mt-4">
                      <div class="col-12">
                        <button type="submit" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveFormation">Guardar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('_partials/footer'); ?>