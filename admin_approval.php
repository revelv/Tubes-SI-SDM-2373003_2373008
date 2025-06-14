<?php
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotAdmin();

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruitment_id = intval($_POST['recruitment_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if ($recruitment_id > 0 && in_array($action, ['review', 'accept', 'reject'])) {
        // Update status di tabel recruitment
        $status_map = [
            'review' => 'reviewed',
            'accept' => 'accepted',
            'reject' => 'rejected'
        ];
        $status = $status_map[$action];
        
        $stmt = $conn->prepare("UPDATE recruitment SET 
                              status = ?, 
                              admin_notes = ?, 
                              processed_at = NOW(), 
                              processed_by = ? 
                              WHERE id = ?");
        $stmt->bind_param("ssii", $status, $notes, $_SESSION['user_id'], $recruitment_id);
        $stmt->execute();
        
        // Jika diterima, pindahkan ke tabel employees
        if ($action === 'accept') {
            // Dapatkan data recruitment
            $stmt = $conn->prepare("SELECT * FROM recruitment WHERE id = ?");
            $stmt->bind_param("i", $recruitment_id);
            $stmt->execute();
            $app = $stmt->get_result()->fetch_assoc();
            
            // Dapatkan department_id dari posisi
            $stmt = $conn->prepare("SELECT department_id FROM job_positions WHERE title = ?");
            $stmt->bind_param("s", $app['position']);
            $stmt->execute();
            $position = $stmt->get_result()->fetch_assoc();
            $department_id = $position['department_id'] ?? null;
            
            if ($department_id) {
                // Masukkan ke tabel employees
                $stmt = $conn->prepare("INSERT INTO employees 
                                      (department_id, name, position, email, image_path, created_by) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssi", 
                    $department_id,
                    $app['name'],
                    $app['position'],
                    $app['email'],
                    $app['image_path'],
                    $_SESSION['user_id']
                );
                $stmt->execute();
                
                // Buat akun user
                $employee_id = $conn->insert_id;
                $username = strtok($app['email'], '@');
                $temp_password = bin2hex(random_bytes(4));
                $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO users 
                                      (username, password, role, employee_id) 
                                      VALUES (?, ?, 'employee', ?)");
                $stmt->bind_param("ssi", $username, $hashed_password, $employee_id);
                $stmt->execute();
            }
        }
        
        header("Location: admin_approval.php"); // Redirect untuk refresh data
        exit();
    }
}

// Query untuk mengambil data recruitment
$sql = "SELECT r.*, 
               d.name AS department_name,
               jp.title AS position,
               u.username AS processed_by_name
        FROM recruitment r
        JOIN job_positions jp ON r.position_id = jp.id
        JOIN departments d ON jp.department_id = d.id
        LEFT JOIN users u ON r.processed_by = u.id
        ORDER BY 
           CASE WHEN r.status = 'pending' THEN 0 
                WHEN r.status = 'reviewed' THEN 1
                ELSE 2 END,
           r.applied_at DESC";

$result = $conn->query($sql);
$applications = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Rekrutmen</title>
    <style>
        /* [Gaya CSS yang sama seperti sebelumnya] */
        .action-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .action-btn {
            padding: 8px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .review-btn {
            background-color: #2196F3;
            color: white;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }
        .reject-btn {
            background-color: #F44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manajemen Rekrutmen</h1>
        
        <!-- [Filter controls seperti sebelumnya] -->
        
        <?php foreach ($applications as $app): ?>
            <div class="application" data-status="<?= $app['status'] ?>">
                <h3><?= htmlspecialchars($app['name']) ?>
                    <span class="status-<?= $app['status'] ?>">
                        (<?= strtoupper($app['status']) ?>)
                    </span>
                </h3>
                
                <div class="app-details">
                    <div class="detail-item">
                        <span class="detail-label">Posisi:</span>
                        <span><?= htmlspecialchars($app['position']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Departemen:</span>
                        <span><?= htmlspecialchars($app['department_name']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span><?= htmlspecialchars($app['email']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Lamar:</span>
                        <span><?= date('d M Y H:i', strtotime($app['applied_at'])) ?></span>
                    </div>
                </div>
                
                <?php if ($app['status'] == 'pending'): ?>
                    <form method="POST" class="action-form">
                        <input type="hidden" name="recruitment_id" value="<?= $app['id'] ?>">
                        <textarea name="notes" placeholder="Catatan admin (opsional)"></textarea>
                        <button type="submit" name="action" value="review" class="action-btn review-btn">Tandai Ditinjau</button>
                        <button type="submit" name="action" value="accept" class="action-btn accept-btn">Terima</button>
                        <button type="submit" name="action" value="reject" class="action-btn reject-btn">Tolak</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>