--Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contact_number VARCHAR(20),
    department VARCHAR(100),
    userRole VARCHAR(50),
    userPassword VARCHAR(255) NOT NULL
);
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year_id INT NOT NULL,
    semester_id INT NOT NULL,
    course_id INT NOT NULL,
    year_section_id INT NOT NULL,
    subject_id INT NOT NULL,
    room_id INT NOT NULL,
    faculty varchar(100) NOT NULL,
    start_time time NOT NULL,
    end_time time NOT NULL,
    class_day varchar(20) NOT NULL,
    room_type varchar(50) NOT NULL,
    building varchar(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (year_section_id) REFERENCES year_section(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);



-- Create facilities table
CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_name VARCHAR(255) NOT NULL,
    building VARCHAR(255),
    status ENUM('Available', 'Unavailable') DEFAULT 'Available',
    building VARCHAR(255),
);

-- Create reservations table
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    reserved_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    user_id INT, -- Assuming user ID is used to track who made the reservation
    FOREIGN KEY (facility_id) REFERENCES facilities(id)
);

CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL,
    building VARCHAR(20) NOT NULL,
    room_type ENUM('Classroom', 'Laboratory') NOT NULL,
    status ENUM('Available', 'Unavailable') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
