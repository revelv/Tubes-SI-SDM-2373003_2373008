<?php
require_once 'process.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $position_id = $_POST['position_id'] ?? 0;
    $photo_path = '';
    $cv_path = '';

    // Handle profile photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['photo']['tmp_name'];
        $file_name = $_FILES['photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
        $dest_path = UPLOAD_DIR . 'photo_' . $new_file_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $photo_path = $dest_path;
            }
        }
    }

    // Handle CV upload
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['cv']['tmp_name'];
        $file_name = $_FILES['cv']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
        $dest_path = UPLOAD_DIR . 'cv_' . $new_file_name;

        $allowed_types = ['pdf', 'doc', 'docx'];
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $cv_path = $dest_path;
            }
        }
    }

    if (!empty($name) && !empty($email) && $position_id > 0 && !empty($cv_path)) {
        try {
            $stmt = $conn->prepare("INSERT INTO recruitment (name, email, phone, position_id, cv_path, photo_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $name, $email, $phone, $position_id, $cv_path, $photo_path);
            $stmt->execute();

            $success_message = "Application submitted successfully! We will contact you soon.";
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill all required fields and upload your CV!";
    }
}

// Get all positions grouped by department
$position_id = [];
$result = $conn->query("
    SELECT p.position_id AS id, p.title, d.name AS department 
    FROM job_positions p
    JOIN departments d ON p.department_id = d.id
    ORDER BY d.name, p.title
");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Styrk Industries</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus {
            border-color: #714B67;
            outline: none;
        }

        input[type="file"] {
            margin-top: 5px;
            width: 100%;
        }

        button {
            background-color: #714B67;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5d3a5a;
        }

        .success {
            color: #28a745;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 6px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 5px solid #28a745;
        }

        .error {
            color: #dc3545;
            padding: 15px;
            background-color: #ffebee;
            border-radius: 6px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 5px solid #dc3545;
        }

        .file-info {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }

        .required:after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="login.php" style="display: inline-block; padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-weight: 600;">&larr; Back to Login</a>
        </div>
        <h1>Job Application Form</h1>

        <?php if ($success_message): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="required">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email" class="required">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="position_id" class="required">Position</label>
                <select id="position_id" name="position_id" required>
                    <option value="">Select a position</option>
                    <?php
                    $current_dept = '';
                    foreach ($positions as $position):
                        if ($position['department'] != $current_dept) {
                            if ($current_dept != '') echo '</optgroup>';
                            echo '<optgroup label="' . htmlspecialchars($position['department']) . '">';
                            $current_dept = $position['department'];
                        }
                    ?>
                        <option value="<?= $position['id'] ?>"><?= htmlspecialchars($position['title']) ?></option>
                    <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="photo">Profile Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <div class="file-info">Format: JPG, PNG (Max 2MB)</div>
            </div>

            <div class="form-group">
                <label for="cv" class="required">Upload CV</label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                <div class="file-info">Format: PDF, DOC, DOCX (Max 5MB)</div>
            </div>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>

</html>