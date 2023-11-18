<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo json_encode(['success' => false, 'data' => $heading, 'message' => $message]);