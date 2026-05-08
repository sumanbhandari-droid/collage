<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
$users = $pdo->query('SELECT u.*, (SELECT COUNT(*) FROM quiz_attempts qa WHERE qa.user_id=u.id) attempts, (SELECT COALESCE(AVG(score),0) FROM quiz_attempts qa WHERE qa.user_id=u.id) avg_score FROM users u ORDER BY u.last_active DESC LIMIT 300')->fetchAll();
?>
<h2>Manage Users</h2><div class="cardx mb-3"><div class="row g-2"><div class="col-md-8"><input id="userSearch" class="form-control" placeholder="Search user"></div><div class="col-md-4"><button class="btn">Export CSV (UI)</button></div></div></div>
<div class="cardx"><div class="table-wrap"><table class="table" id="userTable"><thead><tr><th>Name</th><th>Email</th><th>Grade</th><th>Attempts</th><th>Avg Score</th><th>Last Active</th><th>Action</th></tr></thead><tbody><?php foreach($users as $u): ?><tr><td><?= htmlspecialchars($u['name']) ?></td><td><?= htmlspecialchars($u['email']) ?></td><td><?= (int)$u['grade'] ?></td><td><?= (int)$u['attempts'] ?></td><td><?= round((float)$u['avg_score'],2) ?></td><td><?= htmlspecialchars($u['last_active']) ?></td><td><button class="btn" onclick="alert('View details modal placeholder')">View</button> <button class="btn" onclick="confirm('Delete user?')">Delete</button></td></tr><?php endforeach; ?></tbody></table></div></div>
<script>document.getElementById('userSearch').oninput=e=>{const v=e.target.value.toLowerCase();document.querySelectorAll('#userTable tbody tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(v)?'':'none');};</script>
</main></div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></body></html>
