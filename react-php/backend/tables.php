CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(155) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` CHAR(10) NOT NULL,
    `city` VARCHAR(255),
    `state` VARCHAR(255),
    `address` VARCHAR(255),
    `pincode` CHAR(5),
    `university` VARCHAR(255),
   
    `role` VARCHAR(50) DEFAULT "user",
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(`email`),
    UNIQUE(`phone`));

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) UNIQUE
);

INSERT INTO category VALUES ("Misc","Antiques and Collectibles","Books","Clothing and Accessories","Electronics","Furniture","Jobs","Lost and Found","Carpool","Roommates and Sublets","Tickets and Events","Vehicles");

CREATE TABLE ad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255),
    price DECIMAL(10, 2),
    posting_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    university VARCHAR(255),
    content TEXT,
    contact CHAR(10) ,
    email VARCHAR(155) ,
    category_id INT,
    photo1 VARCHAR(100) DEFAULT "",
    status VARCHAR(50) DEFAULT "Available",
    
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (contact) REFERENCES user(phone),
    FOREIGN KEY (email) REFERENCES user(email),
    FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    listing_id INT,
    FOREIGN KEY (listing_id) REFERENCES ad(id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    UNIQUE KEY unique_wishlist_user_listing (user_id, listing_id)
);

CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT,
    listing_id INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user(id),
    FOREIGN KEY (receiver_id) REFERENCES user(id),
    FOREIGN KEY (listing_id) REFERENCES ad(id)
);

CREATE TABLE rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    rating FLOAT DEFAULT 0.0,
    count INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(155) NOT NULL,
    message VARCHAR(155) NOT NULL
);
