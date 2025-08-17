<?php
$response = array('message' => '');
if (!isset($_POST['username']) || !isset($_POST['response'])) {
    $response['message'] = 'data not set';
    print_r($_POST);
    echo json_encode($response, true);
    exit;
}
session_start();
if (!isset($_SESSION['username'])) {
    $response['message'] = 'session has not started';
    echo json_encode($response, true);
    exit;
}
$pdo = $pdo = new PDO('mysql:host=localhost;dbname=qula_cava;charset=utf8', 'client', 'password');
$username = $_SESSION['username'];
$possibleResponse = $_POST['response'];
try {
    $passphraseQuery = $pdo->query("SELECT passphrase FROM user_data WHERE user_name = '$username'");
} catch (Exception $exception) {
    echo $exception->getMessage();
}
$passphrase = $passphraseQuery->fetch()['passphrase'];
if (!isset($passphrase)) {
    $response['message'] = 'no such user';
    echo json_encode($response, true);
    exit;
}
$challange = $_SESSION['challange'];
$responseAnswer = bin2hex($passphrase) . $challange;
$hashedResponse = hash('sha256', $responseAnswer);
if ($hashedResponse != $possibleResponse) {
    $response['message'] = 'something wrong';
    echo json_encode($response, true);
    exit;
}
$response['message'] = "Hello, user!";
echo json_encode($response, true);
exit;
