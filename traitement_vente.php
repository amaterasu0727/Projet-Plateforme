<?php
include 'config.php'; // Ta connexion PDO
session_start();

try {
    $pdo->beginTransaction(); // Début de la transaction sécurisée

    // --- 1. GESTION DU CLIENT ---
    if (isset($_POST['nouveau_client'])) {
        $stmt = $pdo->prepare("INSERT INTO clients (nom, telephone) VALUES (?, ?)");
        $stmt->execute([$_POST['nom_client'], $_POST['tel_client']]);
        $id_client = $pdo->lastInsertId(); // Récupère l'ID auto-généré
    } else {
        $id_client = $_POST['id_client_existant'];
    }

    // --- 2. GESTION DE L'ARTICLE ---
    if (isset($_POST['nouvel_article'])) {
        $stmt = $pdo->prepare("INSERT INTO articles (nom, prix) VALUES (?, ?)");
        $stmt->execute([$_POST['nom_article'], $_POST['prix_unitaire']]);
        $id_article = $pdo->lastInsertId();
        $prix_unitaire = $_POST['prix_unitaire'];
    } else {
        $id_article = $_POST['id_article_existant'];
        // Récupérer le prix de l'article existant pour la commande
        $stmt = $pdo->prepare("SELECT prix FROM articles WHERE id = ?");
        $stmt->execute([$id_article]);
        $prix_unitaire = $stmt->fetchColumn();
    }

    // --- 3. ENREGISTREMENT DE LA COMMANDE ---
    $stmt = $pdo->prepare("INSERT INTO commandes (id_client, date_commande) VALUES (?, NOW())");
    $stmt->execute([$id_client]);
    $id_commande = $pdo->lastInsertId();[cite: 6]

    // --- 4. ENREGISTREMENT DE LA LIGNE DE COMMANDE ---
    $stmt = $pdo->prepare("INSERT INTO ligne_commande (id_commande, id_article, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_commande, $id_article, $_POST['quantite'], $prix_unitaire]);[cite: 6]

    $pdo->commit(); // Valide toutes les insertions d'un coup[cite: 6]
    echo "Vente enregistrée avec succès !";

} catch (Exception $e) {
    $pdo->rollBack(); // Annule tout en cas d'erreur pour garder la DB propre[cite: 6]
    echo "Erreur lors de l'enregistrement : " . $e->getMessage();
}
?>