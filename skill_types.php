<?php
require 'process.php';
require 'auth.php';
include 'admin_header.php';
redirectIfNotLoggedIn();

// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'odoo_employee_db',
    'username' => 'root',
    'password' => ''
];

// Initialize database connection
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Database functions
function getAllSkills($pdo)
{
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY nama_skill");
    return $stmt->fetchAll();
}

function getSkillById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getEmployees($pdo)
{
    $stmt = $pdo->query("SELECT id, name FROM employees ORDER BY name");
    return $stmt->fetchAll();
}

function getEmployeeSkills($pdo, $employee_id = null)
{
    $sql = "SELECT es.*, s.nama_skill, e.name as employee_name 
            FROM employee_skills es
            JOIN skills s ON es.skill_id = s.id
            JOIN employees e ON es.employee_id = e.id";

    if ($employee_id) {
        $sql .= " WHERE es.employee_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$employee_id]);
    } else {
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll();
}

function addSkill($pdo, $data)
{
    $stmt = $pdo->prepare("INSERT INTO skills (nama_skill, jenis_skill, deskripsi) VALUES (?, ?, ?)");
    $success = $stmt->execute([
        htmlspecialchars($data['nama_skill']),
        htmlspecialchars($data['jenis_skill']),
        htmlspecialchars($data['deskripsi'])
    ]);

    return $success ? $pdo->lastInsertId() : false;
}

function updateSkill($pdo, $id, $data)
{
    $stmt = $pdo->prepare("UPDATE skills SET nama_skill = ?, jenis_skill = ?, deskripsi = ? WHERE id = ?");
    return $stmt->execute([
        htmlspecialchars($data['nama_skill']),
        htmlspecialchars($data['jenis_skill']),
        htmlspecialchars($data['deskripsi']),
        $id
    ]);
}

function deleteSkill($pdo, $id)
{
    // First check if any employee has this skill
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM employee_skills WHERE skill_id = ?");
    $checkStmt->execute([$id]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error_message'] = "Cannot delete skill as it is assigned to employees.";
        return false;
    }

    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    return $stmt->execute([$id]);
}

function addEmployeeSkill($pdo, $data)
{
    // Check for existing skill
    $checkStmt = $pdo->prepare("SELECT id FROM employee_skills WHERE employee_id = ? AND skill_id = ?");
    $checkStmt->execute([$data['employee_id'], $data['skill_id']]);

    if ($checkStmt->fetch()) {
        $_SESSION['error_message'] = "This employee already has this skill.";
        return false;
    }

    $stmt = $pdo->prepare("INSERT INTO employee_skills (employee_id, skill_id, tingkat_keahlian, catatan) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([
        htmlspecialchars($data['employee_id']),
        htmlspecialchars($data['skill_id']),
        htmlspecialchars($data['tingkat_keahlian']),
        htmlspecialchars($data['catatan'])
    ]);

    return $success ? $pdo->lastInsertId() : false;
}

function updateEmployeeSkill($pdo, $id, $data)
{
    $stmt = $pdo->prepare("UPDATE employee_skills SET tingkat_keahlian = ?, catatan = ? WHERE id = ?");
    return $stmt->execute([
        htmlspecialchars($data['tingkat_keahlian']),
        htmlspecialchars($data['catatan']),
        $id
    ]);
}

function deleteEmployeeSkill($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM employee_skills WHERE id = ?");
    return $stmt->execute([$id]);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = '';
    if (isset($_POST['add_skill'])) {
        $action = 'add_skill';
    } elseif (isset($_POST['update_skill'])) {
        $action = 'update_skill';
    } elseif (isset($_POST['add_employee_skill'])) {
        $action = 'add_employee_skill';
    } elseif (isset($_POST['update_employee_skill'])) {
        $action = 'update_employee_skill';
    }

    switch ($action) {
        case 'add_skill':
            $success = addSkill($pdo, $_POST);
            $message = $success ? "Skill added successfully!" : "Failed to add skill. Please try again.";
            break;
            
        case 'update_skill':
            $success = updateSkill($pdo, $_POST['id'], $_POST);
            $message = $success ? "Skill updated successfully!" : "Failed to update skill.";
            break;
            
        case 'add_employee_skill':
            $success = addEmployeeSkill($pdo, $_POST);
            $message = $success ? "Employee skill added successfully!" : "Failed to add employee skill.";
            break;
            
        case 'update_employee_skill':
            $success = updateEmployeeSkill($pdo, $_POST['id'], $_POST);
            $message = $success ? "Employee skill updated successfully!" : "Failed to update employee skill.";
            break;
            
        default:
            $success = false;
            $message = "Invalid action. Please try again.";
    }
    
    $_SESSION[$success ? 'success_message' : 'error_message'] = $message;
    echo "<script>window.location.href='skill_types.php';</script>";
    exit();
}


