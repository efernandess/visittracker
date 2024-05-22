CREATE TABLE tracked_visits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    visited_page VARCHAR(255) NOT NULL,
    referrer VARCHAR(255) DEFAULT NULL,
    visit_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    country VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    device VARCHAR(50) DEFAULT NULL,
    screen_resolution VARCHAR(20) DEFAULT NULL,
    browser VARCHAR(50) DEFAULT NULL,
    browser_version VARCHAR(255) DEFAULT NULL,
    operating_system VARCHAR(50) DEFAULT NULL
);
