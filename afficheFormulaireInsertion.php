<?php
// Démarre la session
session_start();
require_once("fonctions.php");
// Récupère la table choisie
$table = $_SESSION['table'] ?? null;
// Redirection si aucune table n'est choisie
if (!$table) { header("Location: accueil.php"); exit; }
// Vérifie si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertion dans la table Destination
    if ($table === 'Destination') {
        insertDestination(array(
            'nom_destination'    => $_POST['nom_destination'],
            'zone_tarifaire'     => $_POST['zone_tarifaire'] ?: null,
            'code_pays'          => $_POST['code_pays'] ?: null,
            'devise'             => $_POST['devise'],
            'restrictions_envoi' => $_POST['restrictions_envoi'] ?: null
        ));
        // Insertion dans la table TypeEnvoi
    } elseif ($table === 'TypeEnvoi') {
        insertTypeEnvoi(array(
            'nom_type_envoi'     => $_POST['nom_type_envoi'],
            'delai_livraison'    => $_POST['delai_livraison'],
            // Convertit true/false en booléen
            'assurance_possible' => $_POST['assurance_possible'] === 'true',
            'fragile'            => $_POST['fragile'] === 'true',

            'option_tarifaire'   => $_POST['option_tarifaire'] ?: null
        ));
          // Insertion dans la table Tarifer
    } elseif ($table === 'Tarifer') {
        insertTarifer(array(
            'id_destination' => $_POST['id_destination'],
            'id_type_envoi'  => $_POST['id_type_envoi'],
            'poids_min'      => $_POST['poids_min'],
            'poids_max'      => $_POST['poids_max'],
            'tarif'          => $_POST['tarif'],
            'date_debut'     => $_POST['date_debut'],
            'date_fin'       => $_POST['date_fin'] ?: null
        ));
    }
     // Retour vers la table après insertion
    header("Location: afficheTable.php");
    exit;
}

// Récupère les destinations et types d'envoi
$listeDest = ($table === 'Tarifer') ? getAllDestination() : array();
$listeType = ($table === 'Tarifer') ? getAllTypeEnvoi()   : array();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Insertion — <?= htmlspecialchars($table) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="entete">Tarifs Postaux</div>
<div class="contenu-small">
    <h2>Inserer — <?= htmlspecialchars($table) ?></h2>
    <div class="boite">
        <form method="post">
          <!-- Formulaire Destination  -->
            <?php if ($table === 'Destination'): ?>
                <div class="groupe">
                    <label>Nom de la destination *</label>
                    <input type="text" name="nom_destination" required>
                </div>
                <div class="groupe">
                    <label>Zone tarifaire</label>
                    <input type="text" name="zone_tarifaire">
                </div>
                <div class="groupe">
                    <label>Code pays</label>
                    <input type="text" name="code_pays" maxlength="5">
                </div>
                <div class="groupe">
                    <label>Devise *</label>
                    <input type="text" name="devise" required>
                </div>
                <div class="groupe">
                    <label>Restrictions envoi</label>
                    <input type="text" name="restrictions_envoi">
                </div>
         <!-- Formulaire TypeEnvoi -->
            <?php elseif ($table === 'TypeEnvoi'): ?>
                <div class="groupe">
                    <label>Nom type envoi *</label>
                    <input type="text" name="nom_type_envoi" required>
                </div>
                <div class="groupe">
                    <label>Delai livraison (heures) *</label>
                    <input type="number" name="delai_livraison" required min="0">
                </div>
                <div class="groupe">
                    <label>Assurance possible</label>
                    <select name="assurance_possible">
                        <option value="false">Non</option>
                        <option value="true">Oui</option>
                    </select>
                </div>
                <div class="groupe">
                    <label>Fragile</label>
                    <select name="fragile">
                        <option value="false">Non</option>
                        <option value="true">Oui</option>
                    </select>
                </div>
                <div class="groupe">
                    <label>Option tarifaire</label>
                    <input type="text" name="option_tarifaire">
                </div>
        <!-- Formulaire Tarifer -->
            <?php elseif ($table === 'Tarifer'): ?>
                <div class="groupe">
                    <label>Destination *</label>
                    <select name="id_destination" required>
                        <?php foreach ($listeDest as $d): ?>
                            <option value="<?= $d['id_destination'] ?>"><?= htmlspecialchars($d['nom_destination']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="groupe">
                    <label>Type envoi *</label>
                    <select name="id_type_envoi" required>
                        
                        <!-- Affiche les types d'envoi -->
                        <?php foreach ($listeType as $t): ?>
                            <option value="<?= $t['id_type_envoi'] ?>"><?= htmlspecialchars($t['nom_type_envoi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="groupe">
                    <label>Poids minimum (g) *</label>
                    <input type="number" step="0.01" name="poids_min" min="0" value="0" required>
                </div>
                <div class="groupe">
                    <label>Poids maximum (g) *</label>
                    <input type="number" step="0.01" name="poids_max" min="0" required>
                </div>
                <div class="groupe">
                    <label>Tarif *</label>
                    <input type="number" step="0.01" name="tarif" min="0" required>
                </div>
                <div class="groupe">
                    <label>Date debut *</label>
                    <input type="date" name="date_debut" required>
                </div>
                <div class="groupe">
                    <label>Date fin</label>
                    <input type="date" name="date_fin">
                </div>
            <?php endif; ?>

            <div class="barre">
                <!-- Bouton enregistrer -->
                <button type="submit">Enregistrer</button>
                 <!-- Bouton annuler -->
                <a href="afficheTable.php" class="btn-annuler">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