// Handle GET actions (delete)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete_skill':
            if (isset($_GET['id'])) {
                $success = deleteSkill($pdo, $_GET['id']);
                $message = $success
                    ? "Skill deleted successfully!"
                    : "Failed to delete skill.";
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $success = deleteEmployeeSkill($pdo, $_GET['id']);
                $message = $success
                    ? "Employee skill deleted successfully!"
                    : "Failed to delete employee skill.";
            }
            break;

        case 'edit_skill':
            // This will be handled in the view
            break;
    }

    if (isset($message)) {
        $_SESSION[$success ? 'success_message' : 'error_message'] = $message;
        echo "<script>window.location.href='skill_types.php';</script>";
        exit();
    }
}

// Get all data
$skills = getAllSkills($pdo);
$employees = getEmployees($pdo);
$selectedEmployee = $_GET['employee_id'] ?? null;
$employeeSkills = getEmployeeSkills($pdo, $selectedEmployee);
$editingSkill = isset($_GET['action']) && $_GET['action'] === 'edit_skill' && isset($_GET['id'])
    ? getSkillById($pdo, $_GET['id'])
    : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Skills Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            color: #333;
            margin-top: 70px;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .ireng {
            color: #2c3e50;
            text-align: center;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 5px;
            margin-top: 5px;
        }

        .btn-submit {
            background-color: #2ecc71;
            color: white;
        }

        .btn-submit:hover {
            background-color: #27ae60;
        }

        .btn-cancel {
            background-color: #e74c3c;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c0392b;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-filter {
            background-color: #9b59b6;
            color: white;
        }

        .btn-filter:hover {
            background-color: #8e44ad;
        }

        .skills-list {
            margin-top: 20px;
        }

        .skill-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .skill-name {
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }

        .skill-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .soft-skill {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .hard-skill {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .skill-desc {
            font-size: 14px;
            color: #555;
        }

        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .proficiency {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-top: 5px;
        }

        .proficiency-beginner {
            background-color: #ffebee;
            color: #c62828;
        }

        .proficiency-intermediate {
            background-color: #fff8e1;
            color: #f57f17;
        }

        .proficiency-advanced {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .proficiency-expert {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .employee-filter {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .employee-skill-actions {
            margin-top: 10px;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }

        .tab.active {
            background-color: #3498db;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .skills-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .skills-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .skills-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .edit-form {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
        }

        function confirmDelete(id, type = 'employee') {
            const message = type === 'employee' ?
                'Are you sure you want to delete this employee skill?' :
                'Are you sure you want to delete this skill?';

            if (confirm(message)) {
                const url = type === 'employee' ?
                    `skill_types.php?action=delete&id=${id}` :
                    `skill_types.php?action=delete_skill&id=${id}`;
                window.location.href = url;
            }
        }

        function toggleEditForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>

<body>
    <div class="container">
        <h1 class="ireng">Skills</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab active" data-tab="skills-tab" onclick="showTab('skills-tab')">Skills</div>
            <div class="tab" data-tab="employee-skills-tab" onclick="showTab('employee-skills-tab')">Employee Skills</div>
        </div>

        <div id="skills-tab" class="tab-content active">
            <div class="form-container">
                <h2><?= $editingSkill ? 'Edit Skill' : 'Add New Skill' ?></h2>
                <form method="POST">
                    <?php if ($editingSkill): ?>
                        <input type="hidden" name="id" value="<?= $editingSkill['id'] ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nama_skill">Skill Name:</label>
                        <input type="text" id="nama_skill" name="nama_skill" required
                            value="<?= htmlspecialchars($editingSkill['nama_skill'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="jenis_skill">Skill Type:</label>
                        <select id="jenis_skill" name="jenis_skill" required>
                            <option value="hard_skill" <?= isset($editingSkill) && $editingSkill['jenis_skill'] === 'hard_skill' ? 'selected' : '' ?>>Hard Skill</option>
                            <option value="soft_skill" <?= isset($editingSkill) && $editingSkill['jenis_skill'] === 'soft_skill' ? 'selected' : '' ?>>Soft Skill</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Description:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"><?= htmlspecialchars($editingSkill['deskripsi'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" name="<?= $editingSkill ? 'update_skill' : 'add_skill' ?>" class="btn btn-submit">
                        <?= $editingSkill ? 'Update Skill' : 'Add Skill' ?>
                    </button>
                    <?php if ($editingSkill): ?>
                        <a href="skill_types.php" class="btn btn-cancel">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="skills-list">
                <h2>Existing Skills</h2>
                <?php if (empty($skills)): ?>
                    <p>No skills found in database.</p>
                <?php else: ?>
                    <div class="skills-grid">
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-card">
                                <div class="skill-name"><?= htmlspecialchars($skill['nama_skill']) ?></div>
                                <div class="skill-type <?= $skill['jenis_skill'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $skill['jenis_skill'])) ?>
                                </div>
                                <?php if (!empty($skill['deskripsi'])): ?>
                                    <div class="skill-desc"><?= htmlspecialchars($skill['deskripsi']) ?></div>
                                <?php endif; ?>
                                <div class="action-buttons">
                                    <a href="skill_types.php?action=edit_skill&id=<?= $skill['id'] ?>" class="btn btn-edit">Edit</a>
                                    <button class="btn btn-cancel" onclick="confirmDelete(<?= $skill['id'] ?>, 'skill')">Delete</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="employee-skills-tab" class="tab-content">
            <div class="employee-filter">
                <h2>Employee Skills</h2>
                <form method="GET" action="skill_types.php">
                    <div class="form-group">
                        <label for="employee_id">Filter by Employee:</label>
                        <select id="employee_id" name="employee_id" onchange="this.form.submit()">
                            <option value="">All Employees</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>" <?= ($selectedEmployee == $employee['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($employee['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <div class="form-container">
                <h2>Add Skill to Employee</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="add_employee_id">Employee:</label>
                        <select id="add_employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>" <?= ($selectedEmployee == $employee['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($employee['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_skill_id">Skill:</label>
                        <select id="add_skill_id" name="skill_id" required>
                            <option value="">Select Skill</option>
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?= $skill['id'] ?>">
                                    <?= htmlspecialchars($skill['nama_skill']) ?>
                                    (<?= ucfirst(str_replace('_', ' ', $skill['jenis_skill'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_tingkat_keahlian">Proficiency Level:</label>
                        <select id="add_tingkat_keahlian" name="tingkat_keahlian" required>
                            <option value="">Select Level</option>
                            <option value="pemula">Beginner</option>
                            <option value="menengah">Intermediate</option>
                            <option value="mahir">Advanced</option>
                            <option value="ahli">Expert</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_catatan">Notes:</label>
                        <textarea id="add_catatan" name="catatan" rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_employee_skill" class="btn btn-submit">Add to Employee</button>
                </form>
            </div>

            <h2>Employee Skills List</h2>
            <?php if (empty($employeeSkills)): ?>
                <p>No employee skills found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Skill</th>
                            <th>Proficiency</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employeeSkills as $es):
                            $proficiencyMap = [
                                'pemula' => ['class' => 'proficiency-beginner', 'text' => 'Beginner'],
                                'menengah' => ['class' => 'proficiency-intermediate', 'text' => 'Intermediate'],
                                'mahir' => ['class' => 'proficiency-advanced', 'text' => 'Advanced'],
                                'ahli' => ['class' => 'proficiency-expert', 'text' => 'Expert']
                            ];
                            $proficiency = $proficiencyMap[$es['tingkat_keahlian'] ?? ['class' => '', 'text' => 'Unknown']];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($es['employee_name']) ?></td>
                                <td><?= htmlspecialchars($es['nama_skill']) ?></td>
                                <td>
                                    <span class="proficiency <?= $proficiency['class'] ?>">
                                        <?= $proficiency['text'] ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($es['catatan'] ?? 'N/A') ?></td>
                                <td>
                                    <button class="btn btn-edit" onclick="toggleEditForm('edit-form-<?= $es['id'] ?>')">Edit</button>
                                    <button class="btn btn-cancel" onclick="confirmDelete(<?= $es['id'] ?>)">Delete</button>

                                    <div id="edit-form-<?= $es['id'] ?>" class="edit-form">
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $es['id'] ?>">
                                            <div class="form-group">
                                                <label>Proficiency Level:</label>
                                                <select name="tingkat_keahlian" required>
                                                    <?php foreach ($proficiencyMap as $key => $value): ?>
                                                        <option value="<?= $key ?>" <?= ($es['tingkat_keahlian'] == $key) ? 'selected' : '' ?>>
                                                            <?= $value['text'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Notes:</label>
                                                <textarea name="catatan" rows="3"><?= htmlspecialchars($es['catatan'] ?? '') ?></textarea>
                                            </div>
                                            <button type="submit" name="update_employee_skill" class="btn btn-submit">Update</button>
                                            <button type="button" class="btn btn-cancel" onclick="toggleEditForm('edit-form-<?= $es['id'] ?>')">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>