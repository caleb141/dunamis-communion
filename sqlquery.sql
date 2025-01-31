
-- CREATE TABLE audit_logs (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL, -- References the user's ID from admins or members
--     user_type ENUM('admin', 'teamlead', 'assistant teamlead', 'member') NOT NULL, -- Identifies the type of user
--     action VARCHAR(100) NOT NULL, -- The action performed (e.g., "login", "update", "delete")
--     description TEXT, -- Additional details about the action
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );


-- CREATE TABLE attendance_logs (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL, -- References the member's ID
--     status ENUM('check-in', 'check-out') NOT NULL, -- Attendance status
--     check_in_time DATETIME DEFAULT NULL, -- When the member checked in
--     check_out_time DATETIME DEFAULT NULL, -- When the member checked out
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );


-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     username VARCHAR(50) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     name VARCHAR(100) UNIQUE NOT NULL,
--     phone VARCHAR(20) UNIQUE,
--     role INT NOT NULL DEFAULT 3,
--     status INT NOT NULL DEFAULT 0,  -- 1: active, 0: inactive
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Automatically updates on change
-- );

-- CREATE TABLE teams (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     team_name VARCHAR(255) UNIQUE NOT NULL,
--     sections JSON,  -- Store sections as a JSON array
--     teamlead_id INT,
--     distPositions_0 JSON,  -- Store the first list of dict objects as JSON
--     distPositions_1 JSON,  -- Store the second list of dict objects as JSON
--     distPositions_2 JSON,  -- Store the third list of dict objects as JSON
--     distPositions_3 JSON,  -- Store the fourth list of dict objects as JSON
--     `members` JSON,
--     tempMembers JSON,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Automatically updates on change
--     FOREIGN KEY (teamlead_id) REFERENCES users(id)  -- Linking to the `teamleads` table
-- );


-- SELECT id, tempMembers FROM teams WHERE id IN (2, 3);


-- Create the `audit_logs` table
CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY, -- Automatically creates an integer column with an auto-incrementing sequence
    user_id INT NOT NULL, -- References the user's ID from admins or members
    user_type VARCHAR(20) NOT NULL CHECK (user_type IN ('admin', 'teamlead', 'assistant teamlead', 'member')), -- Enum-like behavior using CHECK
    action VARCHAR(100) NOT NULL, -- The action performed (e.g., "login", "update", "delete")
    description TEXT, -- Additional details about the action
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Automatically sets the timestamp on creation
);


-- Create the `attendance_logs` table
CREATE TABLE attendance_logs (
    id SERIAL PRIMARY KEY, -- Auto-incrementing primary key
    team_id INT NOT NULL, -- References the team's ID
    date DATE NOT NULL, -- Attendance date in YYYY-MM-DD format
    members JSONB NOT NULL DEFAULT '[]', -- List of members as a JSON array
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Record creation timestamp
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP-- Record update timestamp
);

-- Create the `users` table
CREATE TABLE users (
    id SERIAL PRIMARY KEY, -- Auto-incrementing primary key
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE,
    role INT NOT NULL DEFAULT 3,
    status INT NOT NULL DEFAULT 0, -- 1: active, 0: inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the `teams` table
CREATE TABLE teams (
    id SERIAL PRIMARY KEY, -- Auto-incrementing primary key
    team_name VARCHAR(255) UNIQUE NOT NULL,
    sections JSONB, -- Store sections as a JSON array (JSONB for better performance in queries)
    teamlead_id INT,
    ass_teamlead_id INT,
    distPositions_0 JSONB, -- Store the first list of dict objects as JSONB
    distPositions_1 JSONB, -- Store the second list of dict objects as JSONB
    distPositions_2 JSONB, -- Store the third list of dict objects as JSONB
    distPositions_3 JSONB, -- Store the fourth list of dict objects as JSONB
    members JSONB, -- Store the members as JSONB
    tempMembers JSONB, -- Store temporary members as JSONB
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teamlead_id) REFERENCES users(id) ON DELETE SET NULL -- Linking to the `users` table, allowing NULL on delete
);

-- Create the `team_request` table
CREATE TABLE team_request (
    id SERIAL PRIMARY KEY,
    requester_id INT NOT NULL,
    num_members_needed VARCHAR(10) NOT NULL,
    request_date DATE NOT NULL,
    status INT NOT NULL DEFAULT 0, -- 1: active, 0: inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create the `users_ava` table
CREATE TABLE users_availability (
    id SERIAL PRIMARY KEY,
    team_id INT NOT NULL,
    the_date DATE NOT NULL,
    members JSONB,
    status INT NOT NULL DEFAULT 0, -- 1: active, 0: inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL
);