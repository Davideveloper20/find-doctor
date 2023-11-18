<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header', ['title' => 'Error']);
?>
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

<?php $this->load->view('_partials/js'); ?>