<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$resp = [
	'success' => false,
	'type' => $severity,
	'severity' => $severity,
	'message' => 'A PHP Error was encountered. '.$message,
	'filename' => $filepath,
	'line' => $line,
];

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) {
	$resp['backtrace'] = [];
	foreach (debug_backtrace() as $error) {
		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) {
			$resp['backtrace'][] = [
				'file' => $error['file'],
				'line' => $error['line'],
				'function' => $error['function'],
			];
		}
	}
}
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo json_encode($resp);
