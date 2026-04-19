<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Set admin role if not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['user_role'] = 'admin';
    $_SESSION['user_email'] = 'admin@bsu.edu.ph';
    $_SESSION['user_name'] = 'Security Administrator';
}

// Handle password change
$password_change_success = false;
$password_change_error = false;
$password_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password)) {
        $password_change_error = true;
        $password_error_message = 'Please enter your current password.';
    } elseif (strlen($new_password) < 6) {
        $password_change_error = true;
        $password_error_message = 'New password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $password_change_error = true;
        $password_error_message = 'New password and confirmation do not match.';
    } else {
        $password_change_success = true;
        $_SESSION['temp_password'] = $new_password;
    }
}

// Handle staff account actions (approve/reject from pending)
$staff_action_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_action'])) {
    $staff_id = $_POST['staff_id'] ?? '';
    $action = $_POST['action_type'] ?? '';
    
    if ($action === 'approve') {
        $staff_action_message = "Staff account #$staff_id has been approved!";
    } elseif ($action === 'reject') {
        $staff_action_message = "Staff account #$staff_id has been rejected.";
    }
}

// Handle staff status change (activate/deactivate)
$staff_status_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_status_action'])) {
    $staff_id = $_POST['staff_id'] ?? '';
    $action = $_POST['action_type'] ?? '';
    
    if ($action === 'activate') {
        $staff_status_message = "Staff account #$staff_id has been activated!";
    } elseif ($action === 'deactivate') {
        $staff_status_message = "Staff account #$staff_id has been deactivated.";
    }
}

// Handle RSO organization actions
$rso_action_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rso_action'])) {
    $rso_id = $_POST['rso_id'] ?? '';
    $action = $_POST['action_type'] ?? '';
    
    if ($action === 'deactivate') {
        $rso_action_message = "Organization #$rso_id has been deactivated.";
    } elseif ($action === 'activate') {
        $rso_action_message = "Organization #$rso_id has been activated.";
    } elseif ($action === 'approve') {
        $rso_action_message = "Organization #$rso_id has been approved.";
    } elseif ($action === 'reject') {
        $rso_action_message = "Organization #$rso_id has been rejected.";
    }
}

// Sample pending staff accounts data (for approval)
$pending_staff = [
    ['id' => 'STF-001', 'name' => 'Maria Santos', 'email' => 'maria.santos@bsu.edu.ph', 'position' => 'SOAU Staff', 'date_applied' => '2024-01-20'],
    ['id' => 'STF-002', 'name' => 'John Dela Cruz', 'email' => 'john.delacruz@bsu.edu.ph', 'position' => 'SOAU Staff', 'date_applied' => '2024-01-18'],
    ['id' => 'STF-003', 'name' => 'Anna Reyes', 'email' => 'anna.reyes@bsu.edu.ph', 'position' => 'SOAU Staff', 'date_applied' => '2024-01-15'],
];

// Sample approved/active staff accounts
$active_staff = [
    ['id' => 'STF-004', 'name' => 'Michael Cruz', 'email' => 'michael.cruz@bsu.edu.ph', 'position' => 'SOAU Coordinator', 'status' => 'active', 'date_approved' => '2023-06-10'],
    ['id' => 'STF-005', 'name' => 'Sarah Lopez', 'email' => 'sarah.lopez@bsu.edu.ph', 'position' => 'Student Affairs Officer', 'status' => 'active', 'date_approved' => '2023-08-15'],
    ['id' => 'STF-006', 'name' => 'David Reyes', 'email' => 'david.reyes@bsu.edu.ph', 'position' => 'SOAU Staff', 'status' => 'inactive', 'date_approved' => '2023-10-20'],
    ['id' => 'STF-007', 'name' => 'Christine Santos', 'email' => 'christine.santos@bsu.edu.ph', 'position' => 'SOAU Staff', 'status' => 'active', 'date_approved' => '2024-01-05'],
];

$active_staff_count = count(array_filter($active_staff, fn($staff) => $staff['status'] === 'active'));
$inactive_staff_count = count(array_filter($active_staff, fn($staff) => $staff['status'] === 'inactive'));

// Sample RSO organizations data
$rso_organizations = [
    ['id' => 'RSO-001', 'name' => 'IT SOCIETY', 'email' => 'it.society@bsu.edu.ph', 'adviser' => 'Prof. Jose Rizal', 'members' => '42', 'status' => 'active', 'date_registered' => '2023-06-15'],
    ['id' => 'RSO-002', 'name' => 'STUDENT COUNCIL', 'email' => 'student.council@bsu.edu.ph', 'adviser' => 'Prof. Maria Santos', 'members' => '35', 'status' => 'active', 'date_registered' => '2023-08-20'],
    ['id' => 'RSO-003', 'name' => 'BSU Chorale', 'email' => 'chorale@bsu.edu.ph', 'adviser' => 'Prof. Ricardo Cruz', 'members' => '50', 'status' => 'inactive', 'date_registered' => '2023-03-10'],
    ['id' => 'RSO-004', 'name' => 'Junior Marketing Association', 'email' => 'jma@bsu.edu.ph', 'adviser' => 'Prof. Ana Lopez', 'members' => '28', 'status' => 'active', 'date_registered' => '2023-10-05']
];

