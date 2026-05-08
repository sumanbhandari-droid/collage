<?php
require_once __DIR__ . '/../admin/includes/db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $grade = (int)($_GET['grade'] ?? 11);
    $chapter = isset($_GET['chapter']) ? (int)$_GET['chapter'] : null;
    $limit = max(1, min(100, (int)($_GET['limit'] ?? 10)));
    $sql = 'SELECT id, grade, chapter, difficulty, question, option_a, option_b, option_c, option_d, correct_option, explanation FROM quiz_questions WHERE grade = ? AND is_active = 1';
    $params = [$grade];
    if ($chapter) { $sql .= ' AND chapter = ?'; $params[] = $chapter; }
    $sql .= ' ORDER BY RAND() LIMIT ' . $limit;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());
    exit;
}
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$stmt = $pdo->prepare('INSERT INTO quiz_attempts (user_id, student_name, grade, chapter, score, total_questions, attempted_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([
    $payload['user_id'] ?? null,
    $payload['student_name'] ?? ($payload['name'] ?? 'Guest'),
    (int)($payload['grade'] ?? 11),
    (int)($payload['chapter'] ?? 0),
    (int)($payload['score'] ?? 0),
    (int)($payload['total_questions'] ?? 10)
]);
echo json_encode(['attempt_id' => (int)$pdo->lastInsertId()]);
