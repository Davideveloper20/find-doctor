<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->output->delete_cache();
        $this->load->helper(array('url_helper','url','form'));
        $this->load->library(array('session','form_validation'));
    }

	public function index() {
		try {
			if($this->ss->validSession()) {
				$this->load->model('Ss');
				$this->load->database();
			  	$connected = $this->db->initialize();
				if (!$connected) {
					throw new Exception("No se puede conectar a la base de datos", 403);
				}
				redirect('dashboard');
			} else {
				redirect('auth/login');
			}
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function validateEmailSignUp() {
		try {
			$email = $this->input->post('q');
			$passBD = $this->ss->getCampos('ss_users', ["email" => $email], ['idusers']);
			if($passBD['success'] && count($passBD['data']) > 0) {
				return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'message' => trans('email_exists'), 'data' => [true]]));	
			}
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'message' => trans('email_dont_exists'), 'data' => [false]]));	
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'message' => $e->getMessage(), 'data'=>[false], 'heading' => 'Error '.$e->getCode()]));
		}
	}
}
