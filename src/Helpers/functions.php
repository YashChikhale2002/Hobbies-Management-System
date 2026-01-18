<?php
declare(strict_types=1);

session_start();

// Define base URL for your application
define('BASE_URL', '/Hobbies-Management-System/public/index.php');

function redirect(string $page, array $params = []): void {
    $url = url($page, $params);
    header("Location: " . $url);
    exit();
}

function url(string $page, array $params = []): string {
    $url = BASE_URL . '?page=' . urlencode($page);
    
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            // Convert value to string before encoding
            $url .= '&' . urlencode($key) . '=' . urlencode((string)$value);
        }
    }
    
    return $url;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('login');
    }
}

function sanitize(string $data): string {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function showAlert(string $type, string $message): string {
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-400 text-blue-700'
    ];
    
    $color = $colors[$type] ?? $colors['info'];
    
    return "<div class='border-l-4 p-4 mb-4 {$color} rounded-r' role='alert'>
                <p class='font-medium'>{$message}</p>
            </div>";
}

function getSessionMessage(): string {
    if (isset($_SESSION['message'])) {
        $msg = showAlert($_SESSION['message']['type'], $_SESSION['message']['text']);
        unset($_SESSION['message']);
        return $msg;
    }
    return '';
}

function setSessionMessage(string $type, string $text): void {
    $_SESSION['message'] = ['type' => $type, 'text' => $text];
}

function formatTimeAgo(string $datetime): string {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}

function getCurrentUserId(): int {
    return $_SESSION['user_id'] ?? 0;
}

function getCurrentUsername(): string {
    return $_SESSION['username'] ?? '';
}

function getCurrentUserFullName(): string {
    return $_SESSION['full_name'] ?? '';
}

function dd($data): void {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateUsername(string $username): bool {
    // Username must be 3-50 characters, alphanumeric and underscores only
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username) === 1;
}

function generateProfileColor(string $username): string {
    $colors = [
        'from-blue-400 to-blue-600',
        'from-green-400 to-green-600',
        'from-purple-400 to-purple-600',
        'from-pink-400 to-pink-600',
        'from-indigo-400 to-indigo-600',
        'from-red-400 to-red-600',
        'from-yellow-400 to-yellow-600',
        'from-teal-400 to-teal-600',
    ];
    
    $index = ord($username[0]) % count($colors);
    return $colors[$index];
}
