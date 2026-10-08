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
$mime     = mime_content_type($file['tmp_name']);
$origName = $file['name'];
$size     = $file['size'];

if ($size > 100 * 1024 * 1024) {
    echo json_encode(['error' => 'Файл слишком большой (макс. 100 МБ)']);
    exit;
}

// --- СОРТИРОВКА ПО ПАПКАМ ---
$type = 'files';

if (str_starts_with($mime, 'image/')) {
    $type = 'images';
} elseif (str_starts_with($mime, 'video/')) {
    $type = 'videos';
} elseif (str_starts_with($mime, 'audio/')) {
    $type = 'audio';
} elseif (str_starts_with($mime, 'application/pdf')) {
    $type = 'documents';
} elseif (in_array($mime, [
    'application/zip', 'application/x-rar-compressed',
    'application/x-7z-compressed', 'application/gzip',
]) || preg_match('/\.(zip|rar|7z|tar|gz)$/i', $origName)) {
    $type = 'archives';
} elseif (in_array($mime, [
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/plain', 'text/csv', 'application/rtf',
])) {
    $type = 'documents';
}

// --- СОЗДАНИЕ ПАПКИ ---
$targetDir = __DIR__ . '/uploads/' . $type;

if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0775, true)) {
        error_log("mkdir failed: " . $targetDir);
        echo json_encode(['error' => 'Не удалось создать папку: ' . $type]);
        exit;
    }
}

// --- СОХРАНЕНИЕ ФАЙЛА ---
$ext        = pathinfo($origName, PATHINFO_EXTENSION);
$safeName   = bin2hex(random_bytes(8)) . ($ext ? '.' . $ext : '');
$targetPath = $targetDir . '/' . $safeName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    error_log("move_uploaded_file failed: " . $targetPath);
    echo json_encode(['error' => 'Не удалось сохранить файл']);
    exit;
}

// --- ТИП ДЛЯ ФРОНТА ---
$mediaType = 'file';
if ($type === 'images')     $mediaType = 'image';
elseif ($type === 'videos') $mediaType = 'video';
elseif ($type === 'audio')  $mediaType = 'audio';

$webPath = '/uploads/' . $type . '/' . $safeName;

echo json_encode([
    'path' => $webPath,
    'type' => $mediaType,
    'mime' => $mime,
    'name' => $origName,
    'size' => $size,
]);
?>
