<?php
$response = array('message' => '', 'data' => '');
if (!isset($_POST['username'])) {
    $response['message'] = 'data not set';
    echo json_encode($response, true);
    exit;
}
$username = htmlspecialchars($_POST['username']);
$pdo = new PDO('mysql:host=localhost;dbname=qula_cava;charset=utf8', 'client', 'password');
try {
    $doesUserExists = $pdo->query("SELECT count(*) FROM user_data WHERE user_name = '$username'");
} catch (Exception $exception) {
    $response['message'] = $exception->getMessage();
    echo json_encode($response, true);
    exit;
}
if (!$doesUserExists->fetch()) {
    $response['message'] = 'no such user';
    echo json_encode($response, true);
    exit;
}

session_start();
$_SESSION['username'] = $username;

$challenge = hash('sha256', random_bytes(16));
$_SESSION['challenge'] = $challenge;

$response['message'] = '';
$response['data'] = $challenge;
echo json_encode($response, true);
exit;
