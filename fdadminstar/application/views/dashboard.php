<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
$user = $this->session->userdata('user');
?>
  	<!-- Main Content -->
  	<div id="content-wrapper" class="d-flex flex-column pt-5">

      	<!-- Main Content -->
      	<div id="content">
		  	<div class="container-fluid">
		  		<div class="row justify-content-between">
		  			<div class="col-md-4">
		  				<h2 class="mL">Bienvenido</h2>
		  			</div>
		  			<div class="col-md-4 text-right">
		  				<?php echo form_open('Doctor/chatStatuss', ''); ?>
		  					<?php echo form_hidden('rediruri', 'Dashboard'); ?>
	          				<div class="custom-control custom-switch">
	          					<?php echo form_checkbox(['name'=>'chatstatus','id'=>'customSwitch1', 'onchange'=>'this.parentElement.parentElement.submit()'], 'on', ($user->chat_activo==1), ['class'=>'custom-control-input']); ?>
							  	<label class="custom-control-label" for="customSwitch1"style="font-size: 16px">Disponible para chat</label>
							</div>
						<?php echo form_close(); ?>
					</div>
		  			<div class="col-md-4 text-right">
		  				<?php echo form_open('Doctor/videoChatStatuss', ''); ?>
		  					<?php echo form_hidden('rediruri', 'Dashboard'); ?>
	          				<div class="custom-control custom-switch">
	          					<?php echo form_checkbox(['name'=>'chatstatus','id'=>'customSwitch2', 'onchange'=>'this.parentElement.parentElement.submit()'], 'on', ($user->video_chat_activo==1), ['class'=>'custom-control-input']); ?>
							  	<label class="custom-control-label" for="customSwitch2"style="font-size: 16px">Disponible para Video Llamada</label>
							</div>
						<?php echo form_close(); ?>
		  			</div>
		  		</div>
		  		<div class="form-group">
	              	<?php if($user->idprofile == 2){  ?>
	              	<h4 class="mEB"><?php echo @$info['dprof']->titulo.'. '.$user->fullname.(!empty($info['dprof']->especiality)?' <small class="mL">&mdash; '.@$info['dprof']->especiality.'</small>':''); ?> <small class="pull-right"><a class="" href="<?php echo base_url(); ?>index.php/Doctor/Comments"><?php echo number_format(@$info['rating'],1); ?> <i class="fas fa-star dorado fa-1x"></i> / <?php echo @$info['commcount']; ?> <i class="fas fa-comment-dots fa-1x"></i></a></small></h4>
	          		<?php }  ?>
		  		</div>
		      	<div class="row px-3">
              		<?php if($user->idprofile == 2){  ?>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Profile">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-primary my-0 ml-0 rounded-left">
				                  <i class="fas fa-user fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Porcentaje de perfil</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['profPercent'],1); ?>%
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Formation">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-success my-0 ml-0 rounded-left">
				                  <i class="fas fa-user-graduate fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Formación</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['formation'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Goals">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-warning my-0 ml-0 rounded-left">
				                  <i class="fas fa-award fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Logros</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['goals'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Specialities">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-danger my-0 ml-0 rounded-left">
				                  <i class="fas fa-user-md fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Especialidades</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['specialities'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Services">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-info my-0 ml-0 rounded-left">
				                  <i class="fas fa-briefcase-medical fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Servicios</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['services'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Consulting-Room">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-primary my-0 ml-0 rounded-left">
				                  <i class="fas fa-hospital-alt fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Consultorios</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['consultingRooms'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/EPS-Seguros">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-danger my-0 ml-0 rounded-left">
				                  <i class="fas fa-hand-holding-heart fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Medidicina Prepagada</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['eps'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>

						<div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/PreSchedule">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bg-primary my-0 ml-0 rounded-left">
				                  <i class="fas fa-calendar fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Pre-Agendamiento</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format($info['consultingRooms'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>



			            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
			            	<a class="" href="<?php echo base_url(); ?>index.php/Doctor/Appoinments">
				              <div class="card card-statistic-1 shadow-lg rounded">
				                <div class="card-icon bgblue2 my-0 ml-0 rounded-left">
				                  <i class="fas fa-calendar fa-2x"></i>
				                </div>
				                <div class="card-wrap">
				                  <div class="card-header m-0 pt-3">
				                    <h4>Agendamiento</h4>
				                  </div>
				                  <div class="card-body">
				                    <?php echo number_format(@$info['appoints'],0); ?>
				                  </div>
				                </div>
				              </div>
				            </a>
			            </div>
              		<?php } ?>
          		</div>
          		<div class="row px-3">
          			<div class="col-md-4">
          			</div>
          		</div>
            </div>
<?php $this->load->view('_partials/footer'); ?>