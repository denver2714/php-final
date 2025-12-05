<?php
session_start();
require 'db.php';

#Api testing

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}


$stmt = $db->prepare("SELECT id FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    session_destroy();
    http_response_code(401);
    echo json_encode(['error' => 'User not found']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'toggle_status') {
    $id = $_POST['id'] ?? null;
    $completed = $_POST['completed'] ?? 0;
    
    if ($id) {
        $stmt = $db->prepare("UPDATE tasks SET is_completed = :completed WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':completed' => $completed, ':id' => $id, ':user_id' => $user_id]);
        echo json_encode(['success' => true]);
    }
} elseif ($action === 'update_order') {
    $order = $_POST['order'] ?? []; 
    
    if (!empty($order)) {
        $stmt = $db->prepare("UPDATE tasks SET position = :position WHERE id = :id AND user_id = :user_id");
        foreach ($order as $position => $id) {
            $stmt->execute([':position' => $position, ':id' => $id, ':user_id' => $user_id]);
        }
        echo json_encode(['success' => true]);
    }
} elseif ($action === 'clear_deleted') {
    $stmt = $db->prepare("DELETE FROM tasks WHERE user_id = :user_id AND status = 'deleted'");
    $stmt->execute([':user_id' => $user_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>
