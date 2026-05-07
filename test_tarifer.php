<?php
require_once("fonctions.php");;


echo "<h3>Test getAllTarifer (vide au début)</h3>";
print_r(getAllTarifer());


// Récupérer des données existantes
$dest = getAllDestination();
$type = getAllTypeEnvoi();

if (empty($dest) || empty($type)) {
    die("Erreur : Destination ou TypeEnvoi vide");
}


// TEST INSERT
echo "<h3>Test insertTarifer</h3>";

$t = [
    'id_destination' => $dest[0]['id_destination'],
    'id_type_envoi' => $type[0]['id_type_envoi'],
    'poids_min' => 0,
    'poids_max' => 50,
    'tarif' => 10.5,
    'date_debut' => '2026-01-01',
    'date_fin' => '2026-12-31'
];

insertTarifer($t);

$all = getAllTarifer();
print_r($all);


// TEST GET BY ID
echo "<h3>Test getTariferById</h3>";

$id = $all[count($all)-1]['id_tarif'];
print_r(getTariferById($id));


//  TEST UPDATE
echo "<h3>Test updateTarifer</h3>";

// récupérer l'enregistrement
$t = getTariferById($id);

if (isset($t['id_tarif'])) {

    // modifier les champs
    $t['poids_min'] = 1;
    $t['poids_max'] = 60;
    $t['tarif'] = 15.0;

    // update
    updateTarifer($t);

    // vérifier
    print_r(getTariferById($id));

} else {
    echo "Erreur : ID non trouvé";
}


//  TEST DELETE
echo "<h3>Test deleteTarifer</h3>";

deleteTarifer($id);

print_r(getAllTarifer());

?>
