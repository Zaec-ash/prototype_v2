<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Handle password change (prototype)
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

// Sample application data (for prototype)
$applications = [
    [
        'id' => 'AP-2024-001',
        'type' => 'Activity Permit',
        'title' => 'IT Society General Assembly',
        'date_submitted' => '2024-01-15',
        'status' => 'approved',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-20',
        'remarks' => 'Approved by SOAU Coordinator'
    ],
    [
        'id' => 'AP-2024-002',
        'type' => 'Activity Permit',
        'title' => 'Programming Competition',
        'date_submitted' => '2024-01-20',
        'status' => 'pending',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-28',
        'remarks' => 'Waiting for SOAU approval'
    ],
    [
        'id' => 'AP-2024-003',
        'type' => 'Activity Permit',
        'title' => 'Community Outreach Program',
        'date_submitted' => '2024-01-25',
        'status' => 'pending',
        'campus_type' => 'Off-Campus',
        'date_requested' => '2024-02-05',
        'remarks' => 'Additional requirements needed'
    ],
    [
        'id' => 'AP-2024-004',
        'type' => 'Activity Permit',
        'title' => 'Year-end Party',
        'date_submitted' => '2024-01-10',
        'status' => 'rejected',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-15',
        'remarks' => 'Incomplete requirements'
    ]
];

