<?php
require_once 'process.php';
require_once 'auth.php';
require 'admin_header.php';
redirectIfNotLoggedIn();

$host = 'localhost';
$dbname = 'odoo_employee_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get all leaders (prioritize Operational Manager)
$query = $pdo->query("
    SELECT 
        e.id,
        e.name,
        e.image_path,
        e.email,
        jp.title,
        d.name AS department_name,
        d.id AS department_id,
        jp.position_id,
        CASE 
            WHEN jp.title = 'Operational Manager' THEN 0
            WHEN jp.title LIKE '%Manager%' THEN 1
            WHEN jp.title LIKE '%Supervisor%' THEN 2
            ELSE 3
        END AS priority
    FROM employees e
    JOIN job_positions jp ON e.position_id = jp.position_id
    JOIN departments d ON e.department_id = d.id
    WHERE jp.title LIKE '%Manager%' 
       OR jp.title LIKE '%Supervisor%'
    ORDER BY priority, d.name
");
$leaders = $query->fetchAll(PDO::FETCH_ASSOC);

// Separate Operational Manager from others
$operationalManager = array_filter($leaders, function($leader) {
    return $leader['title'] === 'Operational Manager';
});
$otherLeaders = array_filter($leaders, function($leader) {
    return $leader['title'] !== 'Operational Manager';
});

// Get team members for each leader
function getTeamMembers($pdo, $leader) {
    $stmt = $pdo->prepare("
        SELECT e.id, e.name, e.image_path, e.email, jp.title
        FROM employees e
        JOIN job_positions jp ON e.position_id = jp.position_id
        WHERE e.department_id = ? 
          AND e.position_id != ?
          AND jp.title NOT LIKE '%Manager%'
          AND jp.title NOT LIKE '%Supervisor%'
        ORDER BY jp.position_id, e.name
    ");
    $stmt->execute([$leader['department_id'], $leader['position_id']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($operationalManager as &$leader) {
    $leader['team_members'] = getTeamMembers($pdo, $leader);
}
unset($leader);

foreach ($otherLeaders as &$leader) {
    $leader['team_members'] = getTeamMembers($pdo, $leader);
}
unset($leader);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizational Chart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            overflow-x: auto;
            margin-top: 80px;
        }
        .ireng {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .org-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .top-level {
            margin-bottom: 60px;
            text-align: center;
        }
        .managers-row {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            position: relative;
            padding-top: 50px;
            flex-wrap: wrap;
        }
        .leader-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            margin-bottom: 60px;
        }
        .leader-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px;
            width: 220px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        .operational-manager {
            background-color: #e3f2fd;
            border: 2px solid #3498db;
            width: 250px;
        }
        .leader-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .leader-card.active {
            background-color: #e3f2fd;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            border: 3px solid #3498db;
        }
        .no-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border: 3px solid #3498db;
            color: #7f8c8d;
            font-size: 0.7em;
        }
        .leader-name {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .leader-position {
            color: #16a085;
            font-size: 0.9em;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .leader-department {
            color: #7f8c8d;
            font-size: 0.8em;
            margin-bottom: 10px;
        }
        .team-size {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            display: inline-block;
        }
        .connector-line {
            position: absolute;
            background-color: #95a5a6;
            z-index: 1;
        }
        .vertical-line {
            width: 2px;
            height: 30px;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
        }
        .top-connector {
            width: 2px;
            height: 50px;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
        }
        .horizontal-connector {
            height: 2px;
            width: 80%;
            position: absolute;
            top: -30px;
            left: 10%;
        }
        .team-container {
            display: none;
            position: relative;
            margin-top: 20px;
            width: 100%;
        }
        .team-members {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1000px;
            padding-top: 20px;
            position: relative;
        }
        .team-members:before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 80%;
            height: 2px;
            background-color: #95a5a6;
            transform: translateX(-50%);
        }
        .team-member {
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            padding: 12px;
            width: 180px;
            text-align: center;
            position: relative;
        }
        .team-member:before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background-color: #95a5a6;
        }
        .team-member .profile-pic {
            width: 60px;
            height: 60px;
            border-width: 2px;
        }
        .team-member .no-photo {
            width: 60px;
            height: 60px;
            border-width: 2px;
        }
        .team-member-name {
            font-weight: bold;
            font-size: 1em;
            margin-bottom: 3px;
        }
        .team-member-position {
            color: #7f8c8d;
            font-size: 0.8em;
        }
        .department-title {
            width: 100%;
            text-align: center;
            margin: 20px 0 10px;
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1 class="ireng">Organizational Chart</h1>
    
    <div class="org-container">
        <?php if (!empty($operationalManager)): ?>
            <div class="top-level">
                <?php $om = reset($operationalManager); ?>
                <div class="leader-node">
                    <div class="leader-card operational-manager" onclick="toggleTeam(this, 'om')">
                        <?php if ($om['image_path']): ?>
                            <img src="<?= htmlspecialchars($om['image_path']) ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <div class="no-photo">No photo</div>
                        <?php endif; ?>
                        
                        <div class="leader-name"><?= htmlspecialchars($om['name']) ?></div>
                        <div class="leader-position"><?= htmlspecialchars($om['title']) ?></div>
                        <div class="leader-department"><?= htmlspecialchars($om['department_name']) ?></div>
                        
                        <?php if (count($om['team_members']) > 0): ?>
                            <div class="team-size"><?= count($om['team_members']) ?> team members</div>
                        <?php endif; ?>
                    </div>
                    <div class="connector-line top-connector"></div>
                    
                    <div class="team-container" id="team-om">
                        <?php if (count($om['team_members']) > 0): ?>
                            <div class="department-title"><?= htmlspecialchars($om['department_name']) ?> Team</div>
                            <div class="team-members">
                                <?php foreach ($om['team_members'] as $member): ?>
                                    <div class="team-member">
                                        <?php if ($member['image_path']): ?>
                                            <img src="<?= htmlspecialchars($member['image_path']) ?>" alt="Profile" class="profile-pic">
                                        <?php else: ?>
                                            <div class="no-photo">No photo</div>
                                        <?php endif; ?>
                                        
                                        <div class="team-member-name"><?= htmlspecialchars($member['name']) ?></div>
                                        <div class="team-member-position"><?= htmlspecialchars($member['title']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="team-members">
                                <div style="color:#95a5a6; font-style:italic;">No team members</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="connector-line horizontal-connector"></div>
        <?php endif; ?>
        
        <div class="managers-row">
            <?php foreach ($otherLeaders as $index => $leader): ?>
                <div class="leader-node">
                    <div class="connector-line vertical-line"></div>
                    
                    <div class="leader-card" onclick="toggleTeam(this, <?= $index ?>)">
                        <?php if ($leader['image_path']): ?>
                            <img src="<?= htmlspecialchars($leader['image_path']) ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <div class="no-photo">No photo</div>
                        <?php endif; ?>
                        
                        <div class="leader-name"><?= htmlspecialchars($leader['name']) ?></div>
                        <div class="leader-position"><?= htmlspecialchars($leader['title']) ?></div>
                        <div class="leader-department"><?= htmlspecialchars($leader['department_name']) ?></div>
                        
                        <?php if (count($leader['team_members']) > 0): ?>
                            <div class="team-size"><?= count($leader['team_members']) ?> team members</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="team-container" id="team-<?= $index ?>">
                        <?php if (count($leader['team_members']) > 0): ?>
                            <div class="department-title"><?= htmlspecialchars($leader['department_name']) ?> Team</div>
                            <div class="team-members">
                                <?php foreach ($leader['team_members'] as $member): ?>
                                    <div class="team-member">
                                        <?php if ($member['image_path']): ?>
                                            <img src="<?= htmlspecialchars($member['image_path']) ?>" alt="Profile" class="profile-pic">
                                        <?php else: ?>
                                            <div class="no-photo">No photo</div>
                                        <?php endif; ?>
                                        
                                        <div class="team-member-name"><?= htmlspecialchars($member['name']) ?></div>
                                        <div class="team-member-position"><?= htmlspecialchars($member['title']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="team-members">
                                <div style="color:#95a5a6; font-style:italic;">No team members</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        let currentlyOpen = null;
        
        function toggleTeam(card, index) {
            const teamContainer = document.getElementById(`team-${index}`);
            
            // If clicking the already open team, close it
            if (currentlyOpen === index) {
                card.classList.remove('active');
                teamContainer.style.display = 'none';
                currentlyOpen = null;
                return;
            }
            
            // Close any previously open team
            if (currentlyOpen !== null) {
                const prevCard = document.querySelector(`.leader-card[onclick="toggleTeam(this, ${currentlyOpen})"]`) || 
                                document.querySelector(`.leader-card[onclick="toggleTeam(this, '${currentlyOpen}')"]`);
                const prevTeam = document.getElementById(`team-${currentlyOpen}`);
                if (prevCard && prevTeam) {
                    prevCard.classList.remove('active');
                    prevTeam.style.display = 'none';
                }
            }
            
            // Open the clicked team
            card.classList.add('active');
            teamContainer.style.display = 'block';
            currentlyOpen = index;
            
            // Scroll to ensure the team is visible
            teamContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Open the Operational Manager's team by default
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.operational-manager')) {
                document.querySelector('.operational-manager').click();
            }
        });
    </script>
</body>
</html>