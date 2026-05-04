<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des clients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="header-actions">
        <h2>👥 Liste de tous les clients</h2>
        <div>
            <a href="acceuil.php" class="btn btn-secondary">← Retour</a>
        </div>
    </div>

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
        echo '<h4>Il y a ' . $nbclients . ' clients enregistrés</h4>';
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
</div>
</body>
</html>
