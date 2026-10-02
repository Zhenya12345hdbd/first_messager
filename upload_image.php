<?php
header('Content-Type: application/json');

$uploadDir = __DIR__ . '/uploads/chat_images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Файл не передан']);
    exit;
}

$file = $_FILES['file'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(415);
    echo json_encode(['error' => 'Только JPG/PNG/WEBP/GIF']);
    exit;
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка загрузки файла']);
    exit;
}

// Проверяем, что это действительно картинка
$check = getimagesize($file['tmp_name']);
if ($check === false) {
    http_response_code(415);
    echo json_encode(['error' => 'Файл не является изображением']);
    exit;
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_') . '_' . time() . '.' . $ext;
$destPath = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $destPath)) {
    $relativePath = 'uploads/chat_images/' . $filename;
    echo json_encode(['path' => $relativePath]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Не удалось сохранить файл']);
}
?>
