<?php
session_start();
require 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST['mail'];
    $mp = $_POST['password'];
    $stmt = $pdo->prepare("
        SELECT u.* 
        FROM user u 
        WHERE u.mail = ?
    ");
    $stmt->execute([$mail]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['tentatives'] >= 5) {
            die("Compte bloqué. Contactez l'administrateur.");
        } 
    
        if ($mp === $user['mot_de_passe']) {
            $pdo->prepare("UPDATE user SET tentatives = 0 WHERE id = ?")->execute([$user['id']]);
            
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_id'] = $user['id'];
            
            header("Location: acceuil.php");
            exit();
        } else {
    
            $essais = $user['tentatives'] + 1;
            $pdo->prepare("UPDATE user SET tentatives = ? WHERE id = ?")->execute([$essais, $user['id']]);
            echo "<script>alert('Erreur. Tentative $essais / 5');</script>";
        }
    } else {
        echo "<script>alert('Identifiants inconnus.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion - Magasin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .login-card { background: white; padding: 40px; border-radius: 15px; width: 400px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="text-align: center;">Connexion</h2>
        <form method="POST" class="grid-form" style="grid-template-columns: 1fr;">
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Se connecter</button>
                <a href="inscription.php" class="btn btn-secondary" style="flex: 1; text-align: center;">S'inscrire</a>
            </div>
        </form>
    </div>
</body>
</html>