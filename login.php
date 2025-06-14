<?php
require_once 'auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        header("Location: " . (isAdmin() ? "index.php" : "employee_form.php"));
        exit();
    } else {
        $error = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #ffebee;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #714B67;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5d3a5a;
        }

        .admin-info {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }

        .admin-info strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        code {
            background-color: #f0f0f0;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Employee Management System</h1>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="register_employee.php">Daftar sebagai calon karyawan</a>
        </div>

        <style>
            .register-link {
                text-align: center;
                margin-top: 20px;
                color: #666;
            }

            .register-link a {
                color: #714B67;
                text-decoration: none;
            }

            .register-link a:hover {
                text-decoration: underline;
            }
        </style>

        <div class="admin-info">
            <strong>Login sebagai Admin:</strong>
            <div>Username: <code>admin</code></div>
            <div>Password: <code>admin</code></div>
        </div>
    </div>
</body>

</html>