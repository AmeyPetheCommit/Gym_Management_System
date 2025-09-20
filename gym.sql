CREATE DATABASE gym_db;
USE gym_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','member') NOT NULL
);

CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(20),
  join_date DATE,
  package VARCHAR(50)
);

CREATE TABLE receipts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT,
  amount DECIMAL(10,2),
  date DATE,
  FOREIGN KEY(member_id) REFERENCES members(id)
);
