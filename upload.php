<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Файл не загружен']);
    exit;
}

$file     = $_FILES['file'];
$origName = $file['name'];
$size     = $file['size'];

// Ограничение 100 МБ
if ($size > 100 * 1024 * 1024) {
    echo json_encode(['error' => 'Файл слишком большой (макс. 100 МБ)']);
    exit;
}

// Безопасное имя
$ext = pathinfo($origName, PATHINFO_EXTENSION);
$safeName = bin2hex(random_bytes(8)) . ($ext ? '.' . strtolower($ext) : '');

// Определение типа
$mime = mime_content_type($file['tmp_name']);
$type = 'files';

// Для картинок — дополнительная проверка
if (str_starts_with($mime, 'image/')) {
    $info = @getimagesize($file['tmp_name']);
    if (!$info) {
        echo json_encode(['error' => 'Некорректный файл изображения']);
        exit;
    }
    $type = 'images';
} elseif (str_starts_with($mime, 'video/')) {
    $type = 'videos';
} elseif (str_starts_with($mime, 'audio/')) {
    $type = 'audio';
} elseif (in_array($mime, ['application/pdf'], true)) {
    $type = 'documents';
} elseif (preg_match('/\.(zip|rar|7z|tar|gz)$/i', $origName)) {
    $type = 'archives';
} elseif (in_array($mime, [
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'text/plain', 'text/csv'
], true)) {
    $type = 'documents';
}

// Папка
$baseDir = __DIR__ . '/uploads';
$targetDir = $baseDir . '/' . $type;

if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0755, true)) {
        error_log("mkdir failed: " . $targetDir);
        echo json_encode(['error' => 'Не удалось создать папку: ' . $type]);
        exit;
    }
}

$targetPath = $targetDir . '/' . $safeName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    error_log("move_uploaded_file failed: " . $targetPath);
    echo json_encode(['error' => 'Не удалось сохранить файл']);
    exit;
}

// Относительный путь для фронтенда
$webPath = '/uploads/' . $type . '/' . $safeName;

echo json_encode([
    'path'      => $webPath,
    'type'      => $type,
    'mime'      => $mime,
    'name'      => $origName,
    'size'      => $size,
    'safe_name' => $safeName, // можно сохранить в БД, если нужно
]);
?>