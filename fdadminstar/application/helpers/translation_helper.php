<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('trans')) {
	function trans($key=NULL) {
		$CI = get_instance();
		if(!$key) {
			return '-null-trans-';
		}
		$ret = $CI->lang->line($key);
		if(!isset($ret) || empty($ret)) {
			return '-no-trans-('.$key.')-';
		}
		return $ret;
	}
}

if (!function_exists('set_language')) {
	function set_language($langid) {
		$CI = get_instance();
		$CI->session->set_userdata('set_lang', $langid);
	}

}

if (!function_exists('get_language')) {
	function get_language($langid) {
		$CI = get_instance();
		return $CI->session->userdata('set_lang');
	}

}
