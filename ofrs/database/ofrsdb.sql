CREATE DATABASE IF NOT EXISTS ofrsdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ofrsdb;

CREATE TABLE IF NOT EXISTS tbl_fireteam (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_name VARCHAR(100) NOT NULL,
  team_leader VARCHAR(100) NOT NULL,
  leader_phone VARCHAR(20) NOT NULL,
  members TEXT NULL,
  status ENUM('Active','Busy','Offline') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_firereport (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference_code VARCHAR(20) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  mobile_number VARCHAR(20) NOT NULL,
  location_text VARCHAR(255) NOT NULL,
  landmark VARCHAR(255) NULL,
  fire_type VARCHAR(100) NULL,
  severity ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  people_at_risk VARCHAR(255) NULL,
  description TEXT NOT NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  photo_paths LONGTEXT NULL,
  status ENUM('Reported','Assigned','En Route','In Progress','Resolved') NOT NULL DEFAULT 'Reported',
  assigned_team_id INT NULL,
  assigned_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_report_team FOREIGN KEY (assigned_team_id) REFERENCES tbl_fireteam(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS tbl_report_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  report_id INT NOT NULL,
  status_label VARCHAR(100) NOT NULL,
  remark TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_history_report FOREIGN KEY (report_id) REFERENCES tbl_firereport(id) ON DELETE CASCADE
);

INSERT INTO tbl_fireteam (team_name, team_leader, leader_phone, members, status) VALUES
('Station 4 Alpha', 'Rahul Patil', '9876543210', 'R. Khan, V. Jadhav, A. Mehta', 'Active'),
('Station 2 Bravo', 'Nitin More', '9988776655', 'S. Das, P. Yadav, T. Shaikh', 'Active');

INSERT INTO tbl_firereport (reference_code, full_name, mobile_number, location_text, landmark, fire_type, severity, people_at_risk, description, latitude, longitude, photo_paths, status, assigned_team_id, assigned_at, created_at) VALUES
('FIRE-A7X9', 'Aman Desai', '9876501234', 'Andheri West, Mumbai', 'Near Metro Gate 2', 'Electrical', 'high', '2 trapped', 'Heavy smoke from 3rd floor office area.', 19.1361000, 72.8278000, '[]', 'En Route', 1, NOW() - INTERVAL 12 MINUTE, NOW() - INTERVAL 18 MINUTE),
('FIRE-B2Q4', 'Neha Shah', '9988012345', 'Powai, Mumbai', 'Near Hiranandani Garden', 'Vehicle', 'medium', '0', 'Car engine fire in basement parking.', 19.1187000, 72.9073000, '[]', 'Reported', NULL, NULL, NOW() - INTERVAL 40 MINUTE),
('FIRE-C8K2', 'Imran Khan', '9765432109', 'Dadar East, Mumbai', 'Near Railway Colony', 'Gas/LPG', 'critical', 'Family inside building', 'Possible LPG blast and visible flames in kitchen area.', 19.0176000, 72.8478000, '[]', 'Assigned', 2, NOW() - INTERVAL 9 MINUTE, NOW() - INTERVAL 16 MINUTE);

INSERT INTO tbl_report_history (report_id, status_label, remark, created_at) VALUES
(1, 'Reported', 'Citizen submitted online report', NOW() - INTERVAL 18 MINUTE),
(1, 'Assigned', 'Station 4 Alpha assigned', NOW() - INTERVAL 12 MINUTE),
(1, 'En Route', 'Team is moving towards incident site', NOW() - INTERVAL 8 MINUTE),
(2, 'Reported', 'Citizen submitted online report', NOW() - INTERVAL 40 MINUTE),
(3, 'Reported', 'Citizen submitted online report', NOW() - INTERVAL 16 MINUTE),
(3, 'Assigned', 'Station 2 Bravo assigned', NOW() - INTERVAL 9 MINUTE);
