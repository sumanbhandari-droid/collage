<?php
require_once __DIR__ . '/../admin/includes/db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $grade = isset($_GET['grade']) ? (int)$_GET['grade'] : null;
    $limit = max(1, min(100, (int)($_GET['limit'] ?? 20)));
    $sql = 'SELECT student_name AS name, score, grade, chapter, DATE(attempted_at) AS date FROM quiz_attempts';
    if ($grade) $sql .= ' WHERE grade = ' . $grade;
    $sql .= ' ORDER BY score DESC, attempted_at DESC LIMIT ' . $limit;
    echo json_encode($pdo->query($sql)->fetchAll());
    exit;
}
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$stmt = $pdo->prepare('INSERT INTO leaderboard (student_name, grade, chapter, score, recorded_at) VALUES (?, ?, ?, ?, NOW())');
$stmt->execute([
    $payload['name'] ?? 'Guest',
    (int)($payload['grade'] ?? 11),
    (string)($payload['chapter'] ?? 'all'),
    (int)($payload['score'] ?? 0)
]);
echo json_encode(['status' => 'saved']);
