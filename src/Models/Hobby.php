<?php
declare(strict_types=1);

class Hobby {
    private PDO $conn;
    
    public function __construct(PDO $db) {
        $this->conn = $db;
    }
    
    public function getAllHobbies(): array {
        $query = "SELECT * FROM hobbies ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getUserHobbies(int $userId): array {
        $query = "SELECT h.*, uh.proficiency_level, uh.years_of_experience, uh.added_at, uh.hobby_id
                  FROM hobbies h
                  JOIN user_hobbies uh ON h.id = uh.hobby_id
                  WHERE uh.user_id = :user_id
                  ORDER BY h.name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function addUserHobby(int $userId, int $hobbyId, string $proficiency, int $experience): bool {
        try {
            // First check if the hobby already exists for this user
            $checkQuery = "SELECT id FROM user_hobbies WHERE user_id = :user_id AND hobby_id = :hobby_id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':hobby_id', $hobbyId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->fetch()) {
                // Update existing hobby
                $updateQuery = "UPDATE user_hobbies 
                                SET proficiency_level = :proficiency, years_of_experience = :experience 
                                WHERE user_id = :user_id AND hobby_id = :hobby_id";
                
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $updateStmt->bindParam(':hobby_id', $hobbyId, PDO::PARAM_INT);
                $updateStmt->bindParam(':proficiency', $proficiency);
                $updateStmt->bindParam(':experience', $experience, PDO::PARAM_INT);
                
                return $updateStmt->execute();
            } else {
                // Insert new hobby
                $insertQuery = "INSERT INTO user_hobbies (user_id, hobby_id, proficiency_level, years_of_experience) 
                                VALUES (:user_id, :hobby_id, :proficiency, :experience)";
                
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $insertStmt->bindParam(':hobby_id', $hobbyId, PDO::PARAM_INT);
                $insertStmt->bindParam(':proficiency', $proficiency);
                $insertStmt->bindParam(':experience', $experience, PDO::PARAM_INT);
                
                return $insertStmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Error adding hobby: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeUserHobby(int $userId, int $hobbyId): bool {
        $query = "DELETE FROM user_hobbies WHERE user_id = :user_id AND hobby_id = :hobby_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':hobby_id', $hobbyId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function createHobby(array $data): bool {
        try {
            $query = "INSERT INTO hobbies (name, description, category, icon) 
                      VALUES (:name, :description, :category, :icon)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':icon', $data['icon']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating hobby: " . $e->getMessage());
            return false;
        }
    }
    
    public function getHobbyStats(): array {
        $query = "SELECT h.name, h.icon, COUNT(uh.user_id) as user_count
                  FROM hobbies h
                  LEFT JOIN user_hobbies uh ON h.id = uh.hobby_id
                  GROUP BY h.id
                  ORDER BY user_count DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getHobbyById(int $hobbyId): ?array {
        $query = "SELECT * FROM hobbies WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $hobbyId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
