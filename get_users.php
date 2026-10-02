<?php
require 'db.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, first_name, last_name, avatar_path FROM users");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
