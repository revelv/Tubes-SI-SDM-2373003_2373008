<?php

require_once 'process.php';
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();

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
                $stmt = $conn->prepare("SELECT r.id, r.name, r.email, r.position_id, r.photo_path, jp.title as position_title, jp.department_id
                        FROM recruitment r
                        JOIN job_positions jp ON r.position_id = jp.position_id
                        WHERE r.id = ?");
                $stmt->bind_param("i", $recruitment_id);

                if (!$stmt->execute()) {
                    throw new Exception("Gagal mengambil data recruitment: " . $stmt->error);
                }

                $app = $stmt->get_result()->fetch_assoc();

                error_log("Full app data: " . print_r($app, true));

                if ($app) {
                    // Gunakan path gambar yang baru diupload jika ada
                    $image_path = !empty($app['photo_path']) ? $app['photo_path'] : null;
                    error_log("photo_path: " . var_export($app['photo_path'], true));
                    error_log("image_path yang akan disimpan: " . var_export($image_path, true));
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

         echo "<script>window.location.href='admin_approval.php';</script>";
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
    <title>Styrk Industries</title>

    <style>
        :root {
            --primary-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #333;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #444;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            margin-top: 100px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 30px;
        }


        .application {
            background-color: var(--white);
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .application:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .application h3 {
            margin: 0 0 10px 0;
            color: var(--dark-gray);
            font-size: 1.3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .app-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 3px;
        }

        .action-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            min-height: 80px;
            margin-bottom: 15px;
            font-family: inherit;
            resize: vertical;
        }

        .action-btn {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-btn {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .review-btn:hover {
            background-color: #2980b9;
        }

        .accept-btn {
            background-color: var(--success-color);
            color: var(--white);
        }

        .accept-btn:hover {
            background-color: #27ae60;
        }

        .reject-btn {
            background-color: var(--danger-color);
            color: var(--white);
        }

        .reject-btn:hover {
            background-color: #c0392b;
        }

        /* Status badges */
        [class^="status-"] {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        .status-pending {
            color: var(--warning-color);
            background-color: rgba(243, 156, 18, 0.1);
        }

        .status-reviewed {
            color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.1);
        }

        .status-accepted {
            color: var(--success-color);
            background-color: rgba(46, 204, 113, 0.1);
        }

        .status-rejected {
            color: var(--danger-color);
            background-color: rgba(231, 76, 60, 0.1);
        }

        /* Alert messages */
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
            font-weight: 500;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .app-details {
                grid-template-columns: 1fr;
            }

            .action-btn {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0;
            }
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