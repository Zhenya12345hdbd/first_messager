<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$user1 = (int)($data['user1_id'] ?? 0);
$user2 = (int)($data['user2_id'] ?? 0);

if (!$user1 || !$user2 || $user1 === $user2) {
    echo json_encode(['error' => 'Неверные данные']);
    exit;
}

// Упорядочиваем, чтобы пара всегда была (меньший, больший) — для UNIQUE
$min_id = min($user1, $user2);
$max_id = max($user1, $user2);

// Проверяем, есть ли уже комната
$stmt = $pdo->prepare("SELECT id FROM rooms WHERE user1_id = ? AND user2_id = ?");
$stmt->execute([$min_id, $max_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo json_encode(['room_id' => (int)$existing['id']]);
    exit;
}

// Создаём новую
$stmt = $pdo->prepare("INSERT INTO rooms (user1_id, user2_id) VALUES (?, ?)");
$stmt->execute([$min_id, $max_id]);

echo json_encode(['room_id' => (int)$pdo->lastInsertId()]);
?>
