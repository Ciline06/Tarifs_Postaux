<?php
// Démarre la session
session_start();
require_once("fonctions.php");
// Récupère la table choisie et l'id
$table = $_SESSION['table'] ?? null;
$id    = $_GET['id'] ?? null;
if (!$table || !$id)
    // Redirection vers page d'accueil  si les données sont absentes 
{ header("Location: accueil.php"); exit; }

$enreg  = array();
$tarifs = array();

if ($table === 'Destination') {
    // Récupère la destination par ID 
    $enreg = getDestinationById($id);
    // Recherche les tarifs liés à cette destination
    foreach (getAllTarifer() as $t) {
        if ($t['id_destination'] == $id) $tarifs[] = $t;
    }
} elseif ($table === 'TypeEnvoi') {
 // Récupère le type d'envoi par ID 
   $enreg = getTypeEnvoiById($id);
   // Recherche les tarifs liés à ce type d'envoi
    foreach (getAllTarifer() as $t) {
        if ($t['id_type_envoi'] == $id) $tarifs[] = $t;
    }
    // Si la table est Tarifer
} elseif ($table === 'Tarifer') {
     // Récupère le tarif
    $enreg = getTariferById($id);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Detail enregistrement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="entete">Tarifs Postaux</div>
<div class="contenu-small">
    <h2>Detail — <?= htmlspecialchars($table) ?></h2>
    <div class="boite">
        <?php foreach ($enreg as $nom => $val): ?>
        <div class="champ">
            <span class="nom-champ"><?= htmlspecialchars($nom) ?> :</span>
            <span class="val-champ">
                <?php
                if ($val === 't') echo '<span class="vrai">Oui</span>';
                elseif ($val === 'f') echo '<span class="faux">Non</span>';
                else echo htmlspecialchars((string)($val ?? '-'));
                ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- Affiche les tarifs associés -->
    <?php if (!empty($tarifs)): ?>
    <div class="boite">
        <h3>Tarifs associes</h3>
        <?php foreach ($tarifs as $t): ?>
        <div class="ligne-tarif">
            <?php if ($table === 'Destination'): ?>
                 <!-- Affichage pour une destination -->
                <?= htmlspecialchars($t['nom_type_envoi']) ?> — <?= $t['poids_min'] ?>g a <?= $t['poids_max'] ?>g — <?= $t['tarif'] ?> EUR (<?= $t['date_debut'] ?> au <?= $t['date_fin'] ?? 'indefini' ?>)
            <?php else: ?>
                <!-- Affichage pour un type d'envoi -->
                <?= htmlspecialchars($t['nom_destination']) ?> — <?= $t['poids_min'] ?>g a <?= $t['poids_max'] ?>g — <?= $t['tarif'] ?> EUR (<?= $t['date_debut'] ?> au <?= $t['date_fin'] ?? 'indefini' ?>)
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="barre">
         <!-- Retour -->
        <a href="afficheTable.php" class="btn btn-retour">Retour a la table</a>
        <!-- Modification -->
        <a href="afficheFormulaireModification.php?id=<?= urlencode($id) ?>" class="btn btn-modif">Modifier</a>
         <!-- Suppression -->
        <a href="afficheTable.php?action=supprimer&id=<?= urlencode($id) ?>" class="btn btn-suppr" onclick="return confirm('Supprimer ?')">Supprimer</a>
    </div>
</div>
</body>
</html>
