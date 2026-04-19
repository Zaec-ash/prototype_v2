<!-- Updated Navbar - Larger Design -->
<nav class="main-navbar">
    <div class="nav-brand">
        <a href="dashboard_rso.php">BSU ORG-TRACK</a>
    </div>
    <ul class="nav-links">
               <li><a href="logout.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>">⏻ Sign Out</a></li>
    </ul>
</nav>

<style>
    .main-navbar {
        background: #2d6a4f;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        font-weight: 800;
        font-size: 1.6rem;
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
        gap: 35px;
        margin: 0;
        padding: 0;
    }

    .nav-links li a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        font-size: 1.05rem;
        font-weight: 600;
        transition: all 0.2s;
        padding: 10px 0;
        position: relative;
    }

    .nav-links li a:hover {
        color: white;
    }

    .nav-links li a.active {
        color: white;
        font-weight: 800;
    }

    .nav-links li a.active::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        right: 0;
        height: 3px;
        background: #d8f3dc;
        border-radius: 2px;
    }

    .signin-btn {
        background: white;
        color: #2d6a4f !important;
        padding: 10px 24px !important;
        border-radius: 8px;
        font-weight: 700 !important;
        transition: all 0.2s !important;
    }

    .signin-btn:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
    }

    .signin-btn.active::after {
        display: none;
    }

    /* User info in navbar */
    .user-info-nav {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: 25px;
    }

    .user-greeting {
        color: #d8f3dc;
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Important: Adds space so content doesn't hide under the nav */
    .nav-spacer {
        height: 80px;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .main-navbar {
            padding: 0 4%;
            height: auto;
            min-height: 70px;
            flex-direction: column;
            padding: 15px 4%;
        }

        .nav-brand {
            margin-bottom: 12px;
        }

        .nav-brand a {
            font-size: 1.3rem;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .nav-links li a {
            font-size: 0.9rem;
            padding: 6px 0;
        }

        .nav-spacer {
            height: auto;
            min-height: 110px;
        }

        .user-info-nav {
            margin-left: 0;
        }
    }

    @media (max-width: 480px) {
        .nav-links {
            gap: 15px;
        }
        
        .nav-links li a {
            font-size: 0.8rem;
        }
        
        .signin-btn {
            padding: 6px 16px !important;
        }
    }
</style>

<!-- Conditional spacer - adjusts based on if user is logged in -->
<div class="nav-spacer"></div>

<script>
    // Handle active state for dashboard tabs
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const dashboardLink = document.querySelector('.nav-links a[href="dashboard.php"]');
        
        if (dashboardLink && currentPage === 'dashboard.php') {
            dashboardLink.classList.add('active');
        }
    });
</script>