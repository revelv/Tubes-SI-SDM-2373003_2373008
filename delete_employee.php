<?php
require_once 'process.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$employee_id = intval($_GET['id']);

// Check if employee exists
$stmt = $conn->prepare("SELECT image_path FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $employee = $result->fetch_assoc();
    if (!empty($employee['image_path']) && file_exists($employee['image_path'])) {
        unlink($employee['image_path']);
    }
}
$stmt = $conn->prepare("SELECT id FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Delete employee
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
}

header("Location: index.php");
exit();
