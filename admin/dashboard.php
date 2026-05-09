<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
$stats = [
'total_students' => (int)($pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() ?: 0),
'quiz_attempts' => (int)($pdo->query('SELECT COUNT(*) FROM quiz_attempts')->fetchColumn() ?: 0),
'avg_score' => round((float)($pdo->query('SELECT COALESCE(AVG(score),0) FROM quiz_attempts')->fetchColumn() ?: 0),2),
'content_items' => (int)($pdo->query('SELECT COUNT(*) FROM chapters')->fetchColumn() ?: 0)
];
$recent = $pdo->query('SELECT student_name, grade, score, attempted_at FROM quiz_attempts ORDER BY attempted_at DESC LIMIT 10')->fetchAll();

?>
<h2>Dashboard</h2><div class="stat-grid mb-3">
<div class="cardx"><h6>Total Students</h6><h3><?= $stats['total_students'] ?></h3></div>
<div class="cardx"><h6>Quiz Attempts</h6><h3><?= $stats['quiz_attempts'] ?></h3></div>
<div class="cardx"><h6>Avg Score %</h6><h3><?= $stats['avg_score'] ?></h3></div>
<div class="cardx"><h6>Content Items</h6><h3><?= $stats['content_items'] ?></h3></div></div>
<div class="row g-3 mb-3"><div class="col-md-6"><div class="cardx"><canvas id="lineChart"></canvas></div></div><div class="col-md-6"><div class="cardx"><canvas id="barChart"></canvas></div></div>
<div class="col-md-6"><div class="cardx"><canvas id="pieChart"></canvas></div></div><div class="col-md-6"><div class="cardx"><canvas id="doughnutChart"></canvas></div></div></div>
<div class="cardx"><h5>Recent Attempts</h5><div class="table-wrap"><table class="table"><thead><tr><th>Name</th><th>Grade</th><th>Score</th><th>Date</th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><?= htmlspecialchars($r['student_name']) ?></td><td><?= (int)$r['grade'] ?></td><td><?= (int)$r['score'] ?></td><td><?= htmlspecialchars($r['attempted_at']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<script>
new Chart(document.getElementById('lineChart'),{type:'line',data:{labels:['W1','W2','W3','W4'],datasets:[{label:'Attempts',data:[12,19,9,22]}]}});
new Chart(document.getElementById('barChart'),{type:'bar',data:{labels:['Ch1','Ch2','Ch3','Ch4'],datasets:[{label:'Avg Score',data:[67,72,61,79]}]}});
new Chart(document.getElementById('pieChart'),{type:'pie',data:{labels:['Grade 11','Grade 12'],datasets:[{data:[55,45]}]}});
new Chart(document.getElementById('doughnutChart'),{type:'doughnut',data:{labels:['Easy','Medium','Hard'],datasets:[{data:[40,35,25]}]}});
</script>
</main></div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></body></html>
