<?php
require_once 'process.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$employee_id = intval($_GET['id']);
$employee = null;
$departments = getDepartments($conn);
$job_positions = getJobPositions($conn);


// Fetch employee data
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $employee = $result->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position_id = $_POST['position_id'] ?? ''; 
    $email = $_POST['email'] ?? '';
    $department_id = $_POST['department_id'] ?? 0;
    $current_image = $employee['image_path'] ?? '';
    $new_image_path = $current_image;

    // Handle delete image request
    if (isset($_POST['delete_image']) && $_POST['delete_image'] === '1') {
        if (!empty($current_image) && file_exists($current_image)) {
            if (unlink($current_image)) {
                $new_image_path = ''; // Set ke string kosong
            } else {
                $error_message = "Gagal menghapus gambar lama";
            }
        }
    }

    // Handle new image upload
    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['employee_image']['tmp_name'];
        $file_name = $_FILES['employee_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
        $dest_path = UPLOAD_DIR . $new_file_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                if (!empty($current_image) && file_exists($current_image)) {
                    unlink($current_image);
                }
                $new_image_path = $dest_path;
            }
        }
    }

    // Validate required fields
    if (!empty($name) && !empty($position_id) && $department_id > 0) {
        $stmt = $conn->prepare("UPDATE employees SET name = ?, position_id = ?, email = ?, department_id = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $position_id, $email, $department_id, $new_image_path, $employee_id);

        if ($stmt->execute()) {
            $success_message = "Employee updated successfully!";
            // Update local employee data
            $employee['name'] = $name;
            $employee['position_id'] = $position_id;
            $employee['email'] = $email;
            $employee['department_id'] = $department_id;
            $employee['image_path'] = $new_image_path;
        } else {
            $error_message = "Error updating employee: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Please fill all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f6f7;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }



        .form-container {
            background-color: white;
            border-radius: 0 0 3px 3px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button {
            background-color: #714B67;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e6ffe6;
            border-radius: 3px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebeb;
            border-radius: 3px;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #714B67;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .form-group input[type="checkbox"] {
            width: auto;
            display: inline-block;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            Edit Employee
        </div>
        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="position_id">Position</label>
                    <select id="position_id" name="position_id" required>
                        <option value="">Select Position</option>
                        <?php foreach ($job_positions as $id => $name): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($employee['position_id'] ?? 0) == $id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email (optional)</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="department_id">Department</label>
                    <select id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $id => $name): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($employee['department_id'] ?? 0) == $id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="employee_image">Foto Karyawan</label>
                    <?php if (!empty($employee['image_path'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="<?php echo htmlspecialchars($employee['image_path']); ?>"
                                style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 5px;">
                            <input type="checkbox" id="delete_image" name="delete_image" value="1">
                            <label for="delete_image" style="display: inline; font-weight: normal;">
                                Hapus gambar ini
                            </label>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="employee_image" name="employee_image" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    <img id="imagePreview" src="#" alt="Preview" style="max-width: 150px; display: none; margin-top: 10px;">
                </div>
                <button type="submit">Update Employee</button>
                <a href="index.php" class="back-link">← Back to Employee List</a>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('employee_image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const deleteCheckbox = document.getElementById('delete_image');
            const fileInput = document.getElementById('employee_image');

            if (deleteCheckbox && fileInput) {
                deleteCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        fileInput.value = ''; // Clear file input if delete is checked
                        document.getElementById('imagePreview').style.display = 'none';
                    }
                });

                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0 && deleteCheckbox) {
                        deleteCheckbox.checked = false; // Uncheck delete if new file selected
                    }
                });
            }
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>