<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Liste des ventes</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #e8ecf3;
            padding: 30px 20px;
        }
        .container {
            max-width: 1200px;
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
        h5 {
            color: #555;
            margin: 15px 0 8px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #4a90e2;
        }
        .vente-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f5f7fa;
            border-radius: 8px;
            border: 1px solid #ddd;
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
        .montant-total {
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
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
        .info-vente {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-vente span {
            color: #555;
        }
        .total-vente {
            text-align: right;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #ddd;
            font-weight: bold;
            font-size: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🛒 Liste de toutes les ventes</h2>

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
        echo '<h4>Il y a ' . $nbventes . ' vente(s) enregistrée(s)</h4>';

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

    <a class="lien-retour" href="formulaire_vente.php">+ Effectuer une nouvelle vente</a>
    <input type="button" class="btn-retour" value="Retour à l'accueil" onclick="window.location.href='acceuil.php'">
</div>
</body>
</html>
