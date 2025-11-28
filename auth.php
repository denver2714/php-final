<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$stmt = $db->prepare("SELECT id FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
if (!$stmt->fetch()) {

    session_destroy();
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
