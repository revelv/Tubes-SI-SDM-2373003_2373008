<?php
require 'process.php';
require 'auth.php';
include 'admin_header.php';
redirectIfNotLoggedIn();

// Initialize database connection
try {
    $host = 'localhost';
    $dbname = 'odoo_employee_db';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Functions
function getAllSkills($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM skills ORDER BY nama_skill");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching skills: " . $e->getMessage());
    }
}

function getEmployees($pdo)
{
    try {
        $stmt = $pdo->query("SELECT id, name FROM employees ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching employees: " . $e->getMessage());
    }
}

function getEmployeeSkills($pdo, $employee_id = null)
{
    try {
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
    } catch (PDOException $e) {
        die("Error fetching employee skills: " . $e->getMessage());
    }
}

function addSkill($pdo, $data)
{
    try {
        $stmt = $pdo->prepare("INSERT INTO skills (nama_skill, jenis_skill, deskripsi) VALUES (?, ?, ?)");
        $success = $stmt->execute([
            htmlspecialchars($data['nama_skill']),
            htmlspecialchars($data['jenis_skill']),
            htmlspecialchars($data['deskripsi'])
        ]);

        if (!$success) {
            error_log("Failed to add skill: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Database error adding skill: " . $e->getMessage());
        return false;
    }
}

function addEmployeeSkill($pdo, $data)
{
    try {
        // Check if this employee already has this skill
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

        if (!$success) {
            error_log("Failed to add employee skill: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Database error adding employee skill: " . $e->getMessage());
        return false;
    }
}

function updateEmployeeSkill($pdo, $id, $data)
{
    try {
        $stmt = $pdo->prepare("UPDATE employee_skills SET tingkat_keahlian = ?, catatan = ? WHERE id = ?");
        $success = $stmt->execute([
            htmlspecialchars($data['tingkat_keahlian']),
            htmlspecialchars($data['catatan']),
            $id
        ]);

        if (!$success) {
            error_log("Failed to update employee skill: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        return true;
    } catch (PDOException $e) {
        error_log("Database error updating employee skill: " . $e->getMessage());
        return false;
    }
}

function deleteEmployeeSkill($pdo, $id)
{
    try {
        $stmt = $pdo->prepare("DELETE FROM employee_skills WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Database error deleting employee skill: " . $e->getMessage());
        return false;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_skill'])) {
        if (addSkill($pdo, $_POST)) {
            $_SESSION['success_message'] = "Skill added successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to add skill. Please try again.";
        }
    } elseif (isset($_POST['add_employee_skill'])) {
        if (addEmployeeSkill($pdo, $_POST)) {
            $_SESSION['success_message'] = "Employee skill added successfully!";
        }
    } elseif (isset($_POST['update_employee_skill'])) {
        if (updateEmployeeSkill($pdo, $_POST['id'], $_POST)) {
            $_SESSION['success_message'] = "Employee skill updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update employee skill.";
        }
    }

    echo "<script>window.location.href='skill_types.php';</script>";
    exit();
}

// Handle GET actions (delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (deleteEmployeeSkill($pdo, $_GET['id'])) {
        $_SESSION['success_message'] = "Employee skill deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete employee skill.";
    }
    echo "<script>window.location.href='skill_types.php';</script>";
    exit();
}

function updateSkill($pdo, $id, $data)
{
    try {
        $stmt = $pdo->prepare("UPDATE skills SET nama_skill = ?, jenis_skill = ?, deskripsi = ? WHERE id = ?");
        $success = $stmt->execute([
            htmlspecialchars($data['nama_skill']),
            htmlspecialchars($data['jenis_skill']),
            htmlspecialchars($data['deskripsi']),
            $id
        ]);

        if (!$success) {
            error_log("Failed to update skill: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        return true;
    } catch (PDOException $e) {
        error_log("Database error updating skill: " . $e->getMessage());
        return false;
    }
}

function deleteSkill($pdo, $id)
{
    try {
        // First delete any employee skills associated with this skill
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("DELETE FROM employee_skills WHERE skill_id = ?");
        $stmt->execute([$id]);

        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Database error deleting skill: " . $e->getMessage());
        return false;
    }
}

// Add this with your other POST handlers
if (isset($_POST['update_skill'])) {
    if (updateSkill($pdo, $_POST['id'], $_POST)) {
        $_SESSION['success_message'] = "Skill updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update skill.";
    }
} elseif (isset($_POST['delete_skill'])) {
    if (deleteSkill($pdo, $_POST['id'])) {
        $_SESSION['success_message'] = "Skill deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete skill.";
    }
}

// Get all data
$skills = getAllSkills($pdo);
$employees = getEmployees($pdo);
$employeeSkills = getEmployeeSkills($pdo);
$selectedEmployee = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
$filteredSkills = $selectedEmployee ? getEmployeeSkills($pdo, $selectedEmployee) : $employeeSkills;
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
            padding: 20px;
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
            position: relative;
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

        /* Add these styles to your existing CSS */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .skill-card {
            height: 100%;
            box-sizing: border-box;
        }

        /* Responsive adjustments */
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

        /* Skill Card Enhancements */
        .skill-card {
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .skill-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .skill-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .skill-card:hover .skill-actions {
            opacity: 1;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            color: white;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .edit-btn {
            background-color: #3498db;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
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

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this employee skill?')) {
                window.location.href = `skill_types.php?action=delete&id=${id}`;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h1 class="ireng">Employee Skills Management</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab active" data-tab="skills-tab" onclick="showTab('skills-tab')">Skills</div>
            <div class="tab" data-tab="employee-skills-tab" onclick="showTab('employee-skills-tab')">Employee Skills</div>
        </div>

        <div id="skills-tab" class="tab-content active">
            <div class="form-container">
                <h2>Add Skill to Employee</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="add_employee_id">Employee:</label>
                        <select id="add_employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_skill_id">Skill:</label>
                        <select id="add_skill_id" name="skill_id" required>
                            <option value="">Select Skill</option>
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?php echo $skill['id']; ?>">
                                    <?php echo htmlspecialchars($skill['nama_skill']); ?>
                                    (<?php echo ucfirst(str_replace('_', ' ', $skill['jenis_skill'])); ?>)
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

            <div class="skills-list">
                <h2>Existing Skills</h2>
                <?php if (empty($skills)): ?>
                    <p>No skills found in database.</p>
                <?php else: ?>
                    <div class="skills-grid">
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-card">
                                <div class="skill-actions">
                                    <button class="action-btn edit-btn" onclick="openEditModal(<?php echo $skill['id']; ?>, '<?php echo htmlspecialchars($skill['nama_skill'], ENT_QUOTES); ?>', '<?php echo $skill['jenis_skill']; ?>', `<?php echo htmlspecialchars($skill['deskripsi'], ENT_QUOTES); ?>`)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-btn" onclick="confirmDeleteSkill(<?php echo $skill['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="skill-name"><?php echo htmlspecialchars($skill['nama_skill']); ?></div>
                                <div class="skill-type <?php echo $skill['jenis_skill']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $skill['jenis_skill'])); ?>
                                </div>
                                <?php if (!empty($skill['deskripsi'])): ?>
                                    <div class="skill-desc"><?php echo htmlspecialchars($skill['deskripsi']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Edit Skill Modal -->
            <div id="editSkillModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Skill</h3>
                        <button class="close-modal" onclick="closeModal('editSkillModal')">&times;</button>
                    </div>
                    <form method="POST" id="editSkillForm">
                        <input type="hidden" name="id" id="editSkillId">
                        <div class="form-group">
                            <label for="edit_nama_skill">Skill Name:</label>
                            <input type="text" id="edit_nama_skill" name="nama_skill" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jenis_skill">Skill Type:</label>
                            <select id="edit_jenis_skill" name="jenis_skill" required>
                                <option value="soft_skill">Soft Skill</option>
                                <option value="hard_skill">Hard Skill</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi">Description:</label>
                            <textarea id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" onclick="closeModal('editSkillModal')">Cancel</button>
                            <button type="submit" name="update_skill" class="btn btn-submit">Update Skill</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteSkillModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Confirm Deletion</h3>
                        <button class="close-modal" onclick="closeModal('deleteSkillModal')">&times;</button>
                    </div>
                    <p>Are you sure you want to delete this skill? This action will also remove it from all employees.</p>
                    <form method="POST" id="deleteSkillForm">
                        <input type="hidden" name="id" id="deleteSkillId">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" onclick="closeModal('deleteSkillModal')">Cancel</button>
                            <button type="submit" name="delete_skill" class="btn btn-submit">Delete Skill</button>
                        </div>
                    </form>
                </div>
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
                                <option value="<?php echo $employee['id']; ?>" <?php echo ($selectedEmployee == $employee['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($employee['name']); ?>
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
                                <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_skill_id">Skill:</label>
                        <select id="add_skill_id" name="skill_id" required>
                            <option value="">Select Skill</option>
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?php echo $skill['id']; ?>"><?php echo htmlspecialchars($skill['nama_skill']); ?></option>
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
            <?php if (empty($filteredSkills)): ?>
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
                        <?php foreach ($filteredSkills as $es): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($es['employee_name']); ?></td>
                                <td><?php echo htmlspecialchars($es['nama_skill']); ?></td>
                                <td>
                                    <?php
                                    $proficiencyClass = '';
                                    $proficiencyText = '';
                                    switch ($es['tingkat_keahlian']) {
                                        case 'pemula':
                                            $proficiencyClass = 'proficiency-beginner';
                                            $proficiencyText = 'Beginner';
                                            break;
                                        case 'menengah':
                                            $proficiencyClass = 'proficiency-intermediate';
                                            $proficiencyText = 'Intermediate';
                                            break;
                                        case 'mahir':
                                            $proficiencyClass = 'proficiency-advanced';
                                            $proficiencyText = 'Advanced';
                                            break;
                                        case 'ahli':
                                            $proficiencyClass = 'proficiency-expert';
                                            $proficiencyText = 'Expert';
                                            break;
                                    }
                                    ?>
                                    <span class="proficiency <?php echo $proficiencyClass; ?>">
                                        <?php echo $proficiencyText; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($es['catatan'] ?? 'N/A'); ?></td>
                                <td>
                                    <button class="btn btn-edit" onclick="document.getElementById('edit-form-<?php echo $es['id']; ?>').style.display='block'">Edit</button>
                                    <button class="btn btn-cancel" onclick="confirmDelete(<?php echo $es['id']; ?>)">Delete</button>

                                    <div id="edit-form-<?php echo $es['id']; ?>" style="display:none; margin-top:10px; padding:10px; background:#f9f9f9; border-radius:5px;">
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?php echo $es['id']; ?>">
                                            <div class="form-group">
                                                <label>Proficiency Level:</label>
                                                <select name="tingkat_keahlian" required>
                                                    <option value="pemula" <?php echo ($es['tingkat_keahlian'] == 'pemula') ? 'selected' : ''; ?>>Beginner</option>
                                                    <option value="menengah" <?php echo ($es['tingkat_keahlian'] == 'menengah') ? 'selected' : ''; ?>>Intermediate</option>
                                                    <option value="mahir" <?php echo ($es['tingkat_keahlian'] == 'mahir') ? 'selected' : ''; ?>>Advanced</option>
                                                    <option value="ahli" <?php echo ($es['tingkat_keahlian'] == 'ahli') ? 'selected' : ''; ?>>Expert</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Notes:</label>
                                                <textarea name="catatan" rows="3"><?php echo htmlspecialchars($es['catatan'] ?? ''); ?></textarea>
                                            </div>
                                            <button type="submit" name="update_employee_skill" class="btn btn-submit">Update</button>
                                            <button type="button" class="btn btn-cancel" onclick="document.getElementById('edit-form-<?php echo $es['id']; ?>').style.display='none'">Cancel</button>
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

    <script>
// Modal functions
function openEditModal(id, name, type, description) {
    document.getElementById('editSkillId').value = id;
    document.getElementById('edit_nama_skill').value = name;
    document.getElementById('edit_jenis_skill').value = type;
    document.getElementById('edit_deskripsi').value = description;
    document.getElementById('editSkillModal').style.display = 'flex';
}

function confirmDeleteSkill(id) {
    document.getElementById('deleteSkillId').value = id;
    document.getElementById('deleteSkillModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
    }
}
</script>
</body>

</html>