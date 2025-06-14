<?php
require 'process.php';
require 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();

$_SESSION['last_activity'] = time();
// Handle department filter
$department_filter = isset($_GET['department']) ? intval($_GET['department']) : 0;

// Build SQL query
$sql = "
    SELECT e.*, d.name AS department_name 
    FROM employees e
    JOIN departments d ON e.department_id = d.id
";

if ($department_filter > 0) {
    $sql .= " WHERE e.department_id = $department_filter";
}

$sql .= " ORDER BY d.name, e.name";

$result = $conn->query($sql);
$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

$departments = getDepartments($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
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
            font-size: 24px;
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
            background-color:rgb(135, 197, 212);
            color: white;
            text-decoration: none;
            padding: 8px 15px;
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
            padding: 8px 15px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>


        <div class="content">
            <form method="GET" class="filter-form">
                <select name="department">
                    <option value="0">All Departments</option>
                    <?php foreach ($departments as $id => $name): ?>
                        <option value="<?php echo $id; ?>" <?php echo $department_filter == $id ? 'selected' : ''; ?>>
                            <?php echo $name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>

            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email'] ?? '-'); ?></td>
                            <td>
                                <?php if (!empty($employee['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($employee['image_path']); ?>" style="max-width: 50px; max-height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td class="action-links">
                                <a href="edit_employee.php?id=<?php echo $employee['id']; ?>">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> <!-- Tutup div content -->
    </div> <!-- Tutup div container -->

    <!-- Tambahkan script auto logout -->
    <script>
        // Auto logout setelah 30 menit tidak aktif
        let inactivityTimer;
        const logoutAfter = 30 * 60 * 1000; // 30 menit

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(logout, logoutAfter);
        }

        function logout() {
            window.location.href = 'logout.php';
        }

        // Event listeners untuk reset timer
        ['mousemove', 'keypress', 'click', 'scroll'].forEach(event => {
            window.addEventListener(event, resetInactivityTimer);
        });

        // Mulai timer
        resetInactivityTimer();
    </script>
</body>

</html>
<?php $conn->close(); ?>