<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulaire_article.php");
    exit();
}

require 'config.php';

$codart      = trim($_POST['codart']);
$description = trim($_POST['description']);
$prix        = floatval($_POST['prix']);
$categorie   = trim($_POST['categorie']);

if (empty($codart) || empty($description) || empty($categorie) || $prix < 0) {
    header("Location: formulaire_article.php?statut=erreur&msg=Champs+manquants+ou+invalides");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT codart FROM article WHERE codart = ?");
    $stmt->execute([$codart]);

    if ($stmt->rowCount() > 0) {
        header("Location: formulaire_article.php?statut=doublon");
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO article (codart, description, prix, categorie) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        htmlspecialchars($codart, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
        $prix,
        htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8')
    ]);

    header("Location: formulaire_article.php?statut=ok");
    exit();
} catch (PDOException $e) {
    $msg = urlencode('Erreur MySQL : ' . $e->getMessage());
    header("Location: formulaire_article.php?statut=erreur&msg={$msg}");
    exit();
}
?>