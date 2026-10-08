<?php
header('Content-Type: application/json');
require 'db.php';

$room_id = (int)($_GET['room_id'] ?? 0);
$user_id = (int)($_GET['user_id'] ?? 0);

if (!$room_id) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.text, m.created_at, m.user_id AS from_user_id, 
               m.room_id, m.is_read, m.image_path, m.image_paths, m.file_paths,
               u.first_name, u.last_name, u.avatar_path
        FROM messages m
        JOIN users u ON m.user_id = u.id
        WHERE m.room_id = ?
        ORDER BY m.id ASC
    ");
    $stmt->execute([$room_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array_map(function($row) {
        return [
            'id'           => (int)$row['id'],
            'text'         => $row['text'],
            'file_paths'   => $row['file_paths'] ? json_decode($row['file_paths'], true) : null,
            'from'         => (int)$row['from_user_id'],
            'from_user_id' => (int)$row['from_user_id'],
            'room_id'      => (int)$row['room_id'],
            'is_read'      => (int)$row['is_read'],
            'username'     => trim($row['first_name'] . ' ' . $row['last_name']),
            'avatar_path'  => $row['avatar_path'],
            'created_at'   => $row['created_at']
                ? date('H:i', strtotime($row['created_at']))
                : '00:00',
        ];
    }, $rows));

} catch (Exception $e) {
    error_log("Ошибка get_history: " . $e->getMessage());
    echo json_encode([]);
}
?>
