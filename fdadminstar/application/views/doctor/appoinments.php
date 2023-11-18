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
              <div class="breadcrumb-item">Citas Médicas</div>
            </div>
          </div>
          <div class="section-body mt-3">
              <div class="card-body" id="myCalendardhxtml">
                <!--div class="fc-overflow">
                  <div id="myCalendar"></div>
                </div-->
                <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%'>
                  <div class="dhx_cal_navline">
                    <div class="dhx_cal_prev_button">&nbsp;</div>
                    <div class="dhx_cal_next_button">&nbsp;</div>
                    <div class="dhx_cal_today_button"></div>
                    <div class="dhx_cal_date"></div>
                    <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                    <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                    <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                  </div>
                  <div class="dhx_cal_header">
                  </div>
                  <div class="dhx_cal_data">
                  </div>
                </div>
              </div>
          </div>
        </section>
      </div>
    <!-- Modal Add Novelty-->
    <div class="modal fade" id="event_form" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="event_formtitle">Agendar Evento</h5>
            <button type="button" class="close closeModal"  aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
                <div class="row px-2">
                  <div class="form-group col-md-12 col-12" style="display: none;" id="stautacont">
                    <label for="">Staus</label>
                    <select class="form-control" name="status" id="status">
                      <option value="1">Activo</option>
                      <option value="2">Cancelada</option>
                      <option value="3">Realizada</option>
                      <option value="4">Próxima</option>
                      <option value="5">Inasistente</option>
                    </select>
                    <div class="invalid-feedback">
                      Por favor seleccione un status
                    </div>
                  </div>
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
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <div class="">
                      <input class="form-control" type="text" id="idservice" name="service" placeholder="Seleccione el servicio" value="">
                      <input class="form-control hidden sr-only" type="text" id="idserviceval" name="idservice" value="" required="">
                      <div class="invalid-feedback">
                        Por favor seleccione el servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-12">
                    <div class='input-group costo'>
                      <span class="input-group-addon input-group-prepend">
                        <span class="input-group-text">Duración</span>
                      </span>
                      <select class="form-control" name="duration" id="duration">
                        <option value="15"> 15 minutos</option>
                        <option value="30"> 30 minutos</option>
                        <option value="45"> 45 minutos</option>
                        <option value="60"> 1 hora</option>
                      </select>
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione un tipo de documento
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-12">
                    <div class='input-group costo'>
                      <span class="input-group-addon input-group-prepend">
                        <span class="input-group-text" id="start_at-addon">
                          <label class="custom-switch mt-2">
                            <span class="custom-switch-description ">Prepagado  </span>
                            <input type="checkbox" id="prepaidstatus" name="prepaidstatus"  class="custom-switch-input">
                            <span class="custom-switch-indicator chatt"></span>
                          </label>
                        </span>
                      </span>
                      <input type='number' class="form-control" style="text-align: center;" name="service_amount" id="service_amount" required readonly="" placeholder="Costo del Servicio" min="0" step="0.01" value="0"/>
                      
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione la hora
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-12">
                    <div class='input-group date' id="fecha_cnt">
                      <input type='text' class="form-control" style="text-align: center;" name="apment_date" id="apment_date" required readonly="" placeholder="Fecha de la agenda" value="" />
                      <span class="input-group-addon input-group-append">
                        <span class="input-group-text" id="apment_date-addon">
                          <i class="fas fa-calendar"></i>
                        </span>
                      </span>
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione la fecha de la agenda
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-12">
                    <div class='input-group hora' id="hora_cnt">
                      <input type='text' class="form-control" style="text-align: center;" name="start_at" id="start_at" required readonly="" placeholder="Hora de la agenda" value=""/>
                      <span class="input-group-addon input-group-append">
                        <span class="input-group-text" id="start_at-addon">
                          <i class="fas fa-clock"></i>
                        </span>
                      </span>
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione la hora
                    </div>
                  </div>
                  
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <div class="">
                      <textarea class="form-control" name="details" id="details" placeholder="Descripcion de la cita" cols="30" rows="3"></textarea>
                      <div class="invalid-feedback">
                        Por favor escriba la descripción del agendamiento
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row px-2">
                  <div class="form-group col-lg-12">
                    <button type="button" class="btn btn-secondary pull-left closeModal"><i class="fa fa-times fa-fw"></i> Cerrar</button>
                    <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveAppoint"><i class="fa fa-save fa-fw"></i> Guardar</button>
                    <button type="button" class="btn btn-danger mx-1 pull-right" data-reqid="<?php echo @$iddoctor;?>" id="deleteAppoint"><i class="fa fa-trash fa-fw"></i> Elimnar</button>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var docservices = <?php echo json_encode($docservices);?>;
  var optionCalBD = {
    profile: <?php echo json_encode($profile);?>,
  };
</script>
<?php $this->load->view('_partials/footer'); ?>