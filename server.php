<?php
require 'vendor/autoload.php';
require 'db.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $pdo;
    protected $users;

    public function __construct($pdo) {
        $this->clients = new \SplObjectStorage;
        $this->pdo = $pdo;
        $this->users = [];
    }

    function onOpen(ConnectionInterface $conn) {
        $conn->user_id = null;
        $conn->username = 'Гость';
        $conn->avatar_path = null;

        $this->clients->attach($conn);
        echo "Новое подключение: {$conn->resourceId}\n";
        $this->sendOnlineList($conn);
    }

    function onMessage(ConnectionInterface $from, $msg) {
        if (empty($msg)) return;
        $data = json_decode($msg, true);

        error_log("RAW MSG: " . $msg);

        if (!$data) {
            error_log("JSON PARSE FAILED: " . $msg);
            return;
        }

        // --- Авторизация ---
        if (($data['type'] ?? '') === 'auth') {
            $user_id = (int)($data['user_id'] ?? 0);

            if ($user_id > 0) {
                $stmt = $this->pdo->prepare(
                    "SELECT first_name, last_name, avatar_path FROM users WHERE id = ?"
                );
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $from->user_id     = $user_id;
                    $from->username    = $user['first_name'] . ' ' . $user['last_name'];
                    $from->avatar_path = $user['avatar_path'];
                    $this->users[$user_id] = $from;

                    $from->send(json_encode([
                        'type'     => 'auth_ok',
                        'username' => $from->username,
                    ]));

                    $this->broadcastStatus($user_id, true, $from);
                }
            }
            return;
        }

        // === ТЕКСТОВОЕ СООБЩЕНИЕ ===
        if (($data['type'] ?? '') === 'message' && isset($data['room_id'])) {
            $text    = trim($data['text'] ?? '');
            $room_id = (int)$data['room_id'];

            if ($text === '' || !$from->user_id || !$room_id) {
                return;
            }

            $stmt = $this->pdo->prepare(
                "SELECT user1_id, user2_id FROM rooms WHERE id = ?"
            );
            $stmt->execute([$room_id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Комната не найдена']));
                return;
            }

            $user1 = (int)$room['user1_id'];
            $user2 = (int)$room['user2_id'];

            if ($from->user_id !== $user1 && $from->user_id !== $user2) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Нет доступа к комнате']));
                return;
            }

            $to_id = ($from->user_id === $user1) ? $user2 : $user1;

            $stmt = $this->pdo->prepare(
                "INSERT INTO messages (user_id, to_user_id, room_id, text) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$from->user_id, $to_id, $room_id, $text]);

            $message_id = (int)$this->pdo->lastInsertId();

            $payload = json_encode([
                'type'        => 'message',
                'id'          => $message_id,
                'from'        => $from->user_id,
                'to'          => $to_id,
                'room_id'     => $room_id,
                'username'    => $from->username,
                'text'        => $text,
                'time'        => date('H:i'),
                'avatar_path' => $from->avatar_path,
                'is_read'     => 0,
            ]);

            if (isset($this->users[$to_id])) {
                $this->users[$to_id]->send($payload);
            }
            $from->send($payload);
            return;
        }

        // === МАССИВ КАРТИНОК (без текста) ===
        if (($data['type'] ?? '') === 'image_batch' && isset($data['room_id'])) {
            $room_id    = (int)$data['room_id'];
            $imagePaths = $data['image_paths'] ?? [];

            if (!is_array($imagePaths) || empty($imagePaths) || !$from->user_id) {
                return;
            }

            $stmt = $this->pdo->prepare(
                "SELECT user1_id, user2_id FROM rooms WHERE id = ?"
            );
            $stmt->execute([$room_id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Комната не найдена']));
                return;
            }

            $user1 = (int)$room['user1_id'];
            $user2 = (int)$room['user2_id'];

            if ($from->user_id !== $user1 && $from->user_id !== $user2) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Нет доступа к комнате']));
                return;
            }

            $to_id = ($from->user_id === $user1) ? $user2 : $user1;

            $stmt = $this->pdo->prepare(
                "INSERT INTO messages (user_id, to_user_id, room_id, text, image_paths) 
                 VALUES (?, ?, ?, NULL, ?)"
            );
            $result = $stmt->execute([
                $from->user_id,
                $to_id,
                $room_id,
                json_encode($imagePaths)
            ]);

            if (!$result) {
                error_log("INSERT FAILED: " . print_r($stmt->errorInfo(), true));
                return;
            }

            $message_id = (int)$this->pdo->lastInsertId();

            $payload = json_encode([
                'type'        => 'image_batch',
                'id'          => $message_id,
                'from'        => $from->user_id,
                'to'          => $to_id,
                'room_id'     => $room_id,
                'username'    => $from->username,
                'image_paths' => $imagePaths,
                'time'        => date('H:i'),
                'avatar_path' => $from->avatar_path,
                'is_read'     => 0,
            ]);

            if (isset($this->users[$to_id])) {
                $this->users[$to_id]->send($payload);
            }
            $from->send($payload);
            return;
        }

        // === МАССИВ КАРТИНОК + ТЕКСТ ===
        if (($data['type'] ?? '') === 'image_batch_with_text' && isset($data['room_id'])) {
            $room_id    = (int)$data['room_id'];
            $imagePaths = $data['image_paths'] ?? [];
            $text       = trim($data['text'] ?? '');

            if (!is_array($imagePaths) || empty($imagePaths) || !$from->user_id) {
                return;
            }

            $stmt = $this->pdo->prepare(
                "SELECT user1_id, user2_id FROM rooms WHERE id = ?"
            );
            $stmt->execute([$room_id]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Комната не найдена']));
                return;
            }

            $user1 = (int)$room['user1_id'];
            $user2 = (int)$room['user2_id'];

            if ($from->user_id !== $user1 && $from->user_id !== $user2) {
                $from->send(json_encode(['type' => 'error', 'text' => 'Нет доступа к комнате']));
                return;
            }

            $to_id = ($from->user_id === $user1) ? $user2 : $user1;

            $stmt = $this->pdo->prepare(
                "INSERT INTO messages (user_id, to_user_id, room_id, text, image_paths) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $result = $stmt->execute([
                $from->user_id,
                $to_id,
                $room_id,
                $text !== '' ? $text : null,
                json_encode($imagePaths)
            ]);

            if (!$result) {
                error_log("INSERT FAILED: " . print_r($stmt->errorInfo(), true));
                return;
            }

            $message_id = (int)$this->pdo->lastInsertId();

            $payload = json_encode([
                'type'        => 'image_batch_with_text',
                'id'          => $message_id,
                'from'        => $from->user_id,
                'to'          => $to_id,
                'room_id'     => $room_id,
                'username'    => $from->username,
                'image_paths' => $imagePaths,
                'text'        => $text,
                'time'        => date('H:i'),
                'avatar_path' => $from->avatar_path,
                'is_read'     => 0,
            ]);

            if (isset($this->users[$to_id])) {
                $this->users[$to_id]->send($payload);
            }
            $from->send($payload);
            return;
        }

        // === ОБРАБОТКА "ПРОЧИТАНО" ===
        if (($data['type'] ?? '') === 'mark_all_read') {
            $room_id = (int)($data['room_id'] ?? 0);
            $user_id = (int)($data['user_id'] ?? 0);

            if (!$room_id || !$user_id || !$from->user_id) {
                return;
            }

            $stmt = $this->pdo->prepare(
                "SELECT id, user_id FROM messages 
                 WHERE room_id = ? AND is_read = 0 AND user_id != ?"
            );
            $stmt->execute([$room_id, $user_id]);
            $unreadMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($unreadMessages)) {
                return;
            }

            $updateStmt = $this->pdo->prepare(
                "UPDATE messages SET is_read = 1 
                 WHERE room_id = ? AND is_read = 0 AND user_id != ?"
            );
            $updateStmt->execute([$room_id, $user_id]);

            foreach ($unreadMessages as $msg) {
                $sender_id = (int)$msg['user_id'];
                $message_id = (int)$msg['id'];

                if (isset($this->users[$sender_id])) {
                    $this->users[$sender_id]->send(json_encode([
                        'type'       => 'message_read',
                        'message_id' => $message_id,
                        'room_id'    => $room_id,
                    ]));
                }
            }

            echo "Помечено прочитанными: " . count($unreadMessages) . " сообщений в комнате $room_id\n";
            return;
        }

        error_log("UNKNOWN TYPE: " . ($data['type'] ?? 'NULL'));
    }

    function onClose(ConnectionInterface $conn) {
        $userId = $conn->user_id;
        $this->clients->detach($conn);

        if ($userId && isset($this->users[$userId])) {
            unset($this->users[$userId]);
        }

        echo "Отключение: {$conn->resourceId}\n";

        if ($userId) {
            $this->broadcastStatus($userId, false);
        }
    }

    function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Ошибка: " . $e->getMessage() . "\n";
        $conn->close();
    }

    private function broadcastStatus($userId, $isOnline, $except = null) {
        $payload = json_encode([
            'type'      => 'user_status',
            'user_id'   => $userId,
            'is_online' => $isOnline,
        ]);

        foreach ($this->clients as $client) {
            if ($client !== $except) {
                $client->send($payload);
            }
        }
    }

    private function sendOnlineList(ConnectionInterface $conn) {
        foreach ($this->clients as $client) {
            if ($client !== $conn && $client->user_id) {
                $conn->send(json_encode([
                    'type'      => 'user_status',
                    'user_id'   => $client->user_id,
                    'is_online' => true,
                ]));
            }
        }
    }
}

echo "Сервер запущен на порту 8081...\n";
$server = IoServer::factory(
    new HttpServer(new WsServer(new Chat($pdo))),
    8081
);
$server->run();
