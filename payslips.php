<?php

require_once 'process.php';
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();
// Database connection
$host = '127.0.0.1';
$db   = 'odoo_employee_db';
$user = 'root'; // Change to your database username
$pass = '';     // Change to your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Get employees data
$stmt = $pdo->query("
    SELECT e.id, e.name, e.email, d.name AS department, p.title AS position 
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    LEFT JOIN job_positions p ON e.position_id = p.position_id
    ORDER BY e.name
");
$employees = $stmt->fetchAll();

// Sample payslip data - in a real app this would come from database
$payslips = [];
foreach($employees as $employee) {
    $payslips[] = [
        'reference' => 'SLIP/' . str_pad($employee['id'], 3, '0', STR_PAD_LEFT),
        'employee' => $employee['name'],
        'batch' => date('F Y'),
        'basic' => rand(5000000, 25000000),
        'gross' => rand(5000000, 25000000),
        'net' => rand(4500000, 23000000),
        'status' => rand(0, 1) ? 'Paid' : 'Pending'
    ];
}

// Add some negative values for demo
$payslips[2]['basic'] = -$payslips[2]['basic'];
$payslips[2]['gross'] = -$payslips[2]['gross'];
$payslips[2]['net'] = -$payslips[2]['net'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslips</title>
    <style>
        :root {
            --primary: #714B67;
            --secondary: #5d3a56;
            --accent: #9C6F91;
            --light: #f5f6f7;
            --dark: #333;
            --success: #4cc9f0;
            --warning: #f72585;
            --text-light: #f8f9fa;
            --text-dark: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            padding-top: 60px;
        }
         
        .main-content {
            padding: 30px;
            margin: 0 auto;
            width: 100%;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .card-actions .btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #666;
        }
        
        .filter-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-input:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }
        
        .payslip-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .payslip-table th {
            background-color: var(--primary);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }
        
        .payslip-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .payslip-table tr:hover {
            background-color: #f9f0f7;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-paid {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-pending {
            background-color: #fff3e0;
            color: #e65100;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
            text-align: right;
        }
        
        .positive {
            color: #2e7d32;
        }
        
        .negative {
            color: #c62828;
        }
        
        .action-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            margin-right: 10px;
        }
        
        .action-btn:hover {
            color: var(--secondary);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .page-info {
            font-size: 14px;
            color: #666;
        }
        
        .page-controls {
            display: flex;
            gap: 5px;
        }
        
        .page-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .page-btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .page-btn:hover:not(.active) {
            background-color: #f5f5f5;
        }
     
        
        
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <div class="dashboard">
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <div class="page-title">Employee Payslips</div>
                <div class="user-profile">
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                    <button class="btn" style="background-color: var(--primary); color: white; padding: 8px 15px; border-radius: 4px;">
                        <i class="fas fa-plus"></i> New Payslip
                    </button>
                </div>
            </div>
            
            <!-- Filter Bar -->
            <div class="card">
                <div class="filter-bar">
                    <div class="filter-group">
                        <label class="filter-label">Employee Name</label>
                        <input type="text" class="filter-input" placeholder="Search employee..." id="employeeSearch">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Batch</label>
                        <select class="filter-input" id="batchFilter">
                            <option value="">All Batches</option>
                            <option value="<?= date('F Y') ?>" selected><?= date('F Y') ?></option>
                            <option value="<?= date('F Y', strtotime('-1 month')) ?>"><?= date('F Y', strtotime('-1 month')) ?></option>
                            <option value="<?= date('F Y', strtotime('-2 months')) ?>"><?= date('F Y', strtotime('-2 months')) ?></option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select class="filter-input" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Date Range</label>
                        <input type="date" class="filter-input" id="dateFilter">
                    </div>
                </div>
            </div>
            
            <!-- Payslips Table -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Payslip Records</div>
                    <div class="card-actions">
                        <span class="page-info">Showing <span id="startRecord">1</span>-<span id="endRecord"><?= count($payslips) ?></span> of <span id="totalRecords"><?= count($payslips) ?></span> records</span>
                    </div>
                </div>
                <div class="table-container">
                    <table class="payslip-table" id="payslipTable">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Employee</th>
                                <th>Batch Name</th>
                                <th>Basic Wage</th>
                                <th>Gross Wage</th>
                                <th>Net Wage</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($payslips as $slip): ?>
                                <tr>
                                    <td><?= $slip['reference'] ?></td>
                                    <td><?= $slip['employee'] ?></td>
                                    <td><?= $slip['batch'] ?></td>
                                    <td class="currency <?= $slip['basic'] < 0 ? 'negative' : 'positive' ?>">
                                        Rp <?= number_format(abs($slip['basic']), 2, ',', '.') ?>
                                    </td>
                                    <td class="currency <?= $slip['gross'] < 0 ? 'negative' : 'positive' ?>">
                                        Rp <?= number_format(abs($slip['gross']), 2, ',', '.') ?>
                                    </td>
                                    <td class="currency <?= $slip['net'] < 0 ? 'negative' : 'positive' ?>">
                                        Rp <?= number_format(abs($slip['net']), 2, ',', '.') ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($slip['status']) ?>">
                                            <?= $slip['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="action-btn" title="View" onclick="viewPayslip('<?= $slip['reference'] ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn" title="Edit" onclick="editPayslip('<?= $slip['reference'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn" title="Print" onclick="printPayslip('<?= $slip['reference'] ?>')">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <div class="page-info"><span id="totalPayslips"><?= count($payslips) ?></span> payslips found</div>
                    <div class="page-controls">
                        <button class="page-btn" id="prevPage"><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn" id="nextPage"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple JS for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            document.getElementById('employeeSearch').addEventListener('input', filterTable);
            document.getElementById('batchFilter').addEventListener('change', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('dateFilter').addEventListener('change', filterTable);
            
            // Pagination buttons
            document.getElementById('prevPage').addEventListener('click', function() {
                alert('Previous page clicked');
            });
            
            document.getElementById('nextPage').addEventListener('click', function() {
                alert('Next page clicked');
            });
        });
        
        function filterTable() {
            const employeeSearch = document.getElementById('employeeSearch').value.toLowerCase();
            const batchFilter = document.getElementById('batchFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const rows = document.querySelectorAll('#payslipTable tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const employee = row.cells[1].textContent.toLowerCase();
                const batch = row.cells[2].textContent;
                const status = row.cells[6].textContent;
                
                const matchesEmployee = employee.includes(employeeSearch);
                const matchesBatch = batchFilter === '' || batch === batchFilter;
                const matchesStatus = statusFilter === '' || status === statusFilter;
                // Date filter would need actual date data in the table
                
                if (matchesEmployee && matchesBatch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update record count
            document.getElementById('startRecord').textContent = '1';
            document.getElementById('endRecord').textContent = visibleCount;
            document.getElementById('totalRecords').textContent = visibleCount;
            document.getElementById('totalPayslips').textContent = visibleCount;
        }
        
        function viewPayslip(reference) {
            alert('Viewing payslip: ' + reference);
            // In a real app, this would open a modal or redirect to view page
        }
        
        function editPayslip(reference) {
            alert('Editing payslip: ' + reference);
            // In a real app, this would open an edit form
        }
        
        function printPayslip(reference) {
            alert('Printing payslip: ' + reference);
            // In a real app, this would generate a PDF
        }
    </script>
</body>
</html>