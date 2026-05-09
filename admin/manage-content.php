<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
  if (($_POST['type'] ?? '') === 'chapter') {
    $stmt = $pdo->prepare('INSERT INTO chapters (grade, chapter_number, title, content, summary) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([(int)$_POST['grade'], (int)$_POST['chapter_number'], $_POST['title'] ?? '', $_POST['content'] ?? '', $_POST['summary'] ?? '']);
  }
}
$chapters = $pdo->query('SELECT * FROM chapters ORDER BY grade, chapter_number')->fetchAll();
$past = $pdo->query('SELECT * FROM past_questions ORDER BY year_bs DESC LIMIT 100')->fetchAll();

?>
<h2>Manage Content</h2><div class="cardx mb-3"><h5>Add Chapter</h5><form method="post" class="row g-2"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>"><input type="hidden" name="type" value="chapter"><div class="col-md-2"><input class="form-control" name="grade" placeholder="Grade" required></div><div class="col-md-2"><input class="form-control" name="chapter_number" placeholder="Chapter" required></div><div class="col-md-3"><input class="form-control" name="title" placeholder="Title" required></div><div class="col-md-3"><input class="form-control" name="summary" placeholder="Summary"></div><div class="col-md-2"><button class="btn w-100">Save</button></div><div class="col-12"><textarea class="form-control" name="content" rows="3" placeholder="Content"></textarea></div></form></div>
<div class="row g-3"><div class="col-lg-6"><div class="cardx"><h5>Chapters</h5><table class="table"><thead><tr><th>Grade</th><th>No</th><th>Title</th></tr></thead><tbody><?php foreach($chapters as $c): ?><tr><td><?= (int)$c['grade'] ?></td><td><?= (int)$c['chapter_number'] ?></td><td><?= htmlspecialchars($c['title']) ?></td></tr><?php endforeach; ?></tbody></table></div></div><div class="col-lg-6"><div class="cardx"><h5>Past Questions</h5><table class="table"><thead><tr><th>Year</th><th>Question</th></tr></thead><tbody><?php foreach($past as $p): ?><tr><td><?= (int)$p['year_bs'] ?></td><td><?= htmlspecialchars(mb_strimwidth($p['question'],0,70,'...')) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
</main></div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></body></html>
