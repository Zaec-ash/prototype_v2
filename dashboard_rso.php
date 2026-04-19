<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
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

// Handle file upload submission
$upload_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_requirements'])) {
    $application_id = $_POST['application_id'] ?? '';
    $upload_message = "Requirements for application #$application_id have been submitted successfully!";
}

// Sample application data
$applications = [
    [
        'id' => 'AP-2024-001',
        'type' => 'Activity Permit',
        'title' => 'IT Society General Assembly',
        'date_submitted' => '2024-01-15',
        'status' => 'pending',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-20',
        'remarks' => 'Waiting for SOAU approval',
        'requirements_status' => 'not_submitted'
    ],
    [
        'id' => 'AP-2024-002',
        'type' => 'Activity Permit',
        'title' => 'Programming Competition',
        'date_submitted' => '2024-01-20',
        'status' => 'pending',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-28',
        'remarks' => 'Waiting for SOAU approval',
        'requirements_status' => 'not_submitted'
    ],
    [
        'id' => 'AP-2024-003',
        'type' => 'Activity Permit',
        'title' => 'Community Outreach Program',
        'date_submitted' => '2024-01-25',
        'status' => 'pending',
        'campus_type' => 'Off-Campus',
        'date_requested' => '2024-02-05',
        'remarks' => 'Additional requirements needed',
        'requirements_status' => 'not_submitted'
    ],
    [
        'id' => 'AP-2024-004',
        'type' => 'Activity Permit',
        'title' => 'Year-end Party',
        'date_submitted' => '2024-01-10',
        'status' => 'pending',
        'campus_type' => 'On-Campus',
        'date_requested' => '2024-01-15',
        'remarks' => 'Incomplete requirements',
        'requirements_status' => 'not_submitted'
    ]
];

// Universal Activity Data
$universal_activities = [
    [
        'permit_id' => '08-0114',
        'organization' => 'IT SOCIETY',
        'activity_title' => 'CodeQuest: Debugging Challenge 2026',
        'type' => 'Seminar / Training',
        'start_date' => '2026-03-20',
        'end_date' => '2026-03-21',
        'start_time' => '08:00 AM',
        'end_time' => '05:00 PM',
        'venue' => 'ICT Hall',
        'approval_date' => '2026-03-10',
        'report_due' => '2026-03-28',
        'actual_submission' => '2026-03-25',
        'rating' => '95%',
        'ap_points' => '+10',
        'ar_points' => '+5',
        'remarks' => 'High student engagement recorded.'
    ],
    [
        'permit_id' => '08-0117',
        'organization' => 'STUDENT COUNCIL',
        'activity_title' => 'Leadership Summit: Empowering Voices',
        'type' => 'Meeting / Fellowship',
        'start_date' => '2026-05-12',
        'end_date' => '2026-05-14',
        'start_time' => '09:00 AM',
        'end_time' => '04:00 PM',
        'venue' => 'Multi-Purpose Hall',
        'approval_date' => '2026-04-15',
        'report_due' => '2026-05-21',
        'actual_submission' => 'Pending',
        'rating' => '-',
        'ap_points' => '0',
        'ar_points' => '0',
        'remarks' => 'Venue confirmed.'
    ],
    [
        'permit_id' => '08-0120',
        'organization' => 'BSU Chorale',
        'activity_title' => 'Cultural Night: Voices in Harmony',
        'type' => 'Socialization',
        'start_date' => '2026-06-10',
        'end_date' => '2026-06-10',
        'start_time' => '06:00 PM',
        'end_time' => '09:00 PM',
        'venue' => 'University Auditorium',
        'approval_date' => '2026-05-20',
        'report_due' => '2026-06-17',
        'actual_submission' => 'Pending',
        'rating' => '-',
        'ap_points' => '0',
        'ar_points' => '0',
        'remarks' => 'Sound system approved.'
    ],
    [
        'permit_id' => '08-0123',
        'organization' => 'Junior Marketing Association',
        'activity_title' => 'Marketing Bootcamp 2026',
        'type' => 'Seminar / Training',
        'start_date' => '2026-07-15',
        'end_date' => '2026-07-17',
        'start_time' => '08:30 AM',
        'end_time' => '05:30 PM',
        'venue' => 'Business Building',
        'approval_date' => '2026-06-28',
        'report_due' => '2026-07-24',
        'actual_submission' => '2026-07-22',
        'rating' => '88%',
        'ap_points' => '+8',
        'ar_points' => '+4',
        'remarks' => 'Successful event with 150 participants.'
    ]
];

