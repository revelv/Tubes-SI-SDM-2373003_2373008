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
            margin-top: 20px;
            background-color: #f5f6f8;
            color: #333;
        }
        .container {
            padding: 20px;
            margin: 0 auto;
        }
      
        .filter-section {
            background: white;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filter-options {
            display: flex;
            gap: 20px;
        }
        .filter-group {
            flex: 1;
        }
        .timeoff-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .timeoff-table th, .timeoff-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .timeoff-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .status-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-to-approve {
            background: #fff3cd;
            color: #856404;
        }
        .search-box {
            padding: 8px;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
       
        <div class="main-content">
            <h1>Time Off Management</h1>
            
            <div class="filter-section">
                <div class="filter-options">
                    <div class="filter-group">
                        <h3>STATUS</h3>
                        <select class="form-control">
                            <option>All</option>
                            <option>To Approve</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <h3>DEPARTMENT</h3>
                        <select class="form-control">
                            <option>All</option>
                            <option>Operational</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <h3>Waiting For Me</h3>
                <input type="text" class="search-box" placeholder="Search...">
                
                <table class="timeoff-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>Employee</th>
                            <th>Time Off Type</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Balmond Saputra</td>
                            <td>Appointment</td>
                            <td>Meeting with client</td>
                            <td>06/27/2025 08:00:00</td>
                            <td>06/27/2025 17:00:00</td>
                            <td>1 day</td>
                            <td><span class="status-pill status-to-approve">To Approve</span></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Gregorius Subianto</td>
                            <td>Family Vacation</td>
                            <td>Liburan ke Bali</td>
                            <td>06/16/2025 08:00:00</td>
                            <td>06/20/2025 17:00:00</td>
                            <td>5 days</td>
                            <td><span class="status-pill status-to-approve">To Approve</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>