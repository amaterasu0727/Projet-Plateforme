<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Ajouter un article</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .formulaire {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 50px;
            width: 100%;
            max-width: 700px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h2 { 
            margin-bottom: 25px; 
            color: #333; 
            font-size: 28px;
            text-align: center;
            font-weight: 700;
        }
        label {
            display: block; 
            margin-top: 18px; 
            margin-bottom: 8px;
            font-size: 15px; 
            color: #555; 
            font-weight: bold;
        }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e0e0e0;
            border-radius: 8px; 
            font-size: 15px; 
            box-sizing: border-box;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background-color: #f8f9ff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        textarea { 
            resize: vertical; 
            height: 140px;
            font-family: Arial, sans-serif;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-row-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group-full {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
        }
        .boutons { 
            display: flex; 
            gap: 15px; 
            margin-top: 35px;
        }
        input[type="submit"] {
            flex: 1; 
            padding: 14px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            border: none; 
            border-radius: 8px;
            font-size: 16px; 
            cursor: pointer; 
            font-weight: bold;
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        input[type="submit"]:active {
            transform: translateY(0);
        }
        input[type="reset"] {
            flex: 1;
            padding: 14px; 
            background-color: #f0f0f0;
            color: #333; 
            border: 2px solid #ddd; 
            border-radius: 8px;
            font-size: 16px; 
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        input[type="reset"]:hover {
            background-color: #e8e8e8;
            border-color: #bbb;
        }
        .message-succes {
            background-color: #d4edda; 
            color: #155724;
            border: 2px solid #c3e6cb; 
            padding: 14px 16px;
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 15px;
        }
        .message-erreur {
            background-color: #f8d7da; 
            color: #721c24;
            border: 2px solid #f5c6cb; 
            padding: 14px 16px;
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 15px;
        }
        .lien-liste {
            display: inline-block;
            text-align: center; 
            margin-top: 25px;
            width: 100%;
            font-size: 14px; 
            color: #667eea; 
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .lien-liste:hover {
            color: #764ba2;
        }
        @media (max-width: 600px) {
            .formulaire {
                padding: 35px 25px;
            }
            h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="formulaire">
    <h2>Ajouter un article</h2>

    <?php
    if (isset($_GET['statut'])) {
        if ($_GET['statut'] === 'ok') {
            echo '<div class="message-succes">&#10003; Article enregistr&eacute; avec succ&egrave;s !</div>';
        } elseif ($_GET['statut'] === 'doublon') {
            echo '<div class="message-erreur">&#10007; Ce code article existe d&eacute;j&agrave; dans la base.</div>';
        } else {
            $msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : "Erreur lors de l'enregistrement.";
            echo '<div class="message-erreur">&#10007; ' . $msg . '</div>';
        }
    }
    ?>

    <form action="enregistrer_article.php" method="POST">

        <div class="form-row">
            <div class="form-group">
                <label for="codart">Code article</label>
                <input type="text" id="codart" name="codart"
                       placeholder="ex: ART001" maxlength="20" required />
            </div>
            <div class="form-group">
                <label for="prix">Prix (FCFA)</label>
                <input type="number" id="prix" name="prix"
                       placeholder="0" step="1" min="0" required />
            </div>
        </div>

        <div class="form-row-full">
            <div class="form-group-full">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          placeholder="Description détaillée de l'article..." required></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <option value="photo">Photo</option>
                    <option value="Vidéo">Vidéo</option>
                    <option value="Informatique">Informatique</option>
                    <option value="divers">Divers</option>
                </select>
            </div>
            <div class="form-group"></div>
        </div>

        <div class="boutons">
            <input type="submit" value="✓ Enregistrer l'article" />
            <input type="reset"  value="↻ Réinitialiser" />
        </div>

    </form>

    <a class="lien-liste" href="liste_articles.php">&#8592; Voir la liste des articles</a>
</div>
</body>
</html>