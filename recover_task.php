<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $db->prepare("UPDATE tasks SET status = 'active' WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id']]);
}

header('Location: deleted_tasks.php');
exit;