// Statistics
$total_applications = count($applications);
$pending_applications = count(array_filter($applications, fn($app) => $app['status'] === 'pending'));
$approved_applications = count(array_filter($applications, fn($app) => $app['status'] === 'approved'));

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'it.society@bsu.edu.ph';
$user_name = $_SESSION['user_name'] ?? 'IT SOCIETY';
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard | BSU ORG-Track</title>
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
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Inter', -apple-system, 'Segoe UI', sans-serif; 
            background: var(--bg-page);
            color: var(--text-dark); 
            margin: 0; 
            padding: 0; 
            line-height: 1.5; 
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, var(--bsu-dark) 0%, var(--bsu-green) 100%);
            border-radius: 16px;
            padding: 32px 40px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: var(--shadow-lg);
        }

        .welcome-text h2 {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: var(--bsu-mint);
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
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
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-icon {
            font-size: 2rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stat-card.total .stat-value { color: var(--bsu-green); }
        .stat-card.pending .stat-value { color: var(--pending); }
        .stat-card.approved .stat-value { color: var(--approved); }

        /* Tab Navigation */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 32px;
            background: white;
            padding: 8px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 24px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn:hover {
            background: var(--bsu-mint);
            color: var(--bsu-green);
        }

        .tab-btn.active {
            background: var(--bsu-green);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card Styles */
        .info-card {
            padding: 32px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--bsu-dark);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bsu-mint);
            display: flex;
            align-items: center;
            gap: 8px;
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
            color: var(--text-dark);
            padding: 6px 0;
        }

        /* Password Form */
        .password-form {
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--bsu-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.75rem;
        }

        .strength-weak { color: var(--rejected); }
        .strength-medium { color: var(--pending); }
        .strength-strong { color: var(--approved); }

        .btn-save {
            background: var(--bsu-green);
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: var(--bsu-dark);
            transform: translateY(-2px);
        }

        /* Alert Messages */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--approved);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--rejected);
        }

        .alert-info {
            background: var(--bsu-mint);
            color: var(--bsu-dark);
            border-left: 4px solid var(--bsu-green);
        }

        /* Applications Table */
        .applications-table {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 16px;
            background: #f9fafb;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.85rem;
        }

        tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-approved {
            background: #d1fae5;
            color: var(--approved);
        }

        .status-pending {
            background: #fed7aa;
            color: var(--pending);
        }

        .status-rejected {
            background: #fee2e2;
            color: var(--rejected);
        }

        .view-link {
            color: var(--bsu-green);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            background: var(--bsu-green);
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--bsu-dark);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: var(--text-muted);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px 16px;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .tabs {
                flex-direction: column;
            }
            .tab-btn {
                justify-content: center;
            }
            .welcome-header {
                padding: 24px;
                text-align: center;
                justify-content: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .info-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

<?php 
    if(file_exists("navbar.php")) { 
        include "navbar.php"; 
    }
?>

<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-header">
        <div class="welcome-text">
            <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
            <p>Manage your organization account and track permit applications</p>
        </div>
        <a href="logout.php" class="logout-btn">🚪 Sign Out</a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total" onclick="switchTabTo('applications')">
            <div class="stat-header">
                <span class="stat-icon">📋</span>
            </div>
            <div class="stat-value"><?php echo $total_applications; ?></div>
            <div class="stat-label">Total Applications</div>
        </div>
        <div class="stat-card pending" onclick="switchTabTo('applications')">
            <div class="stat-header">
                <span class="stat-icon">⏳</span>
            </div>
            <div class="stat-value"><?php echo $pending_applications; ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card approved" onclick="switchTabTo('applications')">
            <div class="stat-header">
                <span class="stat-icon">✅</span>
            </div>
            <div class="stat-value"><?php echo $approved_applications; ?></div>
            <div class="stat-label">Approved</div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('profile')">
            <span>🏢</span> Organization Profile
        </button>
        <button class="tab-btn" onclick="switchTab('password')">
            <span>🔐</span> Security
        </button>
        <button class="tab-btn" onclick="switchTab('applications')">
            <span>📋</span> Applications
        </button>
    </div>

    <!-- Tab 1: Organization Information -->
    <div id="profile" class="tab-content active">
        <div class="info-card">
            <h3 class="section-title">
                <span>🏢</span> Organization Profile
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Organization Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_name); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_email); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Organization Type</span>
                    <span class="info-value">🎓 Recognized Student Organization (RSO)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value"><span class="status-badge status-approved">● Active</span></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date Registered</span>
                    <span class="info-value">January 15, <?php echo $current_year - 1; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Count</span>
                    <span class="info-value">42 Active Members</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Faculty Adviser</span>
                    <span class="info-value">Prof. Maria Theresa Santos, Ph.D.</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact Number</span>
                    <span class="info-value">+63 912 3456 789</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Change Password -->
    <div id="password" class="tab-content">
        <div class="info-card">
            <h3 class="section-title">
                <span>🔐</span> Change Password
            </h3>
            
            <?php if ($password_change_success): ?>
                <div class="alert alert-success">
                    <span>✅</span> Password changed successfully! Your new password has been saved.
                </div>
            <?php endif; ?>
            
            <?php if ($password_change_error): ?>
                <div class="alert alert-error">
                    <span>❌</span> <?php echo $password_error_message; ?>
                </div>
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
            
            <div class="alert alert-info" style="margin-top: 24px;">
                <span>💡</span> <strong>Prototype Note:</strong> Password change is simulated for demonstration purposes.
            </div>
        </div>
    </div>

    <!-- Tab 3: My Applications -->
    <div id="applications" class="tab-content">
        <div class="info-card">
            <h3 class="section-title">
                <span>📋</span> Activity Permit Applications
            </h3>
            
            <?php if (count($applications) > 0): ?>
                <div class="applications-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Activity Title</th>
                                <th>Date</th>
                                <th>Campus</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><strong><?php echo $app['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['title']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($app['date_requested'])); ?></td>
                                    <td><?php echo $app['campus_type']; ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $app['status']; ?>">
                                            <?php echo $app['status'] === 'approved' ? '✓' : ($app['status'] === 'pending' ? '⏎' : '✗'); ?>
                                            <?php echo ucfirst($app['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_application.php?id=<?php echo $app['id']; ?>" class="view-link">View Details →</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p>No applications found</p>
                    <p style="font-size: 0.8rem; margin-top: 8px;">Start by submitting a new activity permit application</p>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 28px; text-align: center;">
                <a href="registration.php" class="btn-primary">
                    <span>+</span> New Application
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
    }
    
    function switchTabTo(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        
        // Find and activate the corresponding button
        const buttons = document.querySelectorAll('.tab-btn');
        const tabNames = ['profile', 'password', 'applications'];
        const index = tabNames.indexOf(tabName);
        if (buttons[index]) {
            buttons[index].classList.add('active');
        }
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
            strength = 'Weak - Too short';
            strengthClass = 'strength-weak';
        } else if (password.length < 10) {
            strength = 'Medium - Could be stronger';
            strengthClass = 'strength-medium';
        } else {
            strength = 'Strong - Good password!';
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
<nav class="main-navbar">
    <div class="nav-brand">
        <a href="index.php">BSU ORG-TRACK</a>
    </div>
    <ul class="nav-links">
        <li><button class="active" onclick="switchTab('profile')">🏢 Organization Profile</button></li>
        <li><button onclick="switchTab('password')">🔐 Security</button></li>
        <li><button onclick="switchTab('applications')">📋 Applications</button></li>
        <li><a href="logout.php" class="signin-btn">Logout</a></li>
    </ul>
</nav>

<!-- Updated Navbar - Consistent Design -->
<?php
// Get current page name for active class
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="main-navbar">
    <div class="nav-brand">
        <a href="dashboard.php">🏛️ BSU ORG-TRACK</a>
    </div>
    <ul class="nav-links">
        <li><button class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" onclick="switchTab('profile')">🏢 Organization Profile</button></li>
        <li><button class="<?php echo $current_page == 'dashboard.php' ? '' : ''; ?>" onclick="switchTab('password')">🔐 Security</button></li>
        <li><button class="<?php echo $current_page == 'dashboard.php' ? '' : ''; ?>" onclick="switchTab('applications')">📋 Applications</button></li>
        <li><a href="logout.php" class="signin-btn">🚪 Logout</a></li>
    </ul>
</nav>