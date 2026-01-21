<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$userId  = $_SESSION['user_id'];
$userNom = $_SESSION['user_nom'];

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'rdv_db';
$user = getenv('DB_USER') ?: 'rdv_user';
$pass = getenv('DB_PASSWORD') ?: 'rdv_password';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$message = "";
$rdvs = [];
$showResults = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $medecin    = trim($_POST['medecin'] ?? '');
    $date_heure = trim($_POST['date_heure'] ?? '');
    $motif      = trim($_POST['motif'] ?? '');

    if ($nom && $email && $medecin && $date_heure && $motif) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO patients (nom, email) VALUES (:nom, :email)");
            $stmt->execute([':nom' => $nom, ':email' => $email]);
            $patientId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("
                INSERT INTO rdv (user_id, patient_id, medecin, date_heure, motif)
                VALUES (:user_id, :patient_id, :medecin, :date_heure, :motif)
            ");
            $stmt->execute([
                ':user_id'    => $userId,
                ':patient_id' => $patientId,
                ':medecin'    => $medecin,
                ':date_heure' => $date_heure,
                ':motif'      => $motif,
            ]);

            $pdo->commit();
            $message = "Votre rendez-vous a été pris avec succès.";

            $sql = "
                SELECT r.id, p.nom, p.email, r.medecin, r.date_heure, r.motif
                FROM rdv r
                JOIN patients p ON r.patient_id = p.id
                WHERE r.user_id = :uid
                ORDER BY r.date_heure ASC
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':uid' => $userId]);
            $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $showResults = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes rendez-vous médicaux</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin:0;
            font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
            background:#f3f4f6;
            color:#111827;
        }
        .topbar {
            height:56px;
            background:#111827;
            color:#f9fafb;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 20px;
        }
        .topbar a { color:#e5e7eb; text-decoration:none; }
        .topbar-left { font-weight:700; }
        .page {
            min-height:calc(100vh - 56px);
            display:flex; justify-content:center; align-items:flex-start;
            padding:30px 16px;
        }
        .card {
            background:#ffffff;
            border-radius:14px;
            padding:24px;
            box-shadow:0 10px 24px rgba(15,23,42,0.08);
            max-width:960px; width:100%;
        }
        h1 { margin-top:0; margin-bottom:10px; }
        .subtitle { margin-top:0; margin-bottom:18px; color:#4b5563; }
        .grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
            gap:20px;
        }
        label {
            display:block; margin-bottom:4px;
            font-size:14px; font-weight:500;
        }
        input[type="text"],input[type="email"],input[type="datetime-local"],textarea,select {
            width:100%; padding:8px 10px;
            border-radius:8px; border:1px solid #d1d5db;
            font:inherit; margin-bottom:8px;
        }
        textarea { min-height:70px; resize:vertical; }
        button {
            margin-top:10px; padding:9px 14px;
            border-radius:999px; border:none;
            background:#2563eb; color:#f9fafb;
            font-weight:600; cursor:pointer;
        }
        .alert {
            padding:10px 12px; border-radius:8px;
            margin-bottom:14px; font-size:14px;
        }
        .alert-success { background:#dcfce7; color:#166534; }
        .alert-error { background:#fee2e2; color:#b91c1c; }
        table {
            width:100%; border-collapse:collapse;
            font-size:14px; margin-top:10px;
        }
        th,td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f1f5f9; }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-left">Espace patient – RDV Médicaux</div>
    <div>
        Bonjour, <?php echo htmlspecialchars($userNom); ?> |
        <a href="logout.php">Déconnexion</a>
    </div>
</header>

<div class="page">
    <div class="card">
        <h1>Prise de rendez-vous</h1>
        <p class="subtitle">Remplissez le formulaire pour réserver un créneau avec un médecin.</p>

        <?php if ($message): ?>
            <div class="alert <?php echo (strpos($message, 'succès') !== false) ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <div>
                <form method="post">
                    <label>Nom du patient</label>
                    <input type="text" name="nom" required>

                    <label>Email du patient</label>
                    <input type="email" name="email" required>

                    <label>Médecin</label>
                    <select name="medecin" required>
                        <option value="">-- Choisissez un médecin --</option>
                        <option value="Dr Dupont">Dr Dupont</option>
                        <option value="Dr Martin">Dr Martin</option>
                        <option value="Dr Diallo">Dr Diallo</option>
                    </select>

                    <label>Date et heure</label>
                    <input type="datetime-local" name="date_heure" required>

                    <label>Motif</label>
                    <textarea name="motif" required></textarea>

                    <button type="submit">Valider le rendez-vous</button>
                </form>
            </div>

            <?php if ($showResults): ?>
            <div>
                <h2>Vos rendez-vous enregistrés</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Date &amp; heure</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rdvs as $rdv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rdv['nom']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['medecin']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['date_heure']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['motif']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
