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
              <div class="breadcrumb-item">Mis Preagendamientos</div>
            </div>
          </div>
          <div class="section-body mt-3">
            <p class="section-lead">Mis Preagendamientos.<a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
            <div class="row">
              <?php if(count($mycosulting) <= 0) { ?>
                <div class="col-12 col-md-4 col-lg-4">
                  <p class="section-lead">Sin Consultorios Registrados, por favor agregue sus Consultorios.<a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></p>
                </div>
              <?php } else { 
                foreach ($mycosulting as $k => $item) {
              ?>
              <div class="col-12 col-md-6 col-lg-6 consultingRoomCnt-<?php echo $item->id; ?>">
                <article class="article article-style-c article-w-logo2">
                  <!--div class="article-header">
                    <div class="article-image" data-background="<?php echo base_url(); ?>assets/img/news/img13.jpg">
                    </div>
                  </div-->
                  <a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-danger btn-circle deleteConsulRoom pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Eliminar" data-id="<?php echo $item->id; ?>"><i class="fa fa-trash"></i></a>
                  <!--a href="javascript:void(<?php echo $item->id; ?>);" class="btn btn-sm btn-primary btn-circle editEpsDoc pull-right mx-1 mt-1 text-white" data-toggle="popover" data-content="Editar" data-id="<?php echo $item->id; ?>"><i class="fa fa-edit"></i></a-->
                  
                  <div class="article-logo article-logo2">
                    <div class="lgo">
                      <img alt="image" src="<?php echo base_url(); ?>assets/images/consultingroom.png" class="lazy">
                    </div>
                  </div>
                  <div class="article-details">
                    <div class="article-category"><span ><?php //echo $item->name; ?></span></div>
                    <div class="article-title">
                      <h2><a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode(htmlentities($item->address.', '.$item->city.', '.$item->state.', '.$item->country)); ?>&travelmode=transit&dir_action=navigate" target="_blank"><?php echo $item->name; ?></a></h2>
                    </div>
                    <address>
                      <p class="text-justify"><a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode(htmlentities($item->address.', '.$item->city.', '.$item->state.', '.$item->country)); ?>&travelmode=transit&dir_action=navigate" target="_blank" class="button link external pull-left"><i class="fas fa-map-marked-alt fa-1x fa-fw"></i></a>
                      <?php 
                      if(strlen($item->address) > 128) {
                        echo substr($item->address.', '.$item->city.', '.$item->state.', '.$item->country, 0, 128).'<span class="collapse" id="speciality'.$item->id.'">'.substr($item->address.', '.$item->city.', '.$item->state.', '.$item->country, 128).'</span><a class="btn btn-link" data-toggle="collapse" href="#speciality'.$item->id.'" role="button" aria-expanded="false" aria-controls="speciality'.$item->id.'"> + Ver más</a></p>';
                      } else {
                        echo $item->address.', '.$item->city.', '.$item->state.', '.$item->country.'</p>'; 
                      }
                    ?>
                    </address>
                    <div class="article-user">
                      <!--img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png"-->
                      <div class="article-user-details">
                        <div class="user-detail-name text-Left">
                          <a href="tel:<?php echo $item->phone1;?>"><i class="fa fa-phone fa-fw"></i><?php echo $item->phone1;?></a><?php echo (!empty($item->phone2) ? ' &mdash; <a href="tel:'.$item->phone2.'" ><i class="fa fa-phone fa-fw"></i>'.$item->phone2.'</a>' : '');?>
                        </div>
                        <?php if(!empty($item->email)) { ?>
                        <div class="user-detail-name text-right"><i class="fa fa-envelope fa-fw"></i><a href="mailto:<?php echo $item->email;?>"><?php echo $item->email;?></a></div>
                        <?php 
                        } 
                        if(!empty($item->url)) { ?>
                        <div class="user-detail-name text-right"><i class="fa fa-link fa-fw"></i><a href="<?php echo $item->url;?>" target="_blank"><?php echo $item->url;?></a></div>
                        <?php } ?>
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
            <h5 class="modal-title" id=""><i class="fa fa-plus fa-fw"></i>Agregar Consultorio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form id="addConsultRoom-frm" action="<?php echo base_url(); ?>Doctor/Consulting-Room-Save" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Nombre del Consultorio</label>
                    <div class="">
                      <input class="form-control" type="text" id="name" name="name" value="" required="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-12">
                    <label class="">Ciudad</label>
                    <div class="">
                      <input class="form-control" type="text" id="idcity" name="city" value="<?php echo set_value('city', @"$city".(!empty($state)?", $state":"")); ?>">
                      <input class="form-control hidden sr-only" type="text" name="idcity" id="idcityval" required="" value="<?php echo set_value('idcity', @$idcity); ?>">
                    </div>
                    <div class="invalid-feedback">
                      Por favor seleccione su ciudad
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Dirección</label>
                    <div class="">
                      <input class="form-control" type="text" id="address" name="address" value="" required>
                      <div class="invalid-feedback">
                        Por favor escriba La Dirección
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                    <label class="">Telefono 1</label>
                    <div class="">
                      <input class="form-control" type="text" id="phone1" name="phone1" value="" required>
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
                      <input class="form-control" type="email" id="email" name="email" value="" >
                      <div class="invalid-feedback">
                        Por favor escriba el Correo Electrónico
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Sitio Web</label>
                    <div class="">
                      <input class="form-control" type="url" id="url" name="url" value="" >
                      <div class="invalid-feedback">
                        Por favor escriba el Sitio Web
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
              <button type="button" class="btn btn-success pull-right" data-reqid="<?php echo @$iddoctor;?>" id="saveFormation"><i class="fa fa-save fa-fw"></i>Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<script>
  var cities = <?php echo json_encode(((array)$cities) );?>;
  var cnfcondultorios = <?php echo json_encode(((array)$cnfcondultorios) );?>;
</script>
<?php $this->load->view('_partials/footer'); ?>