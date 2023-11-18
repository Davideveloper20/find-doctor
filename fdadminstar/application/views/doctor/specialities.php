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
          <div class="section-body mt-3">
            <p class="section-lead">Mis Especialidades.<a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
            <div class="row">
              <?php if(count($myspecialities) <= 0) { ?>
                <div class="col-12 col-md-4 col-lg-4">
                  <p class="section-lead">Sin Especialidades Registrada, por favor agregue sus Especialidades.</p>
                </div>
              <?php } else { 
                foreach ($myspecialities as $k => $item) {
              ?>
              <div class="col-12 col-md-6 col-lg-6 specialityCtn-<?php echo $item->idspectdoc; ?>">
                <article class="article article-style-c article-w-logo">
                  <!--div class="article-header">
                    <div class="article-image" data-background="<?php echo base_url(); ?>assets/img/news/img13.jpg">
                    </div>
                  </div-->
                  <a href="javascript:void(<?php echo $item->idspectdoc; ?>);" class="btn btn-sm btn-danger btn-circle deleteSpeciality pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Eliminar" data-id="<?php echo $item->idspectdoc; ?>"><i class="fa fa-trash"></i></a>
                  <a href="javascript:void(<?php echo $item->idspectdoc; ?>);" class="btn btn-sm btn-primary btn-circle editSpeciality pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Editar" data-id="<?php echo $item->idspectdoc; ?>"><i class="fa fa-edit"></i></a>
                  <div class="article-logo">
                    <a href="javascript:void(<?php echo $item->idspectdoc; ?>);" class="editSpeciality" data-id="<?php echo $item->idspectdoc; ?>">
                      <img alt="image" src="<?php echo base_url(); ?>assets/images/speciality.png">
                    </a>
                  </div>
                  <div class="article-details">
                    <div class="article-category"><span ><?php //echo $item->institucion; ?></span></div>
                    <div class="article-title">
                      <h2><a href="javascript:void('<?php echo $item->idspectdoc; ?>')"class="editSpeciality" data-id="<?php echo $item->idspectdoc; ?>"><?php echo $item->speciality; ?></a>
                      </h2>
                    </div>
                    <p class="text-justify"><?php 
                      if(strlen($item->descrip) > 128) {
                        echo substr($item->descrip, 0, 128).'<span class="collapse" id="speciality'.$item->idspectdoc.'">'.substr($item->descrip, 128).'</span><a class="btn btn-link" data-toggle="collapse" href="#speciality'.$item->idspectdoc.'" role="button" aria-expanded="false" aria-controls="speciality'.$item->idspectdoc.'"> + Ver más</a></p>';
                      } else {
                        echo $item->descrip.'</p>'; 
                      }
                    ?>
                    </p>
                    <!--div class="article-user">
                      <img alt="image" src="<?php echo base_url(); ?>assets/images/speciality.png">
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
    <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id=""><i class="fa fa-plus fa-fw"></i>Agregar Especialidad</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addSpecial-frm" action="<?php echo base_url(); ?>Doctor/SpecialitiesSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Especialidad</label>
                    <div class="">
                      <input class="form-control" type="text" id="idspeciality" name="speciality" value="">
                      <input class="form-control hidden sr-only" type="text" name="idspeciality" id="idspecialityval" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre de la Institución
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 " id="specialityDes-cnt" style="display: none;">
                    <label class="">Describa la Especialidad</label>
                    <div class="">
                      <textarea class="form-control" id="idspecialitydes" name="specialitydes" value="" cols="30" rows="2"></textarea>
                      <div class="invalid-feedback">
                        Por favor describa la especialidad
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
              <div class="form-group col-lg-12">
                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveSpeciality">Guardar</button>
              </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var specialities = <?php echo json_encode(((array)$specialities) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>