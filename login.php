<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username']) || !isset($input['password'])) { // username тут = имя + фамилия или email
    http_response_code(400);
    echo json_encode(['error' => 'Введите имя и пароль']);
    exit;
}

// Для простоты ищем по комбинации имени и фамилии. 
// В реальном проекте лучше использовать email как уникальный логин.
$fullName = $input['username']; 
$parts = explode(' ', $fullName);
$first = $parts[0] ?? '';
$last = $parts[1] ?? '';
$password = $input['password'];

if (empty($first) || empty($last) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Заполните все поля']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE first_name = ? AND last_name = ?");
    $stmt->execute([$first, $last]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Пароль верный!
        echo json_encode([
            'success' => true,
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'avatar_path' => $user['avatar_path']
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Неверное имя или пароль']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера']);
}
?>
