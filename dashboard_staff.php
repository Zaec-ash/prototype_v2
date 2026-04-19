<?php
session_start();

// Check if user is logged in and is staff
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Set staff role if not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'soau_staff') {
    $_SESSION['user_role'] = 'soau_staff';
    $_SESSION['user_email'] = 'staff@bsu.edu.ph';
    $_SESSION['user_name'] = 'SOAU Staff';
}

// Handle organization approval/rejection
$org_action_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['org_action'])) {
    $org_id = $_POST['org_id'] ?? '';
    $action = $_POST['action_type'] ?? '';
    $status = $_POST['status_type'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    
    if ($action === 'approve') {
        $org_action_message = "Organization #$org_id has been approved as $status!";
    } elseif ($action === 'reject') {
        $org_action_message = "Organization #$org_id has been rejected. Reason: $remarks";
    } elseif ($action === 'update_status') {
        $org_action_message = "Organization #$org_id status updated to $status!";
    }
}

// Handle permit approval/rejection
$permit_action_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permit_action'])) {
    $permit_id = $_POST['permit_id'] ?? '';
    $action = $_POST['action_type'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    
    if ($action === 'approve') {
        $permit_action_message = "Permit #$permit_id has been approved!";
    } elseif ($action === 'reject') {
        $permit_action_message = "Permit #$permit_id has been rejected. Reason: $remarks";
    }
}

// Handle report generation
$selected_type = $_POST['report_type'] ?? '';
$report_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $selected_type = $_POST['report_type'] ?? '';
    $report_result = [
        'type' => $selected_type,
        'count' => rand(5, 25),
        'percentage' => rand(10, 90)
    ];
}

// Sample pending organizations (for approval)
$pending_orgs = [
    [
        'id' => 'RSO-001',
        'name' => 'Robotics Club',
        'email' => 'robotics@bsu.edu.ph',
        'adviser' => 'Engr. Mike Santos',
        'date_applied' => '2024-01-10',
        'type' => 'new',
        'status' => 'pending',
        'files' => [
            'letter' => 'letter_robotics.pdf',
            'application_form' => 'form_robotics.pdf',
            'description' => 'description_robotics.pdf',
            'id_cards' => 'ids_robotics.pdf',
            'action_plan' => 'plan_robotics.pdf',
            'members_list' => 'members_robotics.pdf',
            'constitution' => 'constitution_robotics.pdf',
            'sec_reg' => 'sec_robotics.pdf'
        ]
    ],
    [
        'id' => 'RSO-002',
        'name' => 'Debate Society',
        'email' => 'debate@bsu.edu.ph',
        'adviser' => 'Atty. Anna Reyes',
        'date_applied' => '2024-01-12',
        'type' => 'existing',
        'status' => 'pending',
        'files' => [
            'letter' => 'letter_debate.pdf',
            'application_form' => 'form_debate.pdf',
            'id_cards' => 'ids_debate.pdf',
            'action_plan' => 'plan_debate.pdf',
            'members_list' => 'members_debate.pdf',
            'constitution' => 'constitution_debate.pdf'
        ]
    ]
];

