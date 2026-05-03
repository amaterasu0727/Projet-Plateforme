<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Lecture de la table article</title>
<style type="text/css" >
table {border-style:double;border-width: 3px;border-color: red;background-color:
yellow;}
</style>
</head>
<body>
<?php
require 'config.php';
$requete = "SELECT * FROM article ORDER BY categorie";
$result = $pdo->query($requete);

if (!$result) {
    echo "Lecture impossible";
} else {
    $articles = $result->fetchAll(PDO::FETCH_ASSOC);
    $nbart = count($articles);
    echo "<h3>Tous nos articles par catégorie</h3>";
    echo "<h4>Il y a $nbart articles en magasin</h4>";
    echo "<table border=\"1\">";
    echo "<tr><th>Code article</th> <th>Description</th> <th>Prix</th> <th>Catégorie</th></tr>";
    foreach ($articles as $ligne) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($ligne['codart']) . "</td>";
        echo "<td>" . htmlspecialchars($ligne['description']) . "</td>";
        echo "<td>" . htmlspecialchars($ligne['prix']) . "</td>";
        echo "<td>" . htmlspecialchars($ligne['categorie']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
</body>