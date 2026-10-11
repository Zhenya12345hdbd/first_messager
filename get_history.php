<?php
header('Content-Type: application/json; charset=utf-8');
require 'db.php'; // твой PDO-подключение

$room_id = (int)($_GET['room_id'] ?? 0);

if (!$room_id) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.text, m.created_at, m.user_id AS from_user_id, 
               m.room_id, m.is_read, m.file_paths,
               u.first_name, u.last_name, u.avatar_path
        FROM messages m
        JOIN users u ON m.user_id = u.id
        WHERE m.room_id = ?
        ORDER BY m.id ASC
    ");
    $stmt->execute([$room_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = array_map(function($row) {
        // 1. Парсим file_paths из JSON (если есть)
        $filePaths = null;
        if ($row['file_paths']) {
            $decoded = json_decode($row['file_paths'], true);
            if (is_array($decoded)) {
                $filePaths = $decoded;
            }
        }

        // 2. Определяем тип сообщения: если есть файлы — ставим нужный тип
        $type = 'message';
        if (!empty($filePaths)) {
            $type = 'file_batch_with_text';
        } elseif ($row['text'] === null && empty($filePaths)) {
            // Если хочешь отдельно помечать системные — можно добавить логику
        }

        $time = $row['created_at'] 
            ? date('H:i', strtotime($row['created_at'])) 
            : '00:00';

        $username = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
        if (!$username) $username = 'Пользователь';

        return [
            'id'          => (int)$row['id'],
            'text'        => $row['text'] ?? '',
            'type'        => $type,
            'from'        => (int)$row['from_user_id'], // единый ключ для React
            'room_id'     => (int)$row['room_id'],
            'is_read'     => (int)$row['is_read'],
            'username'    => $username,
            'avatar_path' => $row['avatar_path'],
            'time'        => $time,
            'file_paths'  => $filePaths, // уже массив объектов, как ты присылал
        ];
    }, $rows);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('get_history error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}
?>
