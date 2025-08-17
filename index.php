<?php
header('Content-Type: application/json; charset=UTF-8');
$rawPost = file_get_contents('php://input');
if (!isset($_rawPost['username'])) {
    exit;
}
session_start();
