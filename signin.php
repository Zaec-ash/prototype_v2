<?php
session_start();

// Define role-specific redirects
$role_redirects = [
    'rso_member' => 'dashboard_rso.php',
    'rso_officer' => 'dashboard_officer.php',
    'soau_staff' => 'dashboard_staff.php',
    'admin' => 'dashboard_admin.php'
];

// Check if user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $role = $_SESSION['user_role'] ?? 'rso_member';
    $redirect_page = $role_redirects[$role] ?? 'dashboard_rso.php';
    header("Location: $redirect_page");
    exit();
}

$error_message = '';
$selected_role_error = '';

// For prototype - login attempt with validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate role is selected
    if (empty($selected_role)) {
        $selected_role_error = 'Please select a role to continue.';
    }
    // Validate email based on role
    elseif (($selected_role === 'soau_staff' || $selected_role === 'admin') && !str_ends_with($email, '@bsu.edu.ph')) {
        $error_message = 'SOAU Staff and Admin accounts must use a valid @bsu.edu.ph email address.';
    } 
    // For RSO roles, any email is allowed
    elseif (($selected_role === 'rso_member' || $selected_role === 'rso_officer') && empty($email)) {
        $error_message = 'Please enter your email address.';
    }
    else {
        // Store user info in session
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $username;
        $_SESSION['user_role'] = $selected_role;
        $_SESSION['user_department'] = $_POST['department'] ?? '';
        
        // Redirect based on role
        $redirect_page = $role_redirects[$selected_role] ?? 'dashboard_rso.php';
        header("Location: $redirect_page");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | SOAU BSU Portal</title>
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
            --rso-color: #2d6a4f;
            --officer-color: #e67e22;
            --staff-color: #3498db;
            --admin-color: #e74c3c;
            --error: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Inter', -apple-system, 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-page) 0%, var(--bsu-mint) 100%);
            color: var(--text-dark); 
            margin: 0; 
            padding: 0; 
            line-height: 1.5; 
            min-height: 100vh;
        }

        .main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }

        .login-container {
            max-width: 550px;
            width: 100%;
            margin: 0 auto;
        }

        .portal-card {
            background: #ffffff; 
            border-radius: 20px; 
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.15);
            overflow: hidden; 
            border: 1px solid var(--border-color);
        }

        .portal-header { 
            background: linear-gradient(135deg, var(--bsu-dark) 0%, var(--bsu-green) 100%);
            padding: 32px 40px; 
            color: white; 
            text-align: center;
        }
        
        .portal-header h1 { 
            margin: 0; 
            font-size: 1.5rem; 
            font-weight: 700; 
        }
        
        .portal-header p {
            margin: 8px 0 0 0;
            font-size: 0.875rem;
            opacity: 0.85;
        }

        .card-body { 
            padding: 40px; 
        }

        .form-section { 
            margin-bottom: 24px; 
        }

        .section-label {
            display: block; 
            font-size: 0.875rem; 
            font-weight: 700; 
            color: var(--bsu-green);
            margin-bottom: 16px; 
        }

        .field-group { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
            margin-bottom: 20px; 
        }

        label { 
            font-size: 0.875rem; 
            font-weight: 600; 
            color: var(--text-dark); 
        }
        
        input, select {
            padding: 14px 16px; 
            border: 1px solid var(--border-color);
            border-radius: 12px; 
            font-size: 1rem; 
            background: #fff;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        input:focus, select:focus { 
            outline: none; 
            border-color: var(--bsu-green); 
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.15); 
        }

        .required { 
            color: #d93025; 
            margin-left: 2px; 
        }

        .error-message {
            background: #fee2e2;
            color: var(--error);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-bottom: 20px;
            border-left: 4px solid var(--error);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 8px;
        }

        .role-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .role-card.selected {
            border-width: 2px;
        }

        .role-card input {
            display: none;
        }

        .role-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .role-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .role-desc {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .role-requirement {
            font-size: 0.65rem;
            margin-top: 6px;
            padding: 4px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .role-requirement.any-email { background: #e8f5e9; color: var(--rso-color); }
        .role-requirement.bsu-email { background: #e8f0fe; color: var(--staff-color); }

        .role-card.rso.selected { border-color: var(--rso-color); background: #e8f5e9; }
        .role-card.staff.selected { border-color: var(--staff-color); background: #e8f0fe; }
        .role-card.admin.selected { border-color: var(--admin-color); background: #fde8e8; }

        .btn {
            width: 100%;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary { 
            background: var(--bsu-green); 
            color: white; 
        }
        
        .btn-primary:hover {
            background: var(--bsu-dark);
            transform: translateY(-2px);
        }

        .portal-footer {
            padding: 24px 40px;
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .footer-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .footer-text a {
            color: var(--bsu-green);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .register-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .register-link {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--staff-color);
            text-decoration: none;
            font-weight: 600;
        }

        .demo-badge {
            background: var(--bsu-mint);
            color: var(--bsu-dark);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-align: center;
            margin-top: 16px;
            display: inline-block;
        }

        .info-note {
            background: #e8f0fe;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 0.7rem;
            color: var(--staff-color);
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .role-options {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            .card-body { padding: 24px; }
            .portal-header { padding: 24px; }
            .portal-footer { padding: 20px 24px; }
            .register-links { flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>

<?php 
    if(file_exists("navbar.php")) { 
        include "navbar.php"; 
    } else {
        echo '<nav style="background: var(--bsu-dark); padding: 15px 40px; color: white;">
                <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                    <strong>🏛️ BSU ORG-Track</strong>
                    <div>
                        <a href="index.php" style="color: white; text-decoration: none; margin-left: 20px;">Home</a>
                        <a href="about.php" style="color: white; text-decoration: none; margin-left: 20px;">About</a>
                    </div>
                </div>
              </nav>';
    }
?>

<div class="main-content">
    <div class="login-container">
        <div class="portal-card">
            <header class="portal-header">
                <h1>🏛️ BSU ORG-TRACK</h1>
                <p>Student Organizations and Affairs Unit</p>
            </header>

            <div class="card-body">
                <!-- Error Messages -->
                <?php if ($error_message): ?>
                    <div class="error-message">
                        <span>⚠️</span> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($selected_role_error): ?>
                    <div class="error-message">
                        <span>⚠️</span> <?php echo $selected_role_error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" id="loginForm">
                    <div class="form-section">
                        <span class="section-label">Sign In to Your Account</span>
                        
                        <div class="field-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" id="email" placeholder="your@email.com" required>
                            <small id="emailHint" style="font-size: 0.7rem; color: var(--text-muted);"></small>
                        </div>
                        
                        <div class="field-group">
                            <label>Password <span class="required">*</span></label>
                            <input type="password" name="password" placeholder="Enter password" value="password123" required>
                        </div>

                        <div class="field-group">
                            <label>Select Role <span class="required">*</span></label>
                            <div class="role-options">
                                <div class="role-card rso" onclick="selectRole('rso_member')">
                                    <input type="radio" name="role" value="rso_member" required>
                                    <div class="role-icon">👥</div>
                                    <div class="role-title">RSO Member</div>
                                    <div class="role-desc">Organization Member / Officer</div>
                                    <div class="role-requirement any-email">✓ Any email allowed</div>
                                </div>
                                <div class="role-card staff" onclick="selectRole('soau_staff')">
                                    <input type="radio" name="role" value="soau_staff">
                                    <div class="role-icon">📋</div>
                                    <div class="role-title">SOAU Staff</div>
                                    <div class="role-desc">Student Affairs Staff</div>
                                    <div class="role-requirement bsu-email">📧 Requires @bsu.edu.ph email</div>
                                </div>
                                <div class="role-card admin" onclick="selectRole('admin')">
                                    <input type="radio" name="role" value="admin">
                                    <div class="role-icon">⚙️</div>
                                    <div class="role-title">Admin</div>
                                    <div class="role-desc">Security Administrator</div>
                                    <div class="role-requirement bsu-email">📧 Requires @bsu.edu.ph email</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Sign In →
                    </button>
                </form>

                <div class="info-note">
                    💡 <strong>Demo Mode:</strong> Select a role first. Staff/Admin require @bsu.edu.ph email.
                </div>
            </div>

            <div class="portal-footer">
                <div class="footer-text">
                    New to BSU ORG-Track? <a href="org_registration.php">Register Organization</a>
                </div>
                <div class="register-links">
                    <div class="register-link">🔐 <a href="register_staff.php">Register as SOAU Staff</a></div>
                    <div class="register-link">⚙️ <a href="register_admin.php">Register as Admin</a></div>
                </div>
                <div class="demo-info">
                    <span class="demo-badge">🎭 Demo Mode - Select role to access different dashboards</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedRole = null;

    function selectRole(roleValue) {
        selectedRole = roleValue;
        
        // Remove selected class from all role cards
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to clicked card
        const selectedCard = event.currentTarget;
        selectedCard.classList.add('selected');
        
        // Check the radio button
        const radio = selectedCard.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Update email hint based on selected role
        const emailInput = document.getElementById('email');
        const emailHint = document.getElementById('emailHint');
        
        if (roleValue === 'soau_staff' || roleValue === 'admin') {
            emailHint.innerHTML = '⚠️ Must use a valid @bsu.edu.ph email address';
            emailHint.style.color = '#e74c3c';
        } else {
            emailHint.innerHTML = '✓ Any email address is accepted';
            emailHint.style.color = '#10b981';
        }
    }
    
    // Add validation before form submit
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const selectedRoleRadio = document.querySelector('input[name="role"]:checked');
        const email = document.getElementById('email').value;
        
        if (!selectedRoleRadio) {
            e.preventDefault();
            alert('Please select a role before signing in.');
            return false;
        }
        
        const role = selectedRoleRadio.value;
        
        if ((role === 'soau_staff' || role === 'admin') && !email.endsWith('@bsu.edu.ph')) {
            e.preventDefault();
            alert('SOAU Staff and Admin accounts must use a valid @bsu.edu.ph email address.');
            return false;
        }
        
        return true;
    });
</script>

</body>
</html>