<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Hobby.php';

class UserController {
    private User $userModel;
    private Hobby $hobbyModel;
    
    public function __construct(PDO $db) {
        $this->userModel = new User($db);
        $this->hobbyModel = new Hobby($db);
    }
    
    public function dashboard(): void {
        requireLogin();
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $hobbies = $this->hobbyModel->getUserHobbies($_SESSION['user_id']);
        $hobbyStats = $this->hobbyModel->getHobbyStats();
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }
    
    public function profile(): void {
        requireLogin();
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $hobbies = $this->hobbyModel->getUserHobbies($_SESSION['user_id']);
        require_once __DIR__ . '/../Views/users/profile.php';
    }
    
    public function updateProfile(): void {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
            return;
        }
        
        $data = [
            'full_name' => sanitize($_POST['full_name']),
            'bio' => sanitize($_POST['bio'] ?? ''),
            'country' => sanitize($_POST['country'])
        ];
        
        // Validate input
        if (empty($data['full_name']) || strlen($data['full_name']) < 2) {
            setSessionMessage('error', 'Full name must be at least 2 characters long.');
            redirect('profile');
            return;
        }
        
        if (empty($data['country'])) {
            setSessionMessage('error', 'Country is required.');
            redirect('profile');
            return;
        }
        
        try {
            if ($this->userModel->updateProfile($_SESSION['user_id'], $data)) {
                // Update session with new full name
                $_SESSION['full_name'] = $data['full_name'];
                setSessionMessage('success', 'Profile updated successfully!');
            } else {
                setSessionMessage('error', 'Failed to update profile. Please try again.');
            }
        } catch (Exception $e) {
            setSessionMessage('error', 'An error occurred while updating your profile.');
            error_log("Error updating profile: " . $e->getMessage());
        }
        
        redirect('profile');
    }
    
    public function findUsers(): void {
        requireLogin();
        
        try {
            $similarUsers = $this->userModel->findUsersWithCommonHobbies($_SESSION['user_id']);
        } catch (Exception $e) {
            setSessionMessage('error', 'An error occurred while finding users.');
            error_log("Error finding users: " . $e->getMessage());
            $similarUsers = [];
        }
        
        require_once __DIR__ . '/../Views/users/find-users.php';
    }
}
