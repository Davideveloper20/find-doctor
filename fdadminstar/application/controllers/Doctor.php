<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller {

	public function __construct() {
        parent::__construct();
        if(!$this->ss->validSession()) {
        	redirect('Auth/Login');
        }
        $this->output->cache(0);
        $this->output->delete_cache();
        $this->load->helper(array('url_helper','url','form'));
        $this->load->library(array('session','form_validation'));
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
    }

	public function index() {
		try {
			$user = (array) $this->ss->getUser();
			$profile = (array) $this->ss->getDoctorProfile();
			$data = [
				'title' => 'Mi Perfil',
			];
			$data = array_merge($data, $user, $profile);
			$data['user'] = (object) $user;
			$data['typedocs'] = $this->ss->getTypeDocs();
			$data['countries'] = $this->ss->getCountries();
			$data['cities'] = $this->ss->getCities($data['user']->idcountry);
			$this->load->view('doctor/index', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function transactions() {
		try {
			$user = (array) $this->ss->getUser();
			$profile = (array) $this->ss->getDoctorProfile();
			$data = [
				'title' => 'Transacciones',
			];
			$data = array_merge($data, $user, $profile);
			$data['user'] = (object) $user;
			$this->load->view('doctor/transactions', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}


	public function load_transactions() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getUser();
			$resp['ext'] = $user;
        	$sql = "SELECT * from ss_user_transactions where id_doctor = ".$user->doc->iddoctor;
        	$resp = $this->ss->execsql($sql);
        	$resp['sql'] = $sql;
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function updateprofile() {
		try {
			$user = $this->ss->getSessUser();
			$newsletter = $this->input->post('newsletter');
			if(strtolower($newsletter) == 'on') {
				$newsletter = 1;
			} else {
				$newsletter = 0;
			}
			$this->load->helper('upload_files');
			if(!$profileimage = uploadThumbnail()) {
				$profileimage = $this->input->post('profileimage');
			}
			$updb = $this->ss->update([
                'fullname' => $this->input->post('fullname'),
                'phonenumber' => $this->input->post('phonenumber'),
                'description' => $this->input->post('description'),
                'notisms' => $newsletter,
                'notiemail' => $newsletter,
                'notipush' => $newsletter,
                'profileimage' => $profileimage,
                'gender' => $this->input->post('gender'),
				'birthdate' => $this->ss->db_date($this->input->post('birthdate')),
				'address' => $this->input->post('address'),
				'idcity' => $this->input->post('idcity'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
            ], [
                'idusers' => $user->fd_iduser
            ], 'ss_users');
            if($updb['success'] && $updb['data'][0] > 0) {
            }

            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
				$updb = $this->ss->update([
	                'titulo' => $this->input->post('titulo'),
	                'especiality' => $this->input->post('especiality'),
	                'aboutme' => $this->input->post('description'),
	                'type_doc' => $this->input->post('type_doc'),
					'document' => $this->input->post('document'),
					'rethus' => $this->input->post('rethus'),
					'enferme_trat' => $this->input->post('enferme_trat'),
					'numhabi' => $this->input->post('numhabi'),
	            ], [
	                'idusers' => $user->fd_iduser
	            ], 'ss_doctor_users');
	        } else {
	        	$iddoctor = $this->ss->insert([
                    'idusers' => $user->fd_iduser,
                    'titulo' => $this->input->post('titulo'),
	                'especiality' => $this->input->post('especiality'),
	                'aboutme' => $this->input->post('description'),
	                'type_doc' => $this->input->post('type_doc'),
					'document' => $this->input->post('document'),
					'rethus' => $this->input->post('rethus'),
					'enferme_trat' => $this->input->post('enferme_trat'),
					'numhabi' => $this->input->post('numhabi'),
                ], 'ss_doctor_users', true);
	        	if($iddoctor['success']) {
                    $iddoctor = $iddoctor['data'][0];
                }
	        }
	        $user = $this->ss->getUser();
	        $this->session->set_userdata(['user' => $user]);
	        return redirect('Doctor/Profile');
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function goals() {
		try {
			$data = [
				'title' => trans('my_goals'),
				'user' => $this->ss->getUser(),
				'mygoals' => $this->ss->getDoctorGoals(),
			];
			$this->load->view('doctor/goals', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function chatStatuss() {
		try {
			$uri = $this->input->post('rediruri');
			$stats = $this->input->post('chatstatus');
			if(strtolower($stats) == 'on') {
				$stats = 1;
			} else {
				$stats = 0;
			}
			$user = $this->ss->getSessUser();
			$updb = $this->ss->update([
				'chat_activo' => $stats,
            ], [
                'idusers' => $user->fd_iduser
            ], 'ss_users');
            $user = $this->ss->getUser();
			$this->session->set_userdata(['user' => $user]);
			return redirect($uri);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
		//chatstatus
	}

	public function videoChatStatuss() {
		try {
			$uri = $this->input->post('rediruri');
			$stats = $this->input->post('chatstatus');
			if(strtolower($stats) == 'on') {
				$stats = 1;
			} else {
				$stats = 0;
			}
			$user = $this->ss->getSessUser();
			$updb = $this->ss->update([
				'video_chat_activo' => $stats,
            ], [
                'idusers' => $user->fd_iduser
            ], 'ss_users');
            $user = $this->ss->getUser();
			$this->session->set_userdata(['user' => $user]);
			return redirect($uri);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
		//chatstatus
	}

	public function chatStatuss2($id=0) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$updb = $this->ss->update([
				'chat_activo' => $id,
            ], [
                'idusers' => $user->fd_iduser
            ], 'ss_users');
            $user = $this->ss->getUser();
			$this->session->set_userdata(['user' => $user]);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'message' => '', 'data' => $updb]));
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
		//chatstatus
	}

	public function searchpatient($idtpdpc, $document) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if (!in_array($this->input->method(), ['get', 'post'])) {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = ['success' => false, 'data' => []];
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
				$resp = $this->ss->getCampos('ss_doctor_patients', [
					"iddoctor" => $passBD['data'][0]->iddoctor,
					"type_doc" => $idtpdpc, 
					"document" => $document,
					"status" => 1,
				], ['id', 'iddoctor', 'idusers', 'type_doc', 'document', 'name', 'address', 'idcity', 'description', 'status', 'create_at'
				]);
			}

			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
		//chatstatus
	}

	public function savegoal() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
		   	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
					$iddoctor = $this->ss->insert([
              'iddoctor' => $passBD['data'][0]->iddoctor,
              'titulo' => $this->input->post('titulo'),
              'fecha' => $this->ss->db_date($this->input->post('fecha')),
              'descripcion' => $this->input->post('description'),
          ], 'ss_doctor_logros', true);
          return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
	        
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function deletegoal() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
					$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_logros');
          return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getgoal() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT id, titulo, descripcion, fecha, content_mult, create_at FROM ss_doctor_logros WHERE status = 1 AND iddoctor = '$found->iddoctor' AND id = '$id';";
				$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
				//$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
	        
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updategoal() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
      		'titulo' => $this->input->input_stream('titulo'),
          'fecha' => $this->ss->db_date($this->input->input_stream('fecha')),
          'descripcion' => $this->input->input_stream('description'),
      	], ['id' => $id], 'ss_doctor_logros');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
	        
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function formation() {
		try {
			$data = [
				'title' => 'Mi Formación',
				'user' => $this->ss->getUser(),
				'myformation' => $this->ss->getDoctorFormation(),
			];
			$this->load->view('doctor/formation', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function saveformation() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
							$iddoctor = $this->ss->insert([
	                'iddoctor' => $passBD['data'][0]->iddoctor,
	                'institucion' => $this->input->post('institucion'),
	                'titulacion' => $this->input->post('titulacion'),
	                //'fecha' => $this->ss->db_date($this->input->post('fecha')),
	                'descripcion' => $this->input->post('descripcion'),
	            ], 'ss_doctor_formacion', true);
	            return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
	        	} else {
	        		return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
	        	}
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function deleteformation() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
					$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_formacion');
          return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getformation() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT id, iddoctor, institucion, titulacion, descripcion, content_mult, create_at FROM ss_doctor_formacion WHERE status = 1 AND iddoctor = '$found->iddoctor' AND id = '$id';";
				$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
				//$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
	        
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updateformation() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
      		'institucion' => $this->input->input_stream('institucion'),
          'titulacion' => $this->input->input_stream('titulacion'),
          'descripcion' => $this->input->input_stream('descripcion'),
      	], ['id' => $id], 'ss_doctor_formacion');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
	        
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function specialities() {
		try {
			$data = [
				'title' => 'Mis Especialidades',
				'user' => $this->ss->getUser(),
				'myspecialities' => $this->ss->getDoctorSpecialities(),
				'specialities' => $this->ss->getSpecilities(),
			];
			$this->load->view('doctor/specialities', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function savespecialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$idspeciality = $this->input->post('idspeciality');
      	$speciality = $this->input->post('speciality');
      	$specialitydes = $this->input->post('specialitydes');
      	if(empty($idspeciality) || $idspeciality <= 0) {
      		$special = $this->ss->insert([
            'speciality' => $speciality,
            'descrip' => $specialitydes,
            'tipo' => 'Clínica',
          ], 'ss_specilities', true);	
          $idspeciality = $special['data'][0];
      	}
				$iddoctor = $this->ss->insert([
	      	'iddoctor' => $passBD['data'][0]->iddoctor,
          'idspeciality' => $idspeciality,
	      ], 'ss_doctor_specilities', true);
	      return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
    	} else {
    		return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
    	}
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getspecialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT dsp.id idspectdoc, dsp.idspeciality, dsp.default, dsp.create_at, esp.speciality, esp.descrip FROM ss_doctor_specilities dsp INNER JOIN ss_specilities esp ON esp.id = dsp.idspeciality AND esp.status = 1 WHERE 1 AND iddoctor = '$found->iddoctor' AND dsp.id = '$id' ORDER BY dsp.default DESC, esp.speciality ASC;";
				$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
				//$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updatespecialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$idspeciality = $this->input->input_stream('idspeciality');
      	$speciality = $this->input->input_stream('speciality');
      	$specialitydes = $this->input->input_stream('specialitydes');
      	if(empty($idspeciality) || $idspeciality <= 0) {
      		$special = $this->ss->insert([
            'speciality' => $speciality,
            'descrip' => $specialitydes,
            'tipo' => 'Clínica',
          ], 'ss_specilities', true);	
          $idspeciality = $special['data'][0];
      	}
      	$iddoctor = $this->ss->update([
      		'idspeciality' => $idspeciality,
      	], ['id' => $id], 'ss_doctor_specilities');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function deletespecialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
					$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_specilities');
          return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function services() {
		try {
			$data = [
				'title' => 'Mis Servicios',
				'user' => $this->ss->getUser(),
				'myservices' => $this->ss->getDoctorServices(),
			];
			$this->load->view('doctor/services', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function saveservices() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
				$iddoctor = $this->ss->insert([
          'iddoctor' => $passBD['data'][0]->iddoctor,
          'service' => $this->input->post('service'),
          'type' => $this->input->post('type'),
          'amount_prepaid' => $this->input->post('amount_prepaid'),
          'description' => $this->input->post('description'),
          'duration' => $this->input->post('duration'),
          'amount' => $this->input->post('amount'),
        ], 'ss_doctor_services', true);
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
    	} else {
    		return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
    	}
		} catch (Exception $e) {
			$this->load->view('Error', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function getservices() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT id, iddoctor, type, service, description, duration, amount, amount_prepaid, status, create_at FROM ss_doctor_services WHERE status = 1 AND iddoctor = '$found->iddoctor' AND id = '$id' ORDER BY service ASC, create_at ASC;";
				$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
				//$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updateservices() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
          'type' => $this->input->input_stream('type'),
          'amount' => $this->input->input_stream('amount'),
      		'service' => $this->input->input_stream('service'),
          'duration' => $this->input->input_stream('duration'),
          'description' => $this->input->input_stream('description'),
          'amount_prepaid' => $this->input->input_stream('amount_prepaid'),
      	], ['id' => $id], 'ss_doctor_services');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function deleteservices() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
				//$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_services');
				$data = $this->ss->execsql("SELECT service FROM ss_doctor_services WHERE id = '$id';");
				$iddoctor = $this->ss->update([
          'status' => 127,
          'service' => $data['data'][0]->service.'(-eliminado-'.date('d/m/Y h:i:s a').'-)',
      	], ['id' => $id], 'ss_doctor_services');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	protected function getDocPatientChat() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
            	//$sql = "SELECT a.id, a.iddoctor, a.idusers as pat_iduser, a.ideps, a.type_doc as pat_type_docid, a.document as pat_document, a.name as pat_name, a.address as pat_address, a.idcity as pat_idcity, cty.name as pat_city, cty.idstate pat_idstate, sta.name pat_state, sta.idcountry as pat_id_country, ctt.name pat_country, up.profileimage as pat_profileimage, up.fullname as pat_user_fullname, a.description, a.status, a.create_at, ud.fullname as doc_name, ud.gender as doc_gender, ud.profileimage as doc_profileimage, doc.titulo as doc_titulo, doc.especiality doc_especiality, doc.idusers as doc_iduser FROM ss_doctor_patients AS a INNER JOIN ss_users as up ON up.idusers = a.idusers INNER JOIN ss_doctor_users as doc ON doc.iddoctor = a.iddoctor INNER JOIN ss_users as ud ON ud.idusers = doc.idusers LEFT JOIN ss_cities as cty ON cty.idcity = a.idcity LEFT JOIN ss_states as sta ON sta.idstate = cty.idstate LEFT JOIN ss_country as ctt ON sta.idcountry = ctt.idcountry WHERE a.status = 1 AND a.iddoctor = $iddoctor;";
            	//
            	$sql = "SELECT uf.idusers, uf.idusuario, uf.fullname, uf.email, uf.phonenumber, uf.profileimage, uf.idcity, cty.name as pat_city, cty.idstate pat_idstate, sta.name pat_state, sta.idcountry as pat_id_country, ctt.name pat_country, uf.gender FROM ss_chat_users AS a INNER JOIN ss_doctor_users doc ON doc.idusers = a.idusers_to INNER JOIN ss_users as uf ON uf.idusers = a.idusers_from LEFT JOIN ss_cities as cty ON cty.idcity = uf.idcity LEFT JOIN ss_states as sta ON sta.idstate = cty.idstate LEFT JOIN ss_country as ctt ON sta.idcountry = ctt.idcountry WHERE a.status = 1 AND doc.iddoctor = $iddoctor GROUP BY uf.idusers;";
            	$patients = $this->ss->execsql($sql);
            	if($patients['success']) {
            		return $patients['data'];
            	}
            	return [];
            }
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function docChat() {
		try {
			$data = [
				'title' => 'Mis Sala de Chat',
				'user' => $this->ss->getUser(),
				'allusers' => $this->getDocPatientChat(),
			];
			$this->load->view('doctor/chatbox', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function getChatMessages($idpatient, $flow=1, $lastId=0) {
		try {
			//
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->idusers;
            	$sql = "SELECT a.id, a.idusers_from, b.fullname fullname_from, b.profileimage profimg_from, a.idusers_to, c.fullname fullname_to, c.profileimage profimg_to, a.message, a.type, a.status, a.create_at, a.update_at, (a.idusers_from = '$iddoctor') as owner, a.unixtime FROM ss_chat_users as a LEFT JOIN ss_users b ON b.idusers = a.idusers_from LEFT JOIN ss_users c ON c.idusers = a.idusers_to WHERE a.idusers_from = '$idpatient' AND a.idusers_to = '$iddoctor' AND a.status > 0 AND a.id ".($flow > 0 ?' > ' : ' < ')."$lastId UNION ALL SELECT a2.id, a2.idusers_from, b2.fullname fullname_from, b2.profileimage profimg_from, a2.idusers_to, c2.fullname fullname_to, c2.profileimage profimg_to, a2.message, a2.type, a2.status, a2.create_at, a2.update_at, (a2.idusers_from = '$iddoctor') as owner, a2.unixtime FROM ss_chat_users as a2 LEFT JOIN ss_users b2 ON b2.idusers = a2.idusers_from LEFT JOIN ss_users c2 ON c2.idusers = a2.idusers_to WHERE a2.idusers_from = '$iddoctor' AND a2.idusers_to = '$idpatient' AND a2.status > 0 AND a2.id ".($flow > 0 ?' > ' : ' < ')."$lastId ORDER BY create_at ".($flow > 0 ?' ASC ' : ' DESC ').", update_at ".($flow > 0 ?' ASC ' : ' DESC ').";";
            	$resp = $this->ss->execsql($sql);
            	$resp['sql'] = $sql;
            }
			
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function sendChatMessages($idpatient, $type="text") {
		try {
			//
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->idusers;
            	$unixtime = $this->input->post('unixtime');
            	if(!isset($unixtime) || empty($unixtime)) {
            		$unixtime = time();
            	}
            	$msg = $this->ss->insert([
				    'idusers_from' => $iddoctor,
				    'idusers_to' => $idpatient,
				    'message' => $this->input->post('message'),
				    'unixtime' => $unixtime,
				    'type' => $type,
				], 'ss_chat_users');
				$id = $msg['data'][0];
            	$sql = "SELECT a.id, a.idusers_from, b.fullname fullname_from, b.profileimage profimg_from, a.idusers_to, c.fullname fullname_to, c.profileimage profimg_to, a.message, a.type, a.status, a.create_at, a.update_at, (a.idusers_from = '$iddoctor') as owner FROM ss_chat_users as a LEFT JOIN ss_users b ON b.idusers = a.idusers_from LEFT JOIN ss_users c ON c.idusers = a.idusers_to WHERE a.idusers_from = '$iddoctor' AND a.idusers_to = '$idpatient' AND a.status > 0 AND a.id = '$id';";
            	$resp = $this->ss->execsql($sql);
            	$resp['sql'] = $sql;
            }
			
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function doctor_epsseguros() {
		try {
			$data = [
				'title' => 'Mi Medicina Prepagada',
				'user' => $this->ss->getUser(),
				'myservices' => $this->ss->getDoctorEPSSeguros(),
				'allservices' => $this->ss->getEPSSeguros(),
			];
			$this->load->view('doctor/epsseguros', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function savedoctor_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$idseguroeps = $this->input->post('idseguroeps');
      	if(empty($idseguroeps) || $idseguroeps <= 0) {
      		$data = $this->ss->insert([
            'seguroeps' => $this->input->post('seguroeps'),
            'tipo' => 'eps',
          ], 'ss_eps_segur', true);
          $idseguroeps = $data['data'][0];
      	}
				$iddoctor = $this->ss->insert([
          'iddoctor' => $passBD['data'][0]->iddoctor,
          'ideps' => $idseguroeps,
        ], 'ss_doctor_epseguro', true);
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
    	} else {
    		return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
    	}
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function deletedoctor_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
				$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_epseguro');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function consultingroom() {
		try {
			$data = [
				'title' => 'Mis Consultorios',
				'user' => $this->ss->getUser(),
				'mycosulting' => $this->ss->getDoctorConsultorios(),
				'cities' => $this->ss->getCities(),
				'cnfcondultorios' => $this->ss->getConsultingRooms(),
			];
			$this->load->view('doctor/consultingroom', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function saveconsultingroom() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			 	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
				$iddoctor = $this->ss->insert([
	        'name' => $this->input->post('name'),
	        'address' => $this->input->post('address'),
	        'idcity' => $this->input->post('idcity'),
	        'phone1' => $this->input->post('phone1'),
	        'phone2' => $this->input->post('phone2'),
	        'email' => $this->input->post('email'),
	        'url' => $this->input->post('url'),
        ], 'ss_consultorio', true);
	      if($iddoctor['success'] && count($iddoctor['data'])) {
        	$iddoctor = $this->ss->insert([
            'iddoctor' => $passBD['data'][0]->iddoctor,
            'idconsult' => $iddoctor['data'][0],
          ], 'ss_doctor_consultorio', true);
          return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
	    	}
    		return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
	    }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function delconsultingroom() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$id = $this->input->input_stream('id');
				$iddoctor = $this->ss->delete(['id' => $id], 'ss_doctor_consultorio');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }   
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function settingsdoc() {
		try {
			$data = [
				'title' => 'Mis Configuraciones',
				'user' => $this->ss->getUser(),
				'datei' => '08:00',
				'datef' => '17:00',
				'jobplace' => 'consultorio',
			];
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
				$sql = "SELECT time_start datei, time_end datef, jobplace FROM ss_doctor_users WHERE iddoctor = '$iddoctor';";
				$datadb = $this->ss->execsql($sql);
	            if($datadb['success'] && count($datadb['data']) > 0) {
	                $data['datei'] = $datadb['data'][0]->datei;
	                $data['datef'] = $datadb['data'][0]->datef;
	                $data['jobplace'] = $datadb['data'][0]->jobplace;
	            }
	        }
			$this->load->view('doctor/settingsdoc', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function savesettingsdoc() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
				$iddoctor = $this->ss->update([
	                'time_start' => $this->input->post('datei'),
	                'time_end' => $this->input->post('datef'),
	                'jobplace' => $this->input->post('jobplace'),
	            ], [
	                'iddoctor' => $passBD['data'][0]->iddoctor,
	            ], 'ss_doctor_users', true);
				$iddoctor = $this->ss->update([
	                'med_prepagado' => strtolower($this->input->post('med_prepagado')) == 'on' ? 1 : 0,
	                'med_domicilio' => strtolower($this->input->post('med_domicilio')) == 'on' ? 1 : 0,
	            ], [
	                'idusers' => $user->fd_iduser
	            ], 'ss_users', true);
	            if($iddoctor['success']) {
	            	return redirect('Doctor/Settings');
	            }
	        }
	        return redirect('Doctor/Settings');
		} catch (Exception $e) {
			$this->load->view('Error', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function conf_consulting() {
		try {
			$data = [
				'title' => 'Configuración de Consultorios',
				'user' => $this->ss->getUser(),
				'consultingrooms' => $this->ss->getConsultingRooms(),
				'cities' => $this->ss->getCities(),
			];
			$this->load->view('configs/consultingrooms', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function conf_consultingDt() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'get') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	//$found = $passBD['data'][0];
      	$sql = "SELECT a.*, cty.name as city, cts.name as state, ctt.name as country FROM ss_consultorio as a LEFT JOIN ss_cities cty ON cty.idcity = a.idcity LEFT JOIN ss_states AS cts ON cts.idstate = cty.idstate LEFT JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry WHERE 1 ORDER BY a.name ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if(!$iddoctor['success']) {
					$this->output->set_status_header(401);
				}
				$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
      $this->output->set_status_header(403);
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			$this->output->set_status_header(401);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function saveconf_consulting() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			 	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$iddoctor = $this->ss->insert([
        'name' => $this->input->post('name'),
        'address' => $this->input->post('address'),
        'idcity' => $this->input->post('idcity'),
        'phone1' => $this->input->post('phone1'),
        'phone2' => $this->input->post('phone2'),
        'email' => $this->input->post('email'),
        'url' => $this->input->post('url'),
        'latitud' => $this->input->post('latitude'),
        'longitud' => $this->input->post('longitude'),
      ], 'ss_consultorio', true);
      return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getconf_consulting() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT a.*, cty.name as city, cts.name as state, ctt.name as country FROM ss_consultorio as a LEFT JOIN ss_cities cty ON cty.idcity = a.idcity LEFT JOIN ss_states AS cts ON cts.idstate = cty.idstate LEFT JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry WHERE id = '$id' ORDER BY a.name ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
				$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updateconf_consulting() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
          'name' => $this->input->input_stream('name'),
          'address' => $this->input->input_stream('address'),
      		'idcity' => $this->input->input_stream('idcity'),
          'phone1' => $this->input->input_stream('phone1'),
          'phone2' => $this->input->input_stream('phone2'),
          'email' => $this->input->input_stream('email'),
          'url' => $this->input->input_stream('url'),
          'latitud' => $this->input->input_stream('latitud'),
          'longitud' => $this->input->input_stream('longitud'),
      	], ['id' => $id], 'ss_consultorio');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function localtion($country=null, $state=null, $city=null) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			$where = '';
			if(isset($country) && !empty($country)) {
				$country = urldecode($country);
				$where .= "AND c.name LIKE '%$country%' ";
			}
			if(isset($state) && !empty($state)) {
				$state = urldecode($state);
				$where .= "AND b.name LIKE '%$state%' ";
			}
			if(isset($city) && !empty($city)) {
				$city = urldecode($city);
				$where .= "AND a.name LIKE '%$city%' ";
			}
			$sql = "SELECT a.idcity as id, concat(a.name,', ', b.name, ', ', c.name ) item, a.idcity, a.name city, a.descript, a.latitude, a.longitude, a.idstate, b.name state, b.idcountry, c.name country FROM ss_cities a INNER JOIN ss_states b ON b.idstate = a.idstate AND b.status = 1 INNER JOIN ss_country c ON c.idcountry = b.idcountry AND c.status = 1 WHERE a.status = 1 $where ORDER BY c.name ASC, b.name ASC, a.name ASC;";
			$datadb = $this->ss->execsql($sql);
			$datadb['sql'] = $sql;
      return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($datadb));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function appoinments() {
		try {
			$data = [
				'title' => 'Calendario',
				'user' => $this->ss->getUser(),
				'profile' => $this->ss->getDoctorSettings(),
				'docservices' => $this->ss->getDoctorServices2(),
				'typedocs' => $this->ss->getTypeDocs(),
			];
			$this->load->view('doctor/appoinments', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function seekPatiend($in=null) {
		try {
			$passBD = $this->ss->getCampos('ss_doctor_patients', ["iddoctor" => $in->iddoctor, 'id' => $in->idpatient]);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $this->ss->update([
					'iddoctor' => $in->iddoctor,
					'type_doc' => $in->type_doc,
					'document' => $in->document,
					'name' => $in->name,
	            ], ['id' => $in->idpatient], 'ss_doctor_patients');
	            $in->id = $in->idpatient;
            	return (object) $in; //$passBD['data'][0];
            } else {
            	$passBD2 = $this->ss->getCampos('ss_doctor_patients', ["iddoctor" => $in->iddoctor, 'type_doc' => $in->type_doc, 'document' => $in->document]);
            	if($passBD2['success'] && count($passBD2['data'])>0) {
	            	return (object) $passBD2['data'][0];
	            }
            }
            $passBD = $this->ss->insert([
            	'iddoctor' => $in->iddoctor,
				'type_doc' => $in->type_doc,
				'document' => $in->document,
				'name' => $in->name,
				'address' => 'Sin Especificar',
            ], 'ss_doctor_patients');
            $in->idpatient = $passBD['data'][0];
            $in->id = $passBD['data'][0];
            return $in;
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function saveappoinments() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$patient = $this->seekPatiend((object)[
            		'idpatient' => $this->input->post('idpatient'),
            		'name' => $this->input->post('patienname'),
            		'type_doc' => $this->input->post('type_doc'),
            		'document' => $this->input->post('document'),
            		'iddoctor' => $passBD['data'][0]->iddoctor,
            	]);
            	$serv = $this->ss->getDoctorServices2($this->input->post('idservice'));
            	$ndat = $this->ss->db_date($this->input->post('apment_date')).' '.$this->input->post('start_at');
            	$title = $serv[0]->service.' &mdash; '.$patient->name;
            	$time = new DateTime($ndat);
            	$time->add(new DateInterval('PT' . $this->input->post('duration') . 'M'));
            	//$time->add(new DateInterval('PT' . $serv[0]->duration . 'M'));
            	$ndat = $time->format('H:i');
				$iddoctor = $this->ss->insert([
					'iddoctor' => $passBD['data'][0]->iddoctor,
	                'idpatient' => $patient->id,
	                'idservice' => $this->input->post('idservice'),
	                'apment_date' => $this->input->post('apment_date'),
	                'start_at' => $this->input->post('start_at'),
	                'end_at' => $ndat,
	                'title' => $title,
	                'description' => $this->input->post('details'),
	                'amount' => $this->input->post('amount'),
	                'status' => $this->input->post('status'),
	                'prepaid' => $this->input->post('prepaid'),
	                'duration' => $this->input->post('duration'),
	            ], 'ss_doctor_appointments', true);
	            if($iddoctor['success'] && count($iddoctor['data'])) {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'data' => $this->ss->getDocAppoinments($iddoctor['data'][0])[0]]));
	            } else {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'No Se Pudo agendar']));
	            }
	        }
	        return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'Usuario No encontrado']));
		} catch (Exception $e) {
			return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}	

	public function updappoinments($id= -1) {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$patient = $this->seekPatiend((object)[
            		'idpatient' => $this->input->input_stream('idpatient'),
            		'name' => $this->input->input_stream('name'),
            		'type_doc' => $this->input->input_stream('type_doc'),
            		'document' => $this->input->input_stream('document'),
            		'iddoctor' => $passBD['data'][0]->iddoctor,
            	]);
            	$serv = $this->ss->getDoctorServices2($this->input->input_stream('idservice'));
            	$ndat = $this->ss->db_date($this->input->input_stream('apment_date')).' '.$this->input->input_stream('start_at');
            	$title = $serv[0]->service.' &mdash; '.$patient->name;
            	$time = new DateTime($ndat);
            	$time->add(new DateInterval('PT' . $this->input->input_stream('duration') . 'M'));
            	//$time->add(new DateInterval('PT' . $serv[0]->duration . 'M'));
            	$ndat = $time->format('H:i');
            	$iddoctor = $this->ss->update([
					'iddoctor' => $passBD['data'][0]->iddoctor,
	                'idpatient' => $patient->id,
	                'idservice' => $this->input->input_stream('idservice'),
	                'apment_date' => $this->input->input_stream('apment_date'),
	                'start_at' => $this->input->input_stream('start_at'),
	                'end_at' => $ndat,
	                'title' => $title,
	                'description' => $this->input->input_stream('details'),
	                'amount' => $this->input->input_stream('amount'),
	                'prepaid' => $this->input->input_stream('prepaid'),
	                'status' => $this->input->input_stream('status'),
	                'duration' => $this->input->input_stream('duration'),
	            ], ['id' => $id], 'ss_doctor_appointments');
	            if($iddoctor['success'] && count($iddoctor['data'])) {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'data' => $this->ss->getDocAppoinments($id)[0]]));
	            } else {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'No Se Pudo agendar']));
	            }
	        }
	        return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'Usuario No encontrado']));
		} catch (Exception $e) {
			return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function deleteappoinments($id= -1) {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	/*$iddoctor = $this->ss->delete([
            		'id' => $id], 'ss_doctor_appointments', false, true);*/
            	$iddoctor = $this->ss->update([
					'status' => '127',
	            ], ['id' => $id], 'ss_doctor_appointments');
	            if($iddoctor['success'] && count($iddoctor['data'])) {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
	            } else {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'No Se Pudo agendar']));
	            }
	        }
	        return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'Usuario No encontrado']));
		} catch (Exception $e) {
			return $this->output->set_status_header(403)->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function getDocAppoints() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'get') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'data' => $this->ss->getDocAppoinments()]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function setdoc_epsseguros($status) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}

			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $this->ss->update([
					'med_prepagado' => $status == 'true',
	            ], ['idusers' => $user->fd_iduser], 'ss_users');
	            if($iddoctor['success'] && count($iddoctor['data'])) {
	            	if($status == 'false') {
	            		$this->ss->delete([
	            			'iddoctor' => $passBD['data'][0]->iddoctor
	            		], 'ss_doctor_epseguro');
	            	}
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
	            } else {
	            	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => 'No Se Pudo agendar']));
	            }
	        }
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => true, 'data' => $passBD, 'stat' => $status]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function conf_specialities() {
		try {
			$data = [
				'title' => 'Configuración de Especialidades',
				'user' => $this->ss->getUser(),
			];
			$this->load->view('configs/specialities', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function conf_specialitiesDt() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'get') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	//$found = $passBD['data'][0];
      	$sql = "SELECT a.id, a.speciality as item, a.speciality, a.descrip, a.tipo, a.status FROM ss_specilities a WHERE status = 1 ORDER BY a.speciality ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if(!$iddoctor['success']) {
					$this->output->set_status_header(401);
				}
				$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
      $this->output->set_status_header(403);
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			$this->output->set_status_header(401);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function saveconf_specialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			 	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$iddoctor = $this->ss->insert([
        'speciality' => $this->input->post('speciality'),
        'descrip' => $this->input->post('descrip'),
        'tipo' => $this->input->post('tipo'),
      ], 'ss_specilities', true);
      return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getconf_specialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT a.id, a.speciality as item, a.speciality, a.descrip, a.tipo, a.status FROM ss_specilities a WHERE a.id = '$id' ORDER BY a.speciality ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updateconf_specialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
          'speciality' => $this->input->input_stream('speciality'),
        	'descrip' => $this->input->input_stream('descrip'),
        	'tipo' => $this->input->input_stream('tipo'),
      	], ['id' => $id], 'ss_specilities');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function deleteconf_specialities() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->delete(['id' => $id], 'ss_specilities');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function conf_epsseguros() {
		try {
			$data = [
				'title' => 'Configuración de EPS Prepagadas - Seguros',
				'user' => $this->ss->getUser(),
			];
			$this->load->view('configs/epsseguros', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function conf_epssegurosDt() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'get') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	//$found = $passBD['data'][0];
      	$sql = "SELECT a.id, a.seguroeps as item, a.seguroeps, a.tipo, a.status FROM ss_eps_segur a WHERE status = 1 ORDER BY a.seguroeps ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if(!$iddoctor['success']) {
					$this->output->set_status_header(401);
				}
				$iddoctor['sql'] = $sql;
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
      $this->output->set_status_header(403);
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			$this->output->set_status_header(401);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function saveconf_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			 	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$iddoctor = $this->ss->insert([
        'seguroeps' => $this->input->post('seguroeps'),
        'tipo' => $this->input->post('tipo'),
      ], 'ss_eps_segur', true);
      return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function getconf_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$sql = "SELECT a.id, a.seguroeps as item, a.seguroeps, a.tipo, a.status FROM ss_eps_segur a WHERE a.id = '$id' ORDER BY a.seguroeps ASC;";
      	$iddoctor = $this->ss->execsql($sql);
				if($iddoctor['success'] && count($iddoctor['data'])>0) {
					$iddoctor['data'] = $iddoctor['data'][0];
					//$iddoctor['data']->fecha = $this->ss->dbdate_2fr($iddoctor['data']->fecha);
				}
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      }
    	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'd1' => $passBD, 'd2' => $user,'message' => trans('msg_err_record_404'), 'Err' => -2]));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function updateconf_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	$iddoctor = $this->ss->update([
          'seguroeps' => $this->input->input_stream('seguroeps'),
        	'tipo' => $this->input->input_stream('tipo'),
      	], ['id' => $id], 'ss_eps_segur');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	public function deleteconf_epsseguros() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'delete') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_users', ["idusers" => $user->fd_iduser], ['idusers']);
      if($passBD['success'] && count($passBD['data'])>0) {
      	$found = $passBD['data'][0];
      	$id = $this->input->input_stream('id');
      	//$iddoctor = $this->ss->delete(['id' => $id], 'ss_eps_segur');
      	$iddoctor = $this->ss->update([
          'status' => 127,
      	], ['id' => $id], 'ss_eps_segur');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($iddoctor));
      } else {
      	return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_record_404'), 'Err' => -2]));
      }
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'Err' => -2]));
		}
	}

	protected function getPatientsAppDoc() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
            	$sql = "SELECT a.id, a.iddoctor, a.ideps, ps.seguroeps, us.appdoctype type_docid, dt.name type_doc, us.email, us.phonenumber, us.appdocument as document, us.fullname as name, a.address, us.idcity, cty.name as city, cts.name state, ctt.name country, a.description, a.status, a.create_at FROM ss_doctor_patients as a INNER JOIN ss_users as us on us.idusers = a.idusers LEFT JOIN ss_eps_segur ps ON ps.id = a.ideps LEFT JOIN doctypes dt ON dt.id = us.appdoctype LEFT JOIN ss_cities cty ON cty.idcity = us.idcity LEFT JOIN ss_states AS cts ON cts.idstate = cty.idstate AND cts.status = 1 LEFT JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry AND ctt.status = 1 WHERE a.status = 1 AND a.iddoctor = '$iddoctor' ORDER BY a.name ASC;";
            	return $this->ss->execsql($sql)['data'];
            }
		} catch (Exception $e) {
			return [];
		}
	}

	protected function getPatientsDoc() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
            	$sql = "SELECT a.id, a.iddoctor, a.ideps, ps.seguroeps, a.type_doc type_docid, dt.name type_doc, a.document, a.name, a.address, a.idcity, cty.name as city, cts.name state, ctt.name country, a.description, a.status, a.create_at FROM ss_doctor_patients as a LEFT JOIN ss_eps_segur ps ON ps.id = a.ideps LEFT JOIN doctypes dt ON dt.id = a.type_doc LEFT JOIN ss_cities cty ON cty.idcity = a.idcity LEFT JOIN ss_states AS cts ON cts.idstate = cty.idstate AND cts.status = 1 LEFT JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry AND ctt.status = 1 WHERE a.status = 1 AND a.iddoctor = '$iddoctor' AND a.idusers IS NULL ORDER BY a.name ASC;";
            	return $this->ss->execsql($sql)['data'];
            }
		} catch (Exception $e) {
			return [];
		}
	}

	public function mypatientes() {
		try {
			$data = [
				'title' => 'Mis Pacientes',
				'user' => $this->ss->getUser(),
				'mypatientes' => $this->getPatientsDoc(),
				'typedocs' => $this->ss->getTypeDocs(),
				'epses' => $this->ss->getEPSSeguros(),
				//'countries' => $this->ss->getCountries(),
				'cities' => $this->ss->getCities(),
			];
			$this->load->view('doctor/mypatientes', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function mypatientesapp() {
		try {
			$data = [
				'title' => 'Mis Pacientes de la Plataforma',
				'user' => $this->ss->getUser(),
				'mypatientes' => $this->getPatientsAppDoc(),
				//'typedocs' => $this->ss->getTypeDocs(),
				//'epses' => $this->ss->getEPSSeguros(),
				//'countries' => $this->ss->getCountries(),
				//'cities' => $this->ss->getCities(),
			];
			$this->load->view('doctor/mypatientesapp', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function savepatient() {
		try {
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
				$patient = $this->ss->insert([
	                'iddoctor' => $iddoctor,
	                'type_doc' => $this->input->post('type_doc'),
	                'document' => $this->input->post('document'),
	                'document' => $this->input->post('document'),
	                'name' => $this->input->post('patienname'),
	                'address' => $this->input->post('address'),
	                'idcity' => $this->input->post('idcity'),
	                'description' => 'Ninguna',
	            ], 'ss_doctor_patients', true);
	            if($patient['success'] && count($patient['data'])) {
	            } else {
	            	$this->session->set_flashdata('message_error', 'No Se Pudo agregar');
	            }
		    }
        	return redirect('Doctor/Patients');
		} catch (Exception $e) {
			$this->load->view('Error', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function admin_doctors() {
		try {
			$data = [
				'title' => 'Doctores Registrados',
				'user' => $this->ss->getUser(),
				'typedocs' => $this->ss->getTypeDocs(),
				'countries' => $this->ss->getCountries(),
				'cities' => $this->ss->getCities(),
			];
			$this->load->view('configs/admdoctros', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function admin_lstdoctors() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
        	$sql = "SELECT a.idusers, a.idusuario, doc.iddoctor,a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, doc.type_doc, dd.name type_docname, dd.abrev type_docabrev, doc.document, doc.rethus, doc.languages, doc.titulo, doc.aboutme, doc.especiality, doc.enferme_trat, doc.time_start, doc.time_end, doc.content_mult, doc.rating, doc.verified, a.notisms, a.notiemail, a.notipush, a.newsletter, a.reg_date, a.med_domicilio, a.med_prepagado, a.chat_activo, a.update_date, a.profile as idprofile, e.profilename, a.statuscode idstatus, f.name as status, doc.status as docidstatus, fd.name statusdoc, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3 FROM ss_users as a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 INNER JOIN ss_profiles as e ON e.idprofile = a.profile AND e.statuscode = 1 INNER JOIN statuscodes as f ON f.id = a.statuscode INNER JOIN ss_doctor_users as doc ON doc.idusers = a.idusers AND doc.status = a.statuscode LEFT JOIN statuscodes as fd ON fd.id = doc.status LEFT JOIN doctypes as dd ON dd.id = doc.type_doc WHERE 1 AND a.profile = '2';";
        	$resp = $this->ss->execsql($sql);
        	$resp['sql'] = $sql;
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function getDoctorData() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'patch') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
			$id = $this->input->input_stream('id');
    	$sql = "SELECT a.idusers, a.idusuario, doc.iddoctor,a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, doc.type_doc, dd.name type_docname, dd.abrev type_docabrev, doc.document, doc.rethus, doc.languages, doc.titulo, doc.aboutme, doc.especiality, doc.enferme_trat, doc.time_start, doc.time_end, doc.content_mult, doc.rating, doc.verified, a.notisms, a.notiemail, a.notipush, a.newsletter, a.reg_date, a.med_domicilio, a.med_prepagado, a.chat_activo, a.update_date, a.profile as idprofile, e.profilename, a.latitude, a.longitude, a.statuscode idstatus, f.name as status, doc.status as docidstatus, fd.name statusdoc, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3, doc.numhabi FROM ss_users as a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 INNER JOIN ss_profiles as e ON e.idprofile = a.profile AND e.statuscode = 1 INNER JOIN statuscodes as f ON f.id = a.statuscode INNER JOIN ss_doctor_users as doc ON doc.idusers = a.idusers AND doc.status = a.statuscode LEFT JOIN statuscodes as fd ON fd.id = doc.status LEFT JOIN doctypes as dd ON dd.id = doc.type_doc WHERE 1 AND a.profile = '2' AND doc.iddoctor = '$id';";
    	$resp = $this->ss->execsql($sql);
    	if($resp['success'] && count($resp['data'])) {
    		$resp['data'] = $resp['data'][0];
    	} else {
    		$resp['data'] = false;
    	}
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function putDoctorData() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'put') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$user = $this->ss->getSessUser();
				$iddoctor = $this->input->input_stream('id');
				$idusers = $this->input->input_stream('idusers');
				$idcity = $this->input->input_stream('idcity');
				$idcountry = $this->input->input_stream('idcountry');
				if(empty($idcity) || $idcity <= 0) {
					$idcity = $this->input->input_stream('idcity');
					$countryname = $this->input->input_stream('countryname');
					$cityname = explode(',', $this->input->input_stream('idcity'));
					$resp = $this->ss->execsql("SELECT idcity FROM ss_cities WHERE name = '".$cityname[0]."';");
					if(count($resp['data'])>0) {
						$idcity = $resp['data'][0]->idcity;
					} else {
						$resp = $this->ss->seekAddCities($countryname, $cityname[1], $cityname[0]);
					}
				}

					
      	$res = $this->ss->update([
          'fullname' => $this->input->input_stream('fullname'),
          'appdoctype' => $this->input->input_stream('type_doc'),
          'appdocument' => $this->input->input_stream('document'),
          'gender' => $this->input->input_stream('gender'),
          'birthdate' => $this->input->input_stream('birthdate'),
          'phonenumber' => $this->input->input_stream('phonenumber'),
          'idcity' => $idcity,
          'address' => $this->input->input_stream('address'),
          'description' => $this->input->input_stream('aboutme'),
      	], ['idusers' => $idusers], 'ss_users');
      	$res['data2'] = $this->ss->update([
      		'titulo' => $this->input->input_stream('titulo'),
          'type_doc' => $this->input->input_stream('type_doc'),
          'document' => $this->input->input_stream('document'),
          'rethus' => $this->input->input_stream('rethus'),
          'especiality' => $this->input->input_stream('especiality'),
          'enferme_trat' => $this->input->input_stream('enferme_trat'),
          'aboutme' => $this->input->input_stream('aboutme'),
      	], ['iddoctor' => $iddoctor], 'ss_doctor_users');
        return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($res));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage(), 'data' => null]));
		}
	}

	public function admin_setdoctors($status) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => true,
				'data' => [],
				'message' => '',
			];
			$idusers = $this->input->post('idusers');
			$iddoctor = $this->input->post('iddoctor');
        	$resp['data'][] = $this->ss->update(['statuscode' => $status], ['idusers' => $idusers], 'ss_users');
        	$resp['data'][] = $this->ss->update(['status' => $status], ['iddoctor' => $iddoctor], 'ss_doctor_users');
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	//admin_reqpays
	public function admin_reqpays() {
		try {
			$data = [
				'title' => 'Solicitudes de pagos',
				'user' => $this->ss->getUser()
			];
			$this->load->view('configs/admreqdoctros', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function admin_lstreqpays() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
        	$sql = "SELECT a.idusers, a.idusuario, doc.iddoctor,a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, doc.type_doc, dd.name type_docname, dd.abrev type_docabrev, doc.document, doc.rethus, doc.languages, doc.titulo, doc.aboutme, doc.especiality, doc.enferme_trat, doc.time_start, doc.time_end, doc.content_mult, doc.rating, doc.verified, a.notisms, a.notiemail, a.notipush, a.newsletter, a.reg_date, a.med_domicilio, a.med_prepagado, a.chat_activo, a.update_date, a.profile as idprofile, e.profilename, a.statuscode idstatus, f.name as status, doc.status as docidstatus, fd.name statusdoc, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3 FROM ss_users as a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 INNER JOIN ss_profiles as e ON e.idprofile = a.profile AND e.statuscode = 1 INNER JOIN statuscodes as f ON f.id = a.statuscode INNER JOIN ss_doctor_users as doc ON doc.idusers = a.idusers AND doc.status = a.statuscode LEFT JOIN statuscodes as fd ON fd.id = doc.status LEFT JOIN doctypes as dd ON dd.id = doc.type_doc WHERE 1 AND a.profile = '2';";
        	$resp = $this->ss->execsql($sql);
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	//admin_patients
	public function admin_patients() {
		try {
			$data = [
				'title' => 'Pacientes Registrados en la App',
				'user' => $this->ss->getUser()
			];
			$this->load->view('configs/admregappatients', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function admin_lstpatients() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
        	$sql = "SELECT a.idusers, a.idusuario, a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, a.notisms, a.notiemail, a.notipush, a.newsletter, a.reg_date, a.appdoctype as idtypedoc, dd.name type_docname, dd.abrev type_docabrev, a.appdocument as document, a.med_domicilio, a.med_prepagado, a.chat_activo, a.update_date, a.profile as idprofile, e.profilename, a.statuscode idstatus, f.name as status, a.idcity, b.name as city, b.idstate, c.name as state, c.idcountry, d.name as country, d.iso2, d.iso3 FROM ss_users as a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 INNER JOIN ss_profiles as e ON e.idprofile = a.profile AND e.statuscode = 1 INNER JOIN statuscodes as f ON f.id = a.statuscode LEFT JOIN doctypes as dd ON dd.id = a.appdoctype WHERE 1 AND a.profile = '1';";
        	$resp = $this->ss->execsql($sql);
        	$resp['sql'] = $sql;
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function admin_setpatientstat($status) {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => true,
				'data' => [],
				'message' => '',
			];
			$idusers = $this->input->post('idusers');
        	$resp['data'][] = $this->ss->update(['statuscode' => 0], ['idusers' => $idusers], 'ss_users');
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	//admin_nopatients
	public function admin_nopatients() {
		try {
			$data = [
				'title' => 'Pacientes <b><u>NO</u></b> Registrados en la App',
				'user' => $this->ss->getUser()
			];
			$this->load->view('configs/admnoregappatients', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function admin_lstnopatients() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => false,
				'data' => [],
				'message' => '',
			];
			$user = $this->ss->getSessUser();
			$sql = "SELECT a.id, a.iddoctor, a.idusers, a.ideps, a.type_doc as idtypedoc, dd.name type_docname, dd.abrev type_docabrev, a.document, a.name, a.address, a.idcity, b.name as city, b.idstate, c.name as state, c.idcountry, d.name as country, d.iso2, d.iso3, a.description, a.status, a.create_at FROM ss_doctor_patients a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 LEFT JOIN doctypes as dd ON dd.id = a.type_doc WHERE a.idusers is null";
        	$resp = $this->ss->execsql($sql);
        	$resp['sql'] = $sql;
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	//valorations
	public function valorations() {
		try {
			$data = [
				'title' => 'Valoraciones',
				'user' => $this->ss->getUser()
			];
			$this->load->view('doctor/comments', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function get_valorations() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => true,
				'data' => $this->ss->getDoctorRatings()['comments'],
				'message' => '',
			];
			
        	
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function chatlastdocs() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$users = $this->input->post('users');
			$resp = [
				'success' => true,
				'data' => $users,
				'message' => '',
			];
			$where = "";
			foreach ($users as $keyu => $valueu) {
				$where.= (!empty($where)?', ':'')."'$keyu'";;
			}
			$where = " AND idusers_from IN($where)";
			$user = $this->ss->getSessUser();
			$passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $user->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
            	$iddoctor = $passBD['data'][0]->iddoctor;
				$sql ="SELECT idusers_from, max(unixtime) unixtime FROM ss_chat_users WHERE idusers_to = $user->fd_iduser $where GROUP BY idusers_from ORDER BY idusers_from;";

				$resp['sql']=$sql;
				$data = $this->ss->execsql($sql)['data'];
				foreach ($data as $value) {
					$resp['data'][$value->idusers_from] = $value->unixtime;
				}
			}
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function DocPatientChat() {
		try {
			if (!$this->input->is_ajax_request()) {
			   $this->session->set_flashdata('message_error', 'Tipo de solicitud no permitida');
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'message' => trans('msg_err_invalid_request'), 'Err' => -1]));
			}
			if ($this->input->method() != 'post') {
			   return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [],'message' => trans('msg_err_invalid_method'), 'Err' => -2]));
			}
			$resp = [
				'success' => true,
				'data' => $this->getDocPatientChat(),
				'message' => '',
			];
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode($resp));
		} catch (Exception $e) {
			return $this->output->set_content_type('application/json','uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]));
		}
	}

	public function saveAppointmentsApp()
  {

    try {

      $data = json_decode(file_get_contents("php://input"));

      if ($data && $data->appointment) {
        $data = $data->appointment;
      }

      $hour = $data->hours . ':' . $data->minute . ':' . '00';

      $hour_end = $data->hours_end . ':' . $data->minute_end . ':' . '00';

      

      $passBD = $this->ss->insert([
        //'iddoctor' => $data->id_doctor,
        'iddoctor' => 8,

        'document' => $data->document,
        'name' => $data->name_patient,
        'address' => 'Sin Especificar',
      ], 'ss_doctor_patients');


      $idpatient = $passBD['data'][0];

      if ($idpatient) {

        $iddoctor = $this->ss->insert([
          //'iddoctor' => $passBD['data'][0]->iddoctor,
          //'iddoctor' => $data->id_doctor,
          'iddoctor' => 8,
          //'idpatient' => $patient->id,
          'idpatient' => $idpatient,
          'apment_date' => $data->date_appoint,
          //'apment_date' => '2021-04-19',				
          'start_at' => $hour,
          'end_at' => $hour_end,          
          'status' => 1,

        ], 'ss_doctor_appointments', true);
      }

      if($iddoctor){
        $res['data'] = $passBD;
        $res['success'] = true;
        $res['message'] = "Agendado correctamente";
        
      }

     

    } catch (Exception $e) {
      return $this->output->set_status_header(403)->set_content_type('application/json', 'uft-8')->set_output(json_encode(['success' => false, 'data' => [], 'heading' => 'Error ' . $e->getCode(), 'message' => $e->getMessage()]));
    }

    finally {
      // echo json_encode($res);
      echo json_encode($iddoctor);
    }
  }

	public function preSchedule() {
		try {

			$idapp = $this->saveAppointmentsApp();

			$this->ss->getPreSchedule($idapp);

			$data = [
				//'mypre_schedule' => $this->ss->getPreSchedule($idapp)

			];
			// $data = [
			// 	'title' => 'Mis Consultorios',
			// 	'user' => $this->ss->getUser(),
			// 	'mycosulting' => $this->ss->getDoctorConsultorios(),
			// 	'cities' => $this->ss->getCities(),
			// 	'cnfcondultorios' => $this->ss->getConsultingRooms(),
			// ];
			// $this->load->view('doctor/consultingroom', $data);
			$this->load->view('doctor/pre-schedule');
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}
}
