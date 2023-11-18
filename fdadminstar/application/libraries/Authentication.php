<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Authentication extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->output->delete_cache();
        $this->load->helper(array('url_helper','url','form'));
        $this->load->library(array('session','form_validation'));
        $this->load->model('Ss');
    }

    public static function validSession() {
        if(null != $this->session->userdata('token') && !empty($this->session->userdata('token'))) {
            return true;
        }
        return false;
    }
}