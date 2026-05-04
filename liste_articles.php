<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des articles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="header-actions">
        <h2>📋 Liste de tous les articles</h2>
        <div>
            <a href="acceuil.php" class="btn btn-secondary">← Retour</a>
            <a href="formulaire_article.php" class="btn btn-success">+ Ajouter Article</a>
        </div>
    </div>

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
        echo '<h4>Il y a ' . $nbart . ' articles en magasin</h4>';
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
</div>
</body>
</html>