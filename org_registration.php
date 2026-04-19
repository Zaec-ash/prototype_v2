<?php
// At the very top of rso_registration.php (or whatever you name this file)
session_start(); // Optional: for CSRF token later

// You can add validation/redirect logic here if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Organization | BSU ORG-Track</title>
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-dark: #1b4332;
            --bsu-mint: #d8f3dc;
            --text-dark: #1a1c1e;
            --text-muted: #5f6368;
            --border-color: #dadce0;
            --bg-page: #f0f2f5;
        }

        body { 
            font-family: 'Inter', -apple-system, 'Segoe UI', sans-serif; 
            background-color: var(--bg-page); 
            color: var(--text-dark); 
            margin: 0; 
            padding: 40px 20px; 
            line-height: 1.5; 
        }

        .portal-card {
            background: #ffffff; 
            max-width: 800px; 
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
        
        .progress-bar {
            background: #f8f9fa; 
            padding: 12px 40px; 
            border-bottom: 1px solid var(--border-color);
            font-size: 0.75rem; 
            text-transform: uppercase; 
            font-weight: 700; 
            color: var(--bsu-green);
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

        input[type="file"] {
            padding: 10px 12px;
            background: #fafafa;
        }

        input[type="file"]:hover {
            background: #f5f5f5;
            cursor: pointer;
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

        .file-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-top: 8px;
        }

        .file-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-badge {
            background: var(--bsu-mint);
            color: var(--bsu-green);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .card-body { padding: 24px; }
            .portal-header, .progress-bar, .portal-footer { padding-left: 24px; padding-right: 24px; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
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
        <h1>Recognized Student Organization (RSO) Registration</h1>
        <p>Complete the form below to initiate your SOAU registration</p>
    </header>
    <div class="progress-bar">Organization Registration Portal</div>

    <div class="card-body">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            
            <!-- Organization Information Section -->
            <div class="form-section">
                <span class="section-label">Organization Information</span>
                <div class="form-grid">
                    <div class="field-group full-width">
                        <label>Organization Name <span class="required">*</span></label>
                        <input type="text" name="org_name" placeholder="Enter full organization name" required>
                    </div>
                    <div class="field-group">
                        <label>Adviser Name <span class="required">*</span></label>
                        <input type="text" name="adviser_name" placeholder="Full name of faculty adviser" required>
                    </div>
                    <div class="field-group">
                        <label>Contact Number <span class="required">*</span></label>
                        <input type="tel" name="contact" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
                        <div class="hint-text">Format: 09XX-XXX-XXXX</div>
                    </div>
                    <div class="field-group full-width">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" placeholder="organization@gmail.com" required>
                    </div>
                    <div class="field-group full-width">
                        <label>Registration Type <span class="required">*</span></label>
                        <select id="org_type" name="org_type" required>
                            <option value="" disabled selected>Select registration type...</option>
                            <option value="existing">Existing Organization (Renewal/Update)</option>
                            <option value="new">New Organization (First-time Registration)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Document Requirements Section -->
            <div class="form-section">
                <span class="section-label">Document Requirements</span>
                <div class="file-list">
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>1. Letter of Application (PDF)</span>
                    </div>
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>2. Accomplished Application Form (PDF)</span>
                    </div>
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>3. Photocopy of BSU ID Cards (PDF)</span>
                    </div>
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>4. Tentative Action Plan (PDF)</span>
                    </div>
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>5. List of Members (Min. 35 BSU Undergraduates) (PDF)</span>
                    </div>
                    <div class="file-item">
                        <span class="file-badge">Required</span>
                        <span>6. Ratified Constitutions and By-Laws (PDF)</span>
                    </div>
                </div>

                <div class="form-grid" style="margin-top: 20px;">
                    <div class="field-group full-width">
                        <label>1. Letter of Application <span class="required">*</span></label>
                        <input type="file" name="file1" accept=".pdf" required>
                    </div>
                    <div class="field-group full-width">
                        <label>2. Accomplished Application Form <span class="required">*</span></label>
                        <input type="file" name="file2" accept=".pdf" required>
                    </div>
                    <div class="field-group full-width">
                        <label>3. Photocopy of BSU ID Cards <span class="required">*</span></label>
                        <input type="file" name="file3" accept=".pdf" required>
                    </div>
                    <div class="field-group full-width">
                        <label>4. Tentative Action Plan <span class="required">*</span></label>
                        <input type="file" name="file4" accept=".pdf" required>
                    </div>
                    <div class="field-group full-width">
                        <label>5. List of Members (Min. 35 BSU Undergraduates) <span class="required">*</span></label>
                        <input type="file" name="file5" accept=".pdf" required>
                    </div>
                    <div class="field-group full-width">
                        <label>6. Ratified Constitutions and By-Laws <span class="required">*</span></label>
                        <input type="file" name="file6" accept=".pdf" required>
                    </div>
                </div>
            </div>

            <!-- Additional Requirements for New Organizations (Dynamic) -->
            <div id="new_org_fields" class="sub-form" style="display: none;">
                <span class="section-label" style="margin-top: 0;">Additional Requirements for New Organizations</span>
                <div class="field-group full-width">
                    <label>7. Description of Proposed Organization <span class="required">*</span></label>
                    <textarea name="org_desc" rows="4" placeholder="Describe the purpose, goals, and objectives of your organization..."></textarea>
                </div>
                <div class="field-group full-width">
                    <label>8. Description of Institution & SEC Registration</label>
                    <input type="file" name="file8" accept=".pdf">
                    <div class="hint-text">Upload SEC Registration certificate (if applicable)</div>
                </div>
            </div>

            <div class="portal-footer">
                <a href="index.php" class="btn btn-outline">← Back</a>
                <button type="submit" class="btn btn-primary" onclick="return confirmRegistration()">Submit Registration →</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleNewOrgFields() {
        const typeSelect = document.getElementById('org_type');
        const newOrgDiv = document.getElementById('new_org_fields');
        const isNewOrg = typeSelect.value === 'new';
        
        newOrgDiv.style.display = isNewOrg ? 'block' : 'none';
        
        // Toggle required attributes for new org fields
        const descTextarea = document.querySelector('textarea[name="org_desc"]');
        const file8 = document.querySelector('input[name="file8"]');
        
        if (isNewOrg) {
            if (descTextarea) descTextarea.setAttribute('required', '');
            // file8 is optional, so no required attribute
        } else {
            if (descTextarea) descTextarea.removeAttribute('required');
            if (file8) file8.removeAttribute('required');
        }
    }
    
    function confirmRegistration() {
        // Validate that registration type is selected
        const orgType = document.getElementById('org_type').value;
        if (!orgType) {
            alert("Please select a registration type (Existing or New Organization).");
            return false;
        }
        
        // Validate required files are selected
        const fileInputs = document.querySelectorAll('input[type="file"][required]');
        for (let input of fileInputs) {
            if (!input.files || input.files.length === 0) {
                alert("Please upload all required documents.");
                return false;
            }
        }
        
        // Show confirmation dialog
        const orgName = document.querySelector('input[name="org_name"]').value;
        const confirmMsg = `Are you sure you want to register "${orgName}"?\n\nPlease review all information before submitting. We will send your designated password after reviewing your application.Thank you`;
        
        return confirm(confirmMsg);
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('org_type');
        if (typeSelect) {
            typeSelect.addEventListener('change', toggleNewOrgFields);
        }
    });
</script>

</body>
</html>