<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {
	public function __construct() {
        parent::__construct();
        if(!$this->ss->validSession()) {
        	redirect('Auth/Login');
        }
        $this->output->delete_cache();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
    }

	public function index() {
		try {
			//Configuraciones/TipoContratos
			$data = [
				'title' => 'Usuarios',
				'user' => $this->ss->getUser(),
			];
			$this->load->view('users', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

    public function datatable($args = []) {
    	try {
    		$emps = $this->ss->getCamposAndJoin('ss_users AS a', [
    			'a.iduser !=' => '0'
    		], [
    			['ss_cities as b'],
    			['ss_states as c'],
    			['ss_country as d'],
    			['ss_profiles as e', 'INNER'],
    			['statuscodes AS f', 'INNER'],
    		], [
    			"b.idcity = a.idcity AND b.status = 1",
    			"c.idstate = b.idstate AND c.status = 1",
    			"d.idcountry = c.idcountry AND d.status = 1",
    			"e.idprofile = a.profile AND e.status = 1",
    			"f.id = a.status",
    		], "a.iduser, a.idusuario, a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, a.notisms, a.notiemail, a.notipush, a.reg_date, a.update_date, a.profile as idprofile, e.profilename, a.status idstatus, f.name status, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3, '' as actions ", 0, NULL, ['a.fullname', 'asc'],null);
    		$emps['args'] = $args;
    		return $this->output->set_content_type('application/json')
        	->set_output(json_encode($emps));
    	} catch (Exception $e) {
    		return [
    			'succeess' => false,
    			'data' => [],
    			'message' => $e->getMessage(),
    		];
    	}
	}

	/*public function agregar() {
		try {
			$data = [
				'title' => 'Agregar Usuario',
				'user' => $this->ss->getUser(),
				'urlForm' => 'Usuarios/Agregar',
			];
			$this->load->view('users/add', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}*/

	public function editar($id = -1) {
		try {
			//echo print_r(['editar' => $id], true).'<hr>';
			//die();
			$data = $this->ss->getCamposAndJoin('ss_users AS a', [
    			'a.iduser' => $id
    		], [
    			['ss_cities as b'],
    			['ss_states as c'],
    			['ss_country as d'],
    			['ss_profiles as e', 'INNER'],
    			['statuscodes AS f', 'INNER'],
    		], [
    			"b.idcity = a.idcity AND b.status = 1",
    			"c.idstate = b.idstate AND c.status = 1",
    			"d.idcountry = c.idcountry AND d.status = 1",
    			"e.idprofile = a.profile AND e.status = 1",
    			"f.id = a.status",
    		], "a.iduser, a.idusuario, a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, a.notisms, a.notiemail, a.notipush, a.reg_date, a.update_date, a.profile as idprofile, e.profilename, a.status idstatus, f.name status, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3, '' as actions ", 0, NULL, ['a.fullname', 'asc'],null);
    		if($data['success'] && count($data['data'])) {
				$data = (array)$data['data'][0];
				$data['title'] = 'Editar Usuario';
				$data['user'] = $this->ss->getUser();
				$data['cities'] = $this->ss->getCities();
				$data['profilesarr'] = $this->ss->getUsersProfiles();
				$data['urlForm'] = 'Usuarios/Update/'.$id.'/';
			} else {
				$data = [];
				$data['title'] = 'Editar Usuario';
				$data['user'] = $this->ss->getUser();
				$data['urlForm'] = 'Usuarios/Editar/'.$id;
				$this->session->set_flashdata('message_error', 'No Se encontró el registro buscado');
			}
			$this->load->view('users/add', $data);
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

	public function update($id = -1) {
		try {
			if($id <= 0) {
				$this->session->set_flashdata('message_error', 'No se encontró el registro solicitado');
				return redirect('Usuarios');
			}

			$silueta = $this->input->post('profileimage');
			if(isset($_FILES['uploadThumb']) && $_FILES['uploadThumb']['size'] > 0) {
	        	$config = array(
					'upload_path' => sys_get_temp_dir(),
					'allowed_types' => "gif|jpg|png|bmp|webp|jpeg",
					'overwrite' => TRUE,
					'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'max_height' => "1024",
					'max_width' => "1024"
				);
				$this->load->library('upload', $config);
				$rupf = $this->upload->do_upload('uploadThumb');
	        	if($rupf) {
	        		$upload_data = $this->upload->data();
	        		if (function_exists('curl_file_create')) { // php 5.5+
				  		$cFile = curl_file_create($upload_data['full_path'], $upload_data['file_type']);
					} else { // 
				  		$cFile = '@' . realpath($upload_data['full_path']);
					}
					$post = array('private' => 0, 'extra_info' => $upload_data['file_size'],'file_contents'=> $cFile);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					    'X-Secret-Key: '.$this->config->item('fileblocks_key'),
					));
					curl_setopt($ch, CURLOPT_URL, $this->config->item('fileblocks_url').'/1');
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$result=curl_exec($ch);
					if (curl_errno($ch)) {
				        $this->session->set_flashdata('message_error', 'Error:' . curl_error($ch));
						curl_close ($ch);
						return $this->editar($id);
				    }
					curl_close ($ch);
					$result = json_decode($result);
					if($result->success) {
						$silueta = $result->data[0][0]->Location_b;
						@unlink($upload_data['full_path']);
					}
				} else {
					if($this->upload->display_errors() != '') {
						$this->session->set_flashdata('message_error', $this->upload->display_errors());
						return $this->editar($id);
					}
				}
			}

			$this->form_validation->set_rules('gender', 'Género', 'required', [
                	'required' => 'Debe seleccionar el %s.',
                ]
            );
			$this->form_validation->set_rules('fullname', 'Nombre Completo', 'required', [
                	'required' => 'Debe escribir el %s.',
                ]
            );
			$this->form_validation->set_rules('phonenumber', 'Celular', 'required', [
                	'required' => 'Debe escribir el %s.',
                ]
            );
			$this->form_validation->set_rules('idcity', 'Ciudad', 'required', [
                	'required' => 'Debe seleccionar la %s.',
                ]
            );
			$this->form_validation->set_rules('address', 'Direccón', 'required', [
                	'required' => 'Debe escribir la %s.',
                ]
            );
			$this->form_validation->set_rules('idprofile', 'Perfil de Usuario', 'required', [
                	'required' => 'Debe seleccionar el %s.',
                ]
            );

        	//if(isset($_FILES['uploadThumb'])
			if ($this->form_validation->run() == FALSE) {
				return $this->editar($id);
			} else {
				$newData = $this->ss->update([
					'gender' 			=> $this->input->post('gender'),
					'fullname' 			=> $this->input->post('fullname'),
					'phonenumber' 		=> $this->input->post('phonenumber'),
					'idcity' 			=> $this->input->post('idcity'),
					'address' 			=> $this->input->post('address'),
					'profile' 			=> $this->input->post('idprofile'),
					'profileimage' 		=> $silueta,
				], ['iduser' => $id], 'ss_users');

				if($newData['success']) {
					return redirect('Usuarios');
				} else {
					$this->session->set_flashdata('message_error', 'Ha ocurrido un error!.<br>'.$newData['message']);
					return $this->editar($id);
				}
			}
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}
}
