<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
        parent::__construct();
        if(!$this->ss->validSession()) {
        	redirect('Auth/Login2');
        }
        $this->output->cache(0);
        $this->output->delete_cache();
        $this->load->helper(array('url_helper','url','form'));
        $this->load->library(array('session','form_validation'));
    }

	public function index() {
		try {
			$user = $this->ss->getUser();
			$data = [
				'title' => 'Dashboard',
				'user' => $user,
				'info' => $this->infoDash(),
			];
			$this->session->set_userdata(['user' => $user]);
			$this->load->view('dashboard', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	protected function infoDash() {
		try {
			$user = $this->ss->getSessUser();
			$resp = [
        		'profPercent' => 0,
        		'profPints' => 0,
        		'formation' => 0,
        		'goals' => 0,
        		'specialities' => 0,
        		'consultingRooms' => 0,
        		'services' => 0,
        		'eps' => 0,
        	];
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$rat = $this->ss->getDoctorRatings();
            	$dprof = $this->ss->getDoctorProfile();
            	$duser = $this->ss->getUser();
            	$resp = [
            		'profPercent' => 0,
            		'profPints' => 0,
            		'formation' => count($dprof->formation['data']),
            		'goals' => count($dprof->goals['data']),
            		'specialities' => count($dprof->specialities['data']),
            		'consultingRooms' => count($this->ss->getDoctorConsultorios()),
            		'services' => count($this->ss->getDoctorServices()),
            		'eps' => count($this->ss->getDoctorEPSSeguros()),
            		'varified' => $dprof->verified,
            		'rating' => $dprof->rating,
            		'commcount' => count($rat['comments']),
            		'dprof' => $dprof,
            		'appoints' => count($this->ss->getDocAppoinments()),
            	];
            	$resp['profPints']+= !empty($dprof->type_doc)?1:0;
            	$resp['profPints']+= !empty($dprof->document)?1:0;
            	$resp['profPints']+= !empty($dprof->rethus)?1:0;
            	$resp['profPints']+= !empty($dprof->titulo)?1:0;
            	$resp['profPints']+= !empty($dprof->aboutme)?1:0;
            	$resp['profPints']+= !empty($dprof->especiality)?1:0;
            	$resp['profPints']+= !empty($duser->address)?1:0;
            	$resp['profPints']+= !empty($duser->idcity)?1:0;
            	$resp['profPints']+= !(empty($duser->profileimage) || $duser->profileimage != 'https://fileblocks.co/api/Solstar/getfile/k55n7gtp')?1:0;
            	$resp['profPints']+= ($duser->gender==0)?1:0;
            	$resp['profPints']+= ($resp['formation']>0)?1:0;
            	$resp['profPints']+= ($resp['goals']>0)?1:0;
            	$resp['profPints']+= ($resp['consultingRooms']>0)?1:0;
            	$resp['profPints']+= ($resp['services']>0)?1:0;
            	$resp['profPints']+= ($resp['eps']>0)?1:0;
            	$resp['profPercent'] = (($resp['profPints']*100)/15);
            }
			return $resp;
		} catch (Exception $e) {
			return $resp;
		}
	}

	public function getCities($idcountry=null, $idstate=null) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'data' => (array) $this->ss->getCities($idcountry, $idstate)]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}
}
