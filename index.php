<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | BSU ORG-TRACK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-light: #f4f7f5;
            --accent: #40916c;
        }

        body { 
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0; 
            background: var(--bsu-light); 
            color: #333;
        }

        /* Hero Section */
        .hero {
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(rgba(45, 106, 79, 0.9), rgba(45, 106, 79, 0.8)), 
                        url('https://images.unsplash.com/photo-1523050335392-9ae574d64b32?auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 0 20px;
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

        .hero-content h2 { font-size: 2.8rem; margin-bottom: 10px; }
        .hero-content p { font-size: 1.1rem; max-width: 600px; margin: 0 auto 30px; line-height: 1.6; opacity: 0.9; }

        .btn {
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: transform 0.2s;
        }
        .btn-primary { background: white; color: var(--bsu-green); }
        .btn-secondary { border: 2px solid white; color: white; }
        .btn:hover { transform: translateY(-3px); }

        /* Workflow Section */
        .workflow {
            padding: 60px 10%;
            background: white;
            text-align: center;
        }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .step-card {
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #fff;
        }
        .step-number {
            background: var(--bsu-green);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-weight: bold;
        }

        /* Forms Section */
        .resources {
            padding: 40px 10%;
            background: var(--bsu-light);
        }
        .resources h2 { text-align: center; margin-bottom: 30px; }
        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .resource-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        footer { text-align: center; padding: 30px; font-size: 0.8rem; color: #888; }
    </style>
</head>
<body>

    <?php include "navbar.php"; ?>

    <section class="hero">
        <div class="hero-content">
            <h2>Start Your Journey with SOAU</h2>
            <p>Welcome to the official portal. To begin managing activities and filing permits, your organization must first register its account.</p>
            <a href="org_registration.php" class="btn btn-primary">Register Your Organization</a>
        </div>
    </section>

    <section class="workflow">
        <h2>How It Works</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Register Org</h3>
                <p>Create an account for your organization. This establishes your profile and SOAU status.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Verify Status</h3>
                <p>Once registered, wait for SOAU to approve your organization’s activation.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Register Activity</h3>
                <p>Logged-in organizations can now access the Activity Permit module to start filings.</p>
            </div>
        </div>
    </section>

    <section class="resources">
        <h2>Downloadable Templates</h2>
        <div class="resource-grid">
            <div class="resource-card">
                <h3>In-Campus Permit</h3>
                <p>Standardized form for within-university activities.</p>
                <a href="d.pdf" download class="btn btn-primary">Download PDF</a>
            </div>
            <div class="resource-card">
                <h3>Out-Campus Permit</h3>
                <p>Standardized form for off-campus activities.</p>
                <a href="d.pdf" download class="btn btn-primary">Download PDF</a>
            </div>
            <div class="resource-card">
                <h3>Accomplishment Report</h3>
                <p>Post-activity evaluation form.</p>
                <a href="d.pdf" download class="btn btn-primary">Download PDF</a>
            </div>
        </div>
    </section>

    <footer>
        &copy; 2026 Benguet State University - ORG-TRACK
    </footer>

</body>
</html>