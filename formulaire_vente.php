<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Formulaire de vente</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #e8ecf3;
            padding: 30px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
        }

        h2 {
            color: #222;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.3);
        }

        .section-articles {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .section-articles h3 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
        }

        .article-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
            gap: 10px;
            align-items: center;
        }

        .article-item input,
        .article-item select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .article-item .prix-unitaire,
        .article-item .montant-ligne {
            padding: 8px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-align: right;
            font-weight: bold;
        }

        .btn-supprimer {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-supprimer:hover {
            background-color: #c82333;
        }

        .btn-ajouter-article {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .btn-ajouter-article:hover {
            background-color: #218838;
        }

        .resume-total {
            background-color: #e7f3ff;
            border: 2px solid #4a90e2;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            text-align: right;
        }

        .resume-total h3 {
            color: #4a90e2;
            margin-bottom: 10px;
        }

        .montant-total-final {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }

        .boutons-action {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }

        button[type="submit"],
        .btn-retour {
            padding: 12px 30px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button[type="submit"] {
            background-color: #4a90e2;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #357ABD;
        }

        .btn-retour {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-retour:hover {
            background-color: #5a6268;
        }

        .message-erreur {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .message-succes {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .article-item {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🛍️ Formulaire d'enregistrement de vente</h2>

    <?php
    require 'config.php';

    // Afficher les messages de statut
    if (isset($_GET['statut'])) {
        if ($_GET['statut'] === 'ok') {
            echo '<div class="message-succes">✓ Vente enregistrée avec succès !</div>';
        } elseif ($_GET['statut'] === 'erreur') {
            echo '<div class="message-erreur">✗ Erreur : ' . htmlspecialchars($_GET['msg'] ?? 'Une erreur est survenue') . '</div>';
        }
    }

    try {
        $stmtClients = $pdo->query("SELECT id_client, nom, prenom FROM client ORDER BY nom, prenom");
        $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

        $stmtArticles = $pdo->query("SELECT codart, description, prix FROM article ORDER BY description");
        $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message-erreur">Erreur : lecture impossible des données.</div>';
        $clients = [];
        $articles = [];
    }
    ?>

    <form id="form-vente" method="POST" action="enregistrer_vente.php" onsubmit="return soumettreVente(event)">
        <div class="form-group">
            <label for="id_client">Client *</label>
            <select id="id_client" name="id_client" required>
                <option value="">-- Sélectionner un client --</option>
                <?php
                foreach ($clients as $client) {
                    echo '<option value="' . htmlspecialchars($client['id_client']) . '">';
                    echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']);
                    echo '</option>';
                }
                ?>
            </select>
        </div>
        <datalist id="codes-article">
            <?php foreach ($articles as $article) {
                echo '<option value="' . htmlspecialchars($article['codart']) . '">' . htmlspecialchars($article['description']) . '</option>';
            } ?>
        </datalist>

        <div class="section-articles">
            <h3>📦 Articles à vendre</h3>
            
            <button type="button" class="btn-ajouter-article" onclick="ajouterArticle()">
                + Ajouter un article
            </button>

            <div id="articles-list">
                <!-- Les articles seront ajoutés ici via JavaScript -->
            </div>
        </div>

        <div class="resume-total">
            <h3>Résumé de la vente</h3>
            <div>
                <strong>Nombre d'articles : </strong>
                <span id="nb-articles">0</span>
            </div>
            <div class="montant-total-final">
                Montant total : <span id="montant-total">0.00</span> XOF
            </div>
        </div>

        <div class="boutons-action">
            <button type="submit">✓ Valider la vente</button>
            <a href="acceuil.php" class="btn-retour">← Retour</a>
        </div>
    </form>
</div>

<script>
    const articlesData = <?php echo json_encode($articles); ?>;
    let compteurArticles = 0;

    function ajouterArticle() {
        const articlesList = document.getElementById('articles-list');
        compteurArticles++;

        const articleDiv = document.createElement('div');
        articleDiv.className = 'article-item';
        articleDiv.id = 'article-' + compteurArticles;

        articleDiv.innerHTML = `
            <input list="codes-article" type="text" class="codart-input" data-index="${compteurArticles}" placeholder="Code article" oninput="mettreAJourPrixParCode(this)" required>
            <input type="number" class="prix-input" data-index="${compteurArticles}" placeholder="Prix de l'article" min="0" step="0.01" value="0.00" oninput="calculerMontants()" required>
            <input type="number" class="quantite-input" data-index="${compteurArticles}" placeholder="Quantité" min="1" value="1" oninput="calculerMontants()" required>
            <div class="montant-ligne" id="montant-${compteurArticles}">0.00</div>
            <button type="button" class="btn-supprimer" onclick="supprimerArticle(${compteurArticles})">
                ✕
            </button>
        `;

        articlesList.appendChild(articleDiv);
        calculerMontants();
    }

    function mettreAJourPrixParCode(codeInput) {
        const index = codeInput.dataset.index;
        const codart = codeInput.value.trim();
        const article = articlesData.find(a => a.codart === codart);
        const prixInput = document.querySelector(`.prix-input[data-index="${index}"]`);

        if (article && prixInput) {
            prixInput.value = parseFloat(article.prix).toFixed(2);
        }
        calculerMontants();
    }

    function calculerMontants() {
        let montantTotal = 0;
        let nbArticles = 0;

        document.querySelectorAll('.article-item').forEach(item => {
            const codeInput = item.querySelector('.codart-input');
            const prixInput = item.querySelector('.prix-input');
            const quantiteInput = item.querySelector('.quantite-input');
            const index = quantiteInput.dataset.index;

            const code = codeInput.value.trim();
            const prixUnitaire = parseFloat(prixInput.value) || 0;
            const quantite = parseInt(quantiteInput.value) || 0;

            if (code && prixUnitaire >= 0 && quantite > 0) {
                const montantLigne = prixUnitaire * quantite;
                document.getElementById('montant-' + index).textContent = montantLigne.toFixed(2);
                montantTotal += montantLigne;
                nbArticles++;
            } else {
                document.getElementById('montant-' + index).textContent = '0.00';
            }
        });

        document.getElementById('montant-total').textContent = montantTotal.toFixed(2);
        document.getElementById('nb-articles').textContent = nbArticles;
    }

    function supprimerArticle(index) {
        document.getElementById('article-' + index).remove();
        calculerMontants();
    }

    function soumettreVente(event) {
        event.preventDefault();

        const idClient = document.getElementById('id_client').value;
        const articleItems = document.querySelectorAll('.article-item');

        if (!idClient) {
            alert('Veuillez sélectionner un client !');
            return false;
        }

        if (articleItems.length === 0) {
            alert('Veuillez ajouter au moins un article !');
            return false;
        }

        const articles = [];
        let hasValidArticle = false;

        articleItems.forEach(item => {
            const codeInput = item.querySelector('.codart-input');
            const prixInput = item.querySelector('.prix-input');
            const quantiteInput = item.querySelector('.quantite-input');

            const codart = codeInput.value.trim();
            const prixUnitaire = parseFloat(prixInput.value) || 0;
            const quantite = parseInt(quantiteInput.value) || 0;

            if (codart && prixUnitaire >= 0 && quantite > 0) {
                articles.push({
                    codart: codart,
                    prix_unitaire: prixUnitaire,
                    quantite: quantite
                });
                hasValidArticle = true;
            }
        });

        if (!hasValidArticle) {
            alert('Veuillez remplir les articles (code, prix et quantité) !');
            return false;
        }

        // Préparer les données à envoyer
        const formData = new FormData();
        formData.append('id_client', idClient);
        formData.append('articles', JSON.stringify(articles));

        // Envoyer via fetch
        fetch('enregistrer_vente.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                alert('✓ Vente enregistrée avec succès !');
                window.location.href = 'formulaire_vente.php?statut=ok';
            } else if (data.erreur) {
                alert('✗ Erreur : ' + data.erreur);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'enregistrement de la vente. Veuillez réessayer.');
        });

        return false;
    }
    window.addEventListener('load', () => {
        ajouterArticle();
    });
</script>
</body>
</html>
