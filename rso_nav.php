<!-- Updated Navbar - Consistent Design -->
<nav class="main-navbar">
    <div class="nav-brand">
        <a href="index.php">BSU ORG-TRACK</a>
    </div>
    <ul class="nav-links">
        <li><button class="<?php echo $current_page == 'dashboard_rso.php' ? 'active' : ''; ?>" onclick="switchTab('profile')">Organization Profile</button></li>
        <li><button class="<?php echo $current_page == 'dashboard_rso.php' ? '' : ''; ?>" onclick="switchTab('password')">Security</button></li>
        <li><button class="<?php echo $current_page == 'dashboard_rso.php' ? '' : ''; ?>" onclick="switchTab('applications')">Applications</button></li>
        <li><a href="logout.php" class="signin-btn">Logout</a></li>
    </ul>
</nav>

<style>
    .main-navbar {
        background: #2d6a4f;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        font-family: 'Inter', system-ui, sans-serif;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .nav-brand a {
        color: white;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.3rem;
        letter-spacing: 1px;
        transition: opacity 0.2s;
    }

    .nav-brand a:hover {
        opacity: 0.9;
    }

    .nav-links {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 25px;
        margin: 0;
        padding: 0;
    }

    .nav-links li a {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s;
        padding: 8px 0;
        position: relative;
    }

    .nav-links li a:hover {
        color: white;
    }

    .nav-links li a.active {
        font-weight: 600;
        text-decoration: underline;
    }

    .nav-links li a.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #d8f3dc;
        border-radius: 2px;
    }

    .signin-btn {
        background: white;
        color: #2d6a4f !important;
        padding: 8px 20px !important;
        border-radius: 6px;
        font-weight: 700 !important;
        transition: all 0.2s !important;
    }

    .signin-btn:hover {
        background: #f0f0f0;
        transform: translateY(-1px);
    }

    .signin-btn.active::after {
        display: none;
    }

    /* User info in navbar */
    .user-info-nav {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-left: 20px;
    }

    .user-greeting {
        color: #d8f3dc;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Important: Adds space so content doesn't hide under the nav */
    .nav-spacer {
        height: 70px;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .main-navbar {
            padding: 0 4%;
            height: auto;
            min-height: 60px;
            flex-direction: column;
            padding: 12px 4%;
        }

        .nav-brand {
            margin-bottom: 8px;
        }

        .nav-brand a {
            font-size: 1.1rem;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .nav-links li a {
            font-size: 0.8rem;
            padding: 4px 0;
        }

        .nav-spacer {
            height: auto;
            min-height: 95px;
        }

        .user-info-nav {
            margin-left: 0;
        }
    }

    @media (max-width: 480px) {
        .nav-links {
            gap: 12px;
        }
        
        .nav-links li a {
            font-size: 0.7rem;
        }
    }
</style>

<!-- Conditional spacer - adjusts based on if user is logged in -->
<div class="nav-spacer"></div>

<script>
    // Optional: Add this to handle active state for dashboard tabs
    document.addEventListener('DOMContentLoaded', function() {
        // For dashboard.php - highlight Dashboard link when on dashboard
        const currentPage = window.location.pathname.split('/').pop();
        const dashboardLink = document.querySelector('.nav-links a[href="dashboard.php"]');
        
        if (dashboardLink && currentPage === 'dashboard.php') {
            dashboardLink.classList.add('active');
        }
    });
</script>