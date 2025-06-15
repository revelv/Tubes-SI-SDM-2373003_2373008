<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'odoo_employee_db');
define('UPLOAD_DIR', 'uploads/');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getDepartments($conn) {
    $departments = [];
    $result = $conn->query("SELECT id, name FROM departments ORDER BY name");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[$row['id']] = $row['name'];
        }
    }
    return $departments;
}

function getJobPositions($conn) {
    $positions = [];
    $result = $conn->query("SELECT position_id, title FROM job_positions ORDER BY title");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $positions[$row['position_id']] = $row['title'];
        }
    }
    return $positions;
}

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
?>
