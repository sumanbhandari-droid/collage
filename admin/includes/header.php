<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="../assets/css/dashboard.css" rel="stylesheet"><title>Admin Dashboard</title>
</head><body><div class="admin-wrap">
<aside class="sidebar"><h4>NEB Admin</h4>
<a href="dashboard.php"><i class="fa fa-chart-line"></i> Dashboard</a>
<a href="manage-content.php"><i class="fa fa-book"></i> Manage Content</a>
<a href="manage-quiz.php"><i class="fa fa-clipboard-question"></i> Manage Quiz</a>
<a href="manage-users.php"><i class="fa fa-users"></i> Manage Users</a>
<a href="analytics.php"><i class="fa fa-chart-pie"></i> Analytics</a>
<a href="index.php?logout=1"><i class="fa fa-right-from-bracket"></i> Logout</a>
</aside><main class="main">
