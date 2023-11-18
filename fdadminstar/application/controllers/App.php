<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class App extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->output->cache(0);
    $this->output->delete_cache();
    $this->load->helper(array('url_helper', 'url', 'form'));
    $this->load->library(array('session', 'form_validation'));
    //$this->load->model('Ss');
  }
  public function chat($type_chat, $subject_chat, $user, $chatto)
  {
    $res = array("success" => false, "login" => false, "msj" => "Acceso restringido", "data" => null);
    if ($this->user = $this->ss->validToken($this->input->get("auth"))) {
      $res['login'] = true;
      $chatfrom = $this->user;
      if (count($this->ss->getCampos(TABLE_PREFIX . "chatsxusuario", ["iduser" => $this->user, "idsubject" => $subject_chat])) == 0) {
        $idayudas = null;
        $idback = null;
        $data = array(
          "iduser" => $this->user,
          "idsubject" => $subject_chat,
          "statuscode" => 1,
          "typechat" => 2,
          "extraid" => $idayudas,
          "idback" => $idback
        );
        $this->ss->pushtodb($data, TABLE_PREFIX . "chatsxusuario");
      }
      $this->ss->updaterequest(TABLE_PREFIX . "chats", array("idsubject" => $subject_chat, "iduser" => $chatto), array("statuscode" => 2));
      $res['data'] = $this->getChat($chatfrom, $chatto, $type_chat, $subject_chat);
    }
    echo json_encode($res);
  }
  private function getChat($from, $to, $type_chat, $subject_chat)
  {
    $data = [];
    switch ($type_chat) {
      case "user":
        $buscar_mis_chats = $this->ss->getCampos(TABLE_PREFIX . "chatsxusuario", array("iduser" => $this->user, "typechat" => 1));
        $buscar_chat = null;
        if (count($buscar_mis_chats) > 0) {
          foreach ($buscar_mis_chats as $key => $value) {
            $buscar_chat_back = $this->ss->getCampos(TABLE_PREFIX . "chatsxusuario", array("iduser" => $to, "idsubject" => $value['idsubject']));
            if (count($buscar_chat_back) > 0) {
              $buscar_chat = $buscar_chat_back[0]['idsubject'];
            }
          }
        }
        if ($buscar_chat) {
          $subject_chat = $buscar_chat;
        }
        $data['chat'] = $this->ss->getCamposAndJoin(TABLE_PREFIX . "chats c", array("idsubject" => $subject_chat), [USERS_TABLE . ' u'], ["u.idusers=c.iduser"], "CONCAT('','" . URL_USUARIOS . "', profileimage) as profileimage, c.chatmessage, chatdate, idchats,u.fullname,IF(iduser='" . $this->user . "', 'true', 'false') as owner,c.statuscode");
        foreach ($data['chat'] as $key => $value) {
          $data['chat'][$key]['chatdate'] = date('H:i', strtotime($value['chatdate']));
          if ($value['owner'] == "true") {
            $data['chat'][$key]['owner'] = true;
          }
          if ($value['owner'] == "false") {
            $data['chat'][$key]['owner'] = false;
          }
          $data['chat'][$key]['readed'] = $value['statuscode'] == "2" ? true : false;
        }
        $data['user'] = $this->ss->getCampos(USERS_TABLE . " u", array("idusers" => $to), NULL, "IF(identi=1, CONCAT('', '" . base_url() . "uploads/images/usuarios/', profileimage), profileimage) as profileimage,fullname, idusers, 'true' as perfil")[0];
        break;
      default:
        $data['content'] = "";
        $data['chat'] = [];
        break;
    }
    //$this->ss->updaterequest(TABLE_PREFIX."chats",  array("idsubject"=>$subject_chat,"iduser != "=>$this->user), array("statuscode"=>2));
    return $data;
  }
  public function mensaje($iduser, $uncode)
  {
    $res = array("success" => false, "login" => false, "msj" => "Acceso restringido", "data" => null);
    if ($this->user = $this->ss->validToken($this->input->post("auth"))) {
      $info = $this->ss->getCampos(USERS_TABLE, array("idusers" => $this->user));
      $type = $this->input->post("type");
      $id = explode("_", $uncode);
      if (count($id) > 0) {
        $id = $id[1];
      } else {
        $id = null;
      }
      $find = $this->ss->getCampos(TABLE_PREFIX . "chatsxusuario", array("idsubject" => $uncode, "iduser" => $this->user));
      if (count($find) == 0) {
        $data = array(
          "iduser" => $this->user,
          "idsubject" => $uncode,
          "statuscode" => 0,
          "typechat" => $type,
          "extraid" => $id,
          "idback" => $iduser
        );
        $data1 = array(
          "iduser" => $iduser,
          "idsubject" => $uncode,
          "statuscode" => 0,
          "typechat" => $type,
          "extraid" => $id,
          "idback" => $this->user
        );
        $q = $this->ss->pushtodb($data, TABLE_PREFIX . "chatsxusuario");
        $q1 = $this->ss->pushtodb($data1, TABLE_PREFIX . "chatsxusuario");
      }
      $text = str_replace('"', "'", $this->input->post("text"));
      $random = $this->input->post("random");
      $hora = $this->input->post("hour");
      $fecha = date('Y-m-d ') . $hora;
      $data = array(
        "iduser" => $this->user,
        "idsubject" => $uncode,
        "chatmessage" => $text,
        "chatdate" => $fecha,
        "statuscode" => 1
      );
      $q = $this->ss->pushtodb($data, TABLE_PREFIX . "chats", true);
      if ($q) {
        $send_push = true;
        $find_back = $this->ss->getCampos(TABLE_PREFIX . "chatsxusuario", array("idsubject" => $uncode, "iduser" => $iduser, "statuscode" => 0));
        $res['otro'] = $find_back;
        if (count($find_back) == 0) {
          $send_push = false;
        }
        if ($send_push) {
          $_WHO = $info[0]['fullname'];
          if ($type == 2) {
            $ayuda = $this->ss->getCampos(TABLE_PREFIX . "ayudas", array("idayudas" => $id));
            if (count($ayuda) && $ayuda[0]['perfil'] == 1 && $ayuda[0]['idusuario'] != $iduser) {
              $_WHO = "Anónimo - (" . $ayuda[0]['titulo'] . ")";
            } else {
              $_WHO .= " - (" . $ayuda[0]['titulo'] . ")";
            }
          }
          if (function_exists('curl_init')) {
            $this->mensajeria->pushAplication($iduser, $_WHO . ': ' . $text, ["chat_conexion" => ["id" => $info[0]['idusers'], "subject" => $uncode]]);
          }
        }
      }
      $res['success'] = true;
      $res['id'] = $q;
      $res['random'] = $random;
    }
    echo json_encode($res);
  }

  public function getSpecialities()
  {
    try {
      $result = $this->ss->execsql("SELECT f4.id as id, f4.speciality as item from ss_users f1
        inner join ss_doctor_users f2 on f1.idusers = f2.idusers
        inner join ss_doctor_specilities f3 on f3.iddoctor = f2.iddoctor
        inner join ss_specilities f4 on f4.id = f3.idspeciality
        where f1.profile = 3
        GROUP BY f4.id;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Especialidades cargadas correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  public function getCities()
  {
    try {
      $result = $this->ss->execsql("SELECT f2.idcity as id, f2.name as item from ss_users f1
      inner join ss_cities f2 on f2.idcity = f1.idcity 
      where f1.profile = 3
      and f2.idcity not in (10236)
      GROUP BY f2.idcity;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
      }

      $res['data']['cities'] = $data;

      $city = new stdClass();
      $city->id = 10236;
      $city->item = "Medellín";

      $res['data']['defaultCity'] = array($city);
      $res['success'] = true;
      $res['message'] = "Ciudades cargadas correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  public function getDoctors()
  {
    try {

      $data = json_decode(file_get_contents("php://input"));

      $fields = "*";

      $query = "SELECT $fields from ss_users f1";
      $query .= " inner join ss_cities f2 on f1.idcity = f2.idcity";
      $query .= " inner join ss_doctor_users f3 on f3.idusers = f1.idusers";
      $query .= " inner join ss_doctor_specilities f4 on f4.iddoctor = f3.iddoctor ";
      $query .= " inner join ss_specilities f5 on f5.id = f4.idspeciality";

      /**Para filtros mas específicos se muestran a continuación */

      /* $query .= " inner join ss_doctor_services f6 on f6.iddoctor = f3.iddoctor";
       $query .= " inner join ss_doctor_consultorio f7 on f7.iddoctor = f3.iddoctor";
       $query .= " inner join ss_consultorio ssc on ssc.id = f7.idconsult";
       $query .= " inner join ss_doctor_epseguro f8 on f8.iddoctor = f3.iddoctor";
       $query .= " inner join ss_eps_segur f9 on f9.id = f8.ideps";
       $query .= " inner join ss_doctor_valoracion f10 on f10.iddoctor = f3.iddoctor";  */

      $conds = "where profile = 3";

      if ($data && $data->search) {
        $data = $data->search;

        if ($data->idcity && $data->idcity !== null) {
          $conds .= " and f2.idcity = " . $data->idcity;
        }

        if ($data->speciality && $data->speciality !== null && count($data->speciality) > 0) {
          $conds .= " and f5.id in (";

          $cont = 0;

          foreach ($data->speciality as $speciality) {
            $cont++;
            if ($cont == 1) {
              $conds .= "$speciality";
            } else {
              $conds .= ",$speciality";
            }
          }

          $conds .= ")";
        }

        /* if ($data->servicio && $data->servicio !== null) {
           $conds .= " and f6.id = " . $data->servicio;
         }
 
         if ($data->consultorio && $data->consultorio !== null) {
           $conds .= " and f7.idconsult = " . $data->consultorio;
         }
 
         if ($data->seguro_eps && $data->seguro_eps !== null) {
           $conds .= " and f9.id = " . $data->seguro_eps;  //Es el id de la tabla ss_eps_segur
         }*/

        /*if ($data->valoracion && $data->valoracion !== null) {
           $conds .= " and f10.id = " . $data->valoracion;  //Aquí falta definir como se desea la busqueda de la valoración
         }*/
      }

      $result = $this->ss->execsql("$query $conds group by f1.idusers;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Doctores cargados correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  public function getDoctorById($id)
  {
    try {

      if ($id && $id !== null) {
        $result = $this->ss->execsql("SELECT * from ss_users f1 where f1.idusers = $id and f1.profile = 3");
      } else {
        throw new Exception("Error: No data sent", 1);
      }

      if ($result['success']) {
        $data = $result['data'][0];
      } else {
        throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Doctores cargadas correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  public function updateStatusChatByUserId($id)
  {
    try {
      $data = json_decode(file_get_contents("php://input"));

      if ($id && $id !== null && $data && $data->status) {
        $update = $this->ss->update([
          'in_chat' => $data->status
        ], [
          'idusers' => $id
        ], 'ss_users');
      } else {
        throw new Exception("Error: No data sent", 1);
      }

      $res['data'] = $update;
      $res['success'] = true;
      $res['message'] = "Estado actualizado correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /***********************filtros especificos ********************/

  /**filtro por servicio */

  public function getServicio()
  {
    try {
      $result = $this->ss->execsql("SELECT sds.id as id, sds.amount as amount, sds.service as item from ss_users ssu

        inner join ss_doctor_users sdu on ssu.idusers = sdu.idusers
        inner join ss_doctor_services sds on sds.iddoctor = sdu.iddoctor       
        
        where ssu.profile = 3
        GROUP BY sds.id;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Servicios cargados correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /**filtro por consultorio */

  public function getConsultorio()
  {
    try {
      $result = $this->ss->execsql("SELECT sdc.id as id, sdc.idconsult as item from ss_users ssu

        inner join ss_doctor_users sdu on ssu.idusers = sdu.idusers
        inner join ss_doctor_consultorio sdc on sdc.iddoctor = sdu.iddoctor       
        
        where ssu.profile = 3
        GROUP BY sdc.id;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Consultorios cargados correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /**filtro por Eps/seguro */

  public function getEps()
  {
    try {
      $result = $this->ss->execsql("SELECT ses.id as id, ses.seguroeps as item from ss_users ssu

        inner join ss_doctor_users sdu on ssu.idusers = sdu.idusers
        inner join ss_doctor_epseguro sde on sde.iddoctor = sdu.iddoctor
        inner join ss_eps_segur ses on ses.id = sde.ideps       
        
        where ssu.profile = 3
        GROUP BY ses.id;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Eps cargadas correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /**filtro por valoracion rating del médico */

  public function getRating()
  {
    try {
      $result = $this->ss->execsql("SELECT sdv.id as id, sdv.puntualidad as puntualidad,
       sdv.atencion as atencion, sdv.instalaciones as instalaciones from ss_users ssu
        inner join ss_doctor_users sdu on ssu.idusers = sdu.idusers
        inner join ss_doctor_valoracion sdv on sdv.iddoctor = sdu.iddoctor
               
        
        where ssu.profile = 3
        GROUP BY sdv.id;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Valoración cargada correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /**filtro por género del médico */

  public function getGenero()
  {

    /**el género se encuentra en la DB con el '1' para masculino y el '2' para femenino */

    try {

      $jsonArray = file_get_contents('php://input');
      $data      = json_decode($jsonArray, true);
      $genero = $data['genero'];


      $result = $this->ss->execsql("SELECT ssu.idusers as id, ssu.fullname as fullname from ss_users ssu
        
        where ssu.profile = 3 AND ssu.gender = '$genero'
        GROUP BY ssu.idusers;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Género cargado correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  /**Detalles del médico  con información relevante sobre éste*/

  public function getDetalleDoctor()
  {
    try {

      $jsonArray = file_get_contents('php://input');
      $dato      = json_decode($jsonArray, true);
      $idusers = $dato['idusers'];

      $result = $this->ss->execsql("SELECT ssu.fullname as fullname, ssu.email as email, ssu.address as addres, 
      ssu.phonenumber, sdu.especiality as especiality, sdu.enferme_trat as enfermedades, sdu.time_start as timestart
      , sdu.time_end as timeend, ssp.speciality as specility, ssp.descrip as descripcion_especi, ssp.tipo as tipo,
      sdsv.service as services, sdsv.duration as duration_cita, sdsv.amount as amount, sdv.puntualidad as puntualidad,
       sdv.atencion as atencion, sdv.instalaciones as instalaciones, 
       ses.id as id_seg, ses.seguroeps as seguroeps, ses.tipo as tipo_seguro,
       ssc.idcity as idcity, ssc.name as namecity            
      
       from ss_users ssu

        inner join ss_doctor_users sdu on ssu.idusers = sdu.idusers
        inner join ss_doctor_specilities sds on sds.iddoctor = sdu.iddoctor
        inner join ss_specilities ssp on ssp.id = sds.idspeciality
        inner join ss_doctor_services sdsv on sdsv.iddoctor = sdu.iddoctor
        inner join ss_doctor_valoracion sdv on sdv.iddoctor = sdu.iddoctor
        inner join ss_doctor_epseguro sde on sde.iddoctor = sdu.iddoctor
        inner join ss_eps_segur ses on ses.id = sde.ideps 
        inner join ss_cities ssc on ssc.idcity = ssu.idcity             
        
        where ssu.profile = 3 AND ssu.idusers = '$idusers'
        ;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }
      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Detalle Doctor cargado correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

  public function getDoctorsTotal()
  {
    try {

      $data = json_decode(file_get_contents("php://input"));

      $fields = "*";

      $query = "SELECT $fields from ss_users f1";
      $query .= " inner join ss_cities f2 on f1.idcity = f2.idcity";
      $query .= " inner join ss_doctor_users f3 on f3.idusers = f1.idusers";
      $query .= " inner join ss_doctor_specilities f4 on f4.iddoctor = f3.iddoctor ";
      $query .= " inner join ss_specilities f5 on f5.id = f4.idspeciality";

      /**Para filtros mas específicos se muestran a continuación */

      $query .= " inner join ss_doctor_services f6 on f6.iddoctor = f3.iddoctor";
      $query .= " inner join ss_doctor_consultorio f7 on f7.iddoctor = f3.iddoctor";
      $query .= " inner join ss_doctor_epseguro f8 on f8.iddoctor = f3.iddoctor";
      $query .= " inner join ss_eps_segur f9 on f9.id = f8.ideps";
      $query .= " inner join ss_doctor_valoracion f10 on f10.iddoctor = f3.iddoctor";

      $conds = "where profile = 3";

      if ($data && $data->search) {
        $data = $data->search;

        if ($data->idcity && $data->idcity !== null) {
          $conds .= " and f2.idcity = " . $data->idcity;
        }

        if ($data->speciality && $data->speciality !== null && count($data->speciality) > 0) {
          $conds .= " and f5.id in (";

          $cont = 0;

          foreach ($data->speciality as $speciality) {
            $cont++;
            if ($cont == 1) {
              $conds .= "$speciality";
            } else {
              $conds .= ",$speciality";
            }
          }

          $conds .= ")";
        }

        if ($data->servicio && $data->servicio !== null) {
          $conds .= " and f6.id = " . $data->servicio;
        }

        if ($data->consultorio && $data->consultorio !== null) {
          $conds .= " and f7.idconsult = " . $data->consultorio;
        }

        if ($data->seguro_eps && $data->seguro_eps !== null) {
          $conds .= " and f9.id = " . $data->seguro_eps;  //Es el id de la tabla ss_eps_segur
        }

        /*if ($data->valoracion && $data->valoracion !== null) {
          $conds .= " and f10.id = " . $data->valoracion;  //Aquí falta definir como se desea la busqueda de la valoración
        }*/
      }

      $result = $this->ss->execsql("$query $conds group by f1.idusers;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un erro, intentelo mas tarde");
      }

      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Doctores cargados correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {

      echo json_encode($res);
    }
  }

  /**Detalles del paciente  con información relevante sobre éste*/

  public function getDetallePaciente()
  {
    try {

      $jsonArray = file_get_contents('php://input');
      $dato      = json_decode($jsonArray, true);
      $idpatient = $dato['idpatient'];

      $result = $this->ss->execsql("SELECT * FROM ss_doctor_patients sdp

        inner join ss_doctor_appointments sda on sdp.id = sda.idpatient               
        
        where sdp.id = '$idpatient'
        ;");

      if ($result['success']) {
        $data = $result['data'];
      } else {
        throw new Exception("Ha ocurrido un error, intentelo más tarde");
      }
      $res['data'] = $data;
      $res['success'] = true;
      $res['message'] = "Detalle Paciente cargado correctamente";
    } catch (Exception $e) {
      $res['data'] = null;
      $res['success'] = false;
      $res['message'] = $e->getMessage();
    } finally {
      echo json_encode($res);
    }
  }

}
