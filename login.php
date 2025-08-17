<?php
header('Content-Type: application/json; charset=UTF-8');
$response = array('message' => 'none');
$rawContent = file_get_contents('php://input');
if (!isset($rawContent)) {
    $response['message'] = 'Invalid request.';
    echo json_encode($response);
    exit;
}
session_start();
$content = json_decode($rawContent, true);
$_SESSION['client'] = $content['username'];
echo json_encode($response);
