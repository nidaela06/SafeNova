* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.admin-container {
    display: flex;
    width: 100%;
    min-height: 100vh;
}

/* SIDEBAR */
.admin-sidebar {
    width: 260px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.sidebar-header h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 20px;
}

.admin-menu ul {
    list-style: none;
}

.admin-menu ul li {
    margin: 10px 0;
}

.admin-menu ul li a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    padding: 12px 15px;
    border-radius: 8px;
    transition: 0.3s;
}

.admin-menu ul li a:hover,
.admin-menu ul li.active a {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.admin-menu i {
    width: 20px;
}

.sidebar-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.sidebar-footer p {
    font-size: 12px;
    margin-bottom: 10px;
}

.logout-btn {
    display: block;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
    border: none;
    cursor: pointer;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* CONTENT */
.admin-content {
    flex: 1;
    margin-left: 260px;
    padding: 30px 40px;
    overflow-y: auto;
}

.admin-header {
    margin-bottom: 30px;
}

.admin-header h1 {
    font-size: 28px;
    color: #333;
    margin-bottom: 5px;
}

.admin-header p {
    color: #667eea;
    font-size: 14px;
}

/* STATS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

.stat-card.emergency {
    background: linear-gradient(135deg, #fde8e8, #fee8e8);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
}

.stat-icon.users {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.stat-icon.reports {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.stat-icon.pending {
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.stat-card.emergency .stat-icon {
    background: linear-gradient(135deg, #d32f2f, #ff6b6b);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(211, 47, 47, 0.7); }
    50% { box-shadow: 0 0 0 10px rgba(211, 47, 47, 0); }
}

.stat-info h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 5px;
}

.stat-info p {
    color: #999;
    font-size: 12px;
}

/* SECTIONS */
.dashboard-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

.dashboard-section h2 {
    font-size: 18px;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* TABLE */
.table-container {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.admin-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.admin-table td {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.admin-table tbody tr:hover {
    background: #f9f9f9;
}

.btn-small {
    display: inline-block;
    padding: 6px 12px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12px;
    transition: 0.3s;
    border: none;
    cursor: pointer;
}

.btn-small:hover {
    background: #764ba2;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .admin-container {
        flex-direction: column;
    }
    
    .admin-sidebar {
        width: 100%;
        position: static;
        height: auto;
        min-height: auto;
    }
    
    .admin-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-header h1 {
        font-size: 20px;
    }
}