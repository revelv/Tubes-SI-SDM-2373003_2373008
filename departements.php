<?php

require 'process.php';
require 'auth.php';
include 'admin_header.php';
redirectIfNotLoggedIn();

// Handle form submission for new department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_department'])) {
    $department_name = trim($_POST['department_name']);
    
    if (!empty($department_name)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->bind_param("s", $department_name);
        
        if ($stmt->execute()) {
            $success_message = "Department added successfully!";
        } else {
            $error_message = "Error adding department: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $error_message = "Department name cannot be empty!";
    }
}

// Query to get department data with employee counts
$sql = "SELECT d.id, d.name, COUNT(e.id) as employee_count 
        FROM departments d 
        LEFT JOIN employees e ON e.department_id = d.id 
        GROUP BY d.id, d.name 
        ORDER BY d.name";
$result = $conn->query($sql);

// Store department data
$departments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[$row['id']] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin-top: 70px;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 3px;
        }
        .header1 {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e2e2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header1 h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .new-department-btn {
            background: #714B67;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
        }
        .department-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .department-card {
            border: 1px solid #e2e2e2;
            border-radius: 3px;
            padding: 15px;
            transition: box-shadow 0.3s;
        }
        .department-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .department-name {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
            color: #714B67;
        }
        .employee-count {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .department-stats {
            font-size: 13px;
            color: #888;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        .stat-item:last-child {
            border-bottom: none;
        }
        .stat-label {
            font-weight: 500;
        }
        .stat-value {
            color: #333;
        }
        .highlight {
            color: #1890ff;
            font-weight: 500;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 3px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e2e2;
        }
        .modal-title {
            font-size: 16px;
            font-weight: 500;
            margin: 0;
        }
        .close {
            color: #aaa;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #714B67;
            color: white;
            border: none;
        }
        .btn-secondary {
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }
        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .alert-success {
            background-color: #f6ffed;
            border: 1px solid #b7eb8f;
            color: #52c41a;
        }
        .alert-error {
            background-color: #fff1f0;
            border: 1px solid #ffa39e;
            color: #f5222d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header1">
            <h1>Departments</h1>
            <button id="newDepartmentBtn" class="new-department-btn">New Department</button>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="department-grid">
            <?php foreach ($departments as $id => $department): ?>
                <div class="department-card">
                    <div class="department-name"><?php echo htmlspecialchars($department['name']); ?></div>
                    <div class="employee-count"><?php echo $department['employee_count']; ?> Employees</div>
                    <div class="department-stats">
                        <!-- You can add dynamic stats here based on your database -->
                        <?php if ($id == 6): /* Production */ ?>
                            <div class="stat-item">
                                <span class="stat-label">Time Off Requests</span>
                                <span class="stat-value highlight">1</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">New Applicants</span>
                                <span class="stat-value highlight">1</span>
                            </div>
                        <?php elseif ($id == 8): /* IT R&D */ ?>
                            <div class="stat-item">
                                <span class="stat-label">Time Off Requests</span>
                                <span class="stat-value highlight">1</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Appraisals</span>
                                <span class="stat-value highlight">1</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- New Department Modal -->
    <div id="newDepartmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New Department</h3>
                <span class="close">&times;</span>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="department_name">Department Name</label>
                    <input type="text" id="department_name" name="department_name" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" name="new_department" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Get the modal
        const modal = document.getElementById("newDepartmentModal");
        const btn = document.getElementById("newDepartmentBtn");
        const span = document.getElementsByClassName("close")[0];
        const cancelBtn = document.getElementById("cancelBtn");
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        // When the user clicks cancel button, close the modal
        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>