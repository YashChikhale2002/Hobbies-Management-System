<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Hobby.php';
require_once __DIR__ . '/../Models/Connection.php';
require_once __DIR__ . '/../Models/Activity.php';

class ProfileController {
    private User $userModel;
    private Hobby $hobbyModel;
    private Connection $connectionModel;
    private Activity $activityModel;
    
    public function __construct(PDO $db) {
        $this->userModel = new User($db);
        $this->hobbyModel = new Hobby($db);
        $this->connectionModel = new Connection($db);
        $this->activityModel = new Activity($db);
    }
    
    public function viewProfile(): void {
        requireLogin();
        
        // Get user ID from URL or show current user's profile
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];
        
        if ($userId <= 0) {
            setSessionMessage('error', 'Invalid user ID.');
            redirect('dashboard');
            return;
        }
        
        // Get user data
        $user = $this->userModel->getUserById($userId);
        
        if (!$user) {
            setSessionMessage('error', 'User not found.');
            redirect('find-users');
            return;
        }
        
        // Increment profile views if viewing someone else's profile
        if ($userId != $_SESSION['user_id']) {
            $this->userModel->incrementProfileViews($userId);
        }
        
        // Get user's hobbies
        $hobbies = $this->hobbyModel->getUserHobbies($userId);
        
        // Get user stats
        $stats = $this->userModel->getUserStats($userId);
        
        // Check if current user is following this user
        $isFollowing = false;
        $isOwnProfile = ($userId == $_SESSION['user_id']);
        
        if (!$isOwnProfile) {
            $isFollowing = $this->connectionModel->isFollowing($_SESSION['user_id'], $userId);
        }
        
        // Get recent activities
        $activities = $this->activityModel->getUserActivities($userId, 10);
        
        // Get followers and following
        $followers = $this->connectionModel->getFollowers($userId);
        $following = $this->connectionModel->getFollowing($userId);
        
        require_once __DIR__ . '/../Views/profile/view.php';
    }
    
    public function followUser(): void {
        requireLogin();
        
        if (!isset($_GET['id'])) {
            setSessionMessage('error', 'Invalid user ID.');
            redirect('find-users');
            return;
        }
        
        $userIdToFollow = (int)$_GET['id'];
        
        if ($userIdToFollow == $_SESSION['user_id']) {
            setSessionMessage('error', 'You cannot follow yourself.');
            redirect('view-profile', ['id' => $userIdToFollow]);
            return;
        }
        
        if ($this->connectionModel->followUser($_SESSION['user_id'], $userIdToFollow)) {
            $this->activityModel->logActivity($_SESSION['user_id'], 'connected', "followed user ID: {$userIdToFollow}");
            setSessionMessage('success', 'User followed successfully!');
        } else {
            setSessionMessage('error', 'Failed to follow user.');
        }
        
        redirect('view-profile', ['id' => $userIdToFollow]);
    }
    
    public function unfollowUser(): void {
        requireLogin();
        
        if (!isset($_GET['id'])) {
            setSessionMessage('error', 'Invalid user ID.');
            redirect('find-users');
            return;
        }
        
        $userIdToUnfollow = (int)$_GET['id'];
        
        if ($this->connectionModel->unfollowUser($_SESSION['user_id'], $userIdToUnfollow)) {
            setSessionMessage('success', 'User unfollowed successfully!');
        } else {
            setSessionMessage('error', 'Failed to unfollow user.');
        }
        
        redirect('view-profile', ['id' => $userIdToUnfollow]);
    }
    
    public function searchUsers(): void {
        requireLogin();
        
        $searchTerm = isset($_GET['q']) ? sanitize($_GET['q']) : '';
        $results = [];
        
        if (strlen($searchTerm) >= 2) {
            $results = $this->userModel->searchUsers($searchTerm, $_SESSION['user_id']);
        }
        
        require_once __DIR__ . '/../Views/profile/search.php';
    }
}
