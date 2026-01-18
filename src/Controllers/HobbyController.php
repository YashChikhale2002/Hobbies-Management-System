<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/Hobby.php';
require_once __DIR__ . '/../Models/Activity.php';

class HobbyController {
    private Hobby $hobbyModel;
    private Activity $activityModel;
    
    public function __construct(PDO $db) {
        $this->hobbyModel = new Hobby($db);
        $this->activityModel = new Activity($db);
    }
    
    public function index(): void {
        requireLogin();
        
        try {
            $userHobbies = $this->hobbyModel->getUserHobbies($_SESSION['user_id']);
            $allHobbies = $this->hobbyModel->getAllHobbies();
            require_once __DIR__ . '/../Views/hobbies/index.php';
        } catch (Exception $e) {
            error_log("Error loading hobbies: " . $e->getMessage());
            setSessionMessage('error', 'An error occurred while loading hobbies.');
            redirect('dashboard');
        }
    }
    
    public function add(): void {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('hobbies');
            return;
        }
        
        // Validate input
        if (!isset($_POST['hobby_id']) || !isset($_POST['proficiency_level']) || !isset($_POST['years_of_experience'])) {
            setSessionMessage('error', 'All fields are required.');
            redirect('hobbies');
            return;
        }
        
        $hobbyId = (int)$_POST['hobby_id'];
        $proficiency = sanitize($_POST['proficiency_level']);
        $experience = (int)$_POST['years_of_experience'];
        
        // Validate hobby ID
        if ($hobbyId <= 0) {
            setSessionMessage('error', 'Please select a valid hobby.');
            redirect('hobbies');
            return;
        }
        
        // Validate proficiency level
        $validProficiencies = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
        if (!in_array($proficiency, $validProficiencies)) {
            setSessionMessage('error', 'Invalid proficiency level.');
            redirect('hobbies');
            return;
        }
        
        // Validate experience
        if ($experience < 0 || $experience > 50) {
            setSessionMessage('error', 'Years of experience must be between 0 and 50.');
            redirect('hobbies');
            return;
        }
        
        try {
            if ($this->hobbyModel->addUserHobby($_SESSION['user_id'], $hobbyId, $proficiency, $experience)) {
                // Get hobby details for activity log
                $hobby = $this->hobbyModel->getHobbyById($hobbyId);
                
                if ($hobby) {
                    // Log the activity with hobby name
                    $this->activityModel->logActivity(
                        $_SESSION['user_id'], 
                        'added_hobby', 
                        'Added hobby: ' . $hobby['name'] . ' (' . $proficiency . ')'
                    );
                }
                
                setSessionMessage('success', 'Hobby added successfully! 🎉');
            } else {
                setSessionMessage('error', 'Failed to add hobby. It may already exist in your list.');
            }
        } catch (Exception $e) {
            error_log("Error adding hobby: " . $e->getMessage());
            setSessionMessage('error', 'An error occurred while adding the hobby. Please try again.');
        }
        
        redirect('hobbies');
    }
    
    public function remove(): void {
        requireLogin();
        
        if (!isset($_GET['id'])) {
            setSessionMessage('error', 'Invalid hobby ID.');
            redirect('hobbies');
            return;
        }
        
        $hobbyId = (int)$_GET['id'];
        
        if ($hobbyId <= 0) {
            setSessionMessage('error', 'Invalid hobby ID.');
            redirect('hobbies');
            return;
        }
        
        try {
            // Get hobby details before removing for activity log
            $userHobbies = $this->hobbyModel->getUserHobbies($_SESSION['user_id']);
            $hobbyToRemove = null;
            
            foreach ($userHobbies as $hobby) {
                if ($hobby['hobby_id'] == $hobbyId) {
                    $hobbyToRemove = $hobby;
                    break;
                }
            }
            
            if ($this->hobbyModel->removeUserHobby($_SESSION['user_id'], $hobbyId)) {
                // Log the removal activity if we found the hobby
                if ($hobbyToRemove) {
                    $this->activityModel->logActivity(
                        $_SESSION['user_id'], 
                        'updated_profile', 
                        'Removed hobby: ' . $hobbyToRemove['name']
                    );
                }
                
                setSessionMessage('success', 'Hobby removed successfully!');
            } else {
                setSessionMessage('error', 'Failed to remove hobby. Please try again.');
            }
        } catch (Exception $e) {
            error_log("Error removing hobby: " . $e->getMessage());
            setSessionMessage('error', 'An error occurred while removing the hobby. Please try again.');
        }
        
        redirect('hobbies');
    }
}
