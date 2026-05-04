<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Effectuer une Vente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="header-actions">
        <h2>🛍️ Formulaire d'enregistrement de vente</h2>
        <a href="acceuil.php" class="btn btn-secondary">← Retour</a>
    </div>

    <?php
    require 'config.php';
    try {
        $stmtClients = $pdo->query("SELECT nom, prenom, age, numéro, ville, adresse, mail FROM client");
        $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

        $stmtArticles = $pdo->query("SELECT codart, description, prix, categorie FROM article");
        $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $clients = [];
        $articles = [];
    }
    ?>

    <form id="form-vente" method="POST" action="enregistrer_vente.php" onsubmit="return soumettreVente(event)">
        <!-- SECTION 1 : CLIENT -->
        <div class="form-section">
            <h3>👤 Informations du Client</h3>
            <div class="grid-form">
                <input list="liste-clients" name="nom" id="client_nom" placeholder="Nom du client" oninput="remplissageAutoClient(this)" required>
                <input type="text" name="prenom" id="client_prenom" placeholder="Prénom" required>
                <input type="number" name="age" id="client_age" placeholder="Âge" required>
                <input type="tel" name="numéro" id="client_numéro" placeholder="Numéro Téléphone" required>
                <input type="text" name="ville" id="client_ville" placeholder="Ville" required>
                <input type="text" name="adresse" id="client_adresse" placeholder="Adresse" required>
                <input type="email" name="mail" id="client_mail" placeholder="Adresse Mail" required>
            </div>
        </div>
        
        <datalist id="liste-clients">
            <?php foreach ($clients as $c) echo '<option value="'.htmlspecialchars($c['nom']).'">'.htmlspecialchars($c['prenom']).'</option>'; ?>
        </datalist>

        <!-- SECTION 2 : ARTICLES -->
        <div class="form-section">
            <h3>📦 Liste des Articles</h3>
            <div id="articles-list">
                <div class="article-item grid-form" style="margin-bottom: 10px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; align-items: end;">
                    <input list="codes-article" name="codart[]" class="codart-input" placeholder="Code Article (ex: CA300)" oninput="remplissageAuto(this)" required>
                    <input type="text" name="description[]" class="desc-input" placeholder="Description">
                    <input type="number" name="prix[]" class="prix-input" placeholder="Prix" oninput="calculerTotal()">
                    <input type="text" name="categorie[]" class="cat-input" placeholder="Catégorie">
                    <input type="number" name="quantite[]" class="qty-input" placeholder="Quantité" value="1" oninput="calculerTotal()" required>
                    <button type="button" class="btn btn-danger" onclick="supprimerLigne(this)" style="padding: 10px; height: 40px;">✕</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="ajouterArticle()">+ Ajouter un autre article</button>
        </div>
        
        <datalist id="codes-article">
            <?php foreach ($articles as $a) echo '<option value="'.htmlspecialchars($a['codart']).'">'.htmlspecialchars($a['description']).'</option>'; ?>
        </datalist>

        <!-- SECTION 3 : TOTAL -->
        <div class="total-box" style="margin-top: 20px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
            MONTANT À RÉGLER : <span id="total-final" style="font-size: 1.2em; color: var(--success);">0.00</span> XOF
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.2em;">Enregistrer la Vente</button>
        </div>
    </form>
</div>

<script>
    const articlesData = <?php echo json_encode($articles); ?>;
    const clientsData = <?php echo json_encode($clients); ?>;

    function remplissageAutoClient(input) {
        const nom = input.value;
        const client = clientsData.find(c => c.nom === nom);
        if (client) {
            document.getElementById('client_prenom').value = client.prenom;
            document.getElementById('client_age').value = client.age;
            document.getElementById('client_numéro').value = client.numéro;
            document.getElementById('client_ville').value = client.ville;
            document.getElementById('client_adresse').value = client.adresse;
            document.getElementById('client_mail').value = client.mail;
        }
    }

    function ajouterArticle() {
        const list = document.getElementById('articles-list');
        const newItem = list.children[0].cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => {
            if(input.name !== 'quantite[]') input.value = '';
            else input.value = 1;
        });
        list.appendChild(newItem);
    }

    function supprimerLigne(btn) {
        const list = document.getElementById('articles-list');
        if (list.children.length > 1) {
            btn.closest('.article-item').remove();
            calculerTotal();
        }
    }

    function remplissageAuto(input) {
        const item = input.closest('.article-item');
        const data = articlesData.find(a => a.codart === input.value);
        if (data) {
            item.querySelector('.desc-input').value = data.description;
            item.querySelector('.prix-input').value = data.prix;
            item.querySelector('.cat-input').value = data.categorie;
        }
        calculerTotal();
    }

    function calculerTotal() {
        let total = 0;
        document.querySelectorAll('.article-item').forEach(item => {
            const p = item.querySelector('.prix-input').value || 0;
            const q = item.querySelector('.qty-input').value || 0;
            total += (p * q);
        });
        document.getElementById('total-final').textContent = total.toFixed(2);
    }

    function soumettreVente(event) {
        event.preventDefault();
        const form = document.getElementById('form-vente');
        const formData = new FormData(form);

        fetch('enregistrer_vente.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.succes) {
                alert('Vente enregistrée avec succès !');
                form.reset();
                const list = document.getElementById('articles-list');
                while (list.children.length > 1) list.removeChild(list.lastChild);
                document.getElementById('total-final').textContent = '0.00';
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
</script>
</body>
</html>
