<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta girin.';
    } else {
        try {
            $pdo = require __DIR__ . '/config.php';
            $stmt = $pdo->prepare('SELECT username, password FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['username'];
                header('Location: index.php');
                exit;
            }
            $error = 'E-posta veya şifre hatalı.';
        } catch (Exception $e) {
            $error = 'Veritabanı hatası: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Giriş</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="auth container">
    <h2>Giriş Yap</h2>
    <?php if ($error): ?><div class="errors"><p><?php echo htmlspecialchars($error); ?></p></div><?php endif; ?>
    <form method="post" action="login.php" class="form">
        <label>E-posta<br><input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"></label>
        <label>Şifre<br><input type="password" name="password"></label>
        <button type="submit">Giriş</button>
    </form>
    <p>Hesabınız yok mu? <a href="signup.php">Kayıt ol</a></p>
</main>
</body>
</html>
