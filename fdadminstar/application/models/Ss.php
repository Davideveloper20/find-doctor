<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;
class Ss extends CI_Model {

    public function __construct() {
        header('Access-Control-Allow-Origin: *');
    	parent::__construct();
        $this->load->database();
        //$this->load->library('MailChimp');
        //$this->load->helper(array('form', 'url','url_helper'));
        //$db_obj = $this->database->load();
	  	//$connected = $db_obj->initialize();
		//if (!$connected) {
		//	trigger_error('no se puede conectar a la bd', E_USER_ERROR);
		//} 
        /*$conf = $this->getCampos('configs');
        if($conf['success']) {
            foreach ($conf['data'] as $key => $value) {
                //defined(strtoupper($value->id)) OR define(strtoupper($value->id), $value->value, true);
            }
        }*/
    }

    public function db_date($value='') {
        if(empty($value)) {
            return null;
        }
        $arr = explode('/', $value);
        if(count($arr) == 3) {
            $aux = $arr[0];
            $arr[0] = $arr[2];
            $arr[2] = $aux;
            return implode('-', $arr);
        }
        return null;
    }

    public function dbdate_2fr($value='') {
        if(empty($value)) {
            return null;
        }
        $arr = explode('-', $value);
        if(count($arr) == 3) {
            $aux = $arr[0];
            $arr[0] = $arr[2];
            $arr[2] = $aux;
            return implode('/', $arr);
        }
        return null;
    }

    public function validSession() {
        if(null != $this->session->userdata('token') && !empty($this->session->userdata('token'))) {
            return true;
        }
        return false;
    }

    public function getToken() {
        if(null != $this->session->userdata('token') && !empty($this->session->userdata('token'))) {
            return $this->session->userdata('token');
        }
        return false;
    }

    protected function solveToken() {
        if(null != $this->session->userdata('token') && !empty($this->session->userdata('token'))) {
            $decoded = JWT::decode($this->session->userdata('token'), SECY_KEY, array('HS256'));
            if($decoded){
                return (object) $decoded;
            }
        }
        if (!$this->input->is_ajax_request()) {
            $this->session->set_flashdata('message', 'Sesión vencida');
            return redirect('/Auth/Login');
        } else {
            $this->output->set_content_type('application/json','uft-8')->set_header('Location: '.base_url().'Auth/Login')->set_output(json_encode(['success' => false, 'message' => 'Sesión Vencida']));
            die();
        }
    }

      /**
    * Validate session tokens for app users
    *
    * This method is used validate app remote sessions
    *
    * @access public
    * @param string $auth user token 
    * @return string || boolean 
    */
    public function validToken($auth=NULL) {
        try {
            
            $user = $this->getCampos(USERS_TABLE, array("tokensesion"=> $auth), "idusers");

            if($user->success && count($user->data) == 0 ) {
                throw new Exception("La información de usuario suministrada no es precisa.", 1);
            }else{
                return $user[0]['idusers'];
            }
            
        } catch (Exception $e) {
            $message = "Ha ocurrido un error: ".$e->getMessage(); 
        }
        
    }

    public function valid_token($token=null)
    {
        if(null != $token) {
            $decoded = JWT::decode($token, SECY_KEY, array('HS256'));
            if($decoded){
                return (object) $decoded;
            }
        }
        if (!$this->input->is_ajax_request()) {
            return false;
        } else {
            return false;
            die();
        }
    }

    public function getSessUser() {
        return $this->solveToken();
    }

