<?php
require_once __DIR__ . '/../admin/includes/db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $studentId = (int)($_GET['student_id'] ?? 0);
    $stmt = $pdo->prepare('SELECT * FROM student_progress WHERE user_id = ? ORDER BY updated_at DESC');
    $stmt->execute([$studentId]);
    echo json_encode($stmt->fetchAll());
    exit;
}
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
if (!empty($payload['page'])) {
    $stmt = $pdo->prepare('INSERT INTO page_views (path, viewed_at) VALUES (?, NOW())');
    $stmt->execute([$payload['page']]);
    echo json_encode(['status' => 'tracked']);
    exit;
}
$stmt = $pdo->prepare('INSERT INTO student_progress (user_id, grade, chapter, completion, updated_at) VALUES (?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE completion=VALUES(completion), updated_at=VALUES(updated_at)');
$stmt->execute([
    (int)($payload['student_id'] ?? 0),
    (int)($payload['grade'] ?? 11),
    (int)($payload['chapter'] ?? 1),
    (int)($payload['completion'] ?? 0)
]);
echo json_encode(['status' => 'updated']);