$active_count = count(array_filter($rso_organizations, fn($org) => $org['status'] === 'active'));
$inactive_count = count(array_filter($rso_organizations, fn($org) => $org['status'] === 'inactive'));
$pending_count = count(array_filter($rso_organizations, fn($org) => $org['status'] === 'pending'));

$user_email = $_SESSION['user_email'] ?? 'admin@bsu.edu.ph';
$user_name = $_SESSION['user_name'] ?? 'Security Administrator';
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Admin Dashboard | BSU ORG-Track</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-dark: #1b4332;
            --bsu-mint: #d8f3dc;
            --text-dark: #1a1c1e;
            --text-muted: #5f6368;
            --border-color: #dadce0;
            --bg-page: #f0f2f5;
            --approved: #10b981;
            --pending: #f59e0b;
            --rejected: #ef4444;
            --admin: #e74c3c;
            --staff-color: #3498db;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-dark); 
        }

        /* Navbar */
        .main-navbar {
            background: #2d6a4f;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-brand a {
            color: white;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links li a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-links li a:hover { color: white; }

        .signin-btn {
            background: white;
            color: #2d6a4f !important;
            padding: 8px 22px !important;
            border-radius: 8px;
            font-weight: 700 !important;
        }

        .nav-spacer { height: 70px; }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, var(--admin) 0%, #c0392b 100%);
            border-radius: 20px;
            padding: 32px 40px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: var(--shadow-lg);
        }

        .welcome-text h2 {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #fcd5ce;
            font-size: 0.9rem;
        }

        .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            display: inline-block;
            margin-top: 8px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 10px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            backdrop-filter: blur(10px);
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        /* Dashboard Tabs */
        .dashboard-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
            background: white;
            padding: 8px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .dashboard-tab {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .dashboard-tab:hover { background: var(--bsu-mint); color: var(--bsu-green); }
        .dashboard-tab.active { background: var(--admin); color: white; box-shadow: var(--shadow-sm); }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .tab-content.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards */
        .info-card, .table-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .info-card { padding: 32px; }
        .table-card { padding: 0; }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--bsu-dark);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bsu-mint);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 500;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-color);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--admin);
            margin-bottom: 4px;
        }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 16px;
            background: #f9fafb;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }

        .data-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.85rem;
        }

        .data-table tr:hover { background: #f9fafb; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .status-active { background: #d1fae5; color: var(--approved); }
        .status-inactive { background: #fee2e2; color: var(--rejected); }
        .status-pending { background: #fed7aa; color: var(--pending); }

        .btn-sm {
            padding: 6px 14px;
            border: none;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            margin: 0 4px;
            transition: all 0.2s;
        }
        .btn-sm:hover { transform: translateY(-1px); }
        .btn-approve { background: var(--approved); color: white; }
        .btn-reject { background: var(--rejected); color: white; }
        .btn-activate { background: var(--approved); color: white; }
        .btn-deactivate { background: var(--rejected); color: white; }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid var(--approved); }

        /* Password Form */
        .password-form { max-width: 500px; }
        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px; color: var(--text-dark); }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--admin);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }
        .btn-save {
            background: var(--admin);
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-save:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.75rem;
        }
        .strength-weak { color: var(--rejected); }
        .strength-medium { color: var(--pending); }
        .strength-strong { color: var(--approved); }

        /* Sub-section styling */
        .sub-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .main-navbar {
                height: auto;
                min-height: 60px;
                flex-direction: column;
                padding: 12px 4%;
            }
            .nav-brand { margin-bottom: 8px; }
            .nav-links { flex-wrap: wrap; justify-content: center; gap: 15px; }
            .nav-spacer { height: auto; min-height: 100px; }
            .info-grid { grid-template-columns: 1fr; gap: 16px; }
            .dashboard-tabs { flex-direction: column; gap: 8px; }
            .dashboard-tab { justify-content: center; }
            .welcome-header { padding: 24px; text-align: center; justify-content: center; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .info-card { padding: 24px; }
            .data-table { display: block; overflow-x: auto; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php 
    if(file_exists("navbar_admin.php")) { 
        include "navbar_admin.php"; 
    }
?>

<div class="dashboard-container">
    <div class="welcome-header">
        <div class="welcome-text">
            <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>! 👑</h2>
            <p>Security Administrator Dashboard - Manage staff accounts and organizations</p>
            <span class="role-badge">🔐Security Administrator</span>
        </div>
        <a href="logout.php" class="logout-btn">🚪 Sign Out</a>
    </div>

    <!-- Admin Tabs -->
    <div class="dashboard-tabs">
        <button class="dashboard-tab active" onclick="switchTab('profile')">
            <span>👤</span>Security Admin Profile & Security
        </button>
        <button class="dashboard-tab" onclick="switchTab('staff')">
            <span>👥</span> Pending Staff Accounts
            <?php if(count($pending_staff) > 0): ?>
                <span style="background: var(--admin); color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem;"><?php echo count($pending_staff); ?></span>
            <?php endif; ?>
        </button>
        <button class="dashboard-tab" onclick="switchTab('rso')">
            <span>🏢</span> RSO Organizations & Staff
        </button>
    </div>

    <!-- TAB 1: Admin Profile & Security -->
    <div id="profile" class="tab-content active">
        <div class="info-card">
            <h3 class="section-title"><span>👤</span>Security Administrator Profile</h3>
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Full Name</span><span class="info-value"><?php echo htmlspecialchars($user_name); ?></span></div>
                <div class="info-item"><span class="info-label">Email Address</span><span class="info-value"><?php echo htmlspecialchars($user_email); ?></span></div>
                <div class="info-item"><span class="info-label">Role</span><span class="info-value">🔐 Security Administrator</span></div>
                <div class="info-item"><span class="info-label">Access Level</span><span class="info-value">⚙️ Full Account Control</span></div>
                <div class="info-item"><span class="info-label">Last Login</span><span class="info-value">📅 <?php echo date('F d, Y h:i A'); ?></span></div>
                <div class="info-item"><span class="info-label">Account Status</span><span class="info-value"><span class="status-badge status-active">● Active</span></span></div>
            </div>
        </div>

        <div class="info-card">
            <h3 class="section-title"><span>🔐</span> Change Password</h3>
            <?php if ($password_change_success): ?>
                <div class="alert alert-success">✅ Password changed successfully!</div>
            <?php endif; ?>
            <?php if ($password_change_error): ?>
                <div class="alert alert-success" style="background: #fee2e2; color: #991b1b; border-left-color: var(--rejected);">❌ <?php echo $password_error_message; ?></div>
            <?php endif; ?>
            <form method="POST" class="password-form" id="passwordForm">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" placeholder="Enter your current password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Enter new password (min. 6 characters)" required onkeyup="checkPasswordStrength()">
                    <div class="password-strength" id="strengthMessage"></div>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your new password" required onkeyup="checkPasswordMatch()">
                    <div class="password-strength" id="matchMessage"></div>
                </div>
                <button type="submit" name="change_password" class="btn-save">Update Password →</button>
            </form>
        </div>
    </div>

    <!-- TAB 2: Pending Staff Accounts -->
    <div id="staff" class="tab-content">
        <?php if ($staff_action_message): ?>
            <div class="alert alert-success"><?php echo $staff_action_message; ?></div>
        <?php endif; ?>
        
        <div class="table-card">
            <div style="padding: 24px 24px 0 24px;">
                <h3 class="section-title"><span>👥</span> Pending Staff Account Approvals</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Position</th><th>Date Applied</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_staff as $staff): ?>
                    <tr>
                        <td><strong><?php echo $staff['id']; ?></strong></td>
                        <td><?php echo $staff['name']; ?></td>
                        <td><?php echo $staff['email']; ?></td>
                        <td><?php echo $staff['position']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($staff['date_applied'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                <input type="hidden" name="action_type" value="approve">
                                <button type="submit" name="staff_action" class="btn-sm btn-approve">✓ Approve</button>
                            </form>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                <input type="hidden" name="action_type" value="reject">
                                <button type="submit" name="staff_action" class="btn-sm btn-reject">✗ Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: RSO Organizations & SOAU Staff -->
    <div id="rso" class="tab-content">
        <?php if ($rso_action_message): ?>
            <div class="alert alert-success"><?php echo $rso_action_message; ?></div>
        <?php endif; ?>
        
        <?php if ($staff_status_message): ?>
            <div class="alert alert-success"><?php echo $staff_status_message; ?></div>
        <?php endif; ?>
        
        <!-- RSO Organizations Section -->
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-value"><?php echo $active_count; ?></div><div class="stat-label">✅ Active Organizations</div></div>
            <div class="stat-card"><div class="stat-value"><?php echo $inactive_count; ?></div><div class="stat-label">⭕ Inactive Organizations</div></div>
            <div class="stat-card"><div class="stat-value"><?php echo $pending_count; ?></div><div class="stat-label">⏳ Pending Registration</div></div>
            <div class="stat-card"><div class="stat-value"><?php echo count($rso_organizations); ?></div><div class="stat-label">🏢 Total Organizations</div></div>
        </div>

        <div class="table-card">
            <div style="padding: 24px 24px 0 24px;">
                <h3 class="section-title"><span>🏢</span> Recognized Student Organizations</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Organization Name</th><th>Email</th><th>Adviser</th><th>Members</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rso_organizations as $org): ?>
                    <tr>
                        <td><strong><?php echo $org['id']; ?></strong></td>
                        <td><?php echo $org['name']; ?></td>
                        <td><?php echo $org['email']; ?></td>
                        <td><?php echo $org['adviser']; ?></td>
                        <td><?php echo $org['members']; ?></td>
                        <td><span class="status-badge status-<?php echo $org['status']; ?>"><?php echo ucfirst($org['status']); ?></span></td>
                        <td>
                            <?php if ($org['status'] === 'active'): ?>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="rso_id" value="<?php echo $org['id']; ?>">
                                    <input type="hidden" name="action_type" value="deactivate">
                                    <button type="submit" name="rso_action" class="btn-sm btn-deactivate">🔴 Deactivate</button>
                                </form>
                            <?php elseif ($org['status'] === 'inactive'): ?>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="rso_id" value="<?php echo $org['id']; ?>">
                                    <input type="hidden" name="action_type" value="activate">
                                    <button type="submit" name="rso_action" class="btn-sm btn-activate">🟢 Activate</button>
                                </form>
                        
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- SOAU Staff Accounts Section -->
        <div class="sub-section">
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-value"><?php echo $active_staff_count; ?></div><div class="stat-label">✅ Active Staff</div></div>
                <div class="stat-card"><div class="stat-value"><?php echo $inactive_staff_count; ?></div><div class="stat-label">⭕ Inactive Staff</div></div>
                <div class="stat-card"><div class="stat-value"><?php echo count($active_staff); ?></div><div class="stat-label">👥 Total Staff Accounts</div></div>
            </div>

            <div class="table-card">
                <div style="padding: 24px 24px 0 24px;">
                    <h3 class="section-title"><span>👥</span> SOAU Staff Accounts</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 16px;">Manage staff account status (Activate/Deactivate)</p>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Position</th><th>Date Approved</th><th>Status</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($active_staff as $staff): ?>
                        <tr>
                            <td><strong><?php echo $staff['id']; ?></strong></td>
                            <td><?php echo $staff['name']; ?></td>
                            <td><?php echo $staff['email']; ?></td>
                            <td><?php echo $staff['position']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($staff['date_approved'])); ?></td>
                            <td><span class="status-badge status-<?php echo $staff['status']; ?>"><?php echo ucfirst($staff['status']); ?></span></td>
                            <td>
                                <?php if ($staff['status'] === 'active'): ?>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                        <input type="hidden" name="action_type" value="deactivate">
                                        <button type="submit" name="staff_status_action" class="btn-sm btn-deactivate">🔴 Deactivate</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                                        <input type="hidden" name="action_type" value="activate">
                                        <button type="submit" name="staff_status_action" class="btn-sm btn-activate">🟢 Activate</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.dashboard-tab').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        event.target.closest('.dashboard-tab').classList.add('active');
    }
    
    function checkPasswordStrength() {
        const password = document.getElementById('new_password').value;
        const strengthMsg = document.getElementById('strengthMessage');
        
        if (password.length === 0) {
            strengthMsg.innerHTML = '';
            return;
        }
        
        let strength = '';
        let strengthClass = '';
        
        if (password.length < 6) {
            strength = '🔴 Weak - Too short';
            strengthClass = 'strength-weak';
        } else if (password.length < 10) {
            strength = '🟡 Medium - Could be stronger';
            strengthClass = 'strength-medium';
        } else {
            strength = '🟢 Strong - Good password!';
            strengthClass = 'strength-strong';
        }
        
        strengthMsg.innerHTML = strength;
        strengthMsg.className = 'password-strength ' + strengthClass;
    }
    
    function checkPasswordMatch() {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        const matchMsg = document.getElementById('matchMessage');
        
        if (confirm.length === 0) {
            matchMsg.innerHTML = '';
            return;
        }
        
        if (password === confirm) {
            matchMsg.innerHTML = '✓ Passwords match';
            matchMsg.className = 'password-strength strength-strong';
        } else {
            matchMsg.innerHTML = '✗ Passwords do not match';
            matchMsg.className = 'password-strength strength-weak';
        }
    }
</script>

</body>
</html>
