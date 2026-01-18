<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Helpers/functions.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/HobbyController.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/ProfileController.php';
require_once __DIR__ . '/../src/Controllers/ActivityController.php';

$database = new Database();
$db = $database->getConnection();

$authController = new AuthController($db);
$hobbyController = new HobbyController($db);
$userController = new UserController($db);
$profileController = new ProfileController($db);
$activityController = new ActivityController($db);

$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'login':
        if ($action === 'submit') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;
        
    case 'register':
        if ($action === 'submit') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard':
        $userController->dashboard();
        break;
        
    case 'hobbies':
        if ($action === 'add') {
            $hobbyController->add();
        } elseif ($action === 'remove') {
            $hobbyController->remove();
        } else {
            $hobbyController->index();
        }
        break;
        
    case 'profile':
        if ($action === 'update') {
            $userController->updateProfile();
        } else {
            $userController->profile();
        }
        break;
        
    case 'find-users':
        $userController->findUsers();
        break;
        
    case 'view-profile':
        $profileController->viewProfile();
        break;
        
    case 'follow-user':
        $profileController->followUser();
        break;
        
    case 'unfollow-user':
        $profileController->unfollowUser();
        break;
        
    case 'search-users':
        $profileController->searchUsers();
        break;
        
    case 'activity-feed':
        $activityController->feed();
        break;
        
    default:
        if (isLoggedIn()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
        break;
}
