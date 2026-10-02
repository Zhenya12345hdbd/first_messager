<?php
require 'vendor/autoload.php';
require 'db.php';

header('Content-Type: application/json');

$uploadDir = 'uploads/avatars/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сначала проверяем обязательные поля
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$firstName || !$lastName || !$password) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Заполните все поля']);
        exit;
    }

    // Только если поля в порядке — работаем с файлом
    $avatarPath = null; // По умолчанию нет аватара

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/avif', 'image/webp'];

        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Только JPG, PNG, AVIF, WEBP']);
            exit;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'user_' . time() . '_' . rand(100, 999) . '.' . $extension;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $avatarPath = '/' . $targetPath; // Путь для браузера
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Не удалось сохранить файл']);
            exit;
        }
    }

    // Хешируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Вставляем пользователя. avatar_path может быть NULL, если не загружали фото
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, password, avatar_path) VALUES (?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $hashedPassword, $avatarPath]);

        $userId = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName, // Исправлено: было last-name
            'avatar_path' => $avatarPath
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Ошибка БД']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не разрешён']);
}
?>
