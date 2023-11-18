<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('uploadThumbnail')) {
	function uploadThumbnail() {
		if(isset($_FILES['uploadThumb']) && $_FILES['uploadThumb']['size'] > 0) {
        	$config = array(
				'upload_path' => sys_get_temp_dir(),
				'allowed_types' => "gif|jpg|png|bmp|webp|jpeg",
				'overwrite' => TRUE,
				'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
				'max_height' => "1024",
				'max_width' => "1024"
			);
			$CI = get_instance();
			$CI->load->library('upload', $config);
			$rupf = $CI->upload->do_upload('uploadThumb');
        	if($rupf) {
        		$upload_data = $CI->upload->data();
        		if (function_exists('curl_file_create')) { // php 5.5+
			  		$cFile = curl_file_create($upload_data['full_path'], $upload_data['file_type']);
				} else { // 
			  		$cFile = '@' . realpath($upload_data['full_path']);
				}
				$post = array('private' => 0, 'extra_info' => $upload_data['file_size'],'file_contents'=> $cFile);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				    'X-Secret-Key: '.$CI->config->item('fileblocks_key'),
				));
				curl_setopt($ch, CURLOPT_URL, $CI->config->item('fileblocks_url').'/1');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$result=curl_exec($ch);
				if (curl_errno($ch)) {
			        $CI->session->set_flashdata('message_error', 'Error:' . curl_error($ch));
					curl_close ($ch);
					return false;
			    }
				curl_close ($ch);
				$result = json_decode($result);
				if($result->success) {
					return $result->data[0][0]->Location_b;
					@unlink($upload_data['full_path']);
				}
			} else {
				if($CI->upload->display_errors() != '') {
					$CI->session->set_flashdata('message_error', $CI->upload->display_errors());
					return false;
				}
			}
			return false;
		}
		return false;
	}
}

