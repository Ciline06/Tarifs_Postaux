<?php
require 'fonctions.php';

echo "<h3>Test getAllDestination (début)</h3>";
print_r(getAllDestination());


//  TEST getById
echo "<h3>Test getDestinationById (ID existant)</h3>";
print_r(getDestinationById(1));

echo "<h3>Test getDestinationById (ID inexistant)</h3>";
print_r(getDestinationById(999));


//  TEST INSERT
echo "<h3>Test insertDestination</h3>";

$nouvelleDest = [
    'nom_destination' => 'Italie',
    'zone_tarifaire' => 'Zone 1',
    'code_pays' => 'IT',
    'devise' => 'EUR',
    'restrictions_envoi' => NULL
];

insertDestination($nouvelleDest);

// récupérer le dernier élément ajouté
$allDest = getAllDestination();
$dernier = $allDest[count($allDest) - 1];

print_r($dernier);


//  TEST UPDATE
echo "<h3>Test updateDestination</h3>";

// récupérer un élément existant
$dest = getDestinationById($dernier['id_destination']);

if (isset($dest['id_destination'])) {

    $dest['zone_tarifaire'] = 'Zone X';
    $dest['restrictions_envoi'] = 'Test update';

    updateDestination($dest);

    // vérifier
    print_r(getDestinationById($dest['id_destination']));

} else {
    echo "Erreur : ID non trouvé";
}


//  TEST DELETE
echo "<h3>Test deleteDestination</h3>";

$id = $dernier['id_destination'];
deleteDestination($id);

// vérifier suppression
print_r(getDestinationById($id));


//  TEST FINAL
echo "<h3>Test getAllDestination (fin)</h3>";
print_r(getAllDestination());

?>