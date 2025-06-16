<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styrk Industries - HR System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            background-color: #f5f6f7;
            color: #333;
        }

        /* Header Styles */
        .header {
            background-color: #714B67;
            color: white;
            padding: 0 20px;
            font-size: 15px;
            font-weight: 300;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand h1 {
            font-size: 1.2rem;
            margin: 0;
        }

        /* Main Navigation */
        .main-nav {
            display: flex;
            align-items: center;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 20px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: #5d3a56;
        }

        .nav-link i {
            font-size: 0.9rem;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 0 0 4px 4px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            color: black;
            display: block;
            padding: 10px 15px;
            transition: background-color 0.2s;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background-color: #f0e6ee;
        }

        /* User Info Styles */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }



        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }


        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .mobile-menu-btn {
                display: block;
            }

            .main-nav {
                position: fixed;
                top: 60px;
                left: 0;
                width: 100%;
                background-color: #714B67;
                flex-direction: column;
                align-items: stretch;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .main-nav.open {
                max-height: 500px;
            }

            .nav-menu {
                flex-direction: column;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                padding: 15px 20px;
            }

            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                display: none;
                box-shadow: none;
                border-radius: 0;
                background-color: #5d3a56;
            }

            .nav-item:hover .dropdown-menu {
                display: block;
            }

            .dropdown-item {
                text-decoration: none;
                padding-left: 40px;
            }

            .dropdown-item:hover {
                background-color: #4a2e44;
            }
        }

        @media (max-width: 768px) {
            .user-info {
                flex-wrap: wrap;
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .header-container {
                padding: 10px 0;
            }

            .user-info>* {
                margin: 2px 0;
            }

            .add-btn,
            .logout-btn,
            .back-btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header with Navigation -->
    <header class="header">
        <div class="header-container">
            <div class="brand">
                <h1><i class="fas fa-users"></i> Styrk Industries</h1>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="main-nav" id="mainNav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-tie"></i>
                            <span>Employees</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="index.php" class="dropdown-item">All Employees</a>
                            <a href="add_employee.php" class="dropdown-item">Add New</a>
                            <a href="departements.php" class="dropdown-item">Departments</a>
                            <a href="skill_types.php" class="dropdown-item">Skill Types</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Payroll</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="payroll.php" class="dropdown-item">Contracts</a>
                            <a href="work_entry.php" class="dropdown-item">Work Entries</a>
                            <a href="payslips.php" class="dropdown-item">Payslips</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-star"></i>
                            <span>Appraisals</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="appraisals.php" class="dropdown-item">Appraisals Employee</a>
                            <a href="#" class="dropdown-item">Goals</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Time Off</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="timeoff.php" class="dropdown-item">Approvals</a>
                            <a href="dashboard_time_off.php" class="dropdown-item">Calendar</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Recruitment</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="admin_approval.php" class="dropdown-item">Candidates</a>
                        </div>
                    </li>
                </ul>

                <div class="user-info">
                    <span style="color: white;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>

                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mainNav').classList.toggle('open');

            // Change icon between bars and times
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mainNav = document.getElementById('mainNav');
            const mobileBtn = document.getElementById('mobileMenuBtn');

            if (!mainNav.contains(event.target) && event.target !== mobileBtn) {
                mainNav.classList.remove('open');
                mobileBtn.querySelector('i').classList.remove('fa-times');
                mobileBtn.querySelector('i').classList.add('fa-bars');
            }
        });

        // Automatically close dropdowns when clicking another one on mobile
        if (window.innerWidth <= 992) {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                const link = item.querySelector('.nav-link');
                link.addEventListener('click', function(e) {
                    if (item.querySelector('.dropdown-menu')) {
                        e.preventDefault();
                        const dropdown = item.querySelector('.dropdown-menu');
                        const isOpen = dropdown.style.display === 'block';

                        // Close all dropdowns first
                        document.querySelectorAll('.dropdown-menu').forEach(d => {
                            d.style.display = 'none';
                        });

                        // Toggle this one
                        dropdown.style.display = isOpen ? 'none' : 'block';
                    }
                });
            });
        }
    </script>
</body>

</html>