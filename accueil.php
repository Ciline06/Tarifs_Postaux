<?php 
// Démarre la sessionsession_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="entete">Tarifs Postaux</div>
<div class="contenu-small">
    <h2>Accueil</h2>
    <div class="boite">
        <!-- Formulaire envoyé vers afficheTable.php -->
        <form action="afficheTable.php" method="post">
            <div class="groupe">
                 <!-- Liste des tables disponibles -->
                <label for="table">Choisir une table :</label>
                <select name="table" id="table">
                    <option value="Destination">Destination</option>
                    <option value="TypeEnvoi">TypeEnvoi</option>
                    <option value="Tarifer">Tarifer</option>
                </select>
            </div>
            
            <!-- Bouton pour envoyer le formulaire -->
            <button type="submit">Consulter</button>
        </form>
    </div>
</div>
</body>
</html>
