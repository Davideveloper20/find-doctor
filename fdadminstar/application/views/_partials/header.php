<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es" prefix="og: http://ogp.me/ns#">
<head>
  <base href="<?php echo base_url(); ?>">
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="profile" href="//gmpg.org/xfn/11">
    <meta name="application-name" content="<?php echo (isset($title) && !empty($title) ? $title.' &mdash; ' : ''); ?>Find Doctor" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="<?php echo (isset($title) && !empty($title) ? $title.' &mdash; ' : ''); ?>Find Doctor" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#00a1ed" />
  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/images/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url(); ?>assets/images/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/images/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo base_url(); ?>assets/images/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#00a1ed">
  <meta name="msapplication-TileImage" content="<?php echo base_url(); ?>assets/images/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#00a1ed">
  <link href="<?php echo base_url(); ?>favicon.png" rel="icon">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
  <title><?php echo (isset($title) && !empty($title) ? $title.' &mdash; ' : ''); ?>Find Doctor</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/bootstrap/css/bootstrap.min.css">

  <link href="assets/css/sb-admin-2.css?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>" rel="stylesheet">
  <link href="assets/css/file-uploader.css?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-glyphicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome-web/css/v4-shims.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome-web/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/alertify/css/alertify.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/alertify/themes/alertify.bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/jqc/jquery-confirm.min.css">

  <!-- CSS Libraries -->
<?php
if ($this->uri->segment(1) == "Auth") { ?>
  <?php
    if (in_array($this->uri->segment(2), ["Login",'Iniciar-Sesion', 'SignIn'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-social/bootstrap-social.css">
<?php
    } 
    if (in_array($this->uri->segment(2), ["SignUp",'Registro','Register'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
<?php
    }
}
if (in_array($this->uri->segment(1), ["Consultorios",'Specialities','EPS-Seguros'])) { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/dt4/datatables.css">
<?php
}
if ($this->uri->segment(1) == "Doctor") { ?>
  <?php
    if (in_array($this->uri->segment(2), ["Profile",'Perfil'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/IntlTelInput/css/intlTelInput.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
<?php
    }
    if (in_array($this->uri->segment(2), ["Goals",'Logros','Settings','Configuraciones'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
<?php
    }
    if (in_array($this->uri->segment(2), ["Formation",'Formacion'])) { ?> 
<?php
    }
    if (in_array($this->uri->segment(2), ["Specialities",'Especialidades'])) { ?> 
<?php
    }
    if (in_array($this->uri->segment(2), ['Consulting-Room','Consultorios'])) { ?> 
<?php
    }
    if (in_array($this->uri->segment(2), ['EPS-Seguros'])) { ?> 
<?php
    }
    if (in_array($this->uri->segment(2), ['Patients', 'Pacientes', 'Comments'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/dt4/datatables.css">
<?php
    }
    if (in_array($this->uri->segment(2), ["Appoinments",'Citas'])) { ?>
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
      <!--link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fullcalendar/fullcalendar.css"-->
      <!--link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fullcalendar/fullcalendar.print.css"-->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/schuelder/codebase/dhtmlxscheduler.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/schuelder/codebase/dhtmlxscheduler_material.css">
      <style type="text/css" media="screen">
        .dhx_scale_holder_now.custom_color, .dhx_scale_holder.custom_color{
          background-image:url(<?php echo base_url(); ?>assets/images/imgs/hour_bg.png);
          background-position:0px -252px;
        }  
      </style>
    <?php
    }
  }
if ($this->uri->segment(1) == "Admin") { ?>
  <?php
    if (in_array($this->uri->segment(2), ["Doctors","Patients-Users",'Req-Payments','Patients-NoUsers'])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/dt4/datatables.css">
  <?php
    }
    if (in_array($this->uri->segment(2), ["Doctors"])) { ?> 
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/IntlTelInput/css/intlTelInput.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <?php
    } 
    ?>
<?php
} 
?>

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.v2.css?v=<?php echo rand(0, 10).'.'.rand(0, 10); ?>">
  <!-- Start GA -->
  <!-- Google Analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-144567188-1', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- End Google Analytics -->
  <script async src='https://www.google-analytics.com/analytics.js'></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144567188-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-144567188-1');
  gtag('set', {'user_id': 'UA-144567188-1'}); // Establezca el ID de usuario mediante el user_id con el que haya iniciado sesión.
  ga('set', 'userId', 'UA-144567188-1'); // 
</script>
<!-- /END GA --></head>

<?php
if (!in_array($this->uri->segment(1), ["Auth", "auth_forgot_password", "auth_register", "auth_reset_password", "Error", "utilities_contact", "utilities_subscribe"])) {
  $this->load->view('_partials/layout');
  $this->load->view('_partials/sidebar2');
}
?>
