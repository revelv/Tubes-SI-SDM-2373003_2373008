<?php
session_start();


require_once 'process.php';

// Data admin default
const DEFAULT_ADMIN = [
    'username' => 'admin',
    'password' => '$2y$10$fbdJi7jdC0xfeKCuZSDaG.Fv6TM7Hiuway3HYMDNnwqKziU9TsOUy', // password = "admin"
    'role' => 'admin',
    'id' => 1,
    'employee_id' => null
];

function login($username, $password)
{
    // Cek admin default
    if ($username === DEFAULT_ADMIN['username']) {
        if (password_verify($password, DEFAULT_ADMIN['password'])) {
            $_SESSION['user_id'] = DEFAULT_ADMIN['id'];
            $_SESSION['username'] = DEFAULT_ADMIN['username'];
            $_SESSION['role'] = DEFAULT_ADMIN['role'];
            $_SESSION['employee_id'] = DEFAULT_ADMIN['employee_id'];
            return true;
        }
        return false;
    }

    // Jika bukan admin default, cek database
    global $conn;
    $stmt = $conn->prepare("SELECT id, username, password, role, employee_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['employee_id'] = $user['employee_id'];
            return true;
        }
    }
    return false;
}
function registerEmployee($username, $password, $name, $position, $email, $department_id)
{
    global $conn;

    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception("Username sudah digunakan");
    }

    $conn->begin_transaction();

    try {
        // 1. Insert employee data
        $stmt = $conn->prepare("INSERT INTO employees (department_id, name, position, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $department_id, $name, $position, $email);
        $stmt->execute();
        $employee_id = $conn->insert_id;

        // 2. Create user account
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, employee_id) VALUES (?, ?, 'employee', ?)");
        $stmt->bind_param("ssi", $username, $hashed_password, $employee_id);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function isEmployee()
{
    return isLoggedIn() && $_SESSION['role'] === 'employee';
}

function logout() {
    // Unset semua session variables
    $_SESSION = array();
    
    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy session
    session_destroy();
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotEmployee() {
    if (!isEmployee()) {
        header("Location: login.php");
        exit();
    }
}



