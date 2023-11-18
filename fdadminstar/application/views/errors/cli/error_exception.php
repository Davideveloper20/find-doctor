<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

$resp = [
	'success' => false,
	'type' => get_class($exception),
	'message' => 'An uncaught Exception was encountered. '.$message,
	'filename' => $exception->getFile(),
	'line' => $exception->getLine(),
]; 
if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) {

	$resp['backtrace'] = [];
	foreach ($exception->getTrace() as $error) {
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
