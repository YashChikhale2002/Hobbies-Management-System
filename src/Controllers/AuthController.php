<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Activity.php';

class AuthController {
    private User $userModel;
    private Activity $activityModel;
    
    public function __construct(PDO $db) {
        $this->userModel = new User($db);
        $this->activityModel = new Activity($db);
    }
    
    public function showLogin(): void {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        require_once __DIR__ . '/../Views/auth/login.php';
    }
    
    public function showRegister(): void {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        require_once __DIR__ . '/../Views/auth/register.php';
    }
    
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('register');
            return;
        }
        
        $data = [
            'username' => sanitize($_POST['username']),
            'email' => sanitize($_POST['email']),
            'password' => $_POST['password'],
            'full_name' => sanitize($_POST['full_name']),
            'country' => sanitize($_POST['country'])
        ];
        
        // Validate username length
        if (strlen($data['username']) < 3) {
            setSessionMessage('error', 'Username must be at least 3 characters long.');
            redirect('register');
            return;
        }
        
        // Validate password length
        if (strlen($data['password']) < 6) {
            setSessionMessage('error', 'Password must be at least 6 characters long.');
            redirect('register');
            return;
        }
        
        // Check if username already exists
        if ($this->userModel->usernameExists($data['username'])) {
            setSessionMessage('error', 'Username "' . $data['username'] . '" is already taken. Please choose another.');
            redirect('register');
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->emailExists($data['email'])) {
            setSessionMessage('error', 'Email "' . $data['email'] . '" is already registered. Please login or use another email.');
            redirect('register');
            return;
        }
        
        try {
            if ($this->userModel->register($data)) {
                // Get the newly created user to log activity
                $newUser = $this->userModel->login($data['email'], $data['password']);
                if ($newUser) {
                    // Log the "joined" activity
                    $this->activityModel->logActivity(
                        $newUser['id'], 
                        'joined', 
                        'Joined Hobbies Hub'
                    );
                }
                
                setSessionMessage('success', 'Registration successful! Please login to continue.');
                redirect('login');
            } else {
                setSessionMessage('error', 'Registration failed. Please try again.');
                redirect('register');
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            setSessionMessage('error', 'An error occurred during registration. Please try again.');
            redirect('register');
        }
    }
    
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
            return;
        }
        
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        
        // Validate input
        if (empty($email) || empty($password)) {
            setSessionMessage('error', 'Please enter both email and password.');
            redirect('login');
            return;
        }
        
        try {
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Update last active time
                $this->userModel->incrementProfileViews($user['id']); // This will trigger last_active update
                
                setSessionMessage('success', 'Welcome back, ' . $user['full_name'] . '! 👋');
                redirect('dashboard');
            } else {
                setSessionMessage('error', 'Invalid email or password. Please try again.');
                redirect('login');
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            setSessionMessage('error', 'An error occurred during login. Please try again.');
            redirect('login');
        }
    }
    
    public function logout(): void {
        // Store username for goodbye message
        $username = $_SESSION['full_name'] ?? 'User';
        
        // Destroy session
        session_unset();
        session_destroy();
        
        // Start new session for the message
        session_start();
        setSessionMessage('success', 'Goodbye ' . $username . '! You have been logged out successfully.');
        
        redirect('login');
    }
}
