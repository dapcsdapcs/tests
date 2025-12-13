<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
 $products = [
    ['id'=>1,'name'=>'Nash3D','price'=>'35$','img'=>'https://via.placeholder.com/400x250?text=nash3d']
];
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ernyzas Home Page</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="site-header">
    <h1>Ernyzas Shop</h1>
    <nav class="nav-left">
        <div class="menu-wrap left">
            <button id="menuBtn" class="menu-button" aria-label="Menu">⋮</button>
            <div id="menuDropdown" class="menu-dropdown" aria-hidden="true">
                <a href="index.php">Home</a>
                <?php if (!$user): ?>
                    <a href="signup.php">Register</a>
                    <a href="login.php">Log In</a>
                <?php else: ?>
                    <a href="#">My Account (<?php echo htmlspecialchars($user); ?>)</a>
                    <a href="?action=logout">Log Out</a>
                <?php endif; ?>
                <a href="https://t.me/nijonico" target="_blank" rel="noopener">Telegram</a>
            </div>
        </div>
    </nav>
 </header>

<main class="container">
    <h2>Product</h2>
    <div class="products single">
        <?php foreach ($products as $p): ?>
            <div class="card big">
                <img src="<?php echo $p['img']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <h3 class="prod-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                <p class="bykey"><?php echo htmlspecialchars($p['price']); ?></p>
                <div class="actions">
                    <a class="buy-btn" href="https://t.me/nijonico" target="_blank" rel="noopener">Buy Now — Telegram</a>
                    <button id="showFeaturesBtn" class="show-features">Show Features</button>
                </div>
                <div id="featureDetails" class="feature-details" aria-hidden="true">
                    <h4>Cheat Features</h4>
                    <ul>
                        <li><strong>Aimbot</strong>.</li>
                        <li><strong>Wallhack / ESP</strong>.</li>
                        <li><strong>No Recoil</strong>.</li>
                        <li><strong>Speedhack</strong>.</li>
                        <li><strong>Triggerbot</strong>.</li>
                        <li><strong>Bunnyhop</strong>.</li>
                        <li><strong>Radarhack</strong>.</li>
                        <li><strong>Silent Aim</strong>.</li>
                        <li><strong>Spinbot</strong>.</li>
                        <li><strong>Skin Changer</strong>.</li>
                        <li><strong>No Flash / No Smoke</strong>.
                        <li><strong>Auto Shoot / Auto Fire</strong>.</li>
                        <li><strong>Fast Reload</strong>.</li>
                        <li><strong>Backtrack</strong>.</li>
                        <li><strong>Radar / Wall ESP</strong>.
                        <li><strong>Anti-AFK / Auto-Reconnect</strong>.
                    </ul>
                </div>

                <div class="payment-options">
                    <h4>Payment Options</h4>
                    <div class="payments">
                        <a class="payment-link" href="master.png" data-name="" title="Mastercard logo"></a>
                        <a class="payment-link" href="enpddY.png" data-name="" title="Visa logo"></a>
                        <a class="payment-link" href="paypal.jpg" data-name="" title="PayPal logo"></a>
                        <a class="payment-link" href="btc.jpg" data-name="" title="Bitcoin logo"></a>
                        <a class="payment-link" href="litecoin.jpg" data-name="" title="Litecoin logo"></a>
                        <a class="payment-link" href="toncoin.png" data-name="" title="Toncoin logo"></a>
                    </div>
                </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Trending section removed per request; products are user-added via modal and shown in products.json -->

    <!-- Add Product feature removed -->
</main>

<footer class="site-footer">
</footer>

<script>
// Expose login state to JS
window.APP = {};
window.APP.isLoggedIn = <?php echo $user ? 'true' : 'false'; ?>;
window.APP.username = <?php echo $user ? json_encode($user) : 'null'; ?>;

// Dropdown menu toggle and entrance animations
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

// Add loaded class to trigger CSS animations
document.addEventListener('DOMContentLoaded', function(){
    setTimeout(function(){ document.body.classList.add('is-loaded'); }, 60);
});
// Add-product JS removed (feature disabled)

// Initialize payment logos: if href points to an image file, inject an <img>, otherwise show the data-name
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.payment-link').forEach(function(el){
        var href = el.getAttribute('href') || '';
        var name = el.getAttribute('data-name') || '';
        if (href && href !== '#' && /\.(png|jpg|jpeg|svg|gif)(\?|$)/i.test(href)) {
            var img = document.createElement('img');
            img.src = href;
            img.alt = name;
            el.textContent = '';
            el.appendChild(img);
        } else {
            el.textContent = name;
        }
    });
});


// Show features toggle
(function(){
    var btn = document.getElementById('showFeaturesBtn');
    var details = document.getElementById('featureDetails');
    if (!btn || !details) return;
    btn.addEventListener('click', function(){
        var hidden = details.getAttribute('aria-hidden') === 'true';
        details.setAttribute('aria-hidden', hidden ? 'false' : 'true');
        details.style.display = hidden ? 'block' : 'none';
        btn.textContent = hidden ? 'Hide Features' : 'Show Features';
        if (hidden) details.scrollIntoView({behavior:'smooth', block:'center'});
    });
})();
</script>
</body>
</html>
