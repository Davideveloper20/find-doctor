<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
    <!-- Main Content -->
    <div id="content-wrapper" class="d-flex flex-column pt-5">

        <!-- Main Content -->
        <div id="content">
        <div class="container-fluid">
          <div class="form-group mb-0 d-flex justify-content-between">
            <h3 class="mb-0">
              Sala de Chat
              <p class="section-lead title-font-12 mL">Aqui tendra sus mensajes de chat con pacientes.</p>
            </h3>
            <div class="section-header-breadcrumb d-flex my-auto">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item">Sala de CHat</div>
            </div>
          </div>

          <div class="row align-items-center justify-content-center">
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card chat-box" style="height: 422px;">
                <div class="card-header">
                  <h4>Pacientes</h4>
                </div>
                <div class="card-body" id="chatUsersBoxCnt" style="overflow-y: auto !important;">
                  <ul class="list-unstyled list-unstyled-border" id="chatUsersBox">
                    <!-- <?php foreach ($allusers as $idx => $patuser) {
                    ?>
                    <li class="media" data-iduserchat="<?php echo $patuser->idusers; ?>">
                      <img alt="image" class="mr-3 rounded-circle loadChatMessages" data-name="<?php echo $patuser->fullname; ?>" width="50" data-lastid="0" data-firstid="0" data-iduserchat="<?php echo $patuser->idusers; ?>" src="<?php echo $patuser->profileimage; ?>">
                      <div class="media-body">
                        <div class="mt-0 mb-1 font-weight-bold loadChatMessages" data-name="<?php echo $patuser->fullname; ?>" data-iduserchat="<?php echo $patuser->idusers; ?>" data-lastid="0" data-firstid="0"><?php echo $patuser->fullname; ?></div>
                      </div>
                    </li>
                    <?php
                    }
                    ?> -->
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-8 col-lg-8">
              <div class="card chat-box card-success" id="mychatbox">
                <div class="card-header">
                  <h4><i class="fas fa-circle text-success mr-2" title="Online" data-toggle="tooltip"></i> <span id="chatTitleBox">Chat</span></h4>
                </div>
                <div class="card-body chat-content" id="chat-content">
                </div>
                <div class="card-footer chat-form">
                  <form id="chat-form" action="javascript:return false;" enctype="multipart/form-data">
                    <input type="text" class="form-control" id="messageBox-txt" placeholder="Mensaje">
                    <button id="btnSend" disabled data-iduser="-1" type="button" class="btn btn-danger">
                      <i class="far fa-paper-plane"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
      </div>

    <!-- Modal Add Novelty-->
    <div class="modal fade" id="modalDocEduc" tabindex="-1" role="dialog" aria-labelledby="modalPriceTitle" aria-hidden="true" data-reqid="<?php echo @$iddoctor;?>">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Agregar EPS / Seguros</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-panel">
              <form action="<?php echo base_url(); ?>Doctor/EPS-SegurosSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">EPS / Seguro</label>
                    <div class="">
                      <input class="form-control" type="text" id="idseguroeps" name="seguroeps" value="<?php echo set_value('city', @"$city".(!empty($state)?", $state":"")); ?>">
                      <input class="form-control hidden sr-only" type="text" name="idseguroeps" require id="idseguroepsval" value="<?php echo set_value('idcity', @$idcity); ?>">
                      <div class="invalid-feedback">
                        Por favor seleccione la eps
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
  var allusers = <?php echo json_encode(((array)$allusers) );?>;
  var id_doctor = '<?php echo @$user->doc->iddoctor ?>';
</script>
<?php $this->load->view('_partials/footer'); ?>