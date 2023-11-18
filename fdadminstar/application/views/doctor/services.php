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
              <div class="breadcrumb-item">Mis Servicios</div>
            </div>
          </div>
          <div class="section-body mt-3">
            <p class="section-lead">Mis Servicios.<a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
            <div class="row">
              <?php if(count($myservices) <= 0) { ?>
                <div class="col-12 col-md-4 col-lg-4">
                  <p class="section-lead">Sin Servicios Registrados, por favor agregue sus Servicios.<a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
                </div>
              <?php } else { 
                foreach ($myservices as $k => $item) {
              ?>
              <div class="col-12 col-md-6 col-lg-6 serviceCtn-<?php echo $item->id; ?>">
                <article class="article article-style-c">
                  <!--div class="article-header">
                    <div class="article-image" data-background="<?php echo base_url(); ?>assets/img/news/img13.jpg">
                    </div>
                  </div-->
                  <a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-danger btn-circle deleteService pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Eliminar" data-id="<?php echo $item->id; ?>"><i class="fa fa-trash"></i></a>
                  <a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-primary btn-circle editService pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Editar" data-id="<?php echo $item->id; ?>"><i class="fa fa-edit"></i></a>
                  <div class="article-details">
                    <div class="article-category"><span ><?php //echo $item->institucion; ?></span></div>
                    <div class="article-title">
                      <h2><a href="javascript:void('<?php echo $item->id; ?>')" class="editService" data-id="<?php echo $item->id; ?>"><?php echo $item->service; ?></a><small class="pull-right text-muted fs-08"><?php echo ($item->type == 'domicilio'?'En Domicilio':($item->type == 'consultorio'?'En Consultorio':'')); ?></small></h2>
                    </div>
                    <p class="text-justify"><?php 
                      if(strlen($item->description) > 128) {
                        echo substr($item->description, 0, 128).'<span class="collapse" id="speciality'.$item->id.'">'.substr($item->description, 128).'</span><a class="btn btn-link" data-toggle="collapse" href="#speciality'.$item->id.'" role="button" aria-expanded="false" aria-controls="speciality'.$item->id.'"> + Ver más</a></p>';
                      } else {
                        echo $item->description.'</p>'; 
                      }
                    ?>
                  </p>
                    <div class="article-user">
                      <!--img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png"-->
                      <div class="article-user-details">
                        <div class="user-detail-name w5oleft text-center">
                          <a href="javascript:void();"><i class="fas fa-clock fa-fw"></i><?php echo ($item->duration == 60 ? '1 Hora': ($item->duration == 90 ? '1:30 Hora': $item->duration.' Minutos'));?></a>
                        </div>
                        <div class="user-detail-name w5oleft  text-right"><i class="fas fa-dollar-sign fa-fw"></i><?php echo number_format($item->amount, 2);?></div>
                      </div>
                    </div>
                  </div>
                </article>
              </div>
              <?php 
                }
              } ?>
            </div>
          </div>
        </div>
      </div>

    <!-- Modal Add Novelty-->
    <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id=""><i class="fa fa-plus fa-fw"></i>Agregar Servicio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addServices-frm" action="<?php echo base_url(); ?>index.php/Doctor/ServicesSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Tipo</label>
                    <div class="">
                      <select name="type" id="type" class="form-control">
                        <option value="consultorio">En Consultorio</option>
                        <option value="domicilio">En Domicilio del Paciente</option>
                      </select>
                      <div class="invalid-feedback">
                        Por favor escriba el nombre del servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Servicio</label>
                    <div class="">
                      <input class="form-control" type="text" id="service" name="service" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre del servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Duración del servicio</label>
                    <div class="">
                      <select name="duration" id="duration" class="form-control">
                        <option value="15">15 minutos</option>
                        <option value="30">30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60">1 hora</option>
                        <option value="90">1:30 hora</option>
                      </select>
                      <div class="invalid-feedback">
                        Por favor seleccione la duración del servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Costo Normal</label>
                    <div class="">
                      <input class="form-control" type="number" min="0" step="0.01" id="amount" name="amount" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el costo del servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Costo Prepagado</label>
                    <div class="">
                      <input class="form-control" type="number" min="0" step="0.01" id="amount_prepaid" name="amount_prepaid" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el costo prepagado del servicio
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Descripcion</label>
                    <div class="">
                      <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                      <div class="invalid-feedback">
                        Por favor escriba la descripción del servicio
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group col-lg-12">
              <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal"><i class="fa fa-times fa-fw"></i>Cerrar</button>
              <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveService"><i class="fa fa-save fa-fw"></i>Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('_partials/footer'); ?>