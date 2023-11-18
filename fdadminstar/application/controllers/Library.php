<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("vendor/autoload.php");
class Library extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url_helper','url','form','directory'));
        $this->load->library(array('session','form_validation'));
        $this->load->model("fn");
        $this->profile=2;
        $this->table=TABLE_PREFIX."libraries";
    }
    public function index()
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession())
        {
            $my_files = [];
            $_dbfile = $this->ss->getCampos($this->table,["statuscode"=>1]);
            $files = directory_map('./uploads/images', FALSE, TRUE);
            foreach ($_dbfile as $key => $file) 
            {
                if(file_exists('./uploads/images/'.$file['file_location']))
                {
                    $my_files[] = array(
                        "url"=>base_url().'uploads/thumbnails/'.$file['file_thumb'], 
                        "name"=>$file['file_name'], 
                        "id"=>$file['idlibraries'],
                        "location"=>base_url().'uploads/images/'.$file['file_location']
                    );
                }
            }
            $res['data'] = $my_files;
        }
        echo json_encode($res);
    }
    public function files()
    {
        if($this->ss->validSession())
        {
            $ent='';
            $data['name'] = 'Library';
            $data['description'] = 'Manage all files uploaded';
            $data['nombretabla'] = 'Files Table';
            $data['user'] = $this->session->ssbname;//$this->session->NombreOp;
            $data['script'][] = "solstar.shortdatatable('#table_users', '".site_url("library/load")."');";
            $data['script'][] = "$('[data-nav-url=\"library\"]').addClass('active');";
            $this->load->view('templates/header', $data);
            $this->load->view($ent.'templates/sidebar');
            $this->load->view('templates/topbar');
            $this->load->view('library/index');
            $this->load->view($ent.'templates/footer');
            $this->load->view('templates/modals');
            $this->load->view('templates/libs');
        }else{
            redirect("init");
        }
    }
    public function load()
    {
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $edition=1;
        $books=$this->ss->getCampos($this->table,["statuscode"=>1],"*", 1);
        $data = array();

        foreach($books->result() as $r) 
        {
            $button='<a class="btn btn-link btn-sm" href="'.base_url('uploads/images/'.$r->file_location).'" download="'.$r->file_name.'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-download"></i></a> <button class="btn btn-link btn-sm" onclick="del_file('.$r->idlibraries.')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button> ';
            if(file_exists('./uploads/thumbnails/'.$r->file_thumb))
            {
                $file_thumb = '<a href="'.base_url('uploads/images/'.$r->file_location).'" class="popup-vimeo text-dark"><img src="'.base_url('uploads/thumbnails/'.$r->file_thumb).'" class="icon" width="30px" /></a>';
            }else{
                $file_thumb = '<a href="'.base_url('uploads/images/'.$r->file_location).'" class="popup-vimeo text-dark"><img src="'.base_url('uploads/images/'.$r->file_location).'" class="icon" width="30px" /></a>';
            }
            $status = $r->statuscode==1?"Active":"Inactive";
            $data[] = array(
                //'','<img src="'.$r->thumbnail.'" class="img-rounded img-responsive img-thumb">',
                explode('.',$r->file_name)[0],
                $file_thumb,
                $r->file_uploaded_date,
                $button
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $books->num_rows(),
            "recordsFiltered" => $books->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    private function validate_directory($path)
    {
        if(!is_dir($path))
        {
            mkdir($path, 0775);
        }   
    }
    private function upload_image($filename, $name, $path)
    {
        $this->validate_directory($path);
        if(!$name)
        {
            $name = $_FILES[$filename]['name'];
        }
        if(!file_exists($path.$name))
        {
            $config = array(
                'upload_path' => $path,
                'allowed_types' => "png|jpg|jpeg",
                'overwrite' => TRUE,
                'file_name' => $name
            );
            $this->load->library('upload', $config);
            if($this->upload->do_upload($filename))
            {
                $data = array('success'=>true,'etiqueta' => $this->upload->data('file_name'), 'error' => '', 'exists'=>false);
                return $data;
            }else{
                $error = array('success'=>false,'etiqueta' => 'silueta.png', 'error' => $this->upload->display_errors(), 'exists'=>false);
                return $error;
                //$this->load->view('custom_view', $error);
            }
        }
        return array('success' => true, 'etiqueta' => $name, 'error' => '', 'exists'=>true);
    }
    public function add_image()
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        $signup = $this->input->post("signup");
        $res['ext'] = $this->ss->valid_token($this->input->post("auth"));
        if($this->ss->validSession() || $this->user = $this->ss->valid_token($this->input->post("auth")))
        {
            $location = $this->input->post("location");
            $file_name = $location=="usuarios"?"IMG_".uniqid().".jpg":NULL;
            $res['user'] = $location;
            $file = $this->upload_image("X-File-Name", $file_name, "./uploads/images/".$location."/");
            if($file['success'])
            {
                if(!$file['exists'])
                {
                    $thumb = $this->ss->get_image_thumbnail("./uploads/images/".$location."/".$file['etiqueta'], uniqid().".jpg");
                    $data = array(
                        "file_name"=>$file['etiqueta'],
                        "file_location"=>$location."/".$file['etiqueta'],
                        "file_thumb"=>$thumb,
                        "file_type"=>"image/jpg",
                        "file_uploaded_date"=>$this->ss->fechadehoy(),
                        "statuscode"=>1
                    );
                    $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                    $res['img'] = base_url('uploads/images/'.$location.'/'.$file['etiqueta']);
                }else{
                    $id_libraries = $this->ss->getCampos($this->table, ["file_name"=>$_FILES['X-File-Name']['name']]);
                    if(count($id_libraries)==0)
                    {
                        $file = $this->upload_image("X-File-Name", "IMG_".uniqid().".jpg", "./uploads/images/".$location."/");
                        $thumb = $this->ss->get_image_thumbnail("./uploads/images/".$location."/".$file['etiqueta'], uniqid().".jpg");
                        $data = array(
                            "file_name"=>$file['etiqueta'],
                            "file_location"=>$location."/".$file['etiqueta'],
                            "file_thumb"=>$thumb,
                            "file_type"=>"image/jpg",
                            "file_uploaded_date"=>$this->ss->fechadehoy(),
                            "statuscode"=>1
                        );
                        $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                        $res['img'] = base_url('uploads/images/'.$location.'/'.$file['etiqueta']);
                    }else{
                        $res['img'] = base_url('uploads/images/'.$id_libraries[0]['file_location']);
                        $id_libraries = $id_libraries[0]['idlibraries'];
                    }
                }
                $res['success'] = true;
                $res['data'] = $id_libraries;
                $res['id'] = $id_libraries;
                $res['msj'] = "La imagen se ha cargado correctamente";
            }else{
                $res['msj'] = "Something happen while trying to upload the file: ".$file['error'].". Try again in a few minutes";
            }
        }
        echo json_encode($res);
    }
    private function upload_video($filename, $name, $path)
    {
        $this->validate_directory($path);
        if(!$name)
        {
            $name = $_FILES[$filename]['name'];
        }
        if(!file_exists($path.$name))
        {
            $config = array(
                'upload_path' => $path,
                'allowed_types' => "mp4|ogg|mpeg",
                'overwrite' => TRUE,
                'file_name' => $name
            );
            $this->load->library('upload', $config);
            if($this->upload->do_upload($filename))
            {
                $data = array('success'=>true,'etiqueta' => $this->upload->data('file_name'), 'error' => '', "exists"=>false);
                return $data;
            }else{
                $error = array('success'=>false,'etiqueta' => 'siluetaVino.png', 'error' => $this->upload->display_errors(), "exists"=>false);
                return $error;
                //$this->load->view('custom_view', $error);
            }
        }
        return array('success' => true, 'etiqueta' => $name, 'error' => '', 'exists'=>true);
    }
    public function add_video()
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession())
        {   
            $location = $this->input->post("location");
            $file = $this->upload_video("X-File-Name", FALSE, "./uploads/images/".$location."/");
            $res['MENSAJE'] = $file;
            if($file['success'])
            {
                if(!$file['exists'])
                {
                    $thumb = "video_thumb.png"; 
                    //$this->ss->get_video_thumbnail("./uploads/images/".$location."/".$file['etiqueta']);
                    $data = array(
                        "file_name"=>$file['etiqueta'],
                        "file_location"=>$location."/".$file['etiqueta'],
                        "file_thumb"=>$thumb,
                        "file_type"=>"video/mp4",
                        "file_uploaded_date"=>$this->ss->fechadehoy(),
                        "statuscode"=>1
                    );
                    $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                    $res['img'] = base_url('uploads/images/'.$location.'/'.$file['etiqueta']);
                }else{
                    $id_libraries = $this->ss->getCampos($this->table, ["file_name"=>$_FILES['X-File-Name']['name']]);
                    if(count($id_libraries)==0)
                    {
                        $file = $this->upload_video("X-File-Name", "VID_".uniqid().".mp4", "./uploads/images/".$location."/");
                        $res['MENSAJE'] = $file;
                        $thumb = "video_thumb.png"; 
                        //$thumb = $this->ss->get_video_thumbnail("./uploads/images/".$location."/".$file['etiqueta'], uniqid().".jpg");
                        $data = array(
                            "file_name"=>$file['etiqueta'],
                            "file_location"=>$location."/".$file['etiqueta'],
                            "file_thumb"=>$thumb,
                            "file_type"=>"video/mp4",
                            "file_uploaded_date"=>$this->ss->fechadehoy(),
                            "statuscode"=>1
                        );
                        $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                        $res['img'] = base_url('uploads/images/'.$location.'/'.$file['etiqueta']);
                    }else{
                        $this->ss->updaterequest($this->table,["file_name"=>$_FILES['X-File-Name']['name']], ["file_thumb"=>"video_thumb.png"]);
                        $id_libraries = $id_libraries[0]['idlibraries'];
                        $res['img'] = base_url('uploads/images/'.$id_libraries[0]['file_location']);
                    }
                }
                $res['success'] = true;
                $res['data'] = $id_libraries;
                $res['msj'] = "Video successfully loaded!";
            }else{
                $res['msj'] = "Something happen while trying to upload the video: ".$file['error'].". Try again in a few minutes";
            }
        }
        echo json_encode($res);
    }
    private function upload_document($filename, $name, $path)
    {
        $this->validate_directory($path);
        if(!$name)
        {
            $name = $_FILES[$filename]['name'];
        }
        if(!file_exists($path.$name))
        {
            $config = array(
                'upload_path' => $path,
                'allowed_types' => "pdf",
                'overwrite' => TRUE,
                'file_name' => $name
            );
            $this->load->library('upload', $config);
            if($this->upload->do_upload($filename))
            {
                $data = array('success'=>true,'etiqueta' => $this->upload->data('file_name'), 'error' => '');
                return $data;
            }else{
                $error = array('success'=>false,'etiqueta' => 'siluetaVino.png', 'error' => $this->upload->display_errors());
                return $error;
                //$this->load->view('custom_view', $error);
            }
        }
        return array('success' => true, 'etiqueta' => $name, 'error' => '', 'exists'=>true);
    }
    public function add_document()
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession())
        {
            $location = $this->input->post("location");
            $file = $this->upload_document("X-File-Name", FALSE, "./uploads/images/".$location."/");
            if($file['success'])
            {
                if(!$file['exists'])
                {
                    $url = base_url('uploads/images/'.$location."/".$file['etiqueta']);
                    $thumb = "document_thumb.png";
                    $data = array(
                        "file_name"=>$file['etiqueta'],
                        "file_location"=>$location."/".$file['etiqueta'],
                        "file_thumb"=>$thumb,
                        "file_type"=>"application/pdf",
                        "file_uploaded_date"=>$this->ss->fechadehoy(),
                        "statuscode"=>1
                    );
                    $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                }else{
                    $id_libraries = $this->ss->getCampos($this->table, ["file_name"=>$_FILES['X-File-Name']['name']]);
                    if(count($id_libraries)==0)
                    {
                        $file = $this->upload_image("X-File-Name", "IMG_".uniqid().".jpg", "./uploads/images/".$location."/");
                        $url = base_url('uploads/images/'.$location."/".$file['etiqueta']);
                        $thumb = "document_thumb.png";
                        $data = array(
                            "file_name"=>$file['etiqueta'],
                            "file_location"=>$location."/".$file['etiqueta'],
                            "file_thumb"=>$thumb,
                            "file_type"=>"application/pdf",
                            "file_uploaded_date"=>$this->ss->fechadehoy(),
                            "statuscode"=>1
                        );
                        $id_libraries = $this->ss->pushtodb($data, $this->table, true);
                    }else{
                        $url = base_url('uploads/images/'.$id_libraries[0]['file_location']);
                        $id_libraries = $id_libraries[0]['idlibraries'];
                    }
                }
                $res['success'] = true;
                $res['data'] = $id_libraries;
                $res['url'] = $url;
                $res['msj'] = "Document successfully loaded!";
            }else{
                $res['msj'] = "Something happen while trying to upload the document: ".$file['error'].". Try again in a few minutes";
            }
        }
        echo json_encode($res);
    }
    public function info($id=null)
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession() && $id)
        {
            $res['data'] = $this->ss->getCampos($this->table.' t1', array("idlessons"=>$id), null, "lesson_order,lesson_name,CONCAT('".base_url('uploads/images/lessons/')."', lesson_video) as lesson_video,lesson_content,idlessons,lesson_type,idmodule")[0];
            $res['success']=true;
            $res['msj'] = "Lesson information loaded";
        }
        echo json_encode($res);
    }
    public function edit_lesson($id=NULL)
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession() && $id)
        {
            $res['login']=true;
            $validations = ["order","type","name"];
            $validationsmsg = ["Lesson order", "Lesson type","Lesson name"];
            foreach ($validations as $key => $value) {
                $this->form_validation->set_rules($value, $validationsmsg[$key], 'required',
                    array('required' => 'You must provide %s.')
                );   
            }
            if ($this->form_validation->run() === FALSE)
            {
                $res=array("msj"=>validation_errors(),"login"=>true);
            }
            else
            {
                $data = array(
                    "lesson_name"=>$this->input->post("name"),
                    "lesson_image"=>"silueta.png",
                    "lesson_content"=>$this->input->post("content"),
                    "lesson_type"=>$this->input->post("type"),
                    "lesson_order"=>$this->input->post("order"),
                    "idmodule"=>$this->input->post("module"),
                    "statuscode"=>1,
                );
                $image = $this->do_upload("video", "V_".uniqid(), "./uploads/images/lessons/");
                if($image['success'])
                {
                    $getID3 = new getID3();
                    $filename=realpath(getcwd().'/uploads/images/lessons/'.$image['etiqueta']);
                    $fileinfo = $getID3->analyze($filename);

                    $duration=$fileinfo['playtime_string'];
                    $duration = explode(":",$duration);
                    $minutes = $duration[0]*60;
                    $duration = ($minutes+$duration[1])-1;

                    $data["lesson_video"]=$image['etiqueta'];
                    $data["lesson_duration"]=$duration;
                }
                $idlesson=$this->ss->updaterequest($this->table, ["idlessons"=>$id], $data);
                if($idlesson)
                {
                    $res["success"]=true;
                    $res["msj"]="Lesson information updated successfully";
                }else{
                    $res['msj']="Something happen while trying to update the lesson information. Try again in a few minutes";
                }
            }
        }
        echo json_encode($res);
    }
    public function cambiarestado($id)
    {
        $res = array( 'success'  =>  false, 'msj' => 'Something happen, try again in a minute' ); 
        if($this->ss->validSession())
        {
            $query = $this->ss->getCampos(USUARIOS, array("idusers" => $id));
            $row = $query[0];
            $publicado = $row['publicado'];
            $res['success'] = true;
            if($publicado == 1){
                $query = $this->ss->updateAnythingOn(USUARIOS, array("idusers"=>$id), array("estado" => 0));
                $res['msj'] = "Usuario bloqueado";
            }else{
                $query = $this->ss->updateAnythingOn(USUARIOS, array("idusers"=>$id), array("estado" => 1));
                $res['msj'] = "Usuario desbloqueado";
            }
        }
        echo json_encode($res);
    }
    public function del_file()
    {
        $res = array( 'success'  =>  false, 'msj' => 'Something happen, try again in a minute' ); 
        if($this->ss->validSession() && $this->session->hitprofile == PERFIL_SUPERADMIN)
        {
            $id = $this->input->post("file");
            $query = $this->ss->getCampos($this->table, array("idlibraries" => $id));
            if(count($query) > 0)
            {
                $res['row'] = "client_".explode(".", $query[0]['file_name'])[0];
                $query = $this->ss->updaterequest($this->table, array("idlibraries" => $id), ["statuscode"=>2]);
                if($query)
                {
                    $res['success'] = true;
                    $res['msj'] = "File information have been removed correctly";
                }
            }
            
        }
        echo json_encode($res); 
    }
    public function del_file_permanent()
    {
        $res = array( 'success'  =>  false, 'msj' => 'Something happen, try again in a minute' ); 
        if($this->ss->validSession() && $this->session->hitprofile == PERFIL_SUPERADMIN)
        {
            $id = $this->input->post("file");
            $query = $this->ss->getCampos($this->table, array("idlibraries" => $id));
            if(count($query) > 0)
            {
                unlink('./uploads/images/'.$query[0]['file_location']);
                unlink('./uploads/thumbnails/'.$query[0]['file_thumb']);
                $query = $this->ss->deleterequest(array("idlibraries" => $id), $this->table);
                if($query)
                {
                    $res['success'] = true;
                    $res['msj'] = "File information have been removed correctly";
                }
            }
            
        }
        echo json_encode($res); 
    }
    public function getfile($type, $file_name)
    {
        switch ($type) {
            case 'video':
                # code...
                break;
            case 'image':
                # code...
                break;
            
            default:
                # code...
                break;
        }
        $file_info = $this->ss->getCampos($this->table, "file_name LIKE '%".$file_name."%'")[0];
        $file = getcwd().'/uploads/images/'.$file_info['file_location'];
        if($method && $method == "thumbnail")
        {
            header('Content-Type: image/jpg');
            $file = './uploads/thumbnails/'.$file_info['file_thumb'];
            $content = file_get_contents($file);
            echo $content;
            exit;
        }
        header('Content-Type: '.$file_info['file_type']);
        if($file_info['file_type'] != "image/jpg")
        {
            $content = file_get_contents($file);
            echo $content;
            exit;
        }
        readfile($file);
        exit;
    }
    public function base()
    {
        $res=array("success"=>false,"msj"=>"Something happen! Try again later","login"=>false);
        if($this->ss->validSession())
        {
            
        }
        echo json_encode($res);
    }

}