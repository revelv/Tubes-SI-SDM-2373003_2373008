<?php
require_once 'process.php';
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position_id = $_POST['position_id'] ?? '';
    $email = $_POST['email'] ?? '';
    $department_id = $_POST['department_id'] ?? 0;

    $image_path = '';

    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['employee_image']['tmp_name'];
        $file_name = $_FILES['employee_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
        $dest_path = UPLOAD_DIR . $new_file_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $image_path = $dest_path;
            }
        }
    }

    if (!empty($name) && ($position_id) && $department_id > 0) {
        $stmt = $conn->prepare("INSERT INTO employees (department_id, position_id, name,  email, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $department_id, $position_id, $name,  $email, $image_path);

        if ($stmt->execute()) {
            $success_message = "Employee added successfully!";
            // Clear form on success
            $name  = $email = '';
            $position_id = 0;
            $department_id = 0;
        } else {
            $error_message = "Error adding employee: " . $stmt->error;
        }

        echo "<script>window.location.href='index.php';</script>";
        $stmt->close();
    } else {
        $error_message = "Please fill all required fields!";
    }
}

$departments = getDepartments($conn);
$job_positions = getJobPositions($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f6f7;
            color: #333;
        }

        .container {
            max-width: 1500px;
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

        .judul {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="judul">
            Add New Employee
        </div>
        <div class="form-container">
            <?php if ($success_message): ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="employee_image">Foto Karyawan</label>
                    <input type="file" id="employee_image" name="employee_image" accept="image/*">
                    <img id="imagePreview" src="#" alt="Preview" style="max-width: 150px; display: none; margin-top: 10px;">
                </div>
                <div class="form-group">
                    <label for="email">Email (optional)</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="department_id">Department</label>
                    <select id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $id => $name): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($department_id ?? 0) == $id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="position_id">Position</label>
                    <select id="position_id" name="position_id" required>
                        <option value="">Select Position</option>
                        <?php foreach ($job_positions as $id => $name): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($position_id ?? 0) == $id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Add Employee</button>
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
    </script>
</body>

</html>
<?php $conn->close(); ?>