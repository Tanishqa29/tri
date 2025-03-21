CREATE DATABASE employee;
USE employee_course_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'employee') NOT NULL
);

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(100) NOT NULL,
    division VARCHAR(100) NOT NULL,
    pin_no VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    contact_no VARCHAR(20),
    email VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE tni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    training_need_1 TEXT,
    training_need_2 TEXT,
    training_need_3 TEXT,
    training_need_4 TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE training_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    year YEAR NOT NULL,
    training_type VARCHAR(100) NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    institute VARCHAR(255),
    duration VARCHAR(50),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(255) NOT NULL,
    course_description TEXT,
    training_type VARCHAR(100),
    location VARCHAR(255),
    duration VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    course_id INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, email, role) VALUES
('admin', 'admin123', 'admin@123', 'admin'),
('john_doe', 'john123', 'john@123', 'employee'),
('jane_smith', 'jane123', 'jane@123', 'employee'),
('alice_wonder', 'alice123', 'alice@123', 'employee');

INSERT INTO employees (user_id, name, designation, division, pin_no, dob, contact_no, email) VALUES
(2, 'John Doe', 'Software Engineer', 'IT Department', '12345', '1990-05-15', '9876543210', 'john@123'),
(3, 'Jane Smith', 'Data Analyst', 'Business Intelligence', '67890', '1992-08-22', '8765432109', 'jane@123'),
(4, 'Alice Wonder', 'Network Engineer', 'Infrastructure', '54321', '1995-11-10', '7654321098', 'alice@123');

INSERT INTO tni (employee_id, training_need_1, training_need_2, training_need_3, training_need_4) VALUES
(1, 'Advanced Python', 'Data Visualization', 'Machine Learning', 'Cloud Computing'),
(2, 'SQL Optimization', 'Big Data Processing', 'Data Security', 'Tableau for Business Intelligence'),
(3, 'Network Security', 'Linux Administration', 'DevOps Basics', 'AWS Fundamentals');

INSERT INTO training_records (employee_id, year, training_type, course_name, institute, duration) VALUES
(1, 2024, 'Technical', 'Advanced Python', 'Tech Academy', '5 Days'),
(1, 2023, 'Business', 'Project Management', 'Business School', '3 Days'),
(2, 2024, 'Data Analytics', 'SQL Optimization', 'Data Institute', '4 Days'),
(2, 2023, 'Security', 'Cybersecurity Fundamentals', 'Cyber Academy', '6 Days'),
(3, 2024, 'Networking', 'Network Security', 'Tech University', '7 Days'),
(3, 2023, 'Cloud', 'AWS Basics', 'Cloud Academy', '5 Days');

INSERT INTO courses (course_name, course_description, training_type, location, duration) VALUES
('Advanced Python', 'An in-depth course on Python for software developers.', 'Technical', 'Online', '5 Days'),
('Project Management', 'Learn effective project management methodologies.', 'Business', 'New York', '3 Days'),
('SQL Optimization', 'Techniques to optimize SQL queries for performance.', 'Data Analytics', 'Online', '4 Days'),
('Cybersecurity Fundamentals', 'Introduction to network and system security.', 'Security', 'San Francisco', '6 Days'),
('Network Security', 'A comprehensive course on securing network infrastructures.', 'Networking', 'London', '7 Days');

INSERT INTO applications (employee_id, course_id, status) VALUES
(1, 1, 'Pending'),
(1, 2, 'Approved'),
(2, 3, 'Rejected'),
(2, 4, 'Pending'),
(3, 5, 'Approved');

