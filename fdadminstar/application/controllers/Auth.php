<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->output->cache(0);
        $this->output->delete_cache();
        $this->load->helper(array('url_helper','url','form'));
        $this->load->library(array('session','form_validation'));
        //$this->load->model('Ss');
    }

    /**
     * [index description]
     * @return [type] [description]
     */
	public function index() {
		try {
        	if(!$this->ss->validSession()) {
				$this->load->view('auth/login');
			}
		} catch (Exception $e) {
			$this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
		}
	}

    /**
     * [validSession description]
     * @return [type] [description]
     */
    public function validSession() {
        if(null != $this->session->userdata('token') && !empty($this->session->userdata('token'))) {
            return true;
        }
        return false;
    }

    /**
     * [login description]
     * @return [type] [description]
     */
    public function login() {
    	return $this->load->view('auth/login-2', ['title' => 'Iniciar Sesión']);
    }

    public function signin() {
    	$datos = array(
            'email' => $this->input->post('email'),
            'pass' => $this->input->post('password'),
        );
    	$res = [
            'success'   => false,
            'message'   => "Credenciales incorrectas",
            'data'      => []
        ];
        //$passBD = $this->ss->getCampos('ss_users', ["email" => $datos['email'], 'statuscode' => 1, 'profile' => '2'], ['passworddoc', 'idusers', 'profile']);
        $passBD = $this->ss->execsql("SELECT password, passworddoc, idusers, profile, statuscode FROM ss_users WHERE email = '".$datos['email']."' AND profile IN ('2', '3');");

        if($passBD['success'] && count($passBD['data'])>0) {
            $find = $passBD['data'][0];
            if($find->passworddoc == 2) {
                $payload = [];
                $payload['fd_iduser']       = base64_encode($find->idusers);
                $jwt = JWT::encode($payload, SECY_KEY);
                $this->session->set_flashdata('message', "Usuario marcado para recuperar contraseña, ingrese el codigo enviado al email ");
                return redirect('Auth/Restablecer-Clave/'.(base64_encode($jwt)));
            }
            if($find->passworddoc == 99) {
                $res['message']="Usuario bloqueado";
                $this->session->set_flashdata('message', "Usuario bloqueado");
                return redirect('Auth/Login');
            }
            if(password_verify(trim($datos['pass']) , $find->passworddoc)) {
                $payload = [];
                $payload['fd_iduser']       = $find->idusers;
                $payload['fd_idprofile']    = $find->profile;
                $jwt = JWT::encode($payload, SECY_KEY);
                $this->session->set_userdata(['token' => $jwt]);
                return redirect('Dashboard');
            } else {
                $res['message']="Contraseña incorrecta";
                $this->session->set_flashdata('message', "Contraseña incorrecta");
                return redirect('Auth/Login');
            }
        } else {
            $res['message']="No se ha encontrado una cuenta asociada con el correo ".$datos['email'];
            $this->session->set_flashdata('message', "No se ha encontrado una cuenta asociada con el correo ".$datos['email']);
            return redirect('Auth/Login');
        }
    }

    public function register() {
		return $this->load->view('auth/register-2', [
            'title' => 'Crear Una Cuenta',
            'cities' => $this->ss->getCities(),
        ]);
    }

    public function signUp() {
        try {
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required', [
                    'required' => 'Debe escribir el %s.',
                ]
            );
            $this->form_validation->set_rules('password', 'Contraseña', 'required', [
                    'required' => 'Debe escribir la %s.',
                ]
            );
            $this->form_validation->set_rules('idcity', 'Ciudad', 'required', [
                    'required' => 'Debe seleccionar la %s.',
                ]
            );
            $this->form_validation->set_rules('address', 'Dirección', 'required', [
                    'required' => 'Debe escribir la %s.',
                ]
            );
            $this->form_validation->set_rules('agree', 'Términos y Condiciones', 'required', [
                    'required' => 'Debe aceptar los %s.',
                ]
            );
            if ($this->form_validation->run() == FALSE) {
                return $this->register();
            } else {
                $datos = array(
                    'fullname'      => $this->input->post('fullname'),
                    //'phonenumber'   => $this->input->post('phonenumber'),
                    'email'         => $this->input->post('email'),
                    'user'          => $this->input->post('email'),
                    'passworddoc'   => $this->input->post('password'),
                    'password'   => $this->input->post('password'),
                    'idcity'   => $this->input->post('idcity'),
                    'address'   => $this->input->post('address'),
                    'profile'       => '2',
                    'statuscode' => 0,
                    'idusuario'     => time(),
                );
                $datos['password'] = password_hash(trim($datos['password']), PASSWORD_BCRYPT, ['cost' => 12, ]);
                $datos['passworddoc'] = password_hash(trim($datos['passworddoc']), PASSWORD_BCRYPT, ['cost' => 12, ]);
                $iduser = $this->ss->insert($datos, 'ss_users', true);
                if($iduser['success']) {
                    $payload = [];
                    $payload['fd_iduser']       = $iduser['data'][0];
                    $payload['fd_idprofile']    = '2';
                    $jwt = JWT::encode($payload, SECY_KEY);
                    $this->ss->insert([
                        'idusers' => $iduser['data'][0],
                        'status' => 0,
                    ], 'ss_doctor_users', true);
                    $res['url']='Dashboard';
                    $res['success'] = true;
                    $this->session->set_userdata(['token' => $jwt]);
                    redirect('Dashboard');
                }
            }
        } catch (Exception $e) {
            $this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    public function reset_password($key=null) {
        if(!$key || $key == '') {
            $this->session->set_flashdata('message', "URL inválida por favor verifique");
        }
    	return $this->load->view('auth/reset-password', [
            'key' => $key,
            'title' => 'Crear Contraseña Nueva'
        ]);
    }

    public function resetpassword() {
        $key = $this->input->post('csrf-token');
        try {
            $password = trim($this->input->post('password'));
            $token = $this->input->post('csrf-token');
            $token = base64_decode($token);
            $decoded = (object) JWT::decode($token, SECY_KEY, array('HS256'));
            $decoded->fd_iduser = base64_decode($decoded->fd_iduser);
            //$passBD = $this->ss->getCampos('ss_users', ["idusers" => $decoded->fd_iduser, 'profile' => '2'], ['password','passworddoc', 'idusers', 'profile']);        
            $passBD = $this->ss->execsql("SELECT password, passworddoc, idusers, profile FROM ss_users WHERE idusers = '$decoded->fd_iduser' AND profile IN ('2', '3');");
            if($passBD['success'] && count($passBD['data'])>0) {
                    $npassword = trim(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));
                    $updb = $this->ss->update([
                        'tokenrecovery' => null,
                        'statuscode' => 1,
                        'password' => time(),
                        'passworddoc' => $npassword,
                    ], [
                        'idusers' => $decoded->fd_iduser
                    ], 'ss_users');

                    if($updb['success'] && $updb['data'][0] > 0) {
                        $payload = [];
                        $payload['fd_iduser']       = $decoded->fd_iduser;
                        $payload['fd_idprofile']    = '2';
                        $jwt = JWT::encode($payload, SECY_KEY);
                        $this->session->set_userdata(['token' => $jwt]);
                        return redirect('Dashboard');
                    } else {
                        $this->session->set_flashdata('message_errro', "Ha Ocurrido un error intente nuevamente");
                    }
                    return $this->reset_password($key);
            } else {
                $this->session->set_flashdata('message_errro', "No se ha encontrado una cuenta asociada con el correo");
            }
            return $this->reset_password($key);
        } catch (Exception $e) {
            $this->session->set_flashdata('message_errro', 'Error '.$e->getCode().'<hr>'.$e->getMessage());
            return $this->reset_password($key);
        }
    }

    public function forgot_password() {
    	return $this->load->view('auth/forgot-password', ['title' => 'Olvidó su contraseña']);
    }

    public function ftpassword() {
        try {
            $email = $this->input->post('email');
            $passBD = $this->ss->getCampos('ss_users', ["email" => $email, 'profile' => '2'], ['password','passworddoc', 'idusers', 'profile']);        
            if($passBD['success'] && count($passBD['data'])>0) {
                $find = $passBD['data'][0];
                $payload = [];
                $payload['fd_iduser']       = base64_encode($find->idusers);
                $jwt = JWT::encode($payload, SECY_KEY);
                $updb = $this->ss->update([
                    'tokenrecovery' => base64_encode($jwt),
                    'statuscode' => 0,
                    'password' => time(),
                    'passworddoc' => time()
                ], [
                    'idusers' => $find->idusers
                ], 'ss_users');

                if($updb['success'] && $updb['data'][0] > 0) {
                    $this->load->library('email');

                    $subject = 'Restablecer Contraseña &mdash; Find Doctor';
                    $message = '
                        <p>Ha olvidado su contraseña de acceso, para crear una nueva haga click en el siguiente boton</p>
                        <a class="btn btn-primary" target="_blank" href="'.base_url().'Auth/Restablecer-Clave/'.(base64_encode($jwt)).'">Restablecer</a>
                    ';
                    $body = file_get_contents(FCPATH.'application/views/email/basic.html');
                    $file_logo = FCPATH.'assets/images/logo.png';  
                    $this->email->attach($file_logo, 'inline', null, '', true);
                    $cid_logo = $this->email->get_attachment_cid($file_logo);
                    $body = str_replace(['cid:logo_src','__CHARSET__', '__SUBJECT__', '__MESSAGE__'], ['cid:'.$cid_logo, strtolower(config_item('charset')), html_escape($subject), $message], $body);
                    // End attaching the logo.

                    $result = $this->email
                        ->from('finddoctor@solucionesstar.com')
                        ->reply_to('finddoctor@solucionesstar.com')
                        ->to($email)
                        ->subject($subject)
                        ->message($body)
                        ->send();
                    $res['message']="Se ha enviado un email a $email para restablecer su contraseña";
                    $this->session->set_flashdata('message', "Se ha enviado un email a $email para restablecer su contraseña $message");
                    return $this->login();
                } else {
                    $res['message']="Usuario no encontrado, verifique e intente nuevamente";
                    $this->session->set_flashdata('message_errro', "Usuario no encontrado, verifique e intente nuevamente");
                }
            } else {
                $res['message']="No se ha encontrado una cuenta asociada con el correo <b>$email</b>";
                $this->session->set_flashdata('message_errro', "No se ha encontrado una cuenta asociada con el correo <b>$email</b>");
            }
            return $this->forgot_password();
        } catch (Exception $e) {
            $this->load->view('errors/html/error_db', ['heading' => 'Error '.$e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    public function logout() {
    	$this->session->sess_destroy();
    	redirect('/');
    }
    

    public function signInPatient() {
        try {
            $data = json_decode(file_get_contents("php://input"));
            
            if(!$data) throw new Exception("Error: No data sent", 1);

            $data = $data->data;
            
            $email = $data->email;   
            
            $passBD = $this->ss->execsql("SELECT password, passworddoc, idusers, profile, statuscode FROM ss_users WHERE email = '".$email."' AND profile = 2;");

            if($passBD['success'] && count($passBD['data'])>0) {
                $find = $passBD['data'][0];

                if(password_verify(trim($data->password), $find->passworddoc)) {
                    $payload = [];
                    $payload['fd_iduser']       = $find->idusers;
                    $payload['fd_idprofile']    = $find->profile;
                    $jwt = JWT::encode($payload, SECY_KEY);
                    $data = $jwt;
                } else {
                    throw new Exception("Contraseña incorrecta");
                }
            } else {
            throw new Exception("No se ha encontrado una cuenta asociada con el correo $email ");
            }

            $res['data'] = $data;
            $res['success'] = true;
            $res['message'] = "Usuario logeado correctamente";

        } catch (Exception $e) {
          $res['data'] = null;
          $res['success'] = false;
          $res['message'] = $e->getMessage();
        } finally {
           echo json_encode($res);
        }
    }

    public function signUpPatient() {
        try {

            $data = json_decode(file_get_contents("php://input"));
            
            if(!$data) throw new Exception("Error: No data sent", 1);
            $data = $data->data;

            $user = array(
                'fullname' => $data->fullname,
                'email' => $data->email,
                'user' => $data->email,
                'passworddoc' => $data->password,
                'password' => $data->password,
                'profile' => 2,
                'statuscode' => 1,
                'idusuario' => time(),
            );

            $user['password'] = password_hash(trim($user['password']), PASSWORD_BCRYPT, ['cost' => 12, ]);
            $user['passworddoc'] = password_hash(trim($user['passworddoc']), PASSWORD_BCRYPT, ['cost' => 12, ]);
            $iduser = $this->ss->insert($user, 'ss_users', true);

            if($iduser['success']) {
                $payload = [];
                $payload['fd_iduser'] = $iduser['data'][0];
                $payload['fd_idprofile'] = '2';
            }
            
            $res['data'] = $iduser;
            $res['success'] = true;
            $res['message'] = "Usuario registrado correctamente";

        } catch (Exception $e) {
          $res['data'] = null;
          $res['success'] = false;
          $res['message'] = $e->getMessage();
        } finally {
           echo json_encode($res);
        }
    }

    public function getUser() {
        try {

            $data = json_decode(file_get_contents("php://input"));
            
            if(!$data) throw new Exception("Error: No data sent", 1);
            $jwt = $data->auth;

            $user = JWT::decode($jwt, SECY_KEY, array('HS256'));

            
            
            
            
        $result = $this->ss->execsql("SELECT * , f2.name as namecity from ss_users f1
        inner join ss_cities f2 on f1.idcity = f2.idcity

        where f1.profile = $user->fd_idprofile
                and f1.idusers = $user->fd_iduser");
            
            /*$result = $this->ss->execsql("SELECT * from ss_users f1
                where f1.profile = $user->fd_idprofile
                and f1.idusers = $user->fd_iduser");*/
            
            if($result['success']) {
                $data = $result['data'][0];
            } else {
                throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
            }

            $res['data'] = $data;
            $res['success'] = true;
            $res['message'] = "Usuario registrado correctamente";

        } catch (Exception $e) {
          $res['data'] = null;
          $res['success'] = false;
          $res['message'] = $e->getMessage();
        } finally {
           echo json_encode($res);
        }
    }

}
