<?php
$response = array('message' => '');
if (!isset($_POST['username'])) {
    $response['message'] = 'data not set';
    echo json_encode($response, true);
    exit;
}
session_start();
$username = $_SESSION['username'];
if (!isset($_SESSION['username'])) {
    $response['message'] = 'user has logged out';
    echo json_encode($response, true);
    exit;
}
unset($_SESSION['username']);
unset($_SESSION['challenge']);
unset($_SESSION['expectedResponse']);
unset($_SESSION['userId']);

$response['message'] = 'logged out successfully';
echo json_encode($response, true);
exit;
