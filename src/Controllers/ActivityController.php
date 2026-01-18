<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/Activity.php';

class ActivityController {
    private Activity $activityModel;
    
    public function __construct(PDO $db) {
        $this->activityModel = new Activity($db);
    }
    
    public function feed(): void {
        requireLogin();
        
        $activities = $this->activityModel->getActivityFeed($_SESSION['user_id'], 50);
        
        require_once __DIR__ . '/../Views/activity/feed.php';
    }
}
