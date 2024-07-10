-- Use the database
USE votingadvance;

-- Create a users table to store user information
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    voter_id VARCHAR(15) NOT NULL,
    username VARCHAR(100), -- Add the username field
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE (email),
    UNIQUE (voter_id),
    UNIQUE (username) -- Make the username field unique
);

-- Sample admin user (You can add more users)
INSERT INTO users (name, email, password, voter_id, is_admin) -- Include the is_admin field in the INSERT
VALUES ('Admin', 'admin@example.com', 'hashed_password', 'ADMIN123', 1); -- Set is_admin to 1 for the admin user
