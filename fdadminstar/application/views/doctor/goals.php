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
                  <?php echo $title;?>
                </h3>
                <div class="section-header-breadcrumb d-flex my-auto">
                  <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
                  <div class="breadcrumb-item"><?php echo trans('profile');?></div>
                </div>
              </div>
              <div class="section-body">
                <p class="section-lead"><?php echo trans('my_goals');?>.<a href="#modalDocGoal" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidde-xs"><?php echo trans('add'); ?></span></a></p>
                <div class="row" id="itemsGoals">
                  <?php if(count($mygoals) <= 0) { ?>
                    <div class="col-12 col-md-4 col-lg-4">
                      <p class="section-lead"><?php echo trans('no_goals');?>.</p>
                    </div>
                  <?php } else { 
                    foreach ($mygoals as $kgoal => $goal) {
                  ?>
                  <div class="col-12 col-md-4 col-lg-4 goalDocCnt-<?php echo $goal->id; ?>">
                    <article class="article article-style-c">
                      <!--div class="article-header">
                        <div class="article-image" data-background="<?php echo base_url(); ?>assets/img/news/img13.jpg">
                        </div>
                      </div-->
                      <a href="javascript:void(<?php echo $goal->id; ?>);" class="btn btn-sm btn-danger btn-circle deleteGoal pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Eliminar" data-id="<?php echo $goal->id; ?>"><i class="fa fa-trash"></i></a>
                      <a href="javascript:void(<?php echo $goal->id; ?>);" class="btn btn-sm btn-primary btn-circle editGoal pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Editar" data-id="<?php echo $goal->id; ?>"><i class="fa fa-edit"></i></a>
                      <div class="article-details">
                        <div class="article-category">
                          <i class="fa fa-calendar fa-fw pull-left py-1"></i>
                          <span ><?php echo $this->ss->dbdate_2fr($goal->fecha); ?></span>
                          
                        </div>
                        <div class="article-title">
                          <h2><a href="javascript:void('<?php echo $goal->id; ?>')" class="editGoal" data-id="<?php echo $goal->id; ?>"><i class="fas fa-award fa-fw"></i><?php echo $goal->titulo; ?></a></h2>
                        </div>
                        <p><?php echo $goal->descripcion; ?></p>
                        <!--div class="article-user">
                          <img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png">
                          <div class="article-user-details">
                            <div class="user-detail-name">
                              <a href="#">Hasan Basri</a>
                            </div>
                            <div class="text-job">Web Developer</div>
                          </div>
                        </div-->
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
    <div class="modal fade" id="modalDocGoal" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-keyboard="false" data-backdrop="false" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Agregar Logro</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addGoal-frm" action="<?php echo base_url(); ?>Doctor/GoalSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Titulo</label>
                    <div class="">
                      <input type="text" class="form-control" id="titulo" name="titulo" required="">
                      <div class="invalid-feedback">
                        Por favor escriba el titulo del logro
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-12">
                    <label>Fecha</label>
                    <div class='input-group date' id="fecha_cnt">
                      <input type='text' class="form-control" style="text-align: center;" name="fecha" id="fecha" required readonly="" placeholder="Seleccione fecha" value=""/>
                      <span class="input-group-addon input-group-append">
                        <span class="input-group-text" id="fecha-addon">
                          <i class="fas fa-calendar"></i>
                        </span>
                      </span>
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione la fecha del logro
                    </div>
                    <label id="fecha-error" class="error" for="fecha" style="display: none;">Por favor seleccione la fecha del logro</label>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label>Descripción:</label>
                    <textarea name="description" id="description" cols="30" rows="2" maxlength="1024" placeholder="Por favor escriba la descripción del logro" class="form-control" required=""></textarea>
                    <div class="invalid-feedback">
                      Por favor escriba la descripción del logro
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <div class="form-group col-lg-12">
              <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
              <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveGoal"  data-type="0"><i class="fa fa-save"></i> Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('_partials/footer'); ?>