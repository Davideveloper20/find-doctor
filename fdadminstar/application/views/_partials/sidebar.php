<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->session->userdata('user');
?>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/logo.png" class="img img-responsive img-fluid"></a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/icon.png" class="img img-responsive img-fluid"></a>
          </div>
          <div class="mt-2 mb-2 p-2 hide-sidebar-mini">
          	<a href="https://finddoctor.fileblocks.com" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <img src="<?php echo base_url(); ?>assets/images/icon.png" alt="Find Doctor" width="30px" class="img img-fluid mx-2">Find Doctor
              </a>
          </div>

        </aside>
      </div>
