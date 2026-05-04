<?php
header('Content-Type: application/json');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['erreur' => 'Requête invalide']);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. GESTION CLIENT (Vérifie si existe déjà par mail)
    $stmtC = $pdo->prepare("SELECT id_client FROM client WHERE mail = ?");
    $stmtC->execute([$_POST['mail']]);
    $client = $stmtC->fetch();

    if (!$client) {
        $stmtInsC = $pdo->prepare("INSERT INTO client (nom, prenom, age, numéro, ville, adresse, mail, id_user) VALUES (?,?,?,?,?,?,?,NULL)");
        $stmtInsC->execute([$_POST['nom'], $_POST['prenom'], $_POST['age'], $_POST['numéro'], $_POST['ville'], $_POST['adresse'], $_POST['mail']]);
        $idClient = $pdo->lastInsertId();
    } else {
        $idClient = $client['id_client'];
    }

    if (!isset($_POST['codart']) || !is_array($_POST['codart'])) {
        throw new Exception("Aucun article n'a été sélectionné.");
    }

    // 2. CALCUL MONTANT TOTAL ET GESTION ARTICLES
    $totalVente = 0;
    $articlesAEnregistrer = [];

    foreach ($_POST['codart'] as $key => $codart) {
        $description = $_POST['description'][$key];
        $prix = $_POST['prix'][$key];
        $categorie = $_POST['categorie'][$key];
        $quantite = $_POST['quantite'][$key];
        $montantLigne = $prix * $quantite;
        $totalVente += $montantLigne;

        // Vérifie si article existe
        $stmtA = $pdo->prepare("SELECT codart FROM article WHERE codart = ?");
        $stmtA->execute([$codart]);
        if (!$stmtA->fetch()) {
            $stmtInsA = $pdo->prepare("INSERT INTO article (codart, description, prix, categorie) VALUES (?,?,?,?)");
            $stmtInsA->execute([$codart, $description, $prix, $categorie]);
        }

        $articlesAEnregistrer[] = [
            'codart' => $codart,
            'quantite' => $quantite,
            'prix' => $prix,
            'montant' => $montantLigne
        ];
    }

    // 3. ENREGISTREMENT VENTE
    $stmtV = $pdo->prepare("INSERT INTO vente (id_client, date_vente, montant_total) VALUES (?, NOW(), ?)");
    $stmtV->execute([$idClient, $totalVente]);
    $idVente = $pdo->lastInsertId();

    // 4. DETAIL VENTE
    $stmtD = $pdo->prepare("INSERT INTO detail_vente (id_vente, codart, quantite, prix_unitaire, montant) VALUES (?,?,?,?,?)");
    foreach ($articlesAEnregistrer as $art) {
        $stmtD->execute([$idVente, $art['codart'], $art['quantite'], $art['prix'], $art['montant']]);
    }

    // Valider la transaction
    $pdo->commit();

    // Retourner succès
    http_response_code(200);
    echo json_encode(['succes' => true]);
    exit();

} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['erreur' => 'Erreur base de données : ' . $e->getMessage()]);
    exit();
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['erreur' => 'Erreur : ' . $e->getMessage()]);
    exit();
}
?>
