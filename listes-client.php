<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des clients</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #e8ecf3;
            padding: 30px 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
        }
        h2 {
            color: #222;
            margin-top: 0;
            margin-bottom: 8px;
        }
        h4 {
            color: #444;
            margin: 10px 0 18px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #bbb;
            background-color: #fff;
        }
        table thead {
            background-color: #f2f2f2;
            color: #222;
        }
        table th,
        table td {
            padding: 12px 14px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            font-weight: bold;
        }
        table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        table tbody tr:hover {
            background-color: #f4f6fb;
        }
        .lien-retour {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin-right: 10px;
        }
        .lien-retour:hover {
            background-color: #357ABD;
        }
        .btn-retour {
            padding: 10px 18px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-retour:hover {
            background-color: #357ABD;
        }
        .message-erreur {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .message-vide {
            background-color: #e7f3ff;
            color: #185fa5;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>👥 Liste de tous les clients</h2>

    <?php
    require 'config.php';

    try {
        $stmt = $pdo->query("SELECT id_client, nom, prenom, age, numéro, ville, adresse, mail FROM client ORDER BY nom, prenom");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message-erreur">Erreur : lecture impossible de la table client.</div>';
        $clients = [];
    }

    if (empty($clients)) {
        echo '<div class="message-vide">Aucun client enregistré pour le moment.</div>';
    } else {
        $nbclients = count($clients);
        echo '<h4>Il y a ' . $nbclients . ' client(s) enregistré(s)</h4>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nom</th>';
        echo '<th>Prénom</th>';
        echo '<th>Âge</th>';
        echo '<th>Téléphone</th>';
        echo '<th>Ville</th>';
        echo '<th>Adresse</th>';
        echo '<th>Email</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($clients as $ligne) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ligne['id_client']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['nom']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['prenom']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['age']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['numéro']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['ville']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['adresse']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['mail']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
    ?>

    <a class="lien-retour" href="inscription.php">+ Ajouter un nouveau client</a>
    <input type="button" class="btn-retour" value="Retour à l'accueil" onclick="window.location.href='acceuil.php'">
</div>
</body>
</html>
