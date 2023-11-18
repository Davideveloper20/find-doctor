<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->session->userdata('user');
$chat = $this->ss->getUserChatStatus();
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <?php if(in_array($user->idprofile, [0, 2])) { ?>
          <li class="dropdown "><a href="#" id="notifmessage" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i class="far fa-envelope"></i></a>
          </li>
          <li class="dropdown">
            <form id="frm-chgchatstatus" action="<?php echo base_url(); ?>index.php/Doctor/ChatStatus" method="post">
              <input type="text" name="rediruri" value="<?php echo $this->uri->uri_string(); ?>" class="form-control hidden sr-only">
              <label class="custom-switch mt-2">
                <span class="custom-switch-description chatt">Chat</span>
                <input type="checkbox" id="chatstatus" name="chatstatus" <?php echo ($chat->chat_activo == 1 ? 'checked': '');?> class="custom-switch-input">
                <span class="custom-switch-indicator chatt"></span>
              </label>
            </form>
          </li>
          <?php }?>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" id="layoutUserProfImgs" src="<?php echo $user->profileimage; ?>" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block"><?php echo $user->idprofile == '2' ? 'Doctor' : 'Administrador'; ?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="<?php echo base_url(); ?>index.php/Doctor/Profile" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Perfil
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>index.php/Auth/LogOut" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
              </a>
            </div>
          </li>
        </ul>
      </nav> -->
