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
    <title>HR Management System</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="user-profile">
                    <img src="https://via.placeholder.com/40" alt="User Profile">
                    <span>Admin User</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <h2>Dashboard Overview</h2>
                
                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #e3f2fd;">
                            <i class="fas fa-user-tie" style="color: #2196f3;"></i>
                        </div>
                        <div class="stat-info">
                            <h3>125</h3>
                            <p>Total Employees</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #e8f5e9;">
                            <i class="fas fa-money-bill-wave" style="color: #4caf50;"></i>
                        </div>
                        <div class="stat-info">
                            <h3>$245,000</h3>
                            <p>Monthly Payroll</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #fff3e0;">
                            <i class="fas fa-calendar-alt" style="color: #ff9800;"></i>
                        </div>
                        <div class="stat-info">
                            <h3>12</h3>
                            <p>Pending Time Off</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #f3e5f5;">
                            <i class="fas fa-clipboard-list" style="color: #9c27b0;"></i>
                        </div>
                        <div class="stat-info">
                            <h3>8</h3>
                            <p>Open Positions</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities and Quick Links -->
                <div class="content-row">
                    <div class="recent-activities">
                        <h3><i class="fas fa-bell"></i> Recent Activities</h3>
                        <ul>
                            <li>
                                <i class="fas fa-user-plus activity-icon"></i>
                                <div class="activity-details">
                                    <p>New employee onboarded: John Doe</p>
                                    <small>2 hours ago</small>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-file-alt activity-icon"></i>
                                <div class="activity-details">
                                    <p>Payroll processed for June 2023</p>
                                    <small>1 day ago</small>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-calendar-check activity-icon"></i>
                                <div class="activity-details">
                                    <p>Time off approved for Sarah Smith</p>
                                    <small>2 days ago</small>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-star activity-icon"></i>
                                <div class="activity-details">
                                    <p>Performance review completed for 5 employees</p>
                                    <small>3 days ago</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="quick-links">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                        <div class="quick-actions">
                            <a href="#" class="quick-action">
                                <i class="fas fa-user-plus"></i>
                                <span>Add Employee</span>
                            </a>
                            <a href="#" class="quick-action">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Process Payroll</span>
                            </a>
                            <a href="#" class="quick-action">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Request Time Off</span>
                            </a>
                            <a href="#" class="quick-action">
                                <i class="fas fa-clipboard-check"></i>
                                <span>Start Appraisal</span>
                            </a>
                            <a href="#" class="quick-action">
                                <i class="fas fa-bullhorn"></i>
                                <span>Post Job Opening</span>
                            </a>
                            <a href="#" class="quick-action">
                                <i class="fas fa-chart-bar"></i>
                                <span>View Reports</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events -->
                <div class="upcoming-events">
                    <h3><i class="fas fa-calendar-day"></i> Upcoming Events</h3>
                    <div class="events-list">
                        <div class="event">
                            <div class="event-date">
                                <span class="day">15</span>
                                <span class="month">Jun</span>
                            </div>
                            <div class="event-details">
                                <h4>Payroll Processing</h4>
                                <p>Monthly payroll processing deadline</p>
                            </div>
                        </div>
                        <div class="event">
                            <div class="event-date">
                                <span class="day">20</span>
                                <span class="month">Jun</span>
                            </div>
                            <div class="event-details">
                                <h4>Team Building</h4>
                                <p>Company-wide team building event</p>
                            </div>
                        </div>
                        <div class="event">
                            <div class="event-date">
                                <span class="day">30</span>
                                <span class="month">Jun</span>
                            </div>
                            <div class="event-details">
                                <h4>Quarterly Reviews</h4>
                                <p>Q2 performance reviews deadline</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>