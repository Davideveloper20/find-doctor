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
              <div class="breadcrumb-item">Mis EPS Prepagadas - Seguros</div>
            </div>
          </div>
          <div class="section-body mt-3">
            <div class="col-lg-3 col-md-6 col-sm-12 ">
              <label class="">Acepta Medicina Prepagada</label>
              <div class="col-12">
                <label class="custom-switch mt-2">
                <span class="custom-switch-description">No</span>
                <input type="checkbox" id="med_prepagado" name="med_prepagado" <?php echo ($user->med_prepagado == 1 ? 'checked': '');?> class="custom-switch-input">
                <span class="custom-switch-indicator chatt"></span>
                <span class="custom-switch-description">Si</span>
              </label>
              </div>
            </div><a id="doctorEpsAddBtn" href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right <?php echo ($user->med_prepagado == 1 ? '': 'sr-only hidden');?>"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a>
            <div class="row" id="doctorEpsRow">
              <?php 
              if(count($myservices) <= 0) { ?>
                <div class="col-12 col-md-12 col-lg-12">
                  <p class="section-lead">Sin Medicina prepagada registrada, por favor agregue nuevos registros.<a id="doctorEpsAddBtn2"  href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right <?php echo ($user->med_prepagado == 1 ? '': 'sr-only hidden');?>"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
                </div>
              <?php } else { 
                foreach ($myservices as $k => $item) {
              ?>
              <div class="col-12 col-md-4 col-lg-4 epsSegurDocCnt-<?php echo $item->id; ?>">
                <article class="article article-style-c">
                  <!--div class="article-header">
                    <div class="article-image" data-background="<?php echo base_url(); ?>assets/img/news/img13.jpg">
                    </div>
                  </div-->
                  <a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-danger btn-circle deleteEpsDoc pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Eliminar" data-id="<?php echo $item->id; ?>"><i class="fa fa-trash"></i></a>
                  <!--a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-primary btn-circle editEpsDoc pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Editar" data-id="<?php echo $item->id; ?>"><i class="fa fa-edit"></i></a-->
                  <div class="article-details">
                    <div class="article-category"><span ><?php //echo $item->institucion; ?></span></div>
                    <div class="article-title">
                      <h2><a href="javascript:void('<?php echo $item->id; ?>')"><?php echo $item->item; ?></a></h2>
                    </div>
                    <div class="article-user">
                      <!--img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png"-->
                      <div class="article-user-details">
                        <div class="user-detail-name text-center">Tipo: <b>
                          <?php echo strtoupper($item->tipo);?></b>
                        </div>
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
            <h5 class="modal-title" id=""><i class="fa fa-plus fa-fw"></i>Agregar EPS / Seguros</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addEpsSeguro-frm" action="<?php echo base_url(); ?>Doctor/EPS-SegurosSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">EPS / Seguro</label>
                    <div class="">
                      <input class="form-control" type="text" id="idseguroeps" name="seguroeps" value="<?php echo set_value('seguroeps', @"$seguroeps"); ?>">
                      <input class="form-control hidden sr-only" type="text" name="idseguroeps" require id="idseguroepsval" value="<?php echo set_value('idseguroeps', @$idseguroeps); ?>">
                      <div class="invalid-feedback">
                        Por favor seleccione la eps
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
              <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" type="0" id="saveFormation"><i class="fa fa-save fa-fw"></i>Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var allservices = <?php echo json_encode(((array)$allservices) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>