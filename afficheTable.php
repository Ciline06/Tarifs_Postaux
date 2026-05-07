<?php
// Démarre la session
session_start();


require_once("fonctions.php");

// Vérifie si une table est envoyée par formulaire
if (isset($_POST['table'])) {

    $_SESSION['table'] = $_POST['table'];

} elseif (isset($_GET['table'])) {

    // Vérifie si une table est envoyée par URL
    $_SESSION['table'] = $_GET['table'];
}

// Récupère la table choisie
$table = $_SESSION['table'] ?? null;

// Redirection si aucune table n'est choisie
if (!$table) { 
    header("Location: accueil.php"); 
    exit; 
}

// Vérifie si une suppression est demandée
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {

    $id = $_GET['id'];

    // Supprime selon la table choisie
    if ($table === 'Destination') {

        deleteDestination($id);

    } elseif ($table === 'TypeEnvoi') {

        deleteTypeEnvoi($id);

    } elseif ($table === 'Tarifer') {

        deleteTarifer($id);
    }

    // Recharge la page après suppression
    header("Location: afficheTable.php");
    exit;
}


$liste    = array();
$colonnes = array();
$cle      = '';

// Récupère les données selon la table
if ($table === 'Destination') {

    $liste = getAllDestination();

    // Colonnes affichées
    $colonnes = array(
        'id_destination',
        'nom_destination',
        'zone_tarifaire',
        'code_pays',
        'devise',
        'restrictions_envoi'
    );

  
    $cle = 'id_destination';

} elseif ($table === 'TypeEnvoi') {

    $liste = getAllTypeEnvoi();

    $colonnes = array(
        'id_type_envoi',
        'nom_type_envoi',
        'delai_livraison',
        'assurance_possible',
        'fragile',
        'option_tarifaire'
    );

    $cle = 'id_type_envoi';

} elseif ($table === 'Tarifer') {

    $liste = getAllTarifer();

    $colonnes = array(
        'id_tarif',
        'nom_destination',
        'nom_type_envoi',
        'poids_min',
        'poids_max',
        'tarif',
        'date_debut',
        'date_fin'
    );

    $cle = 'id_tarif';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    
    <title>Table <?= htmlspecialchars($table) ?></title>

   
    <link rel="stylesheet" href="style.css">
</head>

<body>


<div class="entete">Tarifs Postaux</div>

<div class="contenu">


    <h2>Contenu de <?= htmlspecialchars($table) ?></h2>

    
    <div class="barre">

        <!-- Retour accueil -->
        <a href="accueil.php" class="btn btn-retour">
            Retour accueil
        </a>

        <!-- Ajouter un enregistrement -->
        <a href="afficheFormulaireInsertion.php" class="btn btn-inserer">
            Inserer un enregistrement
        </a>

    </div>

    <div class="tableau-wrap">

        <!-- Vérifie si la liste est vide -->
        <?php if (empty($liste)): ?>

            <div class="vide">
                Aucun enregistrement.
            </div>

        <?php else: ?>

        <!-- Tableau des données -->
        <table>

            <thead>

                <tr>

                    <!-- Affiche les noms des colonnes -->
                    <?php foreach ($colonnes as $col): ?>

                        <th>
                            <?= htmlspecialchars($col) ?>
                        </th>

                    <?php endforeach; ?>

                    <!-- Colonne actions -->
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

              
                <?php foreach ($liste as $ligne): ?>

                <tr>

                    
                    <?php foreach ($colonnes as $col): ?>

                        <td>

                            <?php
                           
                            $val = $ligne[$col] ?? '';

                            
                            if ($val === 't') {

                                echo '<span class="vrai">Oui</span>';

                            } elseif ($val === 'f') {

                                echo '<span class="faux">Non</span>';

                            } else {

                             
                                echo htmlspecialchars((string)$val);
                            }
                            ?>

                        </td>

                    <?php endforeach; ?>

                   
                    <td class="act">

                        <!-- Voir détail -->
                        <a href="afficheDetailEnregistrement.php?id=<?= urlencode($ligne[$cle]) ?>"
                           class="btn btn-detail">

                           Detailler
                        </a>

                        <!-- Modifier -->
                        <a href="afficheFormulaireModification.php?id=<?= urlencode($ligne[$cle]) ?>"
                           class="btn btn-modif">

                           Modifier
                        </a>

                        <!-- Supprimer -->
                        <a href="afficheTable.php?action=supprimer&id=<?= urlencode($ligne[$cle]) ?>"
                           class="btn btn-suppr"
                           onclick="return confirm('Supprimer cet enregistrement ?')">

                           Supprimer
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <?php endif; ?>

    </div>
</div>

</body>
</html>