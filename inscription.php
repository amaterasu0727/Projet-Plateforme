<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();
        $sqlUser = "INSERT INTO user (nom, prenom, age, numéro, ville, adresse, mail, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $pdo->prepare($sqlUser);
        $stmt1->execute([
            $_POST['nom'], 
            $_POST['prenom'],
            $_POST['age'],
            $_POST['numéro'],
            $_POST['ville'],
            $_POST['adresse'], 
            $_POST['mail'], 
            $_POST['password']
        ]);
    
        $pdo->commit(); 
        echo "<script>alert('Inscription réussie ! Veuillez vous connecter.'); window.location.href='index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription - Magasin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .signup-card { background: white; padding: 40px; border-radius: 15px; width: 500px; box-shadow: var(--shadow); }
    </style>
</head>
<body>
    <div class="signup-card">
        <h2 style="text-align: center; margin-bottom: 20px;">Créer un compte</h2>
        <form method="POST" class="grid-form">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="number" name="age" placeholder="Âge" min="1" max="120" required>
            <input type="tel" name="numéro" placeholder="Téléphone" required>
            <input type="text" name="ville" placeholder="Ville" required>
            <input type="text" name="adresse" placeholder="Adresse" required>
            <input type="email" name="mail" placeholder="Email (Identifiant)" style="grid-column: span 2;" required>
            <input type="password" name="password" placeholder="Mot de passe" style="grid-column: span 2;" required>
            
            <button type="submit" class="btn btn-primary" style="grid-column: span 2;">Valider mon inscription</button>
        </form>
        <div class="login-link">
            Déjà inscrit ? <a href="index.php">Connectez-vous ici</a>
        </div>  
    </div>
</body>
</html>
    