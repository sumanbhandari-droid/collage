<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
  $stmt = $pdo->prepare('INSERT INTO quiz_questions (grade, chapter, difficulty, question, option_a, option_b, option_c, option_d, correct_option, explanation, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
  $stmt->execute([(int)$_POST['grade'], (int)$_POST['chapter'], $_POST['difficulty'] ?? 'easy', $_POST['question'] ?? '', $_POST['a'] ?? '', $_POST['b'] ?? '', $_POST['c'] ?? '', $_POST['d'] ?? '', (int)$_POST['correct'], $_POST['explanation'] ?? '']);
}
$filterGrade = isset($_GET['grade']) ? (int)$_GET['grade'] : 0;
$sql = 'SELECT * FROM quiz_questions' . ($filterGrade ? ' WHERE grade = ' . $filterGrade : '') . ' ORDER BY id DESC LIMIT 300';
$questions = $pdo->query($sql)->fetchAll();

?>
<h2>Manage Quiz</h2><div class="cardx mb-3"><h5>Add Quiz Question</h5><form method="post" class="row g-2"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>"><div class="col-md-1"><input class="form-control" name="grade" placeholder="G" required></div><div class="col-md-1"><input class="form-control" name="chapter" placeholder="Ch" required></div><div class="col-md-2"><select name="difficulty" class="form-select"><option>easy</option><option>medium</option><option>hard</option></select></div><div class="col-md-8"><input class="form-control" name="question" placeholder="Question" required></div><div class="col-md-3"><input class="form-control" name="a" placeholder="Option A" required></div><div class="col-md-3"><input class="form-control" name="b" placeholder="Option B" required></div><div class="col-md-3"><input class="form-control" name="c" placeholder="Option C" required></div><div class="col-md-3"><input class="form-control" name="d" placeholder="Option D" required></div><div class="col-md-2"><input class="form-control" name="correct" placeholder="0-3" required></div><div class="col-md-10"><input class="form-control" name="explanation" placeholder="Explanation"></div><div class="col-md-12"><button class="btn">Save Question</button> <button type="button" class="btn">Import CSV (UI)</button></div></form></div>
<div class="cardx"><h5>Questions</h5><div class="table-wrap"><table class="table"><thead><tr><th>ID</th><th>G</th><th>Ch</th><th>Difficulty</th><th>Question</th><th>Active</th></tr></thead><tbody><?php foreach($questions as $q): ?><tr><td><?= (int)$q['id'] ?></td><td><?= (int)$q['grade'] ?></td><td><?= (int)$q['chapter'] ?></td><td><?= htmlspecialchars($q['difficulty']) ?></td><td><?= htmlspecialchars(mb_strimwidth($q['question'],0,70,'...')) ?></td><td><?= (int)$q['is_active'] ? 'Yes':'No' ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</main></div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></body></html>
