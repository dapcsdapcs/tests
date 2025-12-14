<?php
session_start();
require __DIR__ . '/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.status = 'pending' ORDER BY o.created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Notifications</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="site-header">
    <nav class="nav-left">
        <div class="menu-wrap left">
            <button id="menuBtn" class="menu-button" aria-label="Menu">⋮</button>
            <div id="menuDropdown" class="menu-dropdown" aria-hidden="true">
                <a href="index.php">Home</a>
                <a href="admin.php">Admin Panel</a>
                <a href="?action=logout">Log Out</a>
            </div>
        </div>
    </nav>
    <div class="nav-right">
        <span class="welcome">Notifications</span>
    </div>
</header>

<main class="container">
    <h2>New Orders</h2>
    <?php if (empty($orders)): ?>
        <p>No new orders.</p>
    <?php else: ?>
        <div class="products">
            <?php foreach ($orders as $o): ?>
                <div class="card">
                    <p>User: <?php echo htmlspecialchars($o['username']); ?></p>
                    <p>Product: <?php echo htmlspecialchars($o['product_id']); ?></p>
                    <p>Features: <?php echo htmlspecialchars($o['features']); ?></p>
                    <p>Total: $<?php echo htmlspecialchars($o['total_price']); ?></p>
                    <p>Contact: <?php echo htmlspecialchars($o['contact_type']); ?> - <?php echo htmlspecialchars($o['contact_value']); ?></p>
                    <p>Time: <?php echo htmlspecialchars($o['created_at']); ?></p>
                    <a href="admin.php">Manage</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
document.addEventListener('click', function(e){
    var btn = document.getElementById('menuBtn');
    var dd = document.getElementById('menuDropdown');
    if (!btn) return;
    if (btn.contains(e.target)) {
        var hidden = dd.getAttribute('aria-hidden') === 'true';
        dd.style.display = hidden ? 'block' : 'none';
        dd.setAttribute('aria-hidden', hidden ? 'false' : 'true');
    } else {
        if (!dd.contains(e.target)) {
            dd.style.display = 'none';
            dd.setAttribute('aria-hidden','true');
        }
    }
});
</script>
</body>
</html>