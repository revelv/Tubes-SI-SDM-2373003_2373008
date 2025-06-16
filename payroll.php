<?php
require 'process.php';
require 'auth.php';
include 'admin_header.php';
redirectIfNotLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 30px;
            background-color: #f5f6f8;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .contract-section {
            background: white;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .contract-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .contract-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        .contract-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-running {
            background: #d4edda;
            color: #155724;
        }

        .status-expired {
            background: #f8d7da;
            color: #721c24;
        }

        .status-cancelled {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="main-content">
            <h1>Contracts</h1>

            <div class="contract-section">
                <div class="contract-header">
                    <h3>Employee</h3>
                </div>
            </div>

            <div class="contract-section">
                <div class="contract-header">
                    <h3>New (0)</h3>
                </div>
                <!-- Kosong karena tidak ada kontrak baru -->
            </div>

            <div class="contract-section">
                <div class="contract-header">
                    <h3>Running (6)</h3>
                </div>

                <div class="contract-card">
                    <h4>Anomali ^A.7c...</h4>
                    <p>Operational / Information Technology | IT Staff | 06/05/2025 | Intern | Apprentice 20 hours/week</p>
                    <span class="contract-status status-running">Running</span>
                </div>

                <div class="contract-card">
                    <h4>Balmond Saputra</h4>
                    <p>Operational / Production | Production Staff | 06/01/2025 | 06/30/2025 | Full-Time | Standard 40 hours/week</p>
                    <span class="contract-status status-running">Running</span>
                </div>

                <!-- Tambahkan kontrak running lainnya di sini -->
            </div>

            <div class="contract-section">
                <div class="contract-header">
                    <h3>Expired (3)</h3>
                </div>

                <div class="contract-card">
                    <h4>Kim Sa My</h4>
                    <p>Operational / HR Development | HR Staff | 05/05/2025 | 06/05/2025 | Intern | Apprentice 20 hours/week</p>
                    <span class="contract-status status-expired">Expired</span>
                </div>

                <!-- Tambahkan kontrak expired lainnya di sini -->
            </div>

            <div class="contract-section">
                <div class="contract-header">
                    <h3>Cancelled (2)</h3>
                </div>

                <div class="contract-card">
                    <h4>Anomali ^A.7c U</h4>
                    <p>Operational / IT Research | IT Staff | 01/01/2024 | 05/04/2025 | Apprentice | Apprentice 20 hours/week</p>
                    <span class="contract-status status-cancelled">Cancelled</span>
                </div>

                <!-- Tambahkan kontrak cancelled lainnya di sini -->
            </div>
        </div>
    </div>
</body>

</html>