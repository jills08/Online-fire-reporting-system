<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-mark"></span>
        <h3>OFRS COMMAND</h3>
    </div>
    
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        
        <a href="manage-teams.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage-teams.php' ? 'active' : '' ?>">
            <i class="fas fa-users-cog"></i> Fire Teams
        </a>
        
        <a href="all-requests.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'all-requests.php' ? 'active' : '' ?>">
            <i class="fas fa-list-ul"></i> All Reports
        </a>
        
        <a href="search-report.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'search-report.php' ? 'active' : '' ?>">
            <i class="fas fa-search-location"></i> Trace Incident
        </a>
        
        <a href="bwdates-report-search.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'bwdates-report-search.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i> Analytics
        </a>

        <div class="sidebar-footer">
            <a href="profile.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
            
            <a href="logout.php" class="nav-link logout">
                <i class="fas fa-power-off"></i> System Logout
            </a>
        </div>
    </nav>
</aside>

<style>
    .sidebar { 
        width: 260px; background: rgba(4, 10, 20, 0.95); 
        border-right: 1px solid var(--line); display: flex; 
        flex-direction: column; min-height: 100vh; 
        position: fixed; left: 0; top: 0; z-index: 1000;
    }
    
    .sidebar-brand { padding: 30px 20px; border-bottom: 1px solid var(--line); margin-bottom: 20px; }
    .sidebar-brand h3 { font-family: 'Orbitron'; color: var(--orange); font-size: 16px; margin: 0; letter-spacing: 1px; }
    
    .sidebar-nav { flex: 1; padding: 0 15px; display: flex; flex-direction: column; }
    
    .nav-link { 
        display: flex; align-items: center; padding: 14px 18px; color: #99a7c2; 
        text-decoration: none; border-radius: 12px; margin-bottom: 10px; 
        font-size: 14px; transition: 0.3s; gap: 12px;
    }
    
    .nav-link i { font-size: 18px; width: 25px; text-align: center; }
    .nav-link:hover { background: rgba(255,107,61,0.08); color: white; }
    
    .nav-link.active { 
        background: rgba(255,107,61,0.15); color: white; 
        border: 1px solid rgba(255,107,61,0.3); 
        box-shadow: 0 0 15px rgba(255, 107, 61, 0.1); 
    }
    
    .sidebar-footer { 
        margin-top: auto; 
        padding: 20px 0; 
        border-top: 1px solid var(--line); 
    }
    
    .logout { color: #ff5d73; border: 1px solid rgba(255, 93, 115, 0.1); margin-top: 10px; }
    .logout:hover { background: rgba(255, 93, 115, 0.1); color: #ff5d73; }
</style>