// Sample approved organizations with different statuses
$approved_orgs = [
    [
        'id' => 'RSO-003',
        'name' => 'IT SOCIETY',
        'email' => 'it.society@bsu.edu.ph',
        'adviser' => 'Prof. Jose Rizal',
        'date_approved' => '2023-06-15',
        'registration_status' => 'full',
        'members' => '42',
        'status_badge' => 'Full RSO Status',
        'badge_color' => 'approved'
    ],
    [
        'id' => 'RSO-004',
        'name' => 'STUDENT COUNCIL',
        'email' => 'student.council@bsu.edu.ph',
        'adviser' => 'Prof. Maria Santos',
        'date_approved' => '2023-08-20',
        'registration_status' => 'full',
        'members' => '35',
        'status_badge' => 'Full RSO Status',
        'badge_color' => 'approved'
    ],
    [
        'id' => 'RSO-005',
        'name' => 'Junior Marketing Association',
        'email' => 'jma@bsu.edu.ph',
        'adviser' => 'Prof. Ana Lopez',
        'date_approved' => '2024-01-15',
        'registration_status' => 'partial',
        'members' => '28',
        'status_badge' => 'Partial RSO Status',
        'badge_color' => 'pending'
    ],
    [
        'id' => 'RSO-006',
        'name' => 'BSU Chorale',
        'email' => 'chorale@bsu.edu.ph',
        'adviser' => 'Prof. Ricardo Cruz',
        'date_approved' => '2024-02-01',
        'registration_status' => 'new',
        'members' => '20',
        'status_badge' => 'New RSO Status',
        'badge_color' => 'info'
    ],
    [
        'id' => 'RSO-007',
        'name' => 'Dance Troupe',
        'email' => 'dance@bsu.edu.ph',
        'adviser' => 'Prof. Maria Clara',
        'date_approved' => '2024-02-10',
        'registration_status' => 'partial',
        'members' => '25',
        'status_badge' => 'Partial RSO Status',
        'badge_color' => 'pending'
    ]
];

// Sample pending activity permits
$pending_permits = [
    [
        'id' => 'AP-2024-001',
        'organization' => 'IT SOCIETY',
        'activity_title' => 'Hackathon 2024',
        'type' => 'Contest/Competition',
        'start_date' => '2024-02-15',
        'end_date' => '2024-02-16',
        'start_time' => '08:00 AM',
        'end_time' => '06:00 PM',
        'venue' => 'ICT Building',
        'date_submitted' => '2024-01-20',
        'files' => [
            'proposal' => 'proposal_hackathon.pdf',
            'budget' => 'budget_hackathon.pdf',
            'safety_plan' => 'safety_hackathon.pdf'
        ]
    ],
    [
        'id' => 'AP-2024-002',
        'organization' => 'STUDENT COUNCIL',
        'activity_title' => 'Leadership Summit',
        'type' => 'Seminar/Training/Forum',
        'start_date' => '2024-02-20',
        'end_date' => '2024-02-22',
        'start_time' => '09:00 AM',
        'end_time' => '05:00 PM',
        'venue' => 'University Auditorium',
        'date_submitted' => '2024-01-18',
        'files' => [
            'proposal' => 'proposal_summit.pdf',
            'budget' => 'budget_summit.pdf',
            'safety_plan' => 'safety_summit.pdf'
        ]
    ]
];

// Sample approved permits
$approved_permits = [
    [
        'permit_id' => '08-0114',
        'organization' => 'IT SOCIETY',
        'activity_title' => 'CodeQuest: Debugging Challenge 2026',
        'type' => 'Seminar/Training/Forum',
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
        'remarks' => 'High student engagement recorded.',
        'accomplishment_report' => 'accomplishment_it.pdf',
        'evaluation_report' => 'evaluation_it.pdf'
    ],
    [
        'permit_id' => '08-0117',
        'organization' => 'STUDENT COUNCIL',
        'activity_title' => 'Leadership Summit',
        'type' => 'Meeting/Fellowship',
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
        'remarks' => 'Venue confirmed.',
        'accomplishment_report' => null,
        'evaluation_report' => null
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
        'remarks' => 'Sound system approved.',
        'accomplishment_report' => null,
        'evaluation_report' => null
    ],
    [
        'permit_id' => '08-0123',
        'organization' => 'Junior Marketing Association',
        'activity_title' => 'Marketing Bootcamp 2026',
        'type' => 'Seminar/Training/Forum',
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
        'remarks' => 'Successful event with 150 participants.',
        'accomplishment_report' => 'accomplishment_jma.pdf',
        'evaluation_report' => 'evaluation_jma.pdf'
    ]
];

