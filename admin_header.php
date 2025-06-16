<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0; 
            background-color: #f5f6f7;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background-color: #714B67;
            color: white;
            padding: 15px 20px;
            border-radius: 3px 3px 0 0;
            font-size: 15px;
            font-weight: 300;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content {
            background-color: white;
            border-radius: 0 0 3px 3px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .filter-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        select,
        button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        button {
            background-color: #714B67;
            color: white;
            border: none;
            cursor: pointer;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-table th,
        .employee-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e6e6e6;
        }

        .employee-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .action-links a {
            color: #714B67;
            text-decoration: none;
            margin-right: 10px;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .add-btn {
            background-color: rgb(135, 197, 212);
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 3px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .back-btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 200;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <span>Styrk Industries</span>
            <div class="user-info">
                <span style="color: white;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <div>
                    <a href="login.php" class="back-btn">Home</a>
                </div>
                <a href="add_employee.php" class="add-btn">Add Employee</a>
                <a href="admin_approval.php" class="add-btn">Review Applications</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
</body>

</html>