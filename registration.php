<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Get user info from session
$client_email = $_SESSION['user_email'] ?? '';
$client_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Permit Registration | SOAU BSU</title>
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-dark: #1b4332;
            --bsu-mint: #d8f3dc;
            --text-dark: #1a1c1e;
            --text-muted: #5f6368;
            --border-color: #dadce0;
            --bg-page: #f0f2f5;
            --campus-on: #2d6a4f;
            --campus-off: #e76f51;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background-color: var(--bg-page); 
            color: var(--text-dark); 
            margin: 0; 
            padding: 40px 20px; 
            line-height: 1.5; 
        }

        .portal-card {
            background: #ffffff; 
            max-width: 900px; 
            margin: 0 auto;
            border-radius: 12px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            overflow: hidden; 
            border: 1px solid var(--border-color);
        }

        .portal-header { 
            background: var(--bsu-dark); 
            padding: 24px 40px; 
            color: white; 
        }
        
        .portal-header h1 { 
            margin: 0; 
            font-size: 1.25rem; 
            font-weight: 600; 
        }
        
        .portal-header p {
            margin: 8px 0 0 0;
            font-size: 0.875rem;
            opacity: 0.85;
        }
        
        .user-info-bar {
            background: var(--bsu-mint);
            padding: 12px 40px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .user-email {
            color: var(--bsu-dark);
            font-weight: 600;
        }
        
        .logout-link {
            color: var(--bsu-green);
            text-decoration: none;
            font-weight: 600;
        }
        
        .logout-link:hover {
            text-decoration: underline;
        }
        
        .progress-bar {
            background: #f8f9fa; 
            padding: 12px 40px; 
            border-bottom: 1px solid var(--border-color);
            font-size: 0.75rem; 
            text-transform: uppercase; 
            font-weight: 700; 
            color: var(--bsu-green);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .pdf-actions {
            display: flex;
            gap: 12px;
        }
        
        .pdf-btn {
            background: transparent;
            border: 1px solid var(--bsu-green);
            color: var(--bsu-green);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .pdf-btn:hover {
            background: var(--bsu-green);
            color: white;
        }

        .card-body { 
            padding: 40px; 
        }
        
        .form-section { 
            margin-bottom: 40px; 
        }

        .section-label {
            display: block; 
            font-size: 0.9rem; 
            font-weight: 700; 
            color: var(--bsu-green);
            margin-bottom: 16px; 
            border-bottom: 2px solid var(--bsu-mint); 
            padding-bottom: 8px;
        }

        .form-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 24px; 
        }
        
        .full-width { 
            grid-column: span 2; 
        }
        
        .field-group { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
            margin-bottom: 15px; 
        }

        label { 
            font-size: 0.875rem; 
            font-weight: 600; 
            color: var(--text-dark); 
        }
        
        input, select, textarea {
            padding: 12px 16px; 
            border: 1px solid var(--border-color);
            border-radius: 8px; 
            font-size: 1rem; 
            background: #fff;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        input:focus, select:focus, textarea:focus { 
            outline: none; 
            border-color: var(--bsu-green); 
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.15); 
        }

        .campus-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .campus-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            background: #fafafa;
        }
        
        .campus-card:hover {
            border-color: var(--bsu-green);
            transform: translateY(-2px);
        }
        
        .campus-card.selected {
            border-color: var(--bsu-green);
            background: var(--bsu-mint);
        }
        
        .campus-card.on-campus.selected {
            border-color: var(--campus-on);
            background: #e8f5e9;
        }
        
        .campus-card.off-campus.selected {
            border-color: var(--campus-off);
            background: #fef4e8;
        }
        
        .campus-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .campus-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .campus-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .campus-note {
            font-size: 0.75rem;
            margin-top: 8px;
            padding: 6px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .campus-note.requires {
            background: #fff3cd;
            color: #856404;
        }

        .required { 
            color: #d93025; 
            margin-left: 2px; 
        }
        
        .hint-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .sub-form {
            background: #fcfdfc;
            border: 1px dashed var(--bsu-green);
            padding: 30px;
            border-radius: 12px;
            margin: 20px 0 0 0;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown { 
            from { opacity: 0; transform: translateY(-15px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        .portal-footer {
            padding: 24px 40px;
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 0.875rem;
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
            cursor: pointer;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
        }
        
        .btn-view:hover {
            background: #2980b9;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
        }
        
        #others_container input {
            width: 100%;
            box-sizing: border-box;
            margin-top: 8px;
        }
        
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.875rem;
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
        
        @media (max-width: 640px) {
            .card-body { padding: 24px; }
            .portal-header, .progress-bar, .portal-footer, .user-info-bar { padding-left: 24px; padding-right: 24px; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .campus-options { grid-template-columns: 1fr; }
            .modal-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>
<?php 
    if(file_exists("navbar.php")) { 
        include "navbar.php"; 
    }
?>
<div class="portal-card">
    <header class="portal-header">
        <h1>Activity Permit Registration</h1>
        <p>Complete the form below to request an activity permit</p>
    </header>
    
    <div class="user-info-bar">
        <span>Logged in as: <span class="user-email"><?php echo htmlspecialchars($client_email); ?></span></span>
        <a href="logout.php" class="logout-link">🚪 Logout →</a>
    </div>
    
    <div class="progress-bar">
        <span>Activity Permit Application</span>
        <div class="pdf-actions">
            <button type="button" class="pdf-btn" onclick="window.open('d.pdf', '_blank')">📄 View Template</button>
        </div>
    </div>

    <div class="card-body">
        <form action="process_permit_submission.php" method="POST" id="permitForm">
            
            <!-- Hidden field to pass email -->
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($client_email); ?>">
            <input type="hidden" name="campus_type" id="campus_type" value="">
            
            <!-- Client Information Section -->
            <div class="form-section">
                <span class="section-label">Applicant Information</span>
                <div class="form-grid">
                    <div class="field-group full-width">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="client_name" id="client_name" value="<?php echo htmlspecialchars($client_name); ?>" placeholder="Enter your full name" required>
                    </div>
                    <div class="field-group">
                        <label>Student ID <span class="required">*</span></label>
                        <input type="text" name="id_number" id="id_number" placeholder="Enter your ID number" required>
                    </div>
                    <div class="field-group">
                        <label>Contact Number <span class="required">*</span></label>
                        <input type="tel" name="contact_number" id="contact_number" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
                    </div>
                    <div class="field-group">
                        <label>Position in Organization <span class="required">*</span></label>
                        <input type="text" name="position" id="position" placeholder="e.g., President, Secretary" value="President" required>
                    </div>
                </div>
            </div>
            
            <!-- Campus Type Selection -->
            <div class="form-section">
                <span class="section-label">Activity Type</span>
                <div class="campus-options">
                    <div class="campus-card on-campus" onclick="selectCampus('on')">
                        <div class="campus-icon">🏫</div>
                        <div class="campus-title">On-Campus Activity</div>
                        <div class="campus-desc">Activity conducted within BSU premises</div>
                        <div class="campus-note requires">Standard processing: 3-5 business days</div>
                    </div>
                    <div class="campus-card off-campus" onclick="selectCampus('off')">
                        <div class="campus-icon">🌍</div>
                        <div class="campus-title">Off-Campus Activity</div>
                        <div class="campus-desc">Activity conducted outside BSU premises</div>
                        <div class="campus-note requires">Additional requirements needed</div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Permit Details -->
            <div class="form-section" id="activity-details" style="display: none;">
                <span class="section-label">Activity Permit Details</span>
                
                <div class="field-group full-width">
                    <label>Name of RSO / Organization <span class="required">*</span></label>
                    <input type="text" name="rso_name" id="rso_name" placeholder="Enter your organization name" required>
                </div>
                
                <div class="field-group full-width">
                    <label>Title of Activity <span class="required">*</span></label>
                    <input type="text" name="act_title" id="act_title" placeholder="Enter the full title of the activity" required>
                </div>
                
                <div class="field-group">
                    <label>Nature of Activity <span class="required">*</span></label>
                    <select id="act_type" name="act_type" required onchange="toggleOtherNature()">
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
                    <div id="others_container" style="display:none; margin-top: 10px;">
                        <input type="text" name="other_text" id="other_text" placeholder="Please specify the nature of activity...">
                    </div>
                </div>
                
                <div class="field-group">
                    <label>Objectives <span class="required">*</span></label>
                    <input type="text" name="objectives1" id="objectives1" placeholder="Objective 1" style="margin-bottom:5px;" required>
                    <input type="text" name="objectives2" id="objectives2" placeholder="Objective 2" style="margin-bottom:5px;">
                    <input type="text" name="objectives3" id="objectives3" placeholder="Objective 3">
                    <div class="hint-text">Add at least one objective for the activity</div>
                </div>
                
                <div class="form-grid">
                    <div class="field-group">
                        <label>Start Date <span class="required">*</span></label>
                        <input type="date" name="act_start" id="act_start" required>
                    </div>
                    <div class="field-group">
                        <label>End Date <span class="required">*</span></label>
                        <input type="date" name="act_end" id="act_end" required>
                    </div>
                    <div class="field-group">
                        <label>Start Time <span class="required">*</span></label>
                        <input type="time" name="time_start" id="time_start" required>
                    </div>
                    <div class="field-group">
                        <label>End Time <span class="required">*</span></label>
                        <input type="time" name="time_end" id="time_end" required>
                    </div>
                </div>
                
                <div class="field-group">
                    <label>Venue <span class="required">*</span></label>
                    <input type="text" name="act_venue" id="act_venue" placeholder="Specify the exact venue/location" required>
                </div>
                
                <div class="field-group">
                    <label>RSO Adviser Name <span class="required">*</span></label>
                    <input type="text" name="adviser_name" id="adviser_name" placeholder="Full name of RSO Adviser" required>
                </div>
            </div>
            
            <!-- Off-Campus Additional Requirements -->
            <div id="off-campus-requirements" class="sub-form" style="display: none;">
                <span class="section-label">Off-Campus Requirements</span>
                <div class="warning-box">
                    ⚠️ Off-campus activities require additional processing time (7-10 business days) and extra documentation.
                </div>
                <div class="field-group">
                    <label>Off-Campus Venue Address <span class="required">*</span></label>
                    <input type="text" name="off_campus_address" id="off_campus_address" placeholder="Complete address of off-campus venue">
                </div>
                <div class="field-group">
                    <label>Transportation Arrangement</label>
                    <textarea name="transportation" id="transportation" rows="2" placeholder="Describe transportation plan for participants"></textarea>
                </div>
                <div class="field-group">
                    <label>Safety and Security Plan <span class="required">*</span></label>
                    <textarea name="safety_plan" id="safety_plan" rows="3" placeholder="Describe safety measures, emergency procedures, and risk management plan"></textarea>
                </div>
                <div class="field-group">
                    <label>Parent/Guardian Consent Required?</label>
                    <select name="parent_consent" id="parent_consent">
                        <option value="no">No</option>
                        <option value="yes">Yes - Parent/Guardian consent needed</option>
                    </select>
                </div>
            </div>

            <div class="portal-footer">
                <a href="dashboard.php" class="btn btn-outline">← Cancel</a>
                <button type="button" class="btn btn-view" onclick="viewPermitPDF()">👁️ View Permit</button>
                <button type="button" class="btn btn-primary" id="downloadBtn" onclick="downloadPermitPDF()" style="display: none;">⬇️ Download</button>
                <button type="button" class="btn btn-success" onclick="submitApplication()">📤 Submit Application</button>
            </div>
        </form>
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
let selectedCampus = null;
let currentPDFBlob = null;

function selectCampus(type) {
    selectedCampus = type;
    document.getElementById('campus_type').value = type;
    
    // Update UI
    const onCard = document.querySelector('.campus-card.on-campus');
    const offCard = document.querySelector('.campus-card.off-campus');
    
    if (type === 'on') {
        onCard.classList.add('selected');
        offCard.classList.remove('selected');
        document.getElementById('off-campus-requirements').style.display = 'none';
    } else {
        offCard.classList.add('selected');
        onCard.classList.remove('selected');
        document.getElementById('off-campus-requirements').style.display = 'block';
    }
    
    // Show activity details section
    document.getElementById('activity-details').style.display = 'block';
    
    // Set required attributes for off-campus fields if needed
    toggleOffCampusRequired(type === 'off');
}

function toggleOffCampusRequired(isOffCampus) {
    const offCampusFields = document.querySelectorAll('#off-campus-requirements input, #off-campus-requirements textarea, #off-campus-requirements select');
    
    offCampusFields.forEach(field => {
        if (isOffCampus && field.name !== 'transportation') {
            field.setAttribute('required', '');
        } else {
            field.removeAttribute('required');
        }
    });
}

function toggleOtherNature() {
    var select = document.getElementById("act_type");
    var container = document.getElementById("others_container");
    container.style.display = (select.value === "Others") ? "block" : "none";
    
    var otherInput = container.querySelector('input');
    if (select.value === "Others") {
        otherInput.setAttribute('required', '');
    } else {
        otherInput.removeAttribute('required');
    }
}

function validateFormData() {
    // Validate campus selection
    if (!selectedCampus) {
        alert("Please select whether this is an On-Campus or Off-Campus activity.");
        return false;
    }
    
    // Validate required fields
    const requiredFields = ['rso_name', 'act_title', 'act_type', 'objectives1', 'act_start', 'act_end', 'time_start', 'time_end', 'act_venue', 'adviser_name'];
    
    for (let fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (!field || !field.value || field.value.trim() === '') {
            const label = field.previousElementSibling?.innerText || fieldId;
            alert(`Please fill out: ${label}`);
            field?.focus();
            return false;
        }
    }
    
    // Validate date range
    const startDate = document.getElementById('act_start').value;
    const endDate = document.getElementById('act_end').value;
    
    if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
        alert("End date must be after or equal to start date.");
        return false;
    }
    
    return true;
}

// View Permit PDF
async function viewPermitPDF() {
    if (!validateFormData()) {
        return;
    }
    
    const campusType = document.getElementById('campus_type').value;
    
    if (!campusType) {
        alert("Please select On-Campus or Off-Campus activity type first.");
        return;
    }
    
    // Show loading indicator
    const modal = document.getElementById('pdfModal');
    const iframe = document.getElementById('pdfFrame');
    iframe.src = 'about:blank';
    modal.classList.add('active');
    
    // Create form data
    const formData = new FormData();
    formData.append('rso_name', document.getElementById('rso_name').value);
    formData.append('act_title', document.getElementById('act_title').value);
    formData.append('act_type', document.getElementById('act_type').value);
    formData.append('objectives1', document.getElementById('objectives1').value);
    formData.append('objectives2', document.getElementById('objectives2').value);
    formData.append('objectives3', document.getElementById('objectives3').value);
    formData.append('act_start', document.getElementById('act_start').value);
    formData.append('act_end', document.getElementById('act_end').value);
    formData.append('time_start', document.getElementById('time_start').value);
    formData.append('time_end', document.getElementById('time_end').value);
    formData.append('act_venue', document.getElementById('act_venue').value);
    formData.append('client_name', document.getElementById('client_name').value);
    formData.append('id_number', document.getElementById('id_number').value);
    formData.append('contact_number', document.getElementById('contact_number').value);
    formData.append('adviser_name', document.getElementById('adviser_name').value);
    formData.append('position', document.getElementById('position').value);
    formData.append('email', '<?php echo htmlspecialchars($client_email); ?>');
    formData.append('campus_type', campusType);
    
    const otherText = document.getElementById('other_text');
    if (otherText && otherText.value) {
        formData.append('other_text', otherText.value);
    }
    
    if (campusType === 'off') {
        const offAddress = document.getElementById('off_campus_address');
        const safetyPlan = document.getElementById('safety_plan');
        if (offAddress) formData.append('off_campus_address', offAddress.value);
        if (safetyPlan) formData.append('safety_plan', safetyPlan.value);
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
        a.download = `Activity_Permit_${document.getElementById('rso_name').value.replace(/\s/g, '_')}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    } else {
        alert('Please view the permit first before downloading.');
    }
}

function downloadPermitPDF() {
    if (currentPDFBlob) {
        const url = URL.createObjectURL(currentPDFBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Activity_Permit_${document.getElementById('rso_name').value.replace(/\s/g, '_')}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    } else {
        alert('Please generate the permit first using the View button.');
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

function submitApplication() {
    if (!validateFormData()) {
        return;
    }
    
    const activityTitle = document.getElementById('act_title').value;
    const campusText = selectedCampus === 'on' ? 'On-Campus' : 'Off-Campus';
    const confirmMsg = `Please confirm your activity permit application:\n\n` +
                       `Activity: ${activityTitle}\n` +
                       `Type: ${campusText} Activity\n` +
                       `Organization: ${document.getElementById('rso_name').value}\n\n` +
                       `Do you want to submit this application for approval?`;
    
    if (confirm(confirmMsg)) {
        const form = document.getElementById('permitForm');
        form.submit();
    }
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('act_start');
    const endDateInput = document.getElementById('act_end');
    
    if (startDateInput) startDateInput.min = today;
    if (endDateInput) endDateInput.min = today;
    
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            if (endDateInput) endDateInput.min = this.value;
        });
    }
});
</script>

</body>
</html>