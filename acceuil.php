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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            min-height: 100vh;
        }
        table {
            background-color: white;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        thead {
            background: linear-gradient(135deg, #87CEEB 0%, #4682B4 100%);
        }
        thead th {
            color: white;
        }
        th, td {
            padding: 15px;
        }
        a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            background: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin: 8px;
            transition: all 0.3s ease;
            border: 2px solid #87CEEB;
        }
        a:hover {
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(135, 206, 235, 0.3);
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <table border="1" width="70%" cellpadding="10" cellspacing="0" align="center"> 
        <thead>
            <tr>
                <th><img src="eneam.png" alt="Logo ENEAM" width="100" height="100"></th>
                <th><h1>BIENVENUE SUR MA PLATEFORME <?php echo htmlspecialchars($_SESSION['user_nom']); ?></h1></th>
                <th><img src="uac.jpg" alt="Logo UAC" width="100" height="100"></th>
            </tr>
        </thead>
       <tbody>    
            <tr>
                <td colspan="3" align="center">
                    <h1><a href="liste_articles.php">ARTICLE</a></h1>
                    <h1><a href="listes-vente.php">VENTE</a></h1>
                    <h1><a href="listes-client.php">CLIENT</a></h1>
                    <h1><a href="formulaire_vente.php">EFFECTUER UNE VENTE</a></h1>
                    <h1><a href="index.php?logout">DÉCONNEXION</a></h1>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>