// Sample reports for evaluation
$pending_evaluations = [
    [
        'permit_id' => '08-0114',
        'organization' => 'IT SOCIETY',
        'activity_title' => 'CodeQuest: Debugging Challenge 2026',
        'type' => 'Seminar/Training/Forum',
        'accomplishment_report' => 'accomplishment_it.pdf',
        'evaluation_report' => 'evaluation_it.pdf',
        'rating' => '95%',
        'status' => 'pending_review'
    ],
    [
        'permit_id' => '08-0115',
        'organization' => 'DEVCOM SOCIETY',
        'activity_title' => 'Social: Debate Challenge 2026',
        'type' => 'Seminar/Training/Forum',
        'accomplishment_report' => 'accomplishment_it.pdf',
        'evaluation_report' => 'evaluation_it.pdf',
        'rating' => '95%',
        'status' => 'pending_review'
    ]
];

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'staff@bsu.edu.ph';
$user_name = $_SESSION['user_name'] ?? 'SOAU Staff';
$current_year = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAU Staff Dashboard | BSU ORG-Track</title>
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
            --info: #3498db;
            --staff: #3498db;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', sans-serif; 
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
            background: linear-gradient(135deg, var(--staff) 0%, #2980b9 100%);
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
            color: #d6eaf8;
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
        }

        /* Tabs */
        .dashboard-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
            background: white;
            padding: 8px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .dashboard-tab {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .dashboard-tab:hover { background: var(--bsu-mint); color: var(--bsu-green); }
        .dashboard-tab.active { background: var(--staff); color: white; }

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
        }

        .info-card { padding: 32px; }

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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--staff);
        }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 14px;
            background: #f9fafb;
            font-weight: 600;
            font-size: 0.75rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }

        .data-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.8rem;
        }

        .data-table tr:hover { background: #f9fafb; }

        .status-badge {
            display: inline-flex;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .status-pending { background: #fed7aa; color: var(--pending); }
        .status-approved { background: #d1fae5; color: var(--approved); }
        .status-rejected { background: #fee2e2; color: var(--rejected); }
        .status-info { background: #d1ecf1; color: var(--info); }
        .status-full { background: #d1fae5; color: var(--approved); }
        .status-partial { background: #fed7aa; color: var(--pending); }
        .status-new { background: #d1ecf1; color: var(--info); }

        .btn-sm {
            padding: 6px 14px;
            border: none;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            margin: 0 4px;
        }
        .btn-approve { background: var(--approved); color: white; }
        .btn-reject { background: var(--rejected); color: white; }
        .btn-view { background: var(--staff); color: white; }
        .btn-edit { background: var(--pending); color: white; }
        .btn-update { background: var(--info); color: white; }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid var(--approved); }

        /* Modal */
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
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            padding: 24px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bsu-mint);
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }
        .remarks-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
            resize: vertical;
            margin: 15px 0;
        }
        .remarks-textarea:focus {
            outline: none;
            border-color: var(--staff);
        }
        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
        .file-list { list-style: none; padding: 0; }
        .file-list li {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-list li:last-child { border-bottom: none; }
        .file-link {
            color: var(--staff);
            text-decoration: none;
            font-weight: 500;
        }
        .file-link:hover { text-decoration: underline; }
        .file-icon { font-size: 1.2rem; margin-right: 10px; }
        
        .report-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }
        select, .report-btn {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-right: 10px;
        }
        .report-btn {
            background: var(--staff);
            color: white;
            border: none;
            cursor: pointer;
        }
        .status-select {
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            font-size: 0.75rem;
        }
        
        /* Search Bar */
        .search-bar {
            width: 100%;
            max-width: 350px;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.85rem;
            background: white;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .dashboard-tabs { flex-direction: column; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .data-table { display: block; overflow-x: auto; }
            .table-header { flex-direction: column; align-items: flex-start; }
            .search-bar { max-width: 100%; }
        }
    </style>
</head>
<body>

<?php 
    if(file_exists("navbar_staff.php")) { 
        include "navbar_staff.php"; 
    }
?>
<div class="dashboard-container">
    <div class="welcome-header">
        <div class="welcome-text">
            <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>! 📋</h2>
            <p>SOAU Staff Dashboard - Manage organizations and activity permits</p>
            <span class="role-badge">👥 SOAU Staff</span>
        </div>
    </div>

    <!-- Staff Tabs -->
    <div class="dashboard-tabs">
        <button class="dashboard-tab active" onclick="switchTab('approve_org')">🏢 Approve Organizations</button>
        <button class="dashboard-tab" onclick="switchTab('pending_permits')">📋 Pending Permits</button>
        <button class="dashboard-tab" onclick="switchTab('approved_permits')">✅ Approved Permits</button>
        <button class="dashboard-tab" onclick="switchTab('evaluate_reports')">📊 Accomplishment Reports</button>
        <button class="dashboard-tab" onclick="switchTab('reports')">📈 Generate Reports</button>
    </div>

    <!-- TAB 1: Approve Organizations -->
    <div id="approve_org" class="tab-content active">
        <?php if ($org_action_message): ?>
            <div class="alert alert-success"><?php echo $org_action_message; ?></div>
        <?php endif; ?>
        
        <!-- Pending Organizations Section -->
        <div class="table-card">
            <div style="padding: 24px 24px 0 24px; background: #fef3c7;">
                <h3 class="section-title"><span>⏳</span> Pending Organization Approvals</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Organization Name</th><th>Email</th><th>Adviser</th><th>Date Applied</th><th>Type</th><th>Files</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_orgs as $org): ?>
                    <tr>
                        <td><strong><?php echo $org['id']; ?></strong></td>
                        <td><?php echo $org['name']; ?></td>
                        <td><?php echo $org['email']; ?></td>
                        <td><?php echo $org['adviser']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($org['date_applied'])); ?></td>
                        <td><span class="status-badge status-pending"><?php echo ucfirst($org['type']); ?></span></td>
                        <td><button class="btn-sm btn-view" onclick="viewOrgFiles('<?php echo $org['id']; ?>', '<?php echo $org['type']; ?>')">📄 View Files</button></td>
                        <td>
                            <form method="POST" style="display: inline-block;" id="approveForm_<?php echo $org['id']; ?>">
                                <input type="hidden" name="org_id" value="<?php echo $org['id']; ?>">
                                <input type="hidden" name="action_type" value="approve">
                                <select name="status_type" class="status-select" required style="margin-right: 5px;">
                                    <option value="new">New RSO Status</option>
                                    <option value="partial">Partial RSO Status</option>
                                    <option value="full">Full RSO Status</option>
                                </select>
                                <button type="submit" name="org_action" class="btn-sm btn-approve">✓ Approve</button>
                            </form>
                            <button class="btn-sm btn-reject" onclick="showRejectModal('org', '<?php echo $org['id']; ?>')">✗ Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Approved Organizations Section with Status Labels -->
        <div class="table-card" style="margin-top: 24px;">
            <div style="padding: 24px 24px 0 24px; background: #d1fae5;">
                <h3 class="section-title"><span>✅</span> Approved Organizations</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Organization Name</th><th>Email</th><th>Adviser</th><th>Date Approved</th><th>Members</th><th>RSO Status</th><th>Files</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($approved_orgs as $org): ?>
                    <tr>
                        <td><strong><?php echo $org['id']; ?></strong></td>
                        <td><?php echo $org['name']; ?></td>
                        <td><?php echo $org['email']; ?></td>
                        <td><?php echo $org['adviser']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($org['date_approved'])); ?></td>
                        <td><?php echo $org['members']; ?> members</td>
                        <td>
                            <span class="status-badge status-<?php echo $org['badge_color']; ?>">
                                <?php echo $org['status_badge']; ?>
                            </span>
                        </td>
                        <td>
                             <button class="btn-sm btn-view" onclick="viewApprovedOrgFiles('<?php echo $org['id']; ?>', '<?php echo $org['registration_status']; ?>')">📄 View Files</button>
                         
                        </td>
                        <td>
                              <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="org_id" value="<?php echo $org['id']; ?>">
                                <input type="hidden" name="action_type" value="update_status">
                                <select name="status_type" class="status-select" required>
                                    <option value="new" <?php echo $org['registration_status'] == 'new' ? 'selected' : ''; ?>>New RSO Status</option>
                                    <option value="partial" <?php echo $org['registration_status'] == 'partial' ? 'selected' : ''; ?>>Partial RSO Status</option>
                                    <option value="full" <?php echo $org['registration_status'] == 'full' ? 'selected' : ''; ?>>Full RSO Status</option>
                                </select>
                                <button type="submit" name="org_action" class="btn-sm btn-update">🔄 Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: Pending Activity Permits -->
    <div id="pending_permits" class="tab-content">
        <?php if ($permit_action_message): ?>
            <div class="alert alert-success"><?php echo $permit_action_message; ?></div>
        <?php endif; ?>
        
        <div class="table-card">
            <div style="padding: 24px 24px 0 24px;">
                <h3 class="section-title"><span>📋</span> Pending Activity Permits</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Permit ID</th><th>Organization</th><th>Activity Title</th><th>Type</th><th>Date</th><th>Venue</th><th>Files</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_permits as $permit): ?>
                    <tr>
                        <td><strong><?php echo $permit['id']; ?></strong></td>
                        <td><?php echo $permit['organization']; ?></td>
                        <td><?php echo $permit['activity_title']; ?></td>
                        <td><?php echo $permit['type']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($permit['start_date'])); ?></td>
                        <td><?php echo $permit['venue']; ?></td>
                        <td><button class="btn-sm btn-view" onclick="viewPermitFiles('<?php echo $permit['id']; ?>')">📄 View Files</button></td>
                        <td>
                            <form method="POST" style="display: inline-block;" id="approvePermitForm_<?php echo $permit['id']; ?>">
                                <input type="hidden" name="permit_id" value="<?php echo $permit['id']; ?>">
                                <input type="hidden" name="action_type" value="approve">
                                <button type="submit" name="permit_action" class="btn-sm btn-approve">✓ Approve</button>
                            </form>
                            <button class="btn-sm btn-reject" onclick="showRejectModal('permit', '<?php echo $permit['id']; ?>')">✗ Reject</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: Approved Permits (Editable with Search) -->
    <div id="approved_permits" class="tab-content">
        <div class="table-card">
            <div style="padding: 24px 24px 0 24px;">
                <h3 class="section-title"><span>✅</span> Approved Activity Permits</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px;">Click on any editable field to modify | Use search to filter records</p>
                
                <!-- Universal Search Bar -->
                <div class="table-header">
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Showing <?php echo count($approved_permits); ?> approved permits
                    </div>
                    <input type="text" id="permitSearch" class="search-bar" placeholder="🔍 Search by Permit ID, Organization, Activity, Venue..." onkeyup="filterApprovedPermits()">
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table" id="approvedPermitsTable" style="min-width: 1400px;">
                    <thead>
                        <tr>
                            <th>Permit ID</th><th>Organization</th><th>Activity Title</th><th>Type</th><th>Start Date</th><th>End Date</th>
                            <th>Start Time</th><th>End Time</th><th>Venue</th><th>Approval Date</th><th>Report Due</th>
                            <th>Actual Sub.</th><th>Rating %</th><th>AP +/-</th><th>AR +/-</th><th>Remarks</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approved_permits as $permit): ?>
                        <tr class="permit-row">
                            <td class="permit-id"><?php echo $permit['permit_id']; ?></td>
                            <td><?php echo $permit['organization']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['activity_title']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['type']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['start_date']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['end_date']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['start_time']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['end_time']; ?></td>
                            <td class="venue-cell"><?php echo $permit['venue']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['approval_date']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['report_due']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['actual_submission']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['rating']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['ap_points']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['ar_points']; ?></td>
                            <td contenteditable="true" class="editable"><?php echo $permit['remarks']; ?></td>
                            <td><button class="btn-sm btn-edit" onclick="saveEdit(this)">💾 Save</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: Evaluate Reports -->
    <div id="evaluate_reports" class="tab-content">
        <div class="table-card">
            <div style="padding: 24px 24px 0 24px;">
                <h3 class="section-title"><span>📊</span> Pending Accomplishment Report</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>Permit ID</th><th>Organization</th><th>Activity Title</th><th>Type</th><th>Rating</th><th>Accomplishment Report</th><th>Evaluation Report</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_evaluations as $eval): ?>
                    <tr>
                        <td><strong><?php echo $eval['permit_id']; ?></strong></td>
                        <td><?php echo $eval['organization']; ?></td>
                        <td><?php echo $eval['activity_title']; ?></td>
                        <td><?php echo $eval['type']; ?></td>
                        <td><?php echo $eval['rating']; ?></td>
                        <td><a href="#" class="file-link">📄 <?php echo $eval['accomplishment_report']; ?></a></td>
                        <td><a href="#" class="file-link">📄 <?php echo $eval['evaluation_report']; ?></a></td>
                        <td>
                            <button class="btn-sm btn-approve" onclick="approveEvaluation('<?php echo $eval['permit_id']; ?>')">✓Approve</button>
                            <button class="btn-sm btn-reject" onclick="rejectEvaluation('<?php echo $eval['permit_id']; ?>')">✗Disapprove</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 5: Generate Reports -->
    <div id="reports" class="tab-content">
        <div class="info-card">
            <h3 class="section-title"><span>📈</span> Activity Report Generator</h3>
            
            <form method="POST" class="report-form">
                <label style="font-weight: 600;">Select Activity Type:</label>
                <select name="report_type" required>
                    <option value="" disabled selected>Select Nature of Activity...</option>
                    <option value="Meeting/Fellowship">Meeting/Fellowship</option>
                    <option value="Maintenance/Cleaning">Maintenance/Cleaning</option>
                    <option value="Seminar/Training/Forum">Seminar/Training/Forum</option>
                    <option value="Socialization">Socialization</option>
                    <option value="Contest/Competition">Contest/Competition</option>
                    <option value="Extension/Outreach">Extension/Outreach</option>
                    <option value="Campaign/Recruitment">Campaign/Recruitment</option>
                    <option value="Income Generating Activity">Income Generating Activity</option>
                    <option value="Collection of Fees/Fines">Collection of Fees/Fines</option>
                    <option value="Others">Others</option>
                </select>
                <button type="submit" name="generate_report" class="report-btn">Generate Report →</button>
            </form>

            <?php if ($report_result): ?>
            <div style="margin-top: 24px; padding: 20px; background: var(--bsu-mint); border-radius: 12px;">
                <h4>Report Summary: <?php echo $report_result['type']; ?></h4>
                <p>Total Activities: <strong><?php echo $report_result['count']; ?></strong></p>
                <p>Percentage of Total: <strong><?php echo $report_result['percentage']; ?>%</strong></p>
                <button class="btn-sm btn-view" onclick="downloadReport()">📥 Download Report (PDF)</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✗ Reject Application</h3>
            <button class="close-modal" onclick="closeRejectModal()">&times;</button>
        </div>
        <div style="padding: 10px 0;">
            <p style="margin-bottom: 15px; color: var(--text-muted);">Please provide a reason for rejecting this application:</p>
            <textarea id="rejectRemarks" class="remarks-textarea" rows="4" placeholder="Enter remarks/reason for rejection..."></textarea>
            <div class="modal-buttons">
                <button class="btn-sm btn-outline" onclick="closeRejectModal()">Cancel</button>
                <button class="btn-sm btn-reject" id="confirmRejectBtn">✗ Confirm Rejection</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing files -->