    public function getUser() {
        $decoded = $this->solveToken();
        if($decoded) {
            $sql = "SELECT a.video_chat_activo, a.idusers, a.idusuario, a.fullname, a.email, a.user, a.gender, a.birthdate, a.address, a.phonenumber, a.profileimage, a.description, a.notisms, a.notiemail, a.notipush, a.newsletter, a.reg_date, a.med_domicilio, a.med_prepagado, a.chat_activo, a.update_date, a.profile as idprofile, e.profilename, a.statuscode idstatus, f.name status, a.idcity, b.name city, b.idstate, c.name state, c.idcountry, d.name country, d.iso2, d.iso3, a.latitude, a.longitude FROM ss_users as a LEFT JOIN ss_cities as b ON b.idcity = a.idcity AND b.status = 1 LEFT JOIN ss_states as c ON c.idstate = b.idstate AND c.status = 1 LEFT JOIN ss_country as d ON d.idcountry = c.idcountry AND d.status = 1 INNER JOIN ss_profiles as e ON e.idprofile = a.profile AND e.statuscode = 1 INNER JOIN statuscodes as f ON f.id = a.statuscode WHERE 1 AND a.idusers = '$decoded->fd_iduser';";
            $data = $this->execsql($sql);
            if($data['success'] && count($data['data']) > 0) {

                if($data['data'][0]->idprofile == 2)
                {
                    $sql = "SELECT iddoctor from ss_doctor_users where 1 AND idusers = '$decoded->fd_iduser';";
                    $doc = $this->execsql($sql);
                    if($doc['success'] && count($doc['data']) > 0) {
                        $data['data'][0]->doc = $doc['data'][0];
                    }
                }
                return (object) $data['data'][0];
            }
        }

        $this->session->sess_destroy();
        if (!$this->input->is_ajax_request()) {
            $this->session->set_flashdata('message', 'Sesión vencida');
            return redirect('/Auth/Login');
        } else {
            $this->output->set_content_type('application/json','uft-8')->set_header('Location: '.base_url().'Auth/Login')->set_output(json_encode(['success' => false, 'message' => 'Sesión Vencida']));
            die();
        }
    }

    public function getUserChatStatus() {
        $decoded = $this->solveToken();
        if($decoded) {
            $sql = "SELECT chat_activo FROM ss_users  WHERE idusers = '$decoded->fd_iduser';";
            $data = $this->execsql($sql);
            if($data['success'] && count($data['data']) > 0) {
                return (object) $data['data'][0];
            }
            else return ['chat_activo' => 0];
        }

        $this->session->sess_destroy();
        if (!$this->input->is_ajax_request()) {
            $this->session->set_flashdata('message', 'Sesión vencida');
            return redirect('/Auth/Login');
        } else {
            $this->output->set_content_type('application/json','uft-8')->set_header('Location: '.base_url().'Auth/Login')->set_output(json_encode(['success' => false, 'message' => 'Sesión Vencida']));
            die();
        }
    }