// Statistics
$total_applications = count($applications);
$pending_applications = count(array_filter($applications, fn($app) => $app['status'] === 'pending'));
$approved_applications = count(array_filter($applications, fn($app) => $app['status'] === 'approved'));
$rejected_applications = count(array_filter($applications, fn($app) => $app['status'] === 'rejected'));

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'it.society@bsu.edu.ph';
$user_name = 'IT SOCIETY';
$user_role = $_SESSION['user_role'] ?? 'RSO Officer';
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
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-dark); 
            margin: 0; 
            padding: 0; 
            line-height: 1.5; 
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, var(--bsu-dark) 0%, var(--bsu-green) 100%);
            border-radius: 20px;
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
            border: 1px solid var(--border-color);
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
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
        .stat-card.rejected .stat-value { color: var(--rejected); }

        /* Tab Navigation */
        .tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
            background: white;
            padding: 8px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            flex-wrap: wrap;
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
            border-radius: 20px;
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
            border-bottom: 1px dashed var(--border-color);
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
            border-radius: 12px;
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
            border-radius: 12px;
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

        .requirements-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .requirements-submitted {
            background: #d1fae5;
            color: var(--approved);
        }

        .requirements-not-submitted {
            background: #fed7aa;
            color: var(--pending);
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
            border-radius: 12px;
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

        .btn-requirements {
            background: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-requirements:hover {
            background: #2980b9;
            transform: translateY(-1px);
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 700px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            padding: 0;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 2px solid var(--bsu-mint);
            background: var(--bsu-dark);
            color: white;
            border-radius: 20px 20px 0 0;
        }
        
        .modal-header h3 {
            margin: 0;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .file-upload-list {
            list-style: none;
            padding: 0;
        }
        
        .file-upload-list li {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .file-upload-list li:last-child {
            border-bottom: none;
        }
        
        .file-label {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 200px;
        }
        
        .file-input {
            flex: 1;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.8rem;
        }
        
        .modal-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #f8f9fa;
        }

        /* Universal Activity Table Styles */
        .universal-table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 16px;
        }

        .universal-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .universal-table th {
            background: #f8f9fa;
            padding: 14px 12px;
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 2px solid var(--bsu-mint);
            text-align: left;
            color: var(--bsu-dark);
            position: sticky;
            top: 0;
            white-space: nowrap;
        }

        .universal-table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            background: var(--white);
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        .universal-table tr:hover td {
            background-color: #f9fbf9;
        }

        .col-org {
            font-weight: 700;
            color: var(--bsu-green);
        }

        .col-title {
            font-weight: 600;
        }

        .col-remarks {
            color: var(--text-muted);
        }

        .search-bar {
            width: 100%;
            max-width: 350px;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.85rem;
            background: white;
        }

        .view-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
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
                gap: 8px;
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
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .info-card {
                padding: 24px;
            }
            .view-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-bar {
                max-width: 100%;
            }
            .file-upload-list li {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php 
    if(file_exists("navbar_rso.php")) { 
        include "navbar_rso.php"; 
    }
?>

<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-header">
        <div class="welcome-text">
            <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
            <p>Manage your organization account and track permit applications</p>
            <span class="role-badge"><?php echo htmlspecialchars($user_role); ?></span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total" onclick="switchTab('applications')">
            <div class="stat-value"><?php echo $total_applications; ?></div>
            <div class="stat-label">📋 Total Applications</div>
        </div>
        <div class="stat-card pending" onclick="switchTab('applications')">
            <div class="stat-value"><?php echo $pending_applications; ?></div>
            <div class="stat-label">⏳ Pending Review</div>
        </div>
        <div class="stat-card approved" onclick="switchTab('applications')">
            <div class="stat-value"><?php echo $approved_applications; ?></div>
            <div class="stat-label">✅ Approved</div>
        </div>
        <div class="stat-card rejected" onclick="switchTab('applications')">
            <div class="stat-value"><?php echo $rejected_applications; ?></div>
            <div class="stat-label">❌ Rejected</div>
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
            <span>📋</span> My Applications
        </button>
        <button class="tab-btn" onclick="switchTab('activities')">
            <span>📊</span> Activity Viewing
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
                                <th>Requirements</th>
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
                                        <span class="requirements-badge requirements-<?php echo $app['requirements_status'] == 'submitted' ? 'submitted' : 'not-submitted'; ?>">
                                            <?php if ($app['requirements_status'] == 'submitted'): ?>
                                                ✅ Requirements Submitted
                                            <?php else: ?>
                                                ⚠️ Pending Requirements
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            <a href="view_application.php?id=<?php echo $app['id']; ?>" class="view-link">View Details →</a>
                                            <?php if ($app['status'] === 'pending' && $app['requirements_status'] !== 'submitted'): ?>
                                                <button class="btn-requirements" onclick="openRequirementsModal('<?php echo $app['id']; ?>', '<?php echo htmlspecialchars($app['title']); ?>')">📎 Submit Requirements</button>
                                            <?php endif; ?>
                                        </div>
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

    <!-- Tab 4: Universal Activity Viewing -->
    <div id="activities" class="tab-content">
        <div class="info-card">
            <h3 class="section-title">
                <span>📊</span> Organization Activity List
            </h3>
            
            <div class="view-header">
                <div style="font-size: 0.85rem; color: var(--text-muted);">
                    Showing all approved activities and permits
                </div>
                <input type="text" id="activitySearch" class="search-bar" placeholder="🔍 Search activities..." onkeyup="filterActivities()">
            </div>

            <div class="universal-table-wrapper">
                <table class="universal-table" id="activityTable">
                    <thead>
                        <tr>
                            <th>Permit ID</th>
                            <th class="col-org">Organization</th>
                            <th class="col-title">Activity Title</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Venue</th>
                            <th>Appr. Date</th>
                            <th>Report Due</th>
                            <th>Actual Sub.</th>
                            <th>Rating %</th>
                            <th>AP +/-</th>
                            <th>AR +/-</th>
                            <th class="col-remarks">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($universal_activities as $activity): ?>
                        <tr>
                            <td><?php echo $activity['permit_id']; ?></td>
                            <td class="col-org"><?php echo $activity['organization']; ?></td>
                            <td class="col-title"><?php echo $activity['activity_title']; ?></td>
                            <td><?php echo $activity['type']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($activity['start_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($activity['end_date'])); ?></td>
                            <td><?php echo $activity['start_time']; ?></td>
                            <td><?php echo $activity['end_time']; ?></td>
                            <td><?php echo $activity['venue']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($activity['approval_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($activity['report_due'])); ?></td>
                            <td><?php echo $activity['actual_submission']; ?></td>
                            <td><?php echo $activity['rating']; ?></td>
                            <td><?php echo $activity['ap_points']; ?></td>
                            <td><?php echo $activity['ar_points']; ?></td>
                            <td class="col-remarks"><?php echo $activity['remarks']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info" style="margin-top: 20px;">
                <span>ℹ️</span> This list shows all approved activities and permits across all recognized student organizations.
            </div>
        </div>
    </div>
</div>

<!-- Requirements Upload Modal -->
<div id="requirementsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>📎 Submit Activity Permit Requirements</h3>
            <button class="close-modal" onclick="closeRequirementsModal()">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="requirementsForm">
            <input type="hidden" name="application_id" id="modal_application_id">
            <div class="modal-body">
                <p style="margin-bottom: 15px; color: var(--text-muted);">
                    <strong>Application ID: <span id="modal_app_id_display"></span></strong><br>
                    Activity: <span id="modal_activity_title"></span>
                </p>
                
                <ul class="file-upload-list">
                    <li>
                        <span class="file-label">📄 1. Activity Proposal / Design</span>
                        <input type="file" name="proposal" class="file-input" accept=".pdf,.doc,.docx">
                    </li>
                    <li>
                        <span class="file-label">💰 2. Budget Proposal</span>
                        <input type="file" name="budget" class="file-input" accept=".pdf,.doc,.docx,.xlsx">
                    </li>
                    <li>
                        <span class="file-label">🛡️ 3. Safety and Security Plan</span>
                        <input type="file" name="safety_plan" class="file-input" accept=".pdf,.doc,.docx">
                    </li>
                    <li>
                        <span class="file-label">📝 4. Endorsement Letter from Adviser</span>
                        <input type="file" name="endorsement" class="file-input" accept=".pdf">
                    </li>
                    <li>
                        <span class="file-label">📋 5. Program of Activities / Itinerary</span>
                        <input type="file" name="program" class="file-input" accept=".pdf,.doc,.docx">
                    </li>
                    <li>
                        <span class="file-label">👥 6. List of Participants</span>
                        <input type="file" name="participants" class="file-input" accept=".pdf,.xlsx">
                    </li>
                    <li>
                        <span class="file-label">📜 7. Risk Assessment Form</span>
                        <input type="file" name="risk_assessment" class="file-input" accept=".pdf">
                    </li>
                    <li>
                        <span class="file-label">🏥 8. Medical Clearance (Off-Campus only)</span>
                        <input type="file" name="medical_clearance" class="file-input" accept=".pdf">
                    </li>
                    <li>
                        <span class="file-label">✍️ 9. Parent/Guardian Consent (Off-Campus only)</span>
                        <input type="file" name="parent_consent" class="file-input" accept=".pdf">
                    </li>
                </ul>
                
                <div class="alert alert-info" style="margin-top: 15px;">
                    <span>ℹ️</span> Please upload all required documents in PDF format where possible. Maximum file size: 10MB per file.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-requirements" style="background: #95a5a6;" onclick="closeRequirementsModal()">Cancel</button>
                <button type="submit" name="submit_requirements" class="btn-requirements" style="background: var(--bsu-green);">📤 Submit Requirements</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentAppId = null;
    let currentAppTitle = null;

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
    
    function filterActivities() {
        const input = document.getElementById("activitySearch");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("activityTable");
        const tr = table.getElementsByTagName("tr");
        
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName("td");
            let found = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const textValue = td[j].textContent || td[j].innerText;
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? "" : "none";
        }
    }
    
    function openRequirementsModal(appId, appTitle) {
        currentAppId = appId;
        currentAppTitle = appTitle;
        document.getElementById('modal_application_id').value = appId;
        document.getElementById('modal_app_id_display').innerText = appId;
        document.getElementById('modal_activity_title').innerText = appTitle;
        document.getElementById('requirementsModal').classList.add('active');
    }
    
    function closeRequirementsModal() {
        document.getElementById('requirementsModal').classList.remove('active');
        document.getElementById('requirementsForm').reset();
    }
    
    // Handle form submission via AJAX (demo)
    document.getElementById('requirementsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const appId = document.getElementById('modal_application_id').value;
        
        // Demo: Show success message
        alert(`Requirements for application ${appId} have been submitted successfully!\n\nIn production, the files would be uploaded to the server.`);
        
        closeRequirementsModal();
        
        // Refresh the page to update status (demo)
        location.reload();
    });
    
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