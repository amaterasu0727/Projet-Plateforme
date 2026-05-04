<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>ACCUEIL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-header { text-align: center; margin-bottom: 40px; color: var(--dark); }
        .welcome-header h1 { font-weight: 800; background: linear-gradient(to right, #4f46e5, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .menu-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 500px;
            margin: 0 auto;
        }
        .menu-card {
            padding: 30px;
            text-align: center;
            background: white;
            border: 2px solid #eef2f7;
            border-radius: 10px;
            transition: 0.3s;
            font-size: 1.1em;
        }
        .menu-card.articles { border-left: 5px solid #6366f1; color: #6366f1; }
        .menu-card.ventes { border-left: 5px solid #10b981; color: #10b981; }
        .menu-card.effectuer { border-left: 5px solid #f59e0b; color: #f59e0b; }
        .menu-card.clients { border-left: 5px solid #8b5cf6; color: #8b5cf6; }
        .menu-card:hover { transform: translateX(10px); background: #f8fafc; border-color: inherit; }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-header">
            <h1>BIENVENUE DANS MA PLATEFORME, <?php echo htmlspecialchars($_SESSION['user_nom']); ?></h1>
            <p>Gestionnaire de Magasin</p>
        </div>

        <div class="menu-grid">
            <a href="liste_articles.php" class="menu-card articles btn">📦 ARTICLES</a>
            <a href="listes-vente.php" class="menu-card ventes btn">📊 VOIR LES VENTES</a>
            <a href="formulaire_vente.php" class="menu-card effectuer btn">💰 EFFECTUER UNE VENTE</a>
            <a href="listes-client.php" class="menu-card clients btn">👥 LISTE DES CLIENTS</a>
            <a href="index.php?logout" class="menu-card btn" style="border-color: var(--danger); color: var(--danger); border-left: 5px solid var(--danger);">🚪 DÉCONNEXION</a>
        </div>
    </div>
</body>
</html>