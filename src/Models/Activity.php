<?php
declare(strict_types=1);

class Activity {
    private PDO $conn;
    
    public function __construct(PDO $db) {
        $this->conn = $db;
    }
    
    public function logActivity(int $userId, string $type, string $data = ''): bool {
        try {
            $query = "INSERT INTO activities (user_id, activity_type, activity_data) 
                      VALUES (:user_id, :type, :data)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':data', $data);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error logging activity: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserActivities(int $userId, int $limit = 20): array {
        $query = "SELECT a.*, u.username, u.full_name, u.profile_image
                  FROM activities a
                  JOIN users u ON a.user_id = u.id
                  WHERE a.user_id = :user_id
                  ORDER BY a.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getActivityFeed(int $userId, int $limit = 50): array {
        $query = "SELECT a.*, u.username, u.full_name, u.profile_image
                  FROM activities a
                  JOIN users u ON a.user_id = u.id
                  WHERE a.user_id IN (
                      SELECT following_id FROM user_connections 
                      WHERE follower_id = :user_id1 AND status = 'accepted'
                  )
                  OR a.user_id = :user_id2
                  ORDER BY a.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id1', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id2', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
