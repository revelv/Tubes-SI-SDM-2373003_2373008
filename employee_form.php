<?php
require_once 'auth.php';
redirectIfNotEmployee();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'process.php';
    
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $email = $_POST['email'] ?? '';
    $department_id = $_POST['department_id'] ?? 0;
    $image_path = '';
    $cv_path = '';
    
    // Handle profile image upload
    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['employee_image']['tmp_name'];
        $file_name = $_FILES['employee_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
        $dest_path = UPLOAD_DIR . 'profile_' . $new_file_name;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $image_path = $dest_path;
            }
        }
    }
    
    // Handle CV upload
    if (isset($_FILES['employee_cv']) && $_FILES['employee_cv']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['employee_cv']['tmp_name'];
        $file_name = $_FILES['employee_cv']['name'];
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
    
    if (!empty($name) && !empty($position) && $department_id > 0 && !empty($cv_path)) {
        $conn->begin_transaction();
        
        try {
            // Insert employee data with pending status
            $stmt = $conn->prepare("INSERT INTO employees (department_id, name, position, email, image_path, created_by, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("issssi", $department_id, $name, $position, $email, $image_path, $_SESSION['user_id']);
            $stmt->execute();
            $employee_id = $conn->insert_id;
            
            // Save CV document
            $stmt = $conn->prepare("INSERT INTO employee_documents (employee_id, file_path, file_type) VALUES (?, ?, 'cv')");
            $stmt->bind_param("is", $employee_id, $cv_path);
            $stmt->execute();
            
            $conn->commit();
            $success_message = "Data lamaran berhasil dikirim! Menunggu persetujuan admin.";
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "Harap isi semua field wajib dan upload CV!";
    }
}

$departments = getDepartments($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Data Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f6f7;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        button {
            background-color: #714B67;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .success {
            color: green;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Lamaran Pekerjaan</h1>
        
        <?php if ($success_message): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="position">Posisi yang Dilamar</label>
                <input type="text" id="position" name="position" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="department_id">Departemen</label>
                <select id="department_id" name="department_id" required>
                    <option value="">Pilih Departemen</option>
                    <?php foreach ($departments as $id => $name): ?>
                        <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="employee_image">Foto Profil</label>
                <input type="file" id="employee_image" name="employee_image" accept="image/*">
                <div class="file-info">Format: JPG, PNG (Maks. 2MB)</div>
            </div>
            
            <div class="form-group">
                <label for="employee_cv">Upload CV</label>
                <input type="file" id="employee_cv" name="employee_cv" accept=".pdf,.doc,.docx" required>
                <div class="file-info">Format: PDF, DOC, DOCX (Maks. 5MB)</div>
            </div>
            
            <button type="submit">Kirim Lamaran</button>
        </form>
    </div>
</body>
</html>