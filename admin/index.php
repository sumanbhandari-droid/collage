<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
if (isset($_GET['logout'])) { admin_logout(); header('Location: index.php'); exit; }
$_SESSION['failed_attempts'] = $_SESSION['failed_attempts'] ?? 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['failed_attempts'] >= 5) {
        $error = 'Account temporarily locked after 5 failed attempts.';
    } elseif (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid CSRF token.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$_POST['username'] ?? '']);
        $admin = $stmt->fetch();
        if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
            $_SESSION['failed_attempts'] = 0;
            admin_login($admin);
            header('Location: dashboard.php');
            exit;
        }
        $_SESSION['failed_attempts']++;
        $error = 'Invalid credentials.';
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../assets/css/main.css" rel="stylesheet"></head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:linear-gradient(130deg,#1a237e,#00897b)">
<form method="post" class="glass-card p-4" style="width:min(420px,92vw)"><h3 class="mb-3">Admin Login</h3>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
<div class="mb-2"><label>Username</label><input name="username" class="form-control" required></div>
<div class="mb-2"><label>Password</label><input name="password" type="password" class="form-control" required></div>
<button class="btn btn-accent w-100" type="submit">Sign in</button><p class="small mt-2 mb-0">Default: admin / admin123</p>
</form></body></html>
