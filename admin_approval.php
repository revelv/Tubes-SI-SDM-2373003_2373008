<?php
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotAdmin();


// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruitment_id = intval($_POST['recruitment_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if ($recruitment_id > 0 && in_array($action, ['approve', 'reject'])) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        
        // Update recruitment status
        $stmt = $conn->prepare("UPDATE recruitment SET status = ?, admin_notes = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $notes, $recruitment_id);
        $stmt->execute();
        
        // If approved, move to employees table
        if ($action === 'approve') {
            // Get recruitment data
            $stmt = $conn->prepare("SELECT * FROM recruitment WHERE id = ?");
            $stmt->bind_param("i", $recruitment_id);
            $stmt->execute();
            $app = $stmt->get_result()->fetch_assoc();
            
            // Insert into employees
            $stmt = $conn->prepare("INSERT INTO employees (department_id, name, position, email, image_path, created_by) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssi", $app['department_id'], $app['name'], $app['position'], 
                             $app['email'], $app['image_path'], $_SESSION['user_id']);
            $stmt->execute();
        }
    }
}

// Get recruitment applications
$sql = "SELECT r.*, d.name AS department_name 
        FROM recruitment r
        JOIN departments d ON r.department_id = d.id
        ORDER BY r.applied_at DESC";
$result = $conn->query($sql);
$applications = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruitment Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f6f7;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .application {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .application.pending { background-color: #fffde7; }
        .application.approved { background-color: #e8f5e9; }
        .application.rejected { background-color: #ffebee; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 12px;
        }
        .status-pending { background-color: #ffc107; }
        .status-approved { background-color: #4caf50; }
        .status-rejected { background-color: #f44336; }
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Manajemen Rekrutmen</h1>
        
        <?php foreach ($applications as $app): ?>
            <div class="application <?= $app['status'] ?>">
                <h3><?= htmlspecialchars($app['name']) ?>
                    <span class="status-badge status-<?= $app['status'] ?>">
                        <?= strtoupper($app['status']) ?>
                    </span>
                </h3>
                <p><strong>Posisi:</strong> <?= htmlspecialchars($app['position']) ?></p>
                <p><strong>Departemen:</strong> <?= htmlspecialchars($app['department_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($app['email']) ?></p>
                <p><strong>Tanggal Lamar:</strong> <?= $app['applied_at'] ?></p>
                
                <?php if (!empty($app['image_path'])): ?>
                    <p><strong>Foto Profil:</strong><br>
                    <img src="<?= htmlspecialchars($app['image_path']) ?>" style="max-width: 100px;"></p>
                <?php endif; ?>
                
                <p><strong>CV:</strong> 
                    <a href="<?= htmlspecialchars($app['cv_path']) ?>" target="_blank">Download</a>
                </p>
                
                <?php if (!empty($app['admin_notes'])): ?>
                    <p><strong>Catatan Admin:</strong> <?= htmlspecialchars($app['admin_notes']) ?></p>
                <?php endif; ?>
                
                <?php if ($app['status'] === 'pending'): ?>
                    <form method="POST">
                        <input type="hidden" name="recruitment_id" value="<?= $app['id'] ?>">
                        <textarea name="notes" placeholder="Catatan (opsional)"></textarea>
                        <button type="submit" name="action" value="approve" class="btn-approve">Setujui</button>
                        <button type="submit" name="action" value="reject" class="btn-reject">Tolak</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>