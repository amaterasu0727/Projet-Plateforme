<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();
        $sqlUser = "INSERT INTO user (mail, mot_de_passe) VALUES (?, ?)";
        $stmt1 = $pdo->prepare($sqlUser);
        $stmt1->execute([$_POST['mail'], $_POST['password']]);
    
        $idGenere = $pdo->lastInsertId();
        $sqlClient = "INSERT INTO client (nom, prenom, age, numéro, ville, adresse, mail, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $pdo->prepare($sqlClient);
        $stmt2->execute([
            $_POST['nom'], 
            $_POST['prenom'],
            $_POST['age'],
            $_POST['numéro'],
            $_POST['ville'],
            $_POST['adresse'], 
            $_POST['mail'], 
            $idGenere 
        ]);

        $pdo->commit(); 
        echo "<script>alert('Compte et profil créés !'); window.location.href='index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Magasin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        input {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: #f8f9ff;
        }

        input::placeholder {
            color: #999;
        }

        button {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST">
            <h2>Inscription</h2>
                <input type="text" name="nom" placeholder="Nom" required><br><br>
                <input type="text" name="prenom" placeholder="Prénom" required><br><br>
                <input type="number" name="age" placeholder="Âge" min="1" max="120" required><br><br>
                <input type="tel" name="numéro" placeholder="Numéro de téléphone" required><br><br>
                <input type="text" name="ville" placeholder="Ville" required><br><br>
                <input type="text" name="adresse" placeholder="Adresse" required><br><br>
                <input type="email" name="mail" placeholder="Email (Servira d'identifiant)" required><br><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br><br>
                <button type="submit">Valider mon inscription</button>
        </form>
        <div class="login-link">
            Déjà un compte ? <a href="index.php">Connectez-vous ici</a>
        </div>  
    </div>
</body>
</html>
<form method="POST">
    