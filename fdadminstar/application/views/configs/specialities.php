<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?php echo $title;?></h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?php echo base_url();?>">Dashboard</a></div>
              <div class="breadcrumb-item">Especialidades</div>
            </div>
          </div>
          <div class="section-body">
            <h2 class="section-title">Gestion de Especialidades
            <a href="#modalDocEduc" data-toggle="modal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus fa-fw"></i><span class="hidden-xs"><?php echo trans('add'); ?></span></a></h2>
            <div class="card">
            <div class="card-body">
              <table class="table table-striped table-hover" id="dtDatatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Descripción</th>
                    <th scope="col"><i class="fas fa-cog"></i></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            </div>
          </div>
        </section>
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
              <form id="addSpeciality-frm" action="<?php echo base_url(); ?>index.php/SpecialitiesSave" method="post" accept-charset="utf-8">
                <div class="row px-2">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Nombre</label>
                    <div class="">
                      <input class="form-control" type="text" id="speciality" name="speciality" value="">
                      <div class="invalid-feedback">
                        Por favor escriba el nombre
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Tipo</label>
                    <div class="">
                      <select name="tipo" id="tipo" class="form-control">
                        <option value="Clínica">Clínica</option>
                        <option value="Quirúrgica">Quirúrgica</option>
                        <option value="Médico-Quirúrgica">Médico-Quirúrgica</option>
                        <option value="Diagnósticas">Diagnósticas</option>
                      </select>
                      <div class="invalid-feedback">
                        Por favor seleccione
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 ">
                    <label class="">Descripcion</label>
                    <div class="">
                      <textarea class="form-control" name="descrip" id="descrip" cols="30" rows="3"></textarea>
                      <div class="invalid-feedback">
                        Por favor escriba la descripción del servicio
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
              <button type="button" class="btn btn-success pull-right" data-type="0" data-reqid="" id="saveFormation"><i class="fa fa-save fa-fw"></i>Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('_partials/footer'); ?>
