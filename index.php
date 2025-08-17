<?php
$response = array('message' => '');
if (!isset($_POST['username'])) {
    $response['message'] = 'data not set';
    echo json_encode($response, true);
    exit;
}
$username = htmlspecialchars($_POST['username']);
$pdo = new PDO('mysql:host=localhost;dbname=qula_cava;charset=utf8', 'client', 'password');
try {
    $count = $pdo->query("SELECT count(*) FROM user_data WHERE user_name = '$username'");
} catch (Exception $exception) {
    $response['message'] = $exception->getMessage();
    echo json_encode($response, true);
    exit;
}
if ($count->fetch() <= 0) {
    $response['message'] = 'no such username';
    echo json_encode($response, true);
    exit;
}
session_start();
$_SESSION['username'] = $username;
$challange = "Hello, world!";
$_SESSION['challange'] = $challange;
$response['message'] = "to next";
$response['challange'] = $challange;
echo json_encode($response, true);
exit;
