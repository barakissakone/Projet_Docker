<?php
session_start();

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'rdv_db';
$user = getenv('DB_USER') ?: 'rdv_user';
$pass = getenv('DB_PASSWORD') ?: 'rdv_password';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, nom, password_hash FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow && password_verify($password, $userRow['password_hash'])) {
        $_SESSION['user_id']  = $userRow['id'];
        $_SESSION['user_nom'] = $userRow['nom'];
        header('Location: rdv.php'); // espace de prise de rendez-vous
        exit;
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - RDV Médicaux</title>
    <style>
        /* mêmes styles que register, adaptés */
        * { box-sizing: border-box; }
        body.auth-body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #1f2933;
            background: radial-gradient(circle at top left, #3b82f6, #1f2937 50%, #020617);
        }
        .topbar {
            height: 56px;
            background: #111827;
            color: #f9fafb;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 20px;
        }
        .topbar a { color:#e5e7eb; text-decoration:none; font-weight:500; }
        .topbar-left { font-weight:700; letter-spacing:0.03em; }
        .auth-page {
            min-height: calc(100vh - 56px);
            display:flex; justify-content:center; align-items:center;
            padding:24px;
        }
        .auth-card {
            background: rgba(15,23,42,0.9);
            border-radius:16px;
            padding:28px 26px;
            max-width:420px; width:100%;
            box-shadow:0 20px 40px rgba(0,0,0,0.45);
            color:#e5e7eb;
        }
        .auth-header h1 { margin:0 0 4px; font-size:26px; }
        .auth-header p { margin:0 0 16px; font-size:14px; color:#9ca3af; }
        .form-group { margin-bottom:14px; }
        label { display:block; margin-bottom:5px; font-size:14px; font-weight:500; }
        input[type="email"], input[type="password"] {
            width:100%; padding:9px 11px;
            border-radius:10px; border:1px solid #4b5563;
            outline:none; background:#020617; color:#e5e7eb; font:inherit;
        }
        input::placeholder { color:#6b7280; }
        input:focus {
            border-color:#3b82f6;
            box-shadow:0 0 0 1px rgba(59,130,246,0.5);
        }
        .btn-primary {
            width:100%; margin-top:8px;
            padding:10px 14px; border-radius:999px; border:none;
            font-size:15px; font-weight:600; cursor:pointer;
            background:linear-gradient(90deg,#3b82f6,#22c55e);
            color:#f9fafb;
        }
        .alert {
            padding:10px 12px; border-radius:10px;
            margin-bottom:14px; font-size:14px;
        }
        .alert-error {
            background: rgba(248,113,113,0.15);
            color:#fecaca; border:1px solid rgba(248,113,113,0.5);
        }
        .auth-footer {
            margin-top:16px; text-align:center;
            font-size:14px; color:#9ca3af;
        }
        .auth-footer a { color:#60a5fa; text-decoration:none; font-weight:500; }
    </style>
</head>
<body class="auth-body">
<header class="topbar">
    <div class="topbar-left">RDV Médicaux</div>
    <div><a href="register.php">Créer un compte</a></div>
</header>

<main class="auth-page">
    <section class="auth-card">
        <div class="auth-header">
            <h1>Connexion</h1>
            <p>Accédez à vos rendez-vous et prenez de nouveaux créneaux en ligne.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input id="email" type="email" name="email" placeholder="vous@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Se connecter</button>
        </form>

        <p class="auth-footer">
            Pas encore de compte ? <a href="register.php">S’inscrire</a>
        </p>
    </section>
</main>
</body>
</html>
