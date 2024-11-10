CREATE DATABASE farm2;
USE farm2;

CREATE TABLE admin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE employees (
    employee_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    password VARCHAR(255) NOT NULL
);




CREATE TABLE plants (
    plant_id INT PRIMARY KEY AUTO_INCREMENT,
    plant_name VARCHAR(100) NOT NULL,
    plant_type VARCHAR(100)
);



CREATE TABLE medicines (
    medicine_id INT PRIMARY KEY AUTO_INCREMENT,
    medicine_name VARCHAR(100) NOT NULL,
    dosage VARCHAR(50),
    expiry_date DATE
);



CREATE TABLE plant_health (
    plant_health_id INT PRIMARY KEY AUTO_INCREMENT,
    plant_id INT,
    health_status VARCHAR(100),
    last_updated DATETIME,
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE TABLE medicine_administration (
    administration_id INT PRIMARY KEY AUTO_INCREMENT,
    plant_id INT,
    medicine_id INT,
    administered_by INT,
    administered_on DATETIME,
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (administered_by) REFERENCES employees(employee_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

