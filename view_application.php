<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Get application ID from URL
$application_id = $_GET['id'] ?? 'AP-2024-001';

// Sample application data (in real app, fetch from database)
$application = [
    'id' => $application_id,
    'status' => 'pending',
    'date_submitted' => '2024-01-20',
    'campus_type' => 'on',
    'rso_name' => 'IT SOCIETY',
    'act_title' => 'IT Society General Assembly',
    'act_type' => 'Meeting/Fellowship',
    'objectives1' => 'Plan upcoming events for the semester',
    'objectives2' => 'Elect new set of officers',
    'objectives3' => 'Discuss budget allocation for projects',
    'act_start' => '2024-02-15',
    'act_end' => '2024-02-15',
    'time_start' => '09:00',
    'time_end' => '17:00',
    'act_venue' => 'ICT Building, BSU Main Campus',
    'client_name' => 'Juan Dela Cruz',
    'id_number' => '2021-12345',
    'contact_number' => '0912-345-6789',
    'adviser_name' => 'Prof. Maria Santos',
    'position' => 'President',
    'participant_count' => '50',
    'off_campus_address' => '',
    'safety_plan' => '',
    'other_text' => '',
    'remarks' => 'Waiting for SOAU approval'
];

// Get user info from session
$user_email = $_SESSION['user_email'] ?? 'user@bsu.edu.ph';
$user_name = $_SESSION['user_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application | BSU ORG-Track</title>
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
            font-family: 'Inter', -apple-system, sans-serif; 
            background: var(--bg-page);
            color: var(--text-dark); 
            margin: 0; 
            padding: 40px 20px; 
            line-height: 1.5; 
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .portal-card {
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.15);
            overflow: hidden; 
            border: 1px solid var(--border-color);
        }

        .portal-header { 
            background: var(--bsu-dark); 
            padding: 24px 32px; 
            color: white; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .portal-header h1 { 
            margin: 0; 
            font-size: 1.25rem; 
            font-weight: 600; 
        }
        
        .portal-header p {
            margin: 4px 0 0 0;
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .view-permit-btn {
            background: #3498db;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .view-permit-btn:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .status-bar {
            padding: 12px 32px;
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .status-approved { background: #d1fae5; color: var(--approved); }
        .status-pending { background: #fed7aa; color: var(--pending); }
        .status-rejected { background: #fee2e2; color: var(--rejected); }

        .card-body { 
            padding: 32px; 
        }

        .form-section { 
            margin-bottom: 32px; 
        }

        .section-label {
            display: block; 
            font-size: 0.85rem; 
            font-weight: 700; 
            color: var(--bsu-green);
            margin-bottom: 16px; 
            border-bottom: 2px solid var(--bsu-mint); 
            padding-bottom: 8px;
        }

        .form-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px; 
        }
        
        .full-width { 
            grid-column: span 2; 
        }
        
        .field-group { 
            display: flex; 
            flex-direction: column; 
            gap: 6px; 
            margin-bottom: 12px; 
        }

        label { 
            font-size: 0.8rem; 
            font-weight: 600; 
            color: var(--text-dark); 
        }
        
        input, select, textarea {
            padding: 10px 14px; 
            border: 1px solid var(--border-color);
            border-radius: 8px; 
            font-size: 0.9rem; 
            background: #f9fafb;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        input:focus, select:focus, textarea:focus { 
            outline: none; 
            border-color: var(--bsu-green); 
            background: white;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.15); 
        }

        input.editable, select.editable, textarea.editable {
            background: white;
            border-color: var(--border-color);
        }

        input.editable:focus, select.editable:focus, textarea.editable:focus {
            border-color: var(--bsu-green);
        }

        .readonly-field {
            background: #f0f2f5;
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .campus-options {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .campus-card {
            flex: 1;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .campus-card:hover {
            border-color: var(--bsu-green);
            transform: translateY(-2px);
        }
        
        .campus-card.selected {
            border-color: var(--bsu-green);
            background: var(--bsu-mint);
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        
        .btn-outline { 
            background: #fff; 
            color: var(--text-muted); 
            border: 1px solid var(--border-color); 
        }
        
        .btn-outline:hover {
            background: #f5f5f5;
            border-color: var(--bsu-green);
        }
        
        .btn-primary { 
            background: var(--bsu-green); 
            color: white; 
        }
        
        .btn-primary:hover {
            background: var(--bsu-dark);
            transform: translateY(-1px);
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .edit-mode-indicator {
            background: var(--bsu-mint);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.75rem;
            color: var(--bsu-dark);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        /* PDF Modal */
        .pdf-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .pdf-modal.active {
            display: flex;
        }
        
        .pdf-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .pdf-modal-header {
            padding: 16px 24px;
            background: var(--bsu-dark);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pdf-modal-body {
            padding: 0;
            overflow-y: auto;
            flex: 1;
            background: #525659;
        }
        
        .close-pdf-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .modal-buttons {
            padding: 16px 24px;
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        iframe {
            width: 100%;
            height: 70vh;
            border: none;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .full-width {
                grid-column: span 1;
            }
            .card-body {
                padding: 20px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                text-align: center;
            }
            .campus-options {
                flex-direction: column;
            }
            .portal-header {
                flex-direction: column;
                text-align: center;
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

<div class="container">
    <div class="portal-card">
        <header class="portal-header">
            <div>
                <h1>📄 Application Details</h1>
                <p>Application ID: <?php echo htmlspecialchars($application['id']); ?></p>
            </div>
            <button class="view-permit-btn" onclick="viewPermitPDF()">📄 View Permit PDF</button>
        </header>
        
        <div class="status-bar">
            <div>
                <span class="status-badge status-<?php echo $application['status']; ?>">
                    <?php echo ucfirst($application['status']); ?>
                </span>
                <span style="margin-left: 12px; font-size: 0.75rem; color: var(--text-muted);">
                    Submitted: <?php echo date('F d, Y', strtotime($application['date_submitted'])); ?>
                </span>
            </div>
            <div id="editModeIndicator" class="edit-mode-indicator" style="display: none;">
                ✏️ Edit Mode Active
            </div>
        </div>

        <div class="card-body">
            <form id="applicationForm" method="POST" action="update_application.php">
                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                
                <!-- Applicant Information -->
                <div class="form-section">
                    <span class="section-label">👤 Applicant Information</span>
                    <div class="form-grid">
                        <div class="field-group full-width">
                            <label>Full Name</label>
                            <input type="text" name="client_name" value="<?php echo htmlspecialchars($application['client_name']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Student ID</label>
                            <input type="text" name="id_number" value="<?php echo htmlspecialchars($application['id_number']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Contact Number</label>
                            <input type="tel" name="contact_number" value="<?php echo htmlspecialchars($application['contact_number']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Position in Organization</label>
                            <input type="text" name="position" value="<?php echo htmlspecialchars($application['position']); ?>" class="editable" readonly>
                        </div>
                    </div>
                </div>

                <!-- Activity Type -->
                <div class="form-section">
                    <span class="section-label">📍 Activity Type</span>
                    <div class="campus-options">
                        <div class="campus-card <?php echo $application['campus_type'] == 'on' ? 'selected' : ''; ?>" data-value="on" onclick="if(isEditMode) selectCampusType('on')">
                            <div class="campus-icon">🏫</div>
                            <div class="campus-title">On-Campus Activity</div>
                            <div class="campus-desc">Within BSU premises</div>
                        </div>
                        <div class="campus-card <?php echo $application['campus_type'] == 'off' ? 'selected' : ''; ?>" data-value="off" onclick="if(isEditMode) selectCampusType('off')">
                            <div class="campus-icon">🌍</div>
                            <div class="campus-title">Off-Campus Activity</div>
                            <div class="campus-desc">Outside BSU premises</div>
                        </div>
                    </div>
                    <input type="hidden" name="campus_type" id="campus_type" value="<?php echo $application['campus_type']; ?>">
                </div>

                <!-- Activity Details -->
                <div class="form-section">
                    <span class="section-label">📋 Activity Details</span>
                    <div class="form-grid">
                        <div class="field-group full-width">
                            <label>Name of RSO / Organization</label>
                            <input type="text" name="rso_name" value="<?php echo htmlspecialchars($application['rso_name']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group full-width">
                            <label>Title of Activity</label>
                            <input type="text" name="act_title" value="<?php echo htmlspecialchars($application['act_title']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group full-width">
                            <label>Nature of Activity</label>
                            <select name="act_type" class="editable" disabled>
                                <option value="Meeting/Fellowship" <?php echo $application['act_type'] == 'Meeting/Fellowship' ? 'selected' : ''; ?>>Meeting/Fellowship</option>
                                <option value="Maintenance/Cleaning" <?php echo $application['act_type'] == 'Maintenance/Cleaning' ? 'selected' : ''; ?>>Maintenance/Cleaning</option>
                                <option value="Seminar/Training/Forum" <?php echo $application['act_type'] == 'Seminar/Training/Forum' ? 'selected' : ''; ?>>Seminar/Training/Forum</option>
                                <option value="Socialization" <?php echo $application['act_type'] == 'Socialization' ? 'selected' : ''; ?>>Socialization</option>
                                <option value="Contest/Competition" <?php echo $application['act_type'] == 'Contest/Competition' ? 'selected' : ''; ?>>Contest/Competition</option>
                                <option value="Extension/Outreach" <?php echo $application['act_type'] == 'Extension/Outreach' ? 'selected' : ''; ?>>Extension/Outreach</option>
                                <option value="Campaign/Recruitment" <?php echo $application['act_type'] == 'Campaign/Recruitment' ? 'selected' : ''; ?>>Campaign/Recruitment</option>
                                <option value="Income Generating Activity" <?php echo $application['act_type'] == 'Income Generating Activity' ? 'selected' : ''; ?>>Income Generating Activity</option>
                                <option value="Collection of Fees/Fines" <?php echo $application['act_type'] == 'Collection of Fees/Fines' ? 'selected' : ''; ?>>Collection of Fees/Fines</option>
                                <option value="Others" <?php echo $application['act_type'] == 'Others' ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>
                        <div class="field-group full-width">
                            <label>Objectives</label>
                            <input type="text" name="objectives1" value="<?php echo htmlspecialchars($application['objectives1']); ?>" placeholder="Objective 1" style="margin-bottom:5px;" class="editable" readonly>
                            <input type="text" name="objectives2" value="<?php echo htmlspecialchars($application['objectives2']); ?>" placeholder="Objective 2" style="margin-bottom:5px;" class="editable" readonly>
                            <input type="text" name="objectives3" value="<?php echo htmlspecialchars($application['objectives3']); ?>" placeholder="Objective 3" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Start Date</label>
                            <input type="date" name="act_start" value="<?php echo $application['act_start']; ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>End Date</label>
                            <input type="date" name="act_end" value="<?php echo $application['act_end']; ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Start Time</label>
                            <input type="time" name="time_start" value="<?php echo $application['time_start']; ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>End Time</label>
                            <input type="time" name="time_end" value="<?php echo $application['time_end']; ?>" class="editable" readonly>
                        </div>
                        <div class="field-group full-width">
                            <label>Venue</label>
                            <input type="text" name="act_venue" value="<?php echo htmlspecialchars($application['act_venue']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>Expected Participants</label>
                            <input type="number" name="participant_count" value="<?php echo $application['participant_count']; ?>" class="editable" readonly>
                        </div>
                        <div class="field-group">
                            <label>RSO Adviser</label>
                            <input type="text" name="adviser_name" value="<?php echo htmlspecialchars($application['adviser_name']); ?>" class="editable" readonly>
                        </div>
                    </div>
                </div>

                <!-- Off-Campus Requirements (shown only if off-campus) -->
                <div id="off-campus-section" class="form-section" style="<?php echo $application['campus_type'] == 'off' ? 'display: block;' : 'display: none;'; ?>">
                    <span class="section-label">🚌 Off-Campus Requirements</span>
                    <div class="warning-box">
                        ⚠️ Off-campus activities require additional processing time (7-10 business days).
                    </div>
                    <div class="form-grid">
                        <div class="field-group full-width">
                            <label>Off-Campus Venue Address</label>
                            <input type="text" name="off_campus_address" value="<?php echo htmlspecialchars($application['off_campus_address']); ?>" class="editable" readonly>
                        </div>
                        <div class="field-group full-width">
                            <label>Safety and Security Plan</label>
                            <textarea name="safety_plan" rows="3" class="editable" readonly><?php echo htmlspecialchars($application['safety_plan']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <?php if (!empty($application['remarks'])): ?>
                <div class="form-section">
                    <span class="section-label">📝 Remarks</span>
                    <div class="warning-box" style="background: #e8f0fe; border-left-color: #3498db;">
                        <?php echo htmlspecialchars($application['remarks']); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="dashboard_rso.php" class="btn btn-outline">← Back to Dashboard</a>
                    <button type="button" id="editBtn" class="btn btn-warning" onclick="enableEdit()">✏️ Edit Application</button>
                    <button type="button" id="cancelBtn" class="btn btn-outline" style="display: none;" onclick="cancelEdit()">Cancel</button>
                    <button type="submit" id="saveBtn" class="btn btn-primary" style="display: none;" onclick="return confirmSave()">💾 Save Changes</button>
                    <?php if ($application['status'] == 'pending'): ?>
                    <button type="button" class="btn btn-danger" onclick="cancelApplication()">🗑️ Cancel Application</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div id="pdfModal" class="pdf-modal">
    <div class="pdf-modal-content">
        <div class="pdf-modal-header">
            <h3>📄 Activity Permit Preview</h3>
            <button class="close-pdf-modal" onclick="closePDFModal()">&times;</button>
        </div>
        <div class="pdf-modal-body">
            <iframe id="pdfFrame" src=""></iframe>
        </div>
        <div class="modal-buttons">
            <button class="btn btn-outline" onclick="closePDFModal()">Close</button>
            <button class="btn btn-primary" id="modalDownloadBtn" onclick="downloadCurrentPDF()">⬇️ Download PDF</button>
        </div>
    </div>
</div>

<script>
    let isEditMode = false;
    let currentPDFBlob = null;
    const originalValues = new Map();

    // Function to view permit PDF
    async function viewPermitPDF() {
        // Show loading indicator
        const modal = document.getElementById('pdfModal');
        const iframe = document.getElementById('pdfFrame');
        iframe.src = 'about:blank';
        modal.classList.add('active');
        
        // Get current form data (including any edits if in edit mode)
        const formData = new FormData();
        formData.append('rso_name', document.querySelector('input[name="rso_name"]').value);
        formData.append('act_title', document.querySelector('input[name="act_title"]').value);
        formData.append('act_type', document.querySelector('select[name="act_type"]').value);
        formData.append('objectives1', document.querySelector('input[name="objectives1"]').value);
        formData.append('objectives2', document.querySelector('input[name="objectives2"]').value);
        formData.append('objectives3', document.querySelector('input[name="objectives3"]').value);
        formData.append('act_start', document.querySelector('input[name="act_start"]').value);
        formData.append('act_end', document.querySelector('input[name="act_end"]').value);
        formData.append('time_start', document.querySelector('input[name="time_start"]').value);
        formData.append('time_end', document.querySelector('input[name="time_end"]').value);
        formData.append('act_venue', document.querySelector('input[name="act_venue"]').value);
        formData.append('client_name', document.querySelector('input[name="client_name"]').value);
        formData.append('id_number', document.querySelector('input[name="id_number"]').value);
        formData.append('contact_number', document.querySelector('input[name="contact_number"]').value);
        formData.append('adviser_name', document.querySelector('input[name="adviser_name"]').value);
        formData.append('position', document.querySelector('input[name="position"]').value);
        formData.append('campus_type', document.getElementById('campus_type').value);
        formData.append('participant_count', document.querySelector('input[name="participant_count"]').value);
        
        const otherText = document.querySelector('input[name="other_text"]');
        if (otherText && otherText.value) {
            formData.append('other_text', otherText.value);
        }
        
        const offCampusAddress = document.querySelector('input[name="off_campus_address"]');
        const safetyPlan = document.querySelector('textarea[name="safety_plan"]');
        if (offCampusAddress && offCampusAddress.value) {
            formData.append('off_campus_address', offCampusAddress.value);
        }
        if (safetyPlan && safetyPlan.value) {
            formData.append('safety_plan', safetyPlan.value);
        }
        
        try {
            const response = await fetch('generate_permit.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const blob = await response.blob();
            currentPDFBlob = blob;
            const url = URL.createObjectURL(blob);
            iframe.src = url;
        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF. Please try again.');
            closePDFModal();
        }
    }

    function downloadCurrentPDF() {
        if (currentPDFBlob) {
            const url = URL.createObjectURL(currentPDFBlob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Activity_Permit_${document.querySelector('input[name="rso_name"]').value.replace(/\s/g, '_')}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        } else {
            alert('Please view the permit first before downloading.');
        }
    }

    function closePDFModal() {
        const modal = document.getElementById('pdfModal');
        const iframe = document.getElementById('pdfFrame');
        iframe.src = 'about:blank';
        modal.classList.remove('active');
        if (currentPDFBlob) {
            URL.revokeObjectURL(currentPDFBlob);
            currentPDFBlob = null;
        }
    }

    function selectCampusType(type) {
        document.getElementById('campus_type').value = type;
        
        // Update selected class
        document.querySelectorAll('.campus-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelector(`.campus-card[data-value="${type}"]`).classList.add('selected');
        
        // Show/hide off-campus section
        const offCampusSection = document.getElementById('off-campus-section');
        if (type === 'off') {
            offCampusSection.style.display = 'block';
        } else {
            offCampusSection.style.display = 'none';
        }
    }

    function enableEdit() {
        isEditMode = true;
        
        // Enable all editable inputs
        document.querySelectorAll('.editable').forEach(input => {
            // Store original value
            originalValues.set(input.name || input.id, input.value);
            input.removeAttribute('readonly');
            input.disabled = false;
            input.classList.add('editable');
            input.style.background = 'white';
        });
        
        // Enable select dropdown
        const select = document.querySelector('select[name="act_type"]');
        if (select) {
            select.disabled = false;
        }
        
        // Make campus cards clickable
        document.querySelectorAll('.campus-card').forEach(card => {
            card.style.cursor = 'pointer';
        });
        
        // Show/hide buttons
        document.getElementById('editBtn').style.display = 'none';
        document.getElementById('cancelBtn').style.display = 'inline-block';
        document.getElementById('saveBtn').style.display = 'inline-block';
        document.getElementById('editModeIndicator').style.display = 'inline-flex';
    }

    function cancelEdit() {
        isEditMode = false;
        
        // Restore original values and disable inputs
        document.querySelectorAll('.editable').forEach(input => {
            if (originalValues.has(input.name || input.id)) {
                input.value = originalValues.get(input.name || input.id);
            }
            input.setAttribute('readonly', true);
            input.disabled = true;
            input.style.background = '#f9fafb';
        });
        
        // Disable select dropdown
        const select = document.querySelector('select[name="act_type"]');
        if (select) {
            select.disabled = true;
        }
        
        // Disable campus card clicks
        document.querySelectorAll('.campus-card').forEach(card => {
            card.style.cursor = 'default';
        });
        
        // Restore campus type selection display
        const currentType = document.getElementById('campus_type').value;
        selectCampusType(currentType);
        
        // Show/hide buttons
        document.getElementById('editBtn').style.display = 'inline-block';
        document.getElementById('cancelBtn').style.display = 'none';
        document.getElementById('saveBtn').style.display = 'none';
        document.getElementById('editModeIndicator').style.display = 'none';
    }

    function confirmSave() {
        if (confirm('Are you sure you want to save your changes?')) {
            alert('Changes saved successfully! (Demo mode)');
            // In production, form will submit to update_application.php
            cancelEdit(); // Exit edit mode after save
            return true;
        }
        return false;
    }

    function cancelApplication() {
        if (confirm('Are you sure you want to cancel this application? This action cannot be undone.')) {
            alert('Application has been cancelled. (Demo mode)');
            window.location.href = 'dashboard_rso.php';
        }
    }

    // Initialize campus card styles (not clickable initially)
    document.querySelectorAll('.campus-card').forEach(card => {
        card.style.cursor = 'default';
    });
</script>

</body>
</html>