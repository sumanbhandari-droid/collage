<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name(defined('ADMIN_SESSION_NAME') ? ADMIN_SESSION_NAME : 'neb_admin_session');
    session_start();
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
function admin_login(array $admin): void {
    $_SESSION['admin'] = ['id' => $admin['id'] ?? 1, 'username' => $admin['username'] ?? 'admin'];
}
function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin']);
}
function require_admin(): void {
    if (!is_admin_logged_in()) {
        header('Location: index.php');
        exit;
    }
}
function admin_logout(): void {
    unset($_SESSION['admin']);
    session_regenerate_id(true);
}
