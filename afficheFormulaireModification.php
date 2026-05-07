<?php
// Démarre la session
session_start();
require_once("fonctions.php");
// Récupère la table choisie et l'id
$table = $_SESSION['table'] ?? null;
$id    = $_GET['id'] ?? $_POST['id'] ?? null;
// Redirection si les données sont absentes
if (!$table || !$id) { header("Location: accueil.php"); exit; }

// Vérifie si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Modification de Destination
    if ($table === 'Destination') {
        updateDestination(array(
            'id_destination'     => $_POST['id'],
            'nom_destination'    => $_POST['nom_destination'],
            'zone_tarifaire'     => $_POST['zone_tarifaire'] ?: null,
            'code_pays'          => $_POST['code_pays'] ?: null,
            'devise'             => $_POST['devise'],
            'restrictions_envoi' => $_POST['restrictions_envoi'] ?: null
        ));
        // Modification de TypeEnvoi
    } elseif ($table === 'TypeEnvoi') {
        updateTypeEnvoi(array(
            'id_type_envoi'      => $_POST['id'],
            'nom_type_envoi'     => $_POST['nom_type_envoi'],
            'delai_livraison'    => $_POST['delai_livraison'],
            'assurance_possible' => $_POST['assurance_possible'] === 'true',
            'fragile'            => $_POST['fragile'] === 'true',
            'option_tarifaire'   => $_POST['option_tarifaire'] ?: null
        ));
         // Modification de Tarifer
    } elseif ($table === 'Tarifer') {
        updateTarifer(array(
            'id_tarif'       => $_POST['id'],
            'id_destination' => $_POST['id_destination'],
            'id_type_envoi'  => $_POST['id_type_envoi'],
            'poids_min'      => $_POST['poids_min'],
            'poids_max'      => $_POST['poids_max'],
            'tarif'          => $_POST['tarif'],
            'date_debut'     => $_POST['date_debut'],
            'date_fin'       => $_POST['date_fin'] ?: null
        ));
    }
     // Retour à la table après modification
    header("Location: afficheTable.php");
    exit;
}

$enreg     = array();
$listeDest = array();
$listeType = array();

// Récupère les données selon la table
if ($table === 'Destination') {
    $enreg = getDestinationById($id);
} elseif ($table === 'TypeEnvoi') {
    $enreg = getTypeEnvoiById($id);
} elseif ($table === 'Tarifer') {
    $enreg     = getTariferById($id);
    $listeDest = getAllDestination();
    $listeType = getAllTypeEnvoi();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification — <?= htmlspecialchars($table) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="entete">Tarifs Postaux</div>
<div class="contenu-small">
    <h2>Modifier — <?= htmlspecialchars($table) ?></h2>
    <div class="boite">
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <?php if ($table === 'Destination'): ?>
                <div class="groupe">
                    <label>ID (non modifiable)</label>
                    <input type="text" value="<?= htmlspecialchars($enreg['id_destination']) ?>" readonly>
                </div>
                <div class="groupe">
                    <label>Nom de la destination *</label>
                    <input type="text" name="nom_destination" value="<?= htmlspecialchars($enreg['nom_destination']) ?>" required>
                </div>
                <div class="groupe">
                    <label>Zone tarifaire</label>
                    <input type="text" name="zone_tarifaire" value="<?= htmlspecialchars($enreg['zone_tarifaire'] ?? '') ?>">
                </div>
                <div class="groupe">
                    <label>Code pays</label>
                    <input type="text" name="code_pays" value="<?= htmlspecialchars($enreg['code_pays'] ?? '') ?>" maxlength="5">
                </div>
                <div class="groupe">
                    <label>Devise *</label>
                    <input type="text" name="devise" value="<?= htmlspecialchars($enreg['devise']) ?>" required>
                </div>
                <div class="groupe">
                    <label>Restrictions envoi</label>
                    <input type="text" name="restrictions_envoi" value="<?= htmlspecialchars($enreg['restrictions_envoi'] ?? '') ?>">
                </div>
            <!-- Formulaire TypeEnvoi -->
            <?php elseif ($table === 'TypeEnvoi'): ?>
                <div class="groupe">
                    <label>ID (non modifiable)</label>
                    <input type="text" value="<?= htmlspecialchars($enreg['id_type_envoi']) ?>" readonly>
                </div>
                <div class="groupe">
                    <label>Nom type envoi *</label>
                    <input type="text" name="nom_type_envoi" value="<?= htmlspecialchars($enreg['nom_type_envoi']) ?>" required>
                </div>
                <div class="groupe">
                    <label>Delai livraison (heures) *</label>
                    <input type="number" name="delai_livraison" value="<?= htmlspecialchars($enreg['delai_livraison']) ?>" required min="0">
                </div>
                <div class="groupe">
                    <label>Assurance possible</label>
                    <select name="assurance_possible">
                        <option value="true"  <?= $enreg['assurance_possible']==='t' ? 'selected' : '' ?>>Oui</option>
                        <option value="false" <?= $enreg['assurance_possible']==='f' ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>
                <div class="groupe">
                    <label>Fragile</label>
                    <select name="fragile">
                        <option value="true"  <?= $enreg['fragile']==='t' ? 'selected' : '' ?>>Oui</option>
                        <option value="false" <?= $enreg['fragile']==='f' ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>
                <div class="groupe">
                    <label>Option tarifaire</label>
                    <input type="text" name="option_tarifaire" value="<?= htmlspecialchars($enreg['option_tarifaire'] ?? '') ?>">
                </div>
                <!-- Formulaire Tarifer -->
            <?php elseif ($table === 'Tarifer'): ?>
                <div class="groupe">
                    <label>ID (non modifiable)</label>
                    <input type="text" value="<?= htmlspecialchars($enreg['id_tarif']) ?>" readonly>
                </div>
                <div class="groupe">
                    <label>Destination</label>
                    <select name="id_destination">
                          <!-- Liste des destinations -->
                        <?php foreach ($listeDest as $d): ?>
                            <option value="<?= $d['id_destination'] ?>" <?= $d['id_destination']==$enreg['id_destination'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nom_destination']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="groupe">
                    <label>Type envoi</label>
                    <select name="id_type_envoi">
                         <!-- Liste des types d'envoi -->
                        <?php foreach ($listeType as $t): ?>
                            <option value="<?= $t['id_type_envoi'] ?>" <?= $t['id_type_envoi']==$enreg['id_type_envoi'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nom_type_envoi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="groupe">
                    <label>Poids minimum (g) *</label>
                    <input type="number" step="0.01" name="poids_min" value="<?= $enreg['poids_min'] ?>" required min="0">
                </div>
                <div class="groupe">
                    <label>Poids maximum (g) *</label>
                    <input type="number" step="0.01" name="poids_max" value="<?= $enreg['poids_max'] ?>" required>
                </div>
                <div class="groupe">
                    <label>Tarif *</label>
                    <input type="number" step="0.01" name="tarif" value="<?= $enreg['tarif'] ?>" required min="0">
                </div>
                <div class="groupe">
                    <label>Date debut *</label>
                    <input type="date" name="date_debut" value="<?= $enreg['date_debut'] ?>" required>
                </div>
                <div class="groupe">
                    <label>Date fin</label>
                    <input type="date" name="date_fin" value="<?= $enreg['date_fin'] ?? '' ?>">
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
