<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin | BSU ORG-Track</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-dark: #1b4332;
            --admin-color: #e74c3c;
            --text-dark: #1a1c1e;
            --text-muted: #5f6368;
            --border-color: #dadce0;
            --bg-page: #f0f2f5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-page);
            color: var(--text-dark); 
        }

        .main-navbar {
            background: #2d6a4f;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-brand a { color: white; text-decoration: none; font-weight: 800; font-size: 1.4rem; }
        .nav-links { list-style: none; display: flex; gap: 25px; }
        .nav-links li a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .nav-spacer { height: 70px; }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--admin-color) 0%, #c0392b 100%);
            padding: 32px;
            color: white;
            text-align: center;
        }

        .card-header h1 { font-size: 1.5rem; margin-bottom: 8px; }
        .card-body { padding: 32px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px; }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .form-group input:focus { outline: none; border-color: var(--admin-color); }

        .btn {
            width: 100%;
            padding: 14px;
            background: var(--admin-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { background: #c0392b; }

        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .alert-warning { background: #fed7aa; color: #92400e; }
    </style>
</head>
<body>

<?php 
    if(file_exists("navbar_reg.php")) { 
        include "navbar_reg.php"; 
    }
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>⚙️ Register as Security Admin</h1>
            <p>Create administrator account for Security management</p>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                ⚠️ <strong>Restricted Access:</strong>Security Admin accounts have full account control. Registration requires authorization.
            </div>
            
            <form method="POST" action="signin.php">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="fullname" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" required placeholder="admin@bsu.edu.ph">
                    <small style="color: var(--text-muted);">Must end with @bsu.edu.ph</small>
                </div>
                <div class="form-group">
                    <label>Create Password *</label>
                    <input type="password" name="password" required placeholder="Min. 6 characters">
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" required placeholder="Confirm your password">
                </div>
                <button type="submit" class="btn">Register Admin Account →</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="signin.php" style="color: var(--admin-color);">← Back to Sign In</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>