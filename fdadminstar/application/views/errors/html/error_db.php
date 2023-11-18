<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!function_exists('base_url')) {
  function base_url() {
    global $config;
    if ( ! isset($config['base_url'])) {
      return NULL;
    } elseif (trim($config['base_url']) === '') {
      return '';
    }
    return rtrim($config['base_url'], '/').'/';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Error &mdash; Find Doctor</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
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
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="page-error">
          <div class="page-inner">
            <h1><?php echo (isset($heading) && !empty($heading) ? $heading : 'Error');?></h1>
            <div class="page-description">
            	<?php echo (isset($message) && !empty($message) ? $message : 'Ha Ocurrido Un Error');?>
            </div>
            <div class="page-search">
              <?php echo (isset($severity) && !empty($severity) ? '<p>Severity: '.$severity.'</p>' : '');?>
              <?php echo (isset($filepath) && !empty($filepath) ? '<p>Filename: '.$filepath.'</p>' : '');?>
              <?php echo (isset($line) && !empty($line) ? '<p>Line Number: '.$line.'</p>' : '');?>

              <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) { ?>
                <p>Backtrace:</p>
                <?php if(isset($exception)) { ?>
                  <?php foreach ($exception->getTrace() as $error) { ?>

                    <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) { ?>

                      <p style="margin-left:10px">
                      File: <?php echo $error['file']; ?><br />
                      Line: <?php echo $error['line']; ?><br />
                      Function: <?php echo $error['function']; ?>
                      </p>
                    <?php } ?>

                  <?php } ?>
                <?php } else { ?>
                  <?php foreach (debug_backtrace() as $error) { ?>

                    <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) { ?>

                      <p style="margin-left:10px">
                      File: <?php echo $error['file'] ?><br />
                      Line: <?php echo $error['line'] ?><br />
                      Function: <?php echo $error['function'] ?>
                      </p>

                    <?php } ?>

                  <?php } ?>

                <?php } ?>
              <?php } ?>

              <div class="mt-3">
                <a href="<?php echo base_url(); ?>">Ir Al Inicio</a>
              </div>
            </div>
          </div>
        </div>
        <div class="simple-footer mt-5">
          Copyright &copy; <a href="https://solucionesstar.com" target="_blank">Soluciones Star SAS</a> 2019
        </div>
      </div>
    </section>
  </div>
<!-- General JS Scripts -->
  <script src="<?php echo base_url(); ?>assets/modules/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/modules/tooltip.js"></script>
  <script src="<?php echo base_url(); ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/stisla.js"></script>
<!-- Template JS File -->
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
</html>