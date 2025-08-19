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
$username = $_SESSION['username'];
$clientResponse = $_POST['response'];

$pdo = new PDO('mysql:host=localhost;dbname=qula_cava;charset=utf8', 'client', 'password');
try {
    $passphraseQuery = $pdo->query("SELECT passphrase FROM user_data WHERE user_name = '$username'");
} catch (Exception $exception) {
    $response['message'] = 'internal server error';
    echo json_encode($response, true);
    exit;
}
if (empty($passphraseQuery)) {
    $response['message'] = 'no such user';
    echo json_encode($response, true);
    exit;
}
$passphrase = $passphraseQuery->fetchColumn();

$challenge = $_SESSION['challenge'];
$concatenated = bin2hex($passphrase) . $challenge;
$expectedResponse = hash('sha256', $concatenated);
if ($expectedResponse != $clientResponse) {
    $response['message'] = 'something went wrong';
    echo json_encode($response, true);
    exit;
}

try {
    $userDataQuery = $pdo->query("SELECT udt.id, uig.user_ingame_name FROM user_data udt INNER JOIN user_ingame_data uig ON udt.id = uig.user_id WHERE udt.user_name = '$username'");
} catch (Exception $exception) {
    $response['message'] = 'failed to fetch data';
    echo json_encode($response, true);
    exit;
}
if (empty($userDataQuery)) {
    $response['message'] = 'failed to fetch user data';
    echo json_encode($response, true);
    exit;
}
$userData = $userDataQuery->fetch();
$userId = $userData['id'];
$userIngameName = $userData['user_ingame_name'];
$response['message'] = "Hello, $userIngameName !";

$_SESSION['expectedResponse'] = $expectedResponse;
$_SESSION['userId'] = $userId;
echo json_encode($response, true);
exit;
