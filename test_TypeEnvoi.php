<?php
require 'fonctions.php'; // inclure les fonctions CRUD

//  TEST : récupérer tous les enregistrements
echo "<h3>Test getAllTypeEnvoi</h3>";
print_r(getAllTypeEnvoi());

//  TEST : insertion d’un nouveau type d’envoi
echo "<h3>Test insertTypeEnvoi</h3>";
$t = array(
    "nom_type_envoi" => "Express",
    "delai_livraison" => 24,
    "assurance_possible" => true,
    "fragile" => false,
    "option_tarifaire" => "Standard"
);

// insérer dans la base
insertTypeEnvoi($t);

// afficher après insertion
print_r(getAllTypeEnvoi());


//  TEST : récupérer un enregistrement par ID
echo "<h3>Test getTypeEnvoiById</h3>";
print_r(getTypeEnvoiById(1));

//  TEST : mise à jour
echo "<h3>Test updateTypeEnvoi</h3>";

// récupérer un enregistrement existant
$t = getTypeEnvoiById(1);

// vérifier si l'enregistrement existe
if (isset($t['id_type_envoi'])) {

    // modifier plusieurs champs
    $t['nom_type_envoi'] = "Modifié";
    $t['delai_livraison'] = 48;
    $t['assurance_possible'] = false;
    $t['fragile'] = true;
    $t['option_tarifaire'] = "Premium";

    // faire la mise à jour
    updateTypeEnvoi($t);

    // afficher le résultat après modification
    print_r(getTypeEnvoiById(1));

} else {
    // si ID inexistant
    echo "Erreur : ID non trouvé";
}

//  TEST : suppression
echo "<h3>Test deleteTypeEnvoi</h3>";
deleteTypeEnvoi(1);

// afficher après suppression
print_r(getAllTypeEnvoi());

?>