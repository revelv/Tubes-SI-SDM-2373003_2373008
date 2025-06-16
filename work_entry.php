<?php
// Sample PHP data - in real app this would come from database
$current_month = date('F Y');
$work_days = [
    'Mon' => ['date' => '02', 'hours' => 8],
    'Tue' => ['date' => '03', 'hours' => 7.5],
    'Wed' => ['date' => '04', 'hours' => 8],
    'Thu' => ['date' => '05', 'hours' => 6],
    'Fri' => ['date' => '06', 'hours' => 8],
    'Sat' => ['date' => '07', 'hours' => 0],
    'Sun' => ['date' => '08', 'hours' => 0],
];
$total_hours = array_sum(array_column($work_days, 'hours'));

require_once 'process.php';
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Payroll Dashboard</title>
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
            max-width: 1200px;
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
        
        .work-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .day-card {
            text-align: center;
            padding: 15px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            background-color: white;
            border: 1px solid #eee;
        }
        
        .day-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(113, 75, 103, 0.1);
        }
        
        .day-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .day-date {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .day-hours {
            font-weight: bold;
            color: var(--primary);
            font-size: 16px;
        }
        
        .day-card.weekend {
            background-color: #fafafa;
        }
        
        .day-card.weekend .day-hours {
            color: #aaa;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary), var(--accent));
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #6a8caf, #4a708b);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #c77eb5, #a45c94);
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .activity-icon.primary {
            background-color: #f0e6ee;
            color: var(--primary);
        }
        
        .activity-icon.warning {
            background-color: #fce4ec;
            color: #c2185b;
        }
        
        .activity-content {
            flex-grow: 1;
        }
        
        .activity-title {
            font-weight: 500;
            margin-bottom: 3px;
        }
        
        .activity-time {
            font-size: 12px;
            color: #6c757d;
        }
        
        @media (max-width: 992px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .work-calendar {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .work-calendar {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .main-content {
                padding: 20px 15px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    
    <div class="dashboard">
       
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <div class="page-title">Work Entries</div>
                <div class="user-profile">
                    <span>June 2025</span>
                    <button class="btn"><i class="fas fa-ellipsis-h"></i></button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card primary">
                    <i class="fas fa-calendar"></i>
                    <div class="stat-value"><?= $current_month ?></div>
                    <div class="stat-label">Current Period</div>
                </div>
                <div class="stat-card success">
                    <i class="fas fa-clock"></i>
                    <div class="stat-value"><?= $total_hours ?>h</div>
                    <div class="stat-label">Total Hours</div>
                </div>
                <div class="stat-card warning">
                    <i class="fas fa-euro-sign"></i>
                    <div class="stat-value">€2,450</div>
                    <div class="stat-label">Estimated Pay</div>
                </div>
            </div>
            
            <!-- Work Calendar -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Work Entries - <?= $current_month ?></div>
                    <div class="card-actions">
                        <button class="btn"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                <div class="work-calendar">
                    <?php foreach($work_days as $day => $data): ?>
                        <div class="day-card <?= in_array($day, ['Sat', 'Sun']) ? 'weekend' : '' ?>">
                            <div class="day-name"><?= $day ?></div>
                            <div class="day-date"><?= $data['date'] ?></div>
                            <div class="day-hours">
                                <?= $data['hours'] > 0 ? $data['hours'].'h' : '-' ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Activity</div>
                    <div class="card-actions">
                        <button class="btn"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
                <div>
                    <div class="activity-item">
                        <div class="activity-icon primary">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Timesheet approved</div>
                            <div class="activity-time">June 15, 2025 at 10:30 AM</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Payslip generated</div>
                            <div class="activity-time">June 10, 2025 at 2:15 PM</div>
                        </div>
                    </div>
                    <div class="activity-item" style="border-bottom: none;">
                        <div class="activity-icon primary">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Profile updated</div>
                            <div class="activity-time">June 5, 2025 at 9:45 AM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple JS for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to nav items
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    navItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Highlight current day
            const today = new Date().getDate().toString();
            const dayCards = document.querySelectorAll('.day-card');
            dayCards.forEach(card => {
                const dateElement = card.querySelector('.day-date');
                if(dateElement && dateElement.textContent === today) {
                    card.style.border = '2px solid var(--accent)';
                    card.style.backgroundColor = '#f9f0f7';
                }
                
                if(!card.classList.contains('weekend')) {
                    card.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f9f0f7';
                    });
                    card.addEventListener('mouseleave', function() {
                        if(!this.querySelector('.day-date').textContent === today) {
                            this.style.backgroundColor = 'white';
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>