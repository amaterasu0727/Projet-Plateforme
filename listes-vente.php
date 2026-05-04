<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des ventes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .vente-section { margin-bottom: 30px; padding: 15px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee; }
        h5 { background: var(--light); padding: 10px; border-left: 4px solid var(--primary); margin: 0 0 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header-actions">
        <h2>🛒 Liste de toutes les ventes</h2>
        <div>
            <a href="acceuil.php" class="btn btn-secondary">← Retour</a>
            <a href="formulaire_vente.php" class="btn btn-success">+ Effectuer une vente</a>
        </div>
    </div>

    <?php
    require 'config.php';

    try {
        // Récupérer toutes les ventes avec les informations des clients
        $stmt = $pdo->query("
            SELECT v.id_vente, v.id_client, v.date_vente, v.montant_total, 
                   c.nom, c.prenom 
            FROM vente v
            LEFT JOIN client c ON v.id_client = c.id_client
            ORDER BY v.date_vente DESC
        ");
        $ventes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message-erreur">Erreur : lecture impossible de la table vente.</div>';
        $ventes = [];
    }

    if (empty($ventes)) {
        echo '<div class="message-vide">Aucune vente enregistrée pour le moment.</div>';
    } else {
        $nbventes = count($ventes);
        echo '<h4>Il y a ' . $nbventes . ' ventes enregistrées</h4>';

        $total_general = 0;

        foreach ($ventes as $vente) {
            $id_vente = htmlspecialchars($vente['id_vente']);
            $nom_client = htmlspecialchars($vente['nom'] ?? 'Client supprimé');
            $prenom_client = htmlspecialchars($vente['prenom'] ?? '');
            $date_vente = htmlspecialchars($vente['date_vente']);
            $montant_vente = floatval($vente['montant_total']);
            $total_general += $montant_vente;

            echo '<div class="vente-section">';
            echo '<h5>Vente #' . $id_vente . ' - ' . $nom_client . ' ' . $prenom_client . ' - ' . $date_vente . '</h5>';
            
            // Récupérer les détails de la vente
            try {
                $stmt = $pdo->prepare("
                    SELECT dv.quantite, dv.prix_unitaire, dv.montant, 
                           a.codart, a.description
                    FROM detail_vente dv
                    INNER JOIN article a ON dv.codart = a.codart
                    WHERE dv.id_vente = ?
                    ORDER BY dv.codart
                ");
                $stmt->execute([$vente['id_vente']]);
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($details)) {
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Code article</th>';
                    echo '<th>Description</th>';
                    echo '<th>Quantité</th>';
                    echo '<th>Prix unitaire</th>';
                    echo '<th>Montant</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    foreach ($details as $detail) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($detail['codart']) . '</td>';
                        echo '<td>' . htmlspecialchars($detail['description']) . '</td>';
                        echo '<td>' . htmlspecialchars($detail['quantite']) . '</td>';
                        echo '<td>' . number_format($detail['prix_unitaire'], 2, ',', ' ') . ' XOF</td>';
                        echo '<td class="montant-total">' . number_format($detail['montant'], 2, ',', ' ') . ' XOF</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p style="color: #888; font-style: italic;">Aucun détail d\'article pour cette vente.</p>';
                }

                echo '<div class="total-vente">Montant total de la vente : ' . number_format($montant_vente, 2, ',', ' ') . ' XOF</div>';
            } catch (PDOException $e) {
                echo '<div class="message-erreur">Erreur : lecture impossible des détails de la vente.</div>';
            }

            echo '</div>';
        }

        echo '<div style="margin-top: 30px; padding: 15px; background-color: #f0f8ff; border: 2px solid #4a90e2; border-radius: 8px;">';
        echo '<h4 style="margin-top: 0; color: #4a90e2;">💰 Montant total de toutes les ventes</h4>';
        echo '<h3 style="color: #28a745; margin: 0;">' . number_format($total_general, 2, ',', ' ') . ' XOF</h3>';
        echo '</div>';
    }
    ?>
</div>
</body>
</html>
