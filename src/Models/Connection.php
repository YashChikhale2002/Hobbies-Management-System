<?php
declare(strict_types=1);

class Connection {
    private PDO $conn;
    
    public function __construct(PDO $db) {
        $this->conn = $db;
    }
    
    public function followUser(int $followerId, int $followingId): bool {
        try {
            $query = "INSERT INTO user_connections (follower_id, following_id, status) 
                      VALUES (:follower_id, :following_id, 'accepted')
                      ON DUPLICATE KEY UPDATE status = 'accepted'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':follower_id', $followerId, PDO::PARAM_INT);
            $stmt->bindParam(':following_id', $followingId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error following user: " . $e->getMessage());
            return false;
        }
    }
    
    public function unfollowUser(int $followerId, int $followingId): bool {
        $query = "DELETE FROM user_connections 
                  WHERE follower_id = :follower_id AND following_id = :following_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':follower_id', $followerId, PDO::PARAM_INT);
        $stmt->bindParam(':following_id', $followingId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function isFollowing(int $followerId, int $followingId): bool {
        $query = "SELECT id FROM user_connections 
                  WHERE follower_id = :follower_id AND following_id = :following_id 
                  AND status = 'accepted'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':follower_id', $followerId, PDO::PARAM_INT);
        $stmt->bindParam(':following_id', $followingId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() !== false;
    }
    
    public function getFollowers(int $userId): array {
        $query = "SELECT u.id, u.username, u.full_name, u.profile_image, u.country
                  FROM users u
                  JOIN user_connections uc ON u.id = uc.follower_id
                  WHERE uc.following_id = :user_id AND uc.status = 'accepted'
                  ORDER BY uc.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getFollowing(int $userId): array {
        $query = "SELECT u.id, u.username, u.full_name, u.profile_image, u.country
                  FROM users u
                  JOIN user_connections uc ON u.id = uc.following_id
                  WHERE uc.follower_id = :user_id AND uc.status = 'accepted'
                  ORDER BY uc.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getConnectionStats(int $userId): array {
        $query = "SELECT 
                  (SELECT COUNT(*) FROM user_connections WHERE following_id = :user_id1 AND status = 'accepted') as followers,
                  (SELECT COUNT(*) FROM user_connections WHERE follower_id = :user_id2 AND status = 'accepted') as following";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id1', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id2', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
