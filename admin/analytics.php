<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
$popular = $pdo->query('SELECT chapter, COUNT(*) cnt FROM quiz_attempts GROUP BY chapter ORDER BY cnt DESC LIMIT 8')->fetchAll();
?>
<h2>Analytics</h2><div class="cardx mb-3"><div class="row g-2"><div class="col-md-5"><input type="date" class="form-control"></div><div class="col-md-5"><input type="date" class="form-control"></div><div class="col-md-2"><button class="btn w-100">Apply</button></div></div></div>
<div class="row g-3"><div class="col-md-6"><div class="cardx"><canvas id="popularChart"></canvas></div></div><div class="col-md-6"><div class="cardx"><canvas id="distChart"></canvas></div></div><div class="col-md-6"><div class="cardx"><canvas id="weakChart"></canvas></div></div><div class="col-md-6"><div class="cardx"><canvas id="dailyChart"></canvas></div></div></div>
<script>
new Chart(document.getElementById('popularChart'),{type:'bar',data:{labels:['Ch1','Ch2','Ch3','Ch4'],datasets:[{label:'Most Popular',data:[30,26,22,19]}]}});
new Chart(document.getElementById('distChart'),{type:'bar',data:{labels:['0-20','21-40','41-60','61-80','81-100'],datasets:[{label:'Score Distribution',data:[2,6,12,15,8]}]}});
new Chart(document.getElementById('weakChart'),{type:'bar',data:{labels:['Subnetting','Normalization','Pointers','OOP'],datasets:[{label:'Weakest Topics',data:[41,46,49,52]}]}});
new Chart(document.getElementById('dailyChart'),{type:'line',data:{labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],datasets:[{label:'Daily Active Users',data:[15,22,18,24,26,21,19]}]}});
</script>
</main></div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></body></html>
