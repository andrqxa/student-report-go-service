<?php

// Include the DatabaseConnection class
require_once 'DatabaseConnection.php';

// Get the database connection object
$conn = DatabaseConnection::getConnection();

// Define your SQL statements here
$sql1 = "
CREATE TABLE `role` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `role` (`role_name`) VALUES ('Patient');
INSERT INTO `role` (`role_name`) VALUES ('Healthcare Provider');
INSERT INTO `role` (`role_name`) VALUES ('Pharmacist');
INSERT INTO `role` (`role_name`) VALUES ('Admin');
INSERT INTO `role` (`role_name`) VALUES ('Healthcare Admin');

CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` CHAR(10) ,
    `address` VARCHAR(255),
    `password` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL,

    `medical_history` INT,
    `insurance` VARCHAR(255),

    `license` VARCHAR(255),
    `location` VARCHAR(255),
    `working_hours` VARCHAR(255),
    `specialization` VARCHAR(255),
    
    `pharmacy_name` VARCHAR(255),


    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(`email`),
    FOREIGN KEY (`role_id`) REFERENCES `role`(`id`),
    FOREIGN KEY (`medical_history`) REFERENCES `medical_history`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `medication` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `medication_name` VARCHAR(255) NOT NULL,
    `manufacturer` VARCHAR(50) NOT NULL,
    `user_id` INT NOT NULL,
    `expiry_date` DATE NOT NULL,
    `quantity` INT NOT NULL,
    `price` FLOAT,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `medical_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `medications` INT NOT NULL,
    `diagnosis` VARCHAR(50) NOT NULL,
    `date_of_visit` DATE NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `prescription_id` INT NOT NULL,
    `bp` VARCHAR(20) ,
    `weight` FLOAT,
    `height` FLOAT,
    `bmi` FLOAT,
    
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`medications`) REFERENCES `medication`(`id`),
    FOREIGN KEY (`status`) REFERENCES `appointment`(`status`),
    FOREIGN KEY (`prescription_id`) REFERENCES `prescription`(`id`),
    FOREIGN KEY (`diagnosis`) REFERENCES `prescription`(`diagnosis`),
    FOREIGN KEY (`date_of_visit`) REFERENCES `appointment`(`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prescription` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `healthcareprovider_id` INT NOT NULL,
    `medication_id` INT NOT NULL,
    `date` datetime NOT NULL,
    `instructions` VARCHAR(200) NOT NULL,
    `dosage` VARCHAR(200) NOT NULL,
    `diagnosis` VARCHAR(200) NOT NULL UNIQUE,
    `appointment_id` INT NOT NULL,
   
    FOREIGN KEY (`patient_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`healthcareprovider_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`medication_id`) REFERENCES `medication`(`id`),
    FOREIGN KEY (`appointment_id`) REFERENCES `appointment`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `appointment` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `healthcareprovider_id` INT NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `date_time` DATETIME NOT NULL,
    `reason` VARCHAR(200) NOT NULL,
    `duration` VARCHAR(50) NOT NULL,   
    `prescription_id` INT NOT NULL,
    FOREIGN KEY (`patient_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`healthcareprovider_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`prescription_id`) REFERENCES `prescription`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `medical_dispensation` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `presciption_id` INT NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    
    FOREIGN KEY (`prescription_id`) REFERENCES `prescription`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `password_resets` (`id` INT AUTO_INCREMENT PRIMARY KEY, 
    `email` VARCHAR(50),
`token` VARCHAR(255),
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vulnerability` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `report_date` date NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `problem` VARCHAR(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `error_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `timestamp` datetime NOT NULL,    
    `error` VARCHAR(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `report` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `report_name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL   
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `facility` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `facility_name` VARCHAR(255) NOT NULL,
    `status` VARCHAR(50) NOT NULL,    
    `services` VARCHAR(250) NOT NULL,
    `user_id` INT NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE discussion (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) ,
    `date_time` DATETIME NOT NULL,
    content TEXT NOT NULL
);

CREATE TABLE contact_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL
);

CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user(id),
    FOREIGN KEY (receiver_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";

// Execute SQL statements and print success or error messages
if ($conn->multi_query($sql1) === TRUE) {
    echo "Tables created successfully<br>";
} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}

// Close the database connection when done
$conn->close();
?>
