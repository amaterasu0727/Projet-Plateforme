<?php
header('Content-Type: application/json');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['erreur' => 'Requête invalide']);
    exit();
}

try {
    $idClient = isset($_POST['id_client']) ? intval($_POST['id_client']) : null;
    $articlesJson = isset($_POST['articles']) ? $_POST['articles'] : '[]';
    
    if (!$idClient) {
        http_response_code(400);
        echo json_encode(['erreur' => 'Client invalide']);
        exit();
    }

    $articles = json_decode($articlesJson, true);
    if (!is_array($articles) || empty($articles)) {
        http_response_code(400);
        echo json_encode(['erreur' => 'Aucun article fourni']);
        exit();
    }

    $stmtClient = $pdo->prepare("SELECT id_client FROM client WHERE id_client = ?");
    $stmtClient->execute([$idClient]);
    if ($stmtClient->rowCount() == 0) {
        http_response_code(400);
        echo json_encode(['erreur' => 'Client introuvable']);
        exit();
    }

    $pdo->beginTransaction();

    $stmtArticle = $pdo->prepare("SELECT prix FROM article WHERE codart = ?");
    $stmtInsertArticle = $pdo->prepare("INSERT INTO article (codart, description, prix, categorie) VALUES (?, ?, ?, ?)");
    $stmtInsertCommande = $pdo->prepare("INSERT INTO commande (id_client, date_com) VALUES (?, ?)");
    $stmtInsertLigneCommande = $pdo->prepare("INSERT INTO ligne_commande (id_com, codart, quantite) VALUES (?, ?, ?)");
    $stmtInsertVente = $pdo->prepare("INSERT INTO vente (id_client, date_vente, montant_total) VALUES (?, NOW(), ?)");
    $stmtInsertDetail = $pdo->prepare("INSERT INTO detail_vente (id_vente, codart, quantite, prix_unitaire, montant) VALUES (?, ?, ?, ?, ?)");

    $montantTotal = 0;
    $articlesPreparees = [];

    foreach ($articles as $article) {
        $codart = trim($article['codart'] ?? '');
        $quantite = intval($article['quantite'] ?? 0);
        $prixUnitaire = isset($article['prix_unitaire']) ? floatval($article['prix_unitaire']) : null;

        if (!$codart || $quantite <= 0 || $prixUnitaire === null || $prixUnitaire < 0) {
            throw new Exception("Article invalide : " . $codart);
        }

        $stmtArticle->execute([$codart]);
        $result = $stmtArticle->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $stmtInsertArticle->execute([
                htmlspecialchars($codart, ENT_QUOTES, 'UTF-8'),
                'Article créé depuis la vente',
                $prixUnitaire,
                'Vente'
            ]);
        } else {
            $prixUnitaire = floatval($result['prix']);
        }

        $montantArticle = $prixUnitaire * $quantite;
        $montantTotal += $montantArticle;

        $articlesPreparees[] = [
            'codart' => $codart,
            'quantite' => $quantite,
            'prix_unitaire' => $prixUnitaire,
            'montant' => $montantArticle
        ];
    }

    // Insérer la commande
    $dateCommande = date('m/Y');
    $stmtInsertCommande->execute([$idClient, $dateCommande]);
    $idCommande = $pdo->lastInsertId();

    $stmtInsertVente->execute([$idClient, $montantTotal]);
    $idVente = $pdo->lastInsertId();

    foreach ($articlesPreparees as $article) {
        $stmtInsertLigneCommande->execute([
            $idCommande,
            $article['codart'],
            $article['quantite']
        ]);

        $stmtInsertDetail->execute([
            $idVente,
            $article['codart'],
            $article['quantite'],
            $article['prix_unitaire'],
            $article['montant']
        ]);
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