<div id="fileModal" class="modal">
    <div class="modal-content" style="max-width: 650px;">
        <div class="modal-header">
            <h3 id="modalTitle">📄 Organization Documents</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalBody"></div>
    </div>
</div>

<script>
    let currentRejectType = null;
    let currentRejectId = null;

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.dashboard-tab').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }

    function showRejectModal(type, id) {
        currentRejectType = type;
        currentRejectId = id;
        document.getElementById('rejectModal').classList.add('active');
        document.getElementById('rejectRemarks').value = '';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
        currentRejectType = null;
        currentRejectId = null;
    }

    // Confirm rejection button
    document.getElementById('confirmRejectBtn')?.addEventListener('click', function() {
        const remarks = document.getElementById('rejectRemarks').value.trim();
        
        if (!remarks) {
            alert('Please provide a reason for rejection.');
            return;
        }
        
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = currentRejectType === 'org' ? 'org_action' : 'permit_action';
        typeInput.value = '1';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = currentRejectType === 'org' ? 'org_id' : 'permit_id';
        idInput.value = currentRejectId;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action_type';
        actionInput.value = 'reject';
        
        const remarksInput = document.createElement('input');
        remarksInput.type = 'hidden';
        remarksInput.name = 'remarks';
        remarksInput.value = remarks;
        
        form.appendChild(typeInput);
        form.appendChild(idInput);
        form.appendChild(actionInput);
        form.appendChild(remarksInput);
        document.body.appendChild(form);
        
        // Show confirmation
        alert(`Application ${currentRejectId} has been rejected.\nReason: ${remarks}`);
        
        form.submit();
    });

    // View files for PENDING organizations
    function viewOrgFiles(orgId, orgType) {
        const modal = document.getElementById('fileModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerHTML = '📄 Organization Documents - ' + orgId;
        
        let filesHtml = '<ul class="file-list">';
        if (orgType === 'new') {
            filesHtml += '<li><span class="file-icon">📄</span> 1. Letter of Application: <a href="#" class="file-link">letter.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📄</span> 2. Accomplished Application Form: <a href="#" class="file-link">form.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📄</span> 3. Description of the Proposed Organization: <a href="#" class="file-link">description.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">🪪</span> 4. Photocopy of BSU ID Cards: <a href="#" class="file-link">ids.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📋</span> 5. Tentative Action Plan: <a href="#" class="file-link">action_plan.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">👥</span> 6. List of Members (Min. 35 BSU Undergrads): <a href="#" class="file-link">members.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📜</span> 7. Ratified Constitutions and By-Laws: <a href="#" class="file-link">constitution.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">🏢</span> 8. SEC Registration: <a href="#" class="file-link">sec_reg.pdf</a></li>';
        } else {
            filesHtml += '<li><span class="file-icon">📄</span> 1. Letter of Application: <a href="#" class="file-link">letter.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📄</span> 2. Accomplished Application Form: <a href="#" class="file-link">form.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">🪪</span> 3. Photocopy of BSU ID Cards: <a href="#" class="file-link">ids.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📋</span> 4. Tentative Action Plan: <a href="#" class="file-link">action_plan.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">👥</span> 5. List of Members (Min. 35 BSU Undergrads): <a href="#" class="file-link">members.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">📜</span> 6. Ratified Constitutions and By-Laws: <a href="#" class="file-link">constitution.pdf</a></li>';
        }
        filesHtml += '</ul>';
        
        modalBody.innerHTML = filesHtml;
        modal.classList.add('active');
    }

    // View files for APPROVED organizations (based on status)
    function viewApprovedOrgFiles(orgId, status) {
        const modal = document.getElementById('fileModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerHTML = '📄 Organization Documents - ' + orgId + ' (' + status.toUpperCase() + ' Status)';
        
        let filesHtml = '<ul class="file-list">';
        
        filesHtml += '<li><span class="file-icon">📄</span> 1. Letter of Application: <a href="#" class="file-link">letter.pdf</a></li>';
        filesHtml += '<li><span class="file-icon">📄</span> 2. Accomplished Application Form: <a href="#" class="file-link">form.pdf</a></li>';
        filesHtml += '<li><span class="file-icon">🪪</span> 3. Photocopy of BSU ID Cards: <a href="#" class="file-link">ids.pdf</a></li>';
        filesHtml += '<li><span class="file-icon">📋</span> 4. Tentative Action Plan: <a href="#" class="file-link">action_plan.pdf</a></li>';
        filesHtml += '<li><span class="file-icon">👥</span> 5. List of Members (Min. 35 BSU Undergrads): <a href="#" class="file-link">members.pdf</a></li>';
        filesHtml += '<li><span class="file-icon">📜</span> 6. Ratified Constitutions and By-Laws: <a href="#" class="file-link">constitution.pdf</a></li>';
        
        if (status === 'new') {
            filesHtml += '<li><span class="file-icon">📝</span> 7. Description of the Proposed Organization: <a href="#" class="file-link">description.pdf</a></li>';
            filesHtml += '<li><span class="file-icon">🏢</span> 8. Description of Institution & SEC Registration: <a href="#" class="file-link">sec_reg.pdf</a></li>';
        }
        
        filesHtml += '</ul>';
        
        if (status === 'new') {
            filesHtml += '<div style="margin-top: 15px; padding: 10px; background: #d1ecf1; border-radius: 8px; font-size: 0.75rem;">';
            filesHtml += '📌 <strong>Note:</strong> This organization has <strong>NEW RSO Status</strong> and is under probationary period.';
            filesHtml += '</div>';
        } else if (status === 'partial') {
            filesHtml += '<div style="margin-top: 15px; padding: 10px; background: #fed7aa; border-radius: 8px; font-size: 0.75rem;">';
            filesHtml += '📌 <strong>Note:</strong> This organization has <strong>PARTIAL RSO Status</strong> - Some privileges are limited.';
            filesHtml += '</div>';
        } else {
            filesHtml += '<div style="margin-top: 15px; padding: 10px; background: #d1fae5; border-radius: 8px; font-size: 0.75rem;">';
            filesHtml += '📌 <strong>Note:</strong> This organization has <strong>FULL RSO Status</strong> - All privileges granted.';
            filesHtml += '</div>';
        }
        
        modalBody.innerHTML = filesHtml;
        modal.classList.add('active');
    }

    function viewPermitFiles(permitId) {
        const modal = document.getElementById('fileModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerHTML = '📄 Permit Documents - ' + permitId;
        
        modalBody.innerHTML = `
            <ul class="file-list">
                <li><span class="file-icon">📋</span> Activity Proposal: <a href="#" class="file-link">proposal_${permitId}.pdf</a></li>
                <li><span class="file-icon">💰</span> Budget Proposal: <a href="#" class="file-link">budget_${permitId}.pdf</a></li>
                <li><span class="file-icon">🛡️</span> Safety Plan: <a href="#" class="file-link">safety_${permitId}.pdf</a></li>
                <li><span class="file-icon">📝</span> Endorsement Letter: <a href="#" class="file-link">endorsement_${permitId}.pdf</a></li>
            </ul>
        `;
        modal.classList.add('active');
    }

    function closeModal() {
        document.getElementById('fileModal').classList.remove('active');
    }

    function saveEdit(button) {
        button.textContent = '✓ Saved!';
        button.style.background = '#10b981';
        setTimeout(() => {
            button.textContent = '💾 Save';
            button.style.background = '#f59e0b';
        }, 2000);
    }

    function approveEvaluation(permitId) {
        alert('Accomplishment report for ' + permitId + ' has been APPROVED!');
    }

    function rejectEvaluation(permitId) {
        alert('Accomplishment report for ' + permitId + ' has been DISAPPROVED.');
    }

    function downloadReport() {
        alert('Downloading report as PDF...');
    }
    
    // Universal Search for Approved Permits
    function filterApprovedPermits() {
        const input = document.getElementById('permitSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('approvedPermitsTable');
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length - 1; j++) {
                if (cells[j]) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            row.style.display = found ? '' : 'none';
        }
    }
</script>

</body>
</html>