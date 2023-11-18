<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->session->userdata('user');
?>
<!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion m-0 bg-light" id="accordionSidebar">
      <div class="navbar-inner-nav bg-primary h-100 d-flex flex-column shadow-md align-items-center">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
          <div class="sidebar-brand-icon">
            <i class="fas fa-stethoscope text-white"></i>
          </div>
        </a>
        
        <div class="navbar-content w-75">
          <ul class="sidebar-menu list-unstyled">
            <li class="<?php echo $this->uri->uri_string() == 'Dashboard' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Dashboard"><i class="fas fa-columns fa-fw"></i> <span>Inicio</span></a></li>
            <?php if(in_array($user->idprofile, [0, 2])) { ?>
            <li class="menu-header">Doctor</li>
            <li class="dropdown <?php echo $this->uri->segment(1) == 'Doctor' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i> <span>Perfil</span></a>
              <ul class="dropdown-menu">
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Profile' || $this->uri->uri_string() == 'Doctor' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Profile"><i class="fas fa-user-md fa-fw"></i>Datos Personales</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Goals' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Goals"><i class="fas fa-award fa-fw"></i>Logros</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Formation' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Formation"> <i class="fas fa-university"></i> Formación</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Specialities' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Specialities"><i class="fas fa-stethoscope fa-fw"></i>Especialidades</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Services' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Services"><i class="fas fa-briefcase-medical"></i>Servicios</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/EPS-Seguros' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/EPS-Seguros"><i class="fas fa-hand-holding-heart fa-fw"></i>Medicina Prepagada</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Settings' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Settings"><i class="fas fa-user-cog fa-fw"></i>Configuraciones</a></li>
              </ul>
            </li>
            <li class="<?php echo $this->uri->uri_string() == 'Doctor/Chat-Room' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Chat-Room"><i class="fas fa-comments"></i> <span>Chat</span></a></li>

            <li class="dropdown <?php echo $this->uri->segment(1) == 'Doctor' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i> <span>Pacientes</span></a>
              <ul class="dropdown-menu">
                <li class="<?php echo $this->uri->uri_string() == 'Doctor/Patients-Own' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Patients-Own"><i class="fas fa-users fa-fw"></i>Personales</a></li>
                <li class="<?php echo $this->uri->uri_string() == 'index.php/Doctor/Patients-App' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Patients-App"><i class="fas fa-users"></i> <span>Plataforma</span></a></li>
              </ul>
            </li>
            <li class="menu-header">Pagos</li>
            <li class="<?php echo $this->uri->uri_string() == 'Doctor/transactions' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/transactions"><i class="fas fa-wallet"></i> <span>Transacciones</span></a></li>
            <li class="menu-header">Consultorios</li>
            <li class="<?php echo $this->uri->uri_string() == 'Doctor/Comments' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Comments"><i class="fas fa-comment"></i> <span>Valoraciones</span></a></li>
            <li class="menu-header">Ajustes</li>
            <li class="<?php echo $this->uri->uri_string() == 'Doctor/Consulting-Room' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Consulting-Room"><i class="fas fa-hospital-alt"></i> <span>Administrar</span></a></li>
            <li class="<?php echo $this->uri->uri_string() == 'Doctor/Appoinments' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Doctor/Appoinments"><i class="fas fa-calendar"></i> <span>Citas Médicas</span></a></li>
            <?php } ?>
            <?php  if(in_array($user->idprofile, [0, 3])) { ?>
              <li class="menu-header">Configuraciones</li>
              <li class="dropdown <?php echo in_array($this->uri->uri_string(), ['Consultorios', 'Specialities','EPS-Seguros']) ? 'active' : 'active'; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-settings"></i> <span>General</span></a>
                <ul class="dropdown-menu">
                  <li class="<?php echo $this->uri->uri_string() == 'Consultorios' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Consultorios">Consultorios</a></li>
                  <li class="<?php echo $this->uri->uri_string() == 'Specialities' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Specialities">Especialidades</a></li>
                  <li class="<?php echo $this->uri->uri_string() == 'EPS-Seguros' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/EPS-Seguros">EPS / Seguros</a></li>
                </ul>
              </li>
              <li class="menu-header">Doctores</li>
              <li class="<?php echo $this->uri->uri_string() == 'Admin/Doctors' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Admin/Doctors"><i class="fas fa-user-md fa-fw"></i> <span>Doctores</span></a></li>
              <li class="<?php echo $this->uri->uri_string() == 'Admin/Req-Payments' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Admin/Req-Payments"><i class="fas fa-hand-holding-usd fa-fw"></i> <span>Solicitudes de Pago</span></a></li>
              <li class="menu-header">Pacientes</li>
              <li class="<?php echo $this->uri->uri_string() == 'Admin/Patients-Users' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Admin/Patients-Users"><i class="fas fa-user-check fa-fw"></i> <span>Pacientes Registrados</span></a></li>
              <li class="<?php echo $this->uri->uri_string() == 'Admin/Patients-NoUsers' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Admin/Patients-NoUsers"><i class="fas fa-user-slash fa-fw"></i> <span>Pacientes NO Registrados</span></a></li>
            <?php } ?>
            <li><a class="nav-link" href="<?php echo base_url(); ?>index.php/Auth/logout"><i class="fas fa-user-check fa-fw"></i> <span>Cerrar Sesión</span></a></li>
          </ul>
        </div>
      </div>
    </ul>
