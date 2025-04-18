CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE saved_meals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE meal_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_id VARCHAR(50) NOT NULL,
    rating DECIMAL(2,1) NOT NULL CHECK (rating >= 1.0 AND rating <= 5.0),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE (user_id, meal_id) -- Prevents duplicate ratings per user per meal
); 


-- CREATE TABLE bookmarked_meals (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     meal_id VARCHAR(50) NOT NULL,
--     bookmark_date DATE NOT NULL,
--     FOREIGN KEY (user_id) REFERENCES users(id)
-- );