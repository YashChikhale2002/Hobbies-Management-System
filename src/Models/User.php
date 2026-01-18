<?php
declare(strict_types=1);

class User {
    private PDO $conn;
    
    public function __construct(PDO $db) {
        $this->conn = $db;
    }
    
    public function register(array $data): bool {
        try {
            $query = "INSERT INTO users (username, email, password, full_name, country) 
                      VALUES (:username, :email, :password, :full_name, :country)";
            
            $stmt = $this->conn->prepare($query);
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':country', $data['country']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }
    
    // Add this helper method to check if username/email exists
    public function usernameExists(string $username): bool {
        $query = "SELECT id FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    public function emailExists(string $email): bool {
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    public function login(string $email, string $password): ?array {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
    
    public function getUserById(int $id): ?array {
        $query = "SELECT id, username, email, full_name, bio, country, profile_image, created_at 
                  FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }
    
    public function updateProfile(int $userId, array $data): bool {
        $query = "UPDATE users SET full_name = :full_name, bio = :bio, country = :country 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':bio', $data['bio']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function findUsersWithCommonHobbies(int $userId): array {
        // Use two different parameter names for the two occurrences
        $query = "SELECT DISTINCT u.id, u.username, u.full_name, u.profile_image, u.country,
                  COUNT(DISTINCT uh2.hobby_id) as common_hobbies
                  FROM users u
                  JOIN user_hobbies uh2 ON u.id = uh2.user_id
                  WHERE uh2.hobby_id IN (
                      SELECT hobby_id FROM user_hobbies WHERE user_id = :user_id1
                  )
                  AND u.id != :user_id2
                  GROUP BY u.id, u.username, u.full_name, u.profile_image, u.country
                  ORDER BY common_hobbies DESC
                  LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id1', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id2', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getAllUsers(int $excludeUserId = 0): array {
        if ($excludeUserId > 0) {
            $query = "SELECT id, username, full_name, profile_image, country, created_at 
                      FROM users 
                      WHERE id != :exclude_id
                      ORDER BY created_at DESC 
                      LIMIT 50";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':exclude_id', $excludeUserId, PDO::PARAM_INT);
        } else {
            $query = "SELECT id, username, full_name, profile_image, country, created_at 
                      FROM users 
                      ORDER BY created_at DESC 
                      LIMIT 50";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getUserHobbiesList(int $userId): array {
        $query = "SELECT h.name, h.icon 
                  FROM hobbies h
                  JOIN user_hobbies uh ON h.id = uh.hobby_id
                  WHERE uh.user_id = :user_id
                  ORDER BY h.name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Add at the end of the User class, before the closing brace

    public function incrementProfileViews(int $userId): bool {
        $query = "UPDATE users SET profile_views = profile_views + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function searchUsers(string $searchTerm, int $currentUserId, int $limit = 20): array {
        $searchTerm = "%{$searchTerm}%";
        $query = "SELECT id, username, full_name, profile_image, country, bio
                FROM users 
                WHERE (username LIKE :search1 OR full_name LIKE :search2)
                AND id != :current_user
                ORDER BY full_name ASC
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search1', $searchTerm);
        $stmt->bindParam(':search2', $searchTerm);
        $stmt->bindParam(':current_user', $currentUserId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getUserStats(int $userId): array {
        $query = "SELECT 
                (SELECT COUNT(*) FROM user_hobbies WHERE user_id = :user_id1) as hobby_count,
                (SELECT COUNT(*) FROM user_connections WHERE following_id = :user_id2) as followers,
                (SELECT COUNT(*) FROM user_connections WHERE follower_id = :user_id3) as following,
                u.profile_views, u.created_at
                FROM users u
                WHERE u.id = :user_id4";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id1', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id2', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id3', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id4', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() ?: [];
    }

}