    public function getDoctorProfile() {
        $decoded = $this->solveToken();
        $resp = [
            'iddoctor' => '',
            'idusers' => '',
            'type_doc' => '',
            'type_docname' => '',
            'type_docabrev' => '',
            'document' => '',
            'rethus' => '',
            'languages' => '',
            'titulo' => '',
            'aboutme' => '',
            'especiality' => '',
            'enferme_trat' => '',
            'content_mult' => '',
            'rating' => '',
            'verified' => '',
            'time_start' => '08:00',
            'time_end' => '17:00',
            'id_status' => '',
            'status' => '',
            'jobplace' => '',
            'create_at' => '',
            'numhabi' => '',
            'formation' => [],
            'dprof' => '',
            'goals' => [],
            'specialities' => [],
        ];
        if($decoded) {
            $sql = "SELECT doc.iddoctor, doc.idusers, doc.type_doc, d.name type_docname, d.abrev type_docabrev, doc.document, doc.rethus, doc.languages, doc.titulo, doc.aboutme, doc.especiality, doc.enferme_trat, doc.time_start, doc.time_end, doc.jobplace, doc.content_mult, doc.rating, doc.verified, doc.numhabi, doc.status id_status, f.name status, doc.create_at FROM ss_doctor_users as doc INNER JOIN statuscodes as f ON f.id = doc.status LEFT JOIN doctypes as d ON d.id = doc.type_doc WHERE doc.idusers = '$decoded->fd_iduser';";
            $data = $this->execsql($sql);
            if($data && $data['success'] && $data['data']) {
                $data = (object) array_merge($resp, (array) $data['data'][0]);
                $data->formation = $this->execsql("SELECT id, iddoctor, institucion, titulacion, descripcion, content_mult, create_at FROM ss_doctor_formacion WHERE status = 1 AND iddoctor = '$data->iddoctor';");
                $data->goals = $this->execsql("SELECT id, titulo, descripcion, fecha, content_mult, create_at FROM ss_doctor_logros WHERE status = 1 AND iddoctor = '$data->iddoctor';");
                $data->specialities = $this->execsql("SELECT dsp.id idspectdoc, dsp.idspeciality, dsp.default, dsp.create_at, esp.speciality, esp.descrip FROM ss_doctor_specilities dsp INNER JOIN ss_specilities esp ON esp.id = dsp.idspeciality AND esp.status = 1 WHERE 1 AND iddoctor = '$data->iddoctor' ORDER BY dsp.default DESC, esp.speciality ASC;");
                return $data;
            } else {
                return (object)$resp;
            }
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorSettings() {
        $decoded = $this->solveToken();
        $resp = [
            'iddoctor' => '',
            'idusers' => '',
            'content_mult' => '',
            'rating' => '',
            'verified' => '',
            'time_start' => '08:00',
            'time_end' => '17:00',
            'id_status' => '',
            'status' => '',
            'create_at' => '',
        ];
        if($decoded) {
            $sql = "SELECT doc.iddoctor, doc.idusers, doc.time_start, doc.time_end, doc.content_mult, doc.rating, doc.verified, doc.status id_status, f.name status, doc.create_at FROM ss_doctor_users as doc INNER JOIN statuscodes as f ON f.id = doc.status LEFT JOIN doctypes as d ON d.id = doc.type_doc WHERE doc.idusers = '$decoded->fd_iduser';";
            $data = $this->execsql($sql);
            if($data && $data['success'] && $data['data']) {
                $data = (object) array_merge($resp, (array) $data['data'][0]);
                
                return $data;
            } else {
                return $resp;
            }
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorGoals() {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT id, titulo, descripcion, fecha, content_mult, create_at FROM ss_doctor_logros WHERE status = 1 AND iddoctor = '$iddoc' ORDER BY fecha ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorFormation() {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT id, iddoctor, institucion, titulacion, descripcion, content_mult, create_at FROM ss_doctor_formacion WHERE status = 1 AND iddoctor = '$iddoc' ORDER BY create_at ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorConsultorios() {
        $decoded = $this->solveToken();

        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT dsp.id, dsp.id idconsultdoc, dsp.idconsult, dsp.iddoctor, dsp.create_at, esp.name, esp.address, esp.phone1, esp.phone2, esp.phone3, esp.url, esp.email, esp.idcity, esp.longitud, esp.latitud, cty.name city, cty.idstate, cts.name state, cts.idcountry, ctt.name country, ctt.iso2  FROM ss_doctor_consultorio dsp INNER JOIN ss_consultorio esp ON dsp.idconsult = esp.id AND esp.status = 1 LEFT JOIN ss_cities as cty ON cty.idcity = esp.idcity LEFT JOIN ss_states cts ON cts.idstate = cty.idstate LEFT JOIN ss_country ctt ON ctt.idcountry = cts.idcountry WHERE iddoctor = '$iddoc' ORDER BY esp.name ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getConsultingRooms() {
        $decoded = $this->solveToken();
        if($decoded) {
            $data = $this->execsql("SELECT *, name item FROM ss_consultorio WHERE status = 1 ORDER BY name ASC;");
            if($data && $data['success'] && $data['data']) {
                return $data['data'];
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorServices() {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT id, iddoctor, type, service, description, duration, amount, status, create_at FROM ss_doctor_services WHERE status = 1 AND iddoctor = '$iddoc' ORDER BY service ASC, create_at ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorServices2($id=null) {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $where = '';
                if(isset($id) && !empty($id)) {
                    $where.= " AND id = '$id'";
                }
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT id, iddoctor, concat(service, ', ',duration,' min') item, service, description, duration, amount, amount_prepaid, status, create_at FROM ss_doctor_services WHERE status = 1 AND iddoctor = '$iddoc' $where ORDER BY create_at ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDocAppoinments($idapp=null) {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $this->update(['status' => 5], ['status' => 1, 'apment_date <' => date('Y-m-d'),], 'ss_doctor_appointments');
                $this->update(['status' => 4], ['status' => 1, 'apment_date' => date('Y-m-d'),], 'ss_doctor_appointments');
                $data = $this->execsql("SELECT dsp.id, dsp.iddoctor, dsp.idpatient, dsp.idservice, svr.service, svr.duration serv_duration, svr.amount serv_amount, svr.amount_prepaid serv_amount_prepaid, concat(dsp.apment_date, ' ',dsp.start_at) start_date, concat(dsp.apment_date, ' ',dsp.end_at) end_date, dsp.apment_date, dsp.create_at, dsp.start_at, dsp.end_at, dsp.title, concat('<a class=\'eventLink\' href=\'javascript:showServiceDetail(',COALESCE(dsp.idservice,'-1'),');\'>',COALESCE(svr.service,'Agendamiento'),'</a> &mdash;<a class=\'eventLink\' href=\'javascript:showPatienDetail(',COALESCE(dsp.idpatient,'-1'),');\'>',COALESCE(pat.name,'Evento'),'</a>') text, dsp.title, dsp.amount, dsp.prepaid, dsp.description, dsp.description details, pat.type_doc, pat.document, pat.name, pat.address, pat.idcity, pat.description patdescrip, dsp.status idstatus, stat.name status, stat.bgcolor color, stat.frcolor textColor FROM ss_doctor_appointments dsp LEFT JOIN ss_doctor_services svr ON svr.id = dsp.idservice LEFT JOIN ss_doctor_patients pat ON pat.id = dsp.idpatient AND pat.iddoctor = dsp.iddoctor INNER JOIN statuscodes stat ON stat.id = dsp.status WHERE dsp.iddoctor = '$iddoc' ".((isset($idapp) && $idapp!= '')?"AND dsp.id='$idapp' ":"")."AND dsp.status > 0 AND dsp.status != 127 ORDER BY dsp.apment_date ASC, dsp.start_at ASC ;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorSpecialities() {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $data = $this->execsql("SELECT dsp.id idspectdoc, dsp.idspeciality, dsp.default, dsp.create_at, esp.speciality, esp.descrip FROM ss_doctor_specilities dsp INNER JOIN ss_specilities esp ON esp.id = dsp.idspeciality AND esp.status = 1 WHERE iddoctor = '$iddoc' ORDER BY dsp.default DESC, esp.speciality ASC;");
                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getTypeDocs() {
        $sql = "SELECT a.* FROM doctypes a INNER JOIN statuscodes as f ON f.id = a.status AND f.name = 'ACTIVO' WHERE 1;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function getStatusCodes() {
        $sql = "SELECT a.* FROM statuscodes a WHERE status = 'ACTIVO';";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function getSpecilities() {
        $sql = "SELECT a.id, a.speciality as item, a.speciality, a.descrip, a.tipo, a.status FROM ss_specilities a WHERE status = 1 ORDER BY a.speciality ASC;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function getEPSSeguros() {
        $sql = "SELECT a.id, a.seguroeps as item, a.seguroeps, a.tipo, a.status FROM ss_eps_segur a WHERE status = 1 ORDER BY a.seguroeps ASC;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function getDoctorRatings() {
    	$decoded = $this->solveToken();
        if($decoded) {
        	$resp = [
        		'rating' => 0,
        		'verified' => 0,
        		'comments' =>[]
        	];
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $sql = "SELECT doc.rating, doc.verified FROM ss_doctor_users as doc WHERE doc.idusers = '$decoded->fd_iduser';";
	            $data = $this->execsql($sql);
	            if($data && $data['success'] && $data['data']) {
	            	$resp['rating'] = $data['data'][0]->rating;
	            	$resp['verified'] = $data['data'][0]->verified;
                	$sql = "SELECT a.id, a.iddoctor, a.fecha, a.idusers, b.fullname, a.idusers_verified, a.descripcion, a.puntualidad, a.atencion, a.instalaciones, a.item4, a.item5, a.comments, a.content_mult, a.status, a.create_at, (COALESCE(a.puntualidad, 0)+COALESCE(a.atencion, 0)+COALESCE(a.instalaciones,0)+COALESCE(a.item4,0)+COALESCE(a.item5,0))/5 media FROM ss_doctor_valoracion as a LEFT JOIN ss_users b ON b.idusers = a.idusers WHERE a.status = 1 AND a.iddoctor = '$iddoc' ORDER BY a.fecha DESC, a.create_at DESC;";
                	$datac = $this->execsql($sql);
                	$resp['comments'] = $datac['data'];
                	$cr = 0;
                	if(count($resp['comments']) > 0) {
	                	foreach ($resp['comments'] as $value) {
	                		$cr+= (double) $value->media;
	                	}
                		$resp['rating'] = (double) $cr/count($resp['comments']);
                	} else {
                		$resp['rating'] = 0;
                	}
                	$updb = $this->ss->update([
						'rating' => $resp['rating'],
		            ], [
		                'iddoctor' => $iddoc
		            ], 'ss_doctor_users');
	            }
	        }
            return $resp;
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getDoctorEPSSeguros() {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                $iddoc = $passBD['data'][0]->iddoctor;
                $sql = "SELECT b.id, a.seguroeps as item, a.seguroeps, a.tipo, a.status, b.create_at FROM ss_doctor_epseguro b INNER JOIN ss_eps_segur a ON a.id = b.ideps WHERE a.status = 1 AND b.status = a.status AND iddoctor = '$iddoc' ORDER BY a.seguroeps ASC;";
                    $data = $this->execsql($sql);
                    if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }

    public function getUsersProfiles() {
        $sql = "SELECT a.* FROM ss_profiles a INNER JOIN statuscodes as f ON f.id = a.status AND f.name = 'ACTIVO' WHERE a.idprofile > 0;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function getCities($idcountry=null, $idstate=null) {
        $where = '';

        if(isset($idcountry) && !empty($idcountry)) {
            $where = "AND ctt.idcountry = '$idcountry' ";
        }
        if(isset($idstate) && !empty($idstate)) {
            $where = "AND cts.idstate = '$idstate' ";
        }
        $sql = "SELECT cty.idcity id, cty.idstate, cty.name city, concat(cty.name,', ',cts.name) item, cty.descript, cty.latitude, cty.longitude, cty.default, cty.status idstatus, f.name status FROM ss_cities cty INNER JOIN statuscodes as f ON f.id = cty.status AND f.name = 'ACTIVO' INNER JOIN ss_states AS cts ON cts.idstate = cty.idstate AND cts.status = 1 INNER JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry AND ctt.status = 1 WHERE 1 $where ORDER BY ctt.name, cts.name, cty.name;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function seekAddCities($country=null, $state=null, $city=null) {
        $where = '';

        if(isset($country) && !empty($country)) {
            $where.= "AND ctt.name LIKE '%$country%' ";
        }
        if(isset($state) && !empty($state)) {
            $where.= "AND cts.name LIKE '%$state%' ";
        }
        if(isset($city) && !empty($city)) {
            $where.= "AND cty.name LIKE '%$city%' ";
        }
        $sql = "SELECT cty.idcity id, cty.idstate, cty.name city, ,cts.name as state ctt.name country, cty.descript, cty.latitude, cty.longitude, cty.default, cty.status idstatus, f.name status FROM ss_cities cty INNER JOIN ss_states AS cts ON cts.idstate = cty.idstate AND cts.status = 1 INNER JOIN ss_country AS ctt ON ctt.idcountry = cts.idcountry WHERE 1 $where ORDER BY ctt.name, cts.name, cty.name;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data'] && count($data['data'])) {
            return $data['data'];
        } else {
            $sql = "SELECT idcountry as id, name as item, name, descript, iso2, iso3, capital, region, sub_region, phone_code, latitude, longitude, status FROM ss_country WHERE name = '$country' ORDER BY name ASC;";
            $data = $this->execsql($sql);
            if($data && $data['success'] && $data['data']) {
                
            }
        }
        return [];
    }

    public function getCountries() {
        $sql = "SELECT idcountry as id, name as item, name, descript, iso2, iso3, capital, region, sub_region, phone_code, latitude, longitude, status FROM ss_country WHERE status = 1 ORDER BY name ASC;";
        $data = $this->execsql($sql);
        if($data && $data['success'] && $data['data']) {
            return (object) $data['data'];
        }
        return [];
    }

    public function execsql($sql,$last=false) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $query = $this->db->query($sql);
            if(!$query) {
                $res['data'] = $this->db->error()['code'];
                if($this->db->error()['code']==1062) {
                    $res['message'] = 'El registro ya existe!';
                } else {
                    $res['message'] = array($this->db->error()['message']);
                }
                return $res;
            }
            if($last) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            $res['success'] = true;
            $res['data'] = $query->result_object();
            $res['message'] = count($res['data']).' Registros';
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCamposAndJoin($tabla, $where, $join, $fjoin, $campos="*", $result=0, $groupby=NULL, $orderby=NULL,$lastquery=NULL) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $this->db->select($campos);
            $this->db->from($tabla);
            if(is_array($join) && is_array($fjoin)) {
                if(count($join) != count($fjoin)) {
                    $res['message'] = "La cantidad de Join's no coincide";
                    return $res;
                }

                for ($i=0; $i < count($join); $i++) { 
                    $this->db->join($join[$i][0],$fjoin[$i],(isset($join[$i][1]) && !empty($join[$i][1]) ? strtolower($join[$i][1]) : 'left'));
                }
            } else {
                $this->db->join($join, $fjoin,'left');
            }
            if($where) {
                $this->db->where($where);
            }
            if($groupby) {
                $this->db->group_by($groupby);
            }
            if($orderby && is_array($orderby)) {
                if($orderby[0] && is_array($orderby[0])) {
                    foreach ($orderby as $key => $ord) {
                        $this->db->order_by($ord[0], $ord[1]);
                    }
                } else {
                    $this->db->order_by($orderby[0], $orderby[1]);
                }
            }

            $query = $this->db->get();
            if($lastquery) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            if($result>0) {
                $res['success'] = true;
                $res['data'] = [$query];
                return $res;
            }
            $res['success'] = true;
            $res['data'] = $query->result_object();
            $res['message'] = count($res['data']). ' Registro(s)';
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCampos($tabla, $where = NULL, $campos="*", $result=0, $groupby=NULL, $orderby=NULL,$limit=NULL,$returnquery=NULL) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $this->db->select($campos);
            if($groupby) {
                $this->db->group_by($groupby);
            }

            if($orderby) {
                $this->db->order_by($orderby[0],$orderby[1]);
            }

            if($where == NULL) {
                $query = $this->db->get($tabla);
                if($returnquery) {
                    $res['success'] = true;
                    $res['data'] = [$this->db->last_query()];
                    return $res;
                }
                if($result==1) {
                    $res['success'] = true;
                    $res['data'] = [$query];
                    return $res;
                } else {
                    $res['success'] = true;
                    $res['data'] = $query->result_object();
                    $res['message'] = count($res['data']). ' Registro(s)';
                    return $res;
                }
            }

            $query = $this->db->get_where($tabla, $where);
            if($limit) {
                $this->db->limit($limit[0],$limit[1]);
            }
            if($returnquery) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            if($result==1) {
                $res['success'] = true;
                $res['data'] = [$query];
                return $res;
            } else {  
                $res['success'] = true;
                $res['data'] = $query->result_object();
                return $res;
            }
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCamposArr($tabla, $where = NULL, $campos="*", $result=0, $groupby=NULL, $orderby=NULL,$limit=NULL,$returnquery=NULL) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $this->db->select($campos);
            if($groupby) {
                $this->db->group_by($groupby);
            }

            if($orderby) {
                $this->db->order_by($orderby[0],$orderby[1]);
            }

            if($where == NULL) {
                $query = $this->db->get($tabla);
                if($returnquery) {
                    $res['success'] = true;
                    $res['data'] = [$this->db->last_query()];
                    return $res;
                }
                if($result==1) {
                    $res['success'] = true;
                    $res['data'] = [$query];
                    return $res;
                } else {
                    $res['success'] = true;
                    $res['data'] = $query->result_array();
                    $res['message'] = count($res['data']). ' Registro(s)';
                    return $res;
                }
            }

            $query = $this->db->get_where($tabla, $where);
            if($limit) {
                $this->db->limit($limit[0],$limit[1]);
            }
            if($returnquery) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            if($result==1) {
                $res['success'] = true;
                $res['data'] = [$query];
                return $res;
            } else {  
                $res['success'] = true;
                $res['data'] = $query->result_array();
                return $res;
            }
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    public function insert($data, $table, $idback = true, $last_query = false) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $this->db->set($data);
            $query = $this->db->insert($table, $data);
            if(!$query) {
                $res['data'] = $this->db->error()['code'];
                if($this->db->error()['code']==1062) {
                    $res['message'] = 'El registro ya existe!';
                } else {
                    $res['message'] = array($this->db->error()['message']);
                }
                return $res;
            }
            if($idback) {
                $res['success'] = true;
                $res['data'] = [$this->db->insert_id()];
                return $res;
            }
            if($last_query) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            $res['success'] = true;
            $res['data'] = [$query];
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * UPDATE
     *
     * Compiles an update string and runs the query.
     *
     * @param   string  $table
     * @param   array   $data    An associative array of update values
     * @param   mixed   $where
     * @param   int $limit
     * @return  bool    TRUE on success, FALSE on failure
     */
    public function update($data, $where, $table, $counts = true, $last_query = false) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $this->db->set($data);
            $query = $this->db->update($table, $data, $where);
            if(!$query) {
                $res['data'] = $this->db->error()['code'];
                if($this->db->error()['code']==1062) {
                    $res['message'] = 'El registro ya existe!';
                } else {
                    $res['message'] = array($this->db->error()['message']);
                }
                return $res;
            }
            if($counts) {
                $res['success'] = true;
                $res['data'] = [$this->db->affected_rows()];
                return $res;
            }
            if($last_query) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            $res['success'] = true;
            $res['data'] = [$query];
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete
     *
     * Compiles a delete string and runs the query
     *
     * @param   mixed   the table(s) to delete from. String or array
     * @param   mixed   the where clause
     * @param   bool
     * @return  mixed
     */
    public function delete($where, $table, $counts = true, $last_query = false) {
        try {
            $res = [
                'success' => false,
                'data' => [],
                'message' => ''
            ];
            $query = $this->db->delete($table, $where);
            if(!$query) {
                $res['data'] = $this->db->error()['code'];
                if($this->db->error()['code']==1062) {
                    $res['message'] = 'El registro ya existe!';
                } else {
                    $res['message'] = array($this->db->error()['message']);
                }
                return $res;
            }
            if($counts) {
                $res['success'] = true;
                $res['data'] = [$this->db->affected_rows()];
                return $res;
            }
            if($last_query) {
                $res['success'] = true;
                $res['data'] = [$this->db->last_query()];
                return $res;
            }
            $res['success'] = true;
            $res['data'] = [$query];
            return $res;
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [$e->getCode()],
                'message' => $e->getMessage()
            ];
        }
    }

    public function getPreSchedule($idapp=null) {
        $decoded = $this->solveToken();
        if($decoded) {
            $passBD = $this->ss->getCampos('ss_doctor_users', ["idusers" => $decoded->fd_iduser], ['iddoctor', 'idusers']);
            if($passBD['success'] && count($passBD['data'])>0) {
                //$idapp = $this->saveAppointmentsApp();
                $iddoc = $passBD['data'][0]->iddoctor;
                //$this->update(['status' => 5], ['status' => 1, 'apment_date <' => date('Y-m-d'),], 'ss_doctor_appointments');
                //$this->update(['status' => 4], ['status' => 1, 'apment_date' => date('Y-m-d'),], 'ss_doctor_appointments');
                //$data = $this->execsql("SELECT dsp.id, dsp.iddoctor, dsp.idpatient, dsp.idservice, svr.service, svr.duration serv_duration, svr.amount serv_amount, svr.amount_prepaid serv_amount_prepaid, concat(dsp.apment_date, ' ',dsp.start_at) start_date, concat(dsp.apment_date, ' ',dsp.end_at) end_date, dsp.apment_date, dsp.create_at, dsp.start_at, dsp.end_at, dsp.title, concat('<a class=\'eventLink\' href=\'javascript:showServiceDetail(',COALESCE(dsp.idservice,'-1'),');\'>',COALESCE(svr.service,'Agendamiento'),'</a> &mdash;<a class=\'eventLink\' href=\'javascript:showPatienDetail(',COALESCE(dsp.idpatient,'-1'),');\'>',COALESCE(pat.name,'Evento'),'</a>') text, dsp.title, dsp.amount, dsp.prepaid, dsp.description, dsp.description details, pat.type_doc, pat.document, pat.name, pat.address, pat.idcity, pat.description patdescrip, dsp.status idstatus, stat.name status, stat.bgcolor color, stat.frcolor textColor FROM ss_doctor_appointments dsp LEFT JOIN ss_doctor_services svr ON svr.id = dsp.idservice LEFT JOIN ss_doctor_patients pat ON pat.id = dsp.idpatient AND pat.iddoctor = dsp.iddoctor INNER JOIN statuscodes stat ON stat.id = dsp.status WHERE dsp.iddoctor = '$iddoc' ".((isset($idapp) && $idapp!= '')?"AND dsp.id='$idapp' ":"")."AND dsp.status > 0 AND dsp.status != 127 ORDER BY dsp.apment_date ASC, dsp.start_at ASC ;");
                $data = $this->execsql("SELECT dsp.id, dsp.iddoctor, dsp.idpatient, dsp.idservice, svr.service, svr.duration serv_duration, svr.amount serv_amount, svr.amount_prepaid serv_amount_prepaid, concat(dsp.apment_date, ' ',dsp.start_at) start_date, concat(dsp.apment_date, ' ',dsp.end_at) end_date, dsp.apment_date, dsp.create_at, dsp.start_at, dsp.end_at, dsp.title, concat('<a class=\'eventLink\' href=\'javascript:showServiceDetail(',COALESCE(dsp.idservice,'-1'),');\'>',COALESCE(svr.service,'Agendamiento'),'</a> &mdash;<a class=\'eventLink\' href=\'javascript:showPatienDetail(',COALESCE(dsp.idpatient,'-1'),');\'>',COALESCE(pat.name,'Evento'),'</a>') text, dsp.title, dsp.amount, dsp.prepaid, dsp.description, dsp.description details, pat.type_doc, pat.document, pat.name, pat.address, pat.idcity, pat.description patdescrip, dsp.status idstatus, stat.name status, stat.bgcolor color, stat.frcolor textColor FROM ss_doctor_appointments dsp LEFT JOIN ss_doctor_services svr ON svr.id = dsp.idservice LEFT JOIN ss_doctor_patients pat ON pat.id = dsp.idpatient AND pat.iddoctor = dsp.iddoctor INNER JOIN statuscodes stat ON stat.id = dsp.status WHERE dsp.iddoctor = '$iddoc' ".((isset($idapp) && $idapp!= '')?"AND dsp.id='$idapp' ":"")."AND dsp.status > 0 AND dsp.status != 127 ORDER BY dsp.apment_date ASC, dsp.start_at ASC ;");

                if($data && $data['success'] && $data['data']) {
                    return $data['data'];
                }
            }
            return [];
        }
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'Sesión vencida');
        return redirect('Auth/Login');
    }
}