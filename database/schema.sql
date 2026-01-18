CREATE DATABASE IF NOT EXISTS hobbies_management_system;
USE hobbies_management_system;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    bio TEXT,
    country VARCHAR(50),
    profile_image VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hobbies table
CREATE TABLE hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    category VARCHAR(50),
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User hobbies junction table (many-to-many relationship)
CREATE TABLE user_hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hobby_id INT NOT NULL,
    proficiency_level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') DEFAULT 'Beginner',
    years_of_experience INT DEFAULT 0,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hobby_id) REFERENCES hobbies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_hobby (user_id, hobby_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample hobbies
INSERT INTO hobbies (name, description, category, icon) VALUES
('Reading', 'Enjoying books and literature', 'Arts & Culture', '📚'),
('Sports', 'Physical activities and games', 'Physical', '⚽'),
('Dance', 'Moving to music and rhythm', 'Arts & Culture', '💃'),
('Photography', 'Capturing moments through camera', 'Creative', '📷'),
('Cooking', 'Preparing delicious meals', 'Lifestyle', '🍳'),
('Gaming', 'Playing video games', 'Entertainment', '🎮'),
('Traveling', 'Exploring new places', 'Adventure', '✈️'),
('Music', 'Playing instruments or singing', 'Arts & Culture', '🎵'),
('Painting', 'Creating visual art', 'Creative', '🎨'),
('Gardening', 'Growing plants and flowers', 'Lifestyle', '🌱'),
('Coding', 'Programming and development', 'Technology', '💻'),
('Yoga', 'Mind-body wellness practice', 'Physical', '🧘'),
('Writing', 'Creative or technical writing', 'Creative', '✍️'),
('Cycling', 'Riding bicycles', 'Physical', '🚴'),
('Movies', 'Watching and analyzing films', 'Entertainment', '🎬');
