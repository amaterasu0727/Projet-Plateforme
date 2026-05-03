<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des articles</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #e8ecf3;
            padding: 30px 20px;
        }
        .container {
            max-width: 860px;
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
        }
        .lien-retour:hover {
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
    <h2>📋 Liste de tous les articles</h2>

    <?php
    require 'config.php';

    try {
        $stmt = $pdo->query("SELECT codart, description, prix, categorie FROM article ORDER BY categorie");
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message-erreur">Erreur : lecture impossible de la table article.</div>';
        $articles = [];
    }

    if (empty($articles)) {
        echo '<div class="message-vide">Aucun article enregistré pour le moment.</div>';
    } else {
        $nbart = count($articles);
        echo '<h4>Il y a ' . $nbart . ' article(s) en magasin</h4>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Code article</th>';
        echo '<th>Description</th>';
        echo '<th>Prix</th>';
        echo '<th>Catégorie</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($articles as $ligne) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ligne['codart']) . '</td>';
            echo '<td>' . htmlspecialchars($ligne['description']) . '</td>';
            echo '<td>' . number_format($ligne['prix'], 2, ',', ' ') . '</td>';
            echo '<td>' . htmlspecialchars($ligne['categorie']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
    ?>

    <a class="lien-retour" href="formulaire_article.php">+ Ajouter un nouvel article</a>
    <input type="button" class="lien-retour" value="Retour à l'accueil" onclick="window.location.href='acceuil.php'">
</div>
</body>
</html>