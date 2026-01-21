<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - RDV Médicaux</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #111827;
            background: #f3f4f6;
        }
        .navbar {
            position: fixed; top:0; left:0; right:0;
            height: 64px;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 32px;
            background: rgba(15,23,42,0.9);
            color:#f9fafb;
            z-index:10;
            backdrop-filter:blur(12px);
        }
        .navbar-logo { font-weight:700; letter-spacing:0.08em; }
        .navbar-links { display:flex; gap:20px; font-size:14px; }
        .navbar-links a { color:#e5e7eb; text-decoration:none; }
        .navbar-links a:hover { color:#93c5fd; }
        .navbar-actions { display:flex; gap:10px; }
        .btn-nav {
            font-size:14px; padding:8px 14px; border-radius:999px;
            border:1px solid transparent; cursor:pointer; text-decoration:none;
        }
        .btn-nav.outline {
            background:transparent; color:#e5e7eb; border-color:#e5e7eb;
        }
        .btn-nav.outline:hover { background:#e5e7eb; color:#111827; }
        .btn-nav.filled {
            background:linear-gradient(90deg,#3b82f6,#22c55e);
            color:#f9fafb; box-shadow:0 8px 16px rgba(59,130,246,0.4);
        }
        .btn-nav.filled:hover { filter:brightness(1.05); }
        main { margin-top:64px; }

        .hero {
            min-height:calc(100vh - 64px);
            background:url("img/medical-bg.jpg") center/cover no-repeat fixed;
            position:relative; color:#f9fafb;
        }
        .hero::before {
            content:""; position:absolute; inset:0;
            background:radial-gradient(circle at top left,rgba(59,130,246,0.55),rgba(15,23,42,0.95));
        }
        .hero-container {
            position:relative; max-width:1100px; margin:0 auto;
            padding:60px 24px 80px;
            display:flex; flex-wrap:wrap; align-items:center; gap:40px;
        }
        .hero-text { flex:1 1 320px; }
        .hero-text h1 { font-size:36px; margin-bottom:10px; }
        .hero-text p { font-size:16px; color:#e5e7eb; margin-bottom:24px; }
        .hero-actions { display:flex; flex-wrap:wrap; gap:12px; }
        .hero-secondary-text { margin-top:10px; font-size:13px; color:#9ca3af; }
        .hero-card {
            flex:1 1 280px;
            background:rgba(15,23,42,0.8);
            border-radius:18px;
            padding:20px 18px;
            box-shadow:0 18px 32px rgba(0,0,0,0.45);
        }
        .hero-card h2 { margin-top:0; font-size:18px; }
        .hero-card ul { padding-left:18px; font-size:14px; color:#d1d5db; }

        section { padding:50px 24px; }
        .section-inner { max-width:1100px; margin:0 auto; }
        .section-title { text-align:center; margin-bottom:24px; font-size:24px; }

        .services-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:20px;
        }
        .service-card {
            background:#ffffff;
            border-radius:14px;
            padding:20px;
            box-shadow:0 8px 18px rgba(15,23,42,0.05);
        }
        .service-card h3 { margin-top:0; }
        .service-card p { font-size:14px; color:#4b5563; }

        .contact-grid {
            display:grid;
            grid-template-columns:minmax(0,1.2fr) minmax(0,0.8fr);
            gap:24px;
        }
        @media (max-width:800px){
            .contact-grid { grid-template-columns:1fr; }
        }
        .contact-form {
            background:#ffffff; border-radius:14px; padding:18px;
            box-shadow:0 8px 18px rgba(15,23,42,0.05);
        }
        .contact-info {
            background:#111827; color:#e5e7eb;
            border-radius:14px; padding:18px;
        }
        label {
            display:block; margin-bottom:4px;
            font-size:14px; font-weight:500;
        }
        input[type="text"], input[type="email"], textarea {
            width:100%; padding:8px 10px;
            border-radius:8px; border:1px solid #d1d5db;
            font:inherit; margin-bottom:10px;
        }
        textarea { min-height:80px; resize:vertical; }
        .btn-contact {
            padding:9px 16px; border-radius:999px; border:none;
            background:#2563eb; color:#f9fafb; font-weight:600; cursor:pointer;
        }
        .btn-contact:hover { filter:brightness(1.05); }

        footer {
            text-align:center; font-size:12px;
            color:#6b7280; padding:16px;
        }
    </style>
</head>
<body>
<header class="navbar">
    <div class="navbar-logo">RDV MÉDICAUX</div>
    <nav class="navbar-links">
        <a href="#accueil">Accueil</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="navbar-actions">
        <a href="login.php" class="btn-nav outline">Connexion</a>
        <a href="register.php" class="btn-nav filled">Inscription</a>
    </div>
</header>

<main>
    <section id="accueil" class="hero">
        <div class="hero-container">
            <div class="hero-text">
                <h1>Gérez vos rendez-vous médicaux en toute simplicité</h1>
                <p>Une plateforme sécurisée pour prendre, suivre et organiser vos rendez-vous avec vos médecins.</p>
                <div class="hero-actions">
                    <a href="login.php" class="btn-nav filled">Se connecter</a>
                    <a href="register.php" class="btn-nav outline">Créer un compte</a>
                </div>
                <p class="hero-secondary-text">
                    Après connexion, vous serez redirigé vers votre espace de prise de rendez-vous.
                </p>
            </div>
            <div class="hero-card">
                <h2>Pourquoi RDV Médicaux ?</h2>
                <ul>
                    <li>Prise de rendez-vous 24h/24</li>
                    <li>Historique de vos consultations</li>
                    <li>Interface claire et moderne</li>
                    <li>Accès sécurisé à votre espace patient</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="section-inner">
            <h2 class="section-title">Nos services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Prise de rendez-vous</h3>
                    <p>Choisissez un médecin, une date et un motif en quelques clics.</p>
                </div>
                <div class="service-card">
                    <h3>Suivi des consultations</h3>
                    <p>Consultez vos rendez-vous passés et à venir depuis un espace unique.</p>
                </div>
                <div class="service-card">
                    <h3>Accessibilité</h3>
                    <p>Application accessible depuis n’importe quel appareil connecté à Internet.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="section-inner">
            <h2 class="section-title">Contact</h2>
            <div class="contact-grid">
                <div class="contact-form">
                    <h3>Une question ?</h3>
                    <form>
                        <label>Nom</label>
                        <input type="text" placeholder="Votre nom" required>
                        <label>Email</label>
                        <input type="email" placeholder="vous@example.com" required>
                        <label>Message</label>
                        <textarea placeholder="Votre message..."></textarea>
                        <button type="button" class="btn-contact">Envoyer</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h3>Clinique RDV Médicaux</h3>
                    <p>123 Rue de la Santé<br>59000 Lille</p>
                    <p>Tél : 01 23 45 67 89<br>Email : contact@rdv-medicaux.fr</p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    © <?php echo date('Y'); ?> RDV Médicaux – Projet Docker PHP/MySQL
</footer>
</body>
</html>
