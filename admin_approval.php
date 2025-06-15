<?php


// Handle approval/rejection
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotAdmin();

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Tampilkan data POST
    error_log(print_r($_POST, true));

    $recruitment_id = intval($_POST['recruitment_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($recruitment_id > 0 && in_array($action, ['review', 'accept', 'reject'])) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Update recruitment status
            $status_map = [
                'review' => 'reviewed',
                'accept' => 'accepted',
                'reject' => 'rejected'
            ];
            $status = $status_map[$action];

            $stmt = $conn->prepare("UPDATE recruitment SET 
                      status = ?, 
                      admin_notes = ?
                      WHERE id = ?");
            $stmt->bind_param(
                "ssi",
                $status,          // string (status)
                $notes,           // string (admin_notes)
                $recruitment_id   // integer (id)
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }



            // Only create employee if accepted
            if ($action === 'accept') {
                // Get complete recruitment data with position info
                $stmt = $conn->prepare("SELECT r.*, jp.title as position_title, jp.department_id
                                      FROM recruitment r
                                      JOIN job_positions jp ON r.position_id = jp.position_id
                                      WHERE r.id = ?");
                $stmt->bind_param("i", $recruitment_id);

                if (!$stmt->execute()) {
                    throw new Exception("Gagal mengambil data recruitment: " . $stmt->error);
                }

                $app = $stmt->get_result()->fetch_assoc();



                if ($app) {
                    // Gunakan path gambar yang baru diupload jika ada
                    $image_path = isset($app['image_path']) ? $app['image_path'] : (!empty($app['image_path']) ? $app['image_path'] : null);

                    $stmt = $conn->prepare("INSERT INTO employees 
                              (department_id, position_id, name, email, image_path, created_by) 
                              VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param(
                        "iisssi",
                        $app['department_id'],
                        $app['position_id'],
                        $app['name'],
                        $app['email'],
                        $image_path,
                        $_SESSION['user_id']
                    );

                    if (!$stmt->execute()) {
                        throw new Exception("Gagal membuat employee: " . $stmt->error);
                    }
                }
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Gagal memproses: " . $e->getMessage();
            error_log("Error in admin_approval.php: " . $e->getMessage());
        }

        header("Location: admin_approval.php");
        exit();
    }
}

// Query untuk mengambil data recruitment
// In your admin_approval.php where you fetch applications
$sql = "SELECT r.*, 
        d.name AS department_name,
        jp.title AS position_title,
        jp.position_id
        FROM recruitment r
        JOIN job_positions jp ON r.position_id = jp.position_id
        JOIN departments d ON jp.department_id = d.id
        ORDER BY 
           CASE WHEN r.status = 'pending' THEN 0 
                WHEN r.status = 'reviewed' THEN 1
                ELSE 2 END,
           r.application_date DESC";

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

        .status-pending {
            color: #FFA500;
        }

        .status-reviewed {
            color: #2196F3;
        }

        .status-accepted {
            color: #4CAF50;
        }

        .status-rejected {
            color: #F44336;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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
                        <span><?= htmlspecialchars($app['position_title']) ?></span>
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
                        <span><?= date('d M Y H:i', strtotime($app['application_date'])) ?></span>
                    </div>
                </div>

                <!-- Action buttons section -->
                <?php if ($app['status'] == 'pending'): ?>
                    <form method="POST" class="action-form" enctype="multipart/form-data">
                        <input type="hidden" name="recruitment_id" value="<?= $app['id'] ?>">
                        <textarea name="notes" placeholder="Catatan admin (opsional)"></textarea>
                        <button type="submit" name="action" value="review" class="action-btn review-btn">Tandai Ditinjau</button>
                        <button type="submit" name="action" value="accept" class="action-btn accept-btn">Terima</button>
                        <button type="submit" name="action" value="reject" class="action-btn reject-btn">Tolak</button>
                    </form>

                <?php elseif ($app['status'] == 'reviewed'): ?>
                    <form method="POST" class="action-form" enctype="multipart/form-data">
                        <input type="hidden" name="recruitment_id" value="<?= $app['id'] ?>">
                        <textarea name="notes" placeholder="Catatan admin (opsional)"><?=
                                                                                        !empty($app['admin_notes']) ? htmlspecialchars($app['admin_notes']) : ''
                                                                                        ?></textarea>
                        <button type="submit" name="action" value="accept" class="action-btn accept-btn">Terima</button>
                        <button type="submit" name="action" value="reject" class="action-btn reject-btn">Tolak</button>
                    </form>

                <?php else: ?>
                    <!-- For accepted/rejected applications -->
                    <div class="action-form">
                        <?php if (!empty($app['admin_notes'])): ?>
                            <p><strong>Catatan:</strong> <?= htmlspecialchars($app['admin_notes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Tambahkan ini di bagian atas body untuk menampilkan pesan -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Perbaikan form action -->
    <form method="POST" class="action-form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="recruitment_id" value="<?= $app['id'] ?>">
        <!-- ... elemen form lainnya ... -->
    </form>
</body>

</html>