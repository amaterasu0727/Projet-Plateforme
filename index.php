<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST['mail'];
    $mp = $_POST['password'];
    $stmt = $pdo->prepare("
        SELECT u.*, c.nom 
        FROM user u 
        JOIN client c ON u.id = c.id_user 
        WHERE u.mail = ?
    ");
    $stmt->execute([$mail]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['tentatives'] >= 5) {
            die("Compte bloqué. Contactez l'administrateur.");
        } 
    
        if ($mp == $user['mot_de_passe']) {
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Magasin</title>
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
            max-width: 500px;
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

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
        }

        h2 {
            color: #555;
            margin-bottom: 25px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 25px;
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

        button, .btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        button:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .btn {
            flex: 1;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #764ba2;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 26px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue</h1>
        <p class="subtitle">Veuillez vous identifier ou créer un compte pour continuer.</p>
        <form method="POST">
            <h2>Connexion</h2>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
            <div class="signup-link">
               <h3> Pas de compte? <a href="inscription.php">S'inscrire</a></h3>
            </div>
        </form>
    </div>
</body>
</html>