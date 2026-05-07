<?php
// Importe le fichier de connexion
require_once("monEnv.php");

// FONCTIONS DESTINATION


// Récupère une destination par son id
function getDestinationById($id) {
    $db = connexion();
    pg_prepare($db, "selDestId", "SELECT * FROM G02_Destination WHERE id_destination=$1");
    $res = pg_execute($db, "selDestId", array($id));
    $ligne = pg_fetch_assoc($res);
    pg_free_result($res); pg_close($db);
    return $ligne ? $ligne : array();
}
// Récupère toutes les destinations
function getAllDestination() {
    $db = connexion();
    pg_prepare($db, "selDestAll", "SELECT * FROM G02_Destination ORDER BY nom_destination");
    $res = pg_execute($db, "selDestAll", array());
    $liste = array();
    while ($ligne = pg_fetch_assoc($res)) $liste[] = $ligne;
    pg_free_result($res); pg_close($db);
    return $liste;
}

// Ajoute une destination
function insertDestination($data) {
    $db = connexion();
    pg_prepare($db, "insDest",
        "INSERT INTO G02_Destination (nom_destination, zone_tarifaire, code_pays, devise, restrictions_envoi)
         VALUES ($1, $2, $3, $4, $5)");
    pg_execute($db, "insDest", array(
        $data['nom_destination'], $data['zone_tarifaire'],
        $data['code_pays'], $data['devise'], $data['restrictions_envoi']
    ));
    pg_close($db);
}
// Modifie une destination
function updateDestination($data) {
    $db = connexion();
    pg_prepare($db, "updDest",
        "UPDATE G02_Destination SET nom_destination=$1, zone_tarifaire=$2, code_pays=$3,
         devise=$4, restrictions_envoi=$5 WHERE id_destination=$6");
    pg_execute($db, "updDest", array(
        $data['nom_destination'], $data['zone_tarifaire'], $data['code_pays'],
        $data['devise'], $data['restrictions_envoi'], $data['id_destination']
    ));
    pg_close($db);
}
// Supprime une destination
function deleteDestination($id) {
    $db = connexion();
    pg_prepare($db, "delDest", "DELETE FROM G02_Destination WHERE id_destination=$1");
    pg_execute($db, "delDest", array($id));
    pg_close($db);
}

// FONCTIONS TYPE ENVOI


// Récupère un type d'envoi par id
function getTypeEnvoiById($id) {
    $db = connexion();
    pg_prepare($db, "selTypeId", "SELECT * FROM G02_TypeEnvoi WHERE id_type_envoi=$1");
    $res = pg_execute($db, "selTypeId", array($id));
    $ligne = pg_fetch_assoc($res);
    pg_free_result($res); pg_close($db);
    return $ligne ? $ligne : array();
}

// Récupère tous les types d'envoi
function getAllTypeEnvoi() {
    $db = connexion();
    pg_prepare($db, "selTypeAll", "SELECT * FROM G02_TypeEnvoi ORDER BY nom_type_envoi");
    $res = pg_execute($db, "selTypeAll", array());
    $liste = array();
    while ($ligne = pg_fetch_assoc($res)) $liste[] = $ligne;
    pg_free_result($res); pg_close($db);
    return $liste;
}
// Ajoute un type d'envoi
function insertTypeEnvoi($data) {
    $db = connexion();
    pg_prepare($db, "insType",
        "INSERT INTO G02_TypeEnvoi (nom_type_envoi, delai_livraison, assurance_possible, fragile, option_tarifaire)
         VALUES ($1, $2, $3, $4, $5)");
    pg_execute($db, "insType", array(
        $data['nom_type_envoi'], $data['delai_livraison'],
        $data['assurance_possible'] ? 'true' : 'false',
        $data['fragile'] ? 'true' : 'false',
        $data['option_tarifaire']
    ));
    pg_close($db);
}
// Modifie un type d'envoi
function updateTypeEnvoi($data) {
    $db = connexion();
    pg_prepare($db, "updType",
        "UPDATE G02_TypeEnvoi SET nom_type_envoi=$1, delai_livraison=$2, assurance_possible=$3,
         fragile=$4, option_tarifaire=$5 WHERE id_type_envoi=$6");
    pg_execute($db, "updType", array(
        $data['nom_type_envoi'], $data['delai_livraison'],
        $data['assurance_possible'] ? 'true' : 'false',
        $data['fragile'] ? 'true' : 'false',
        $data['option_tarifaire'], $data['id_type_envoi']
    ));
    pg_close($db);
}
// Supprime un type d'envoi
function deleteTypeEnvoi($id) {
    $db = connexion();
    pg_prepare($db, "delType", "DELETE FROM G02_TypeEnvoi WHERE id_type_envoi=$1");
    pg_execute($db, "delType", array($id));
    pg_close($db);
}
// FONCTIONS TARIFER

// Récupère un tarif par id
function getTariferById($id) {
    $db = connexion();
    pg_prepare($db, "selTarifId",
        "SELECT t.*, d.nom_destination, e.nom_type_envoi
         FROM G02_Tarifer t
         JOIN G02_Destination d ON t.id_destination = d.id_destination
         JOIN G02_TypeEnvoi e ON t.id_type_envoi = e.id_type_envoi
         WHERE t.id_tarif=$1");
    $res = pg_execute($db, "selTarifId", array($id));
    $ligne = pg_fetch_assoc($res);
    pg_free_result($res); pg_close($db);
    return $ligne ? $ligne : array();
}
// Récupère tous les tarifs
function getAllTarifer() {
    $db = connexion();
    pg_prepare($db, "selTarifAll",
        "SELECT t.*, d.nom_destination, e.nom_type_envoi
         FROM G02_Tarifer t
         JOIN G02_Destination d ON t.id_destination = d.id_destination
         JOIN G02_TypeEnvoi e ON t.id_type_envoi = e.id_type_envoi
         ORDER BY t.date_debut DESC");
    $res = pg_execute($db, "selTarifAll", array());
    $liste = array();
    while ($ligne = pg_fetch_assoc($res)) $liste[] = $ligne;
    pg_free_result($res); pg_close($db);
    return $liste;
}
// Ajoute un tarif
function insertTarifer($data) {
    $db = connexion();
    pg_prepare($db, "insTarif",
        "INSERT INTO G02_Tarifer (id_destination, id_type_envoi, poids_min, poids_max, tarif, date_debut, date_fin)
         VALUES ($1, $2, $3, $4, $5, $6, $7)");
    pg_execute($db, "insTarif", array(
        $data['id_destination'], $data['id_type_envoi'], $data['poids_min'],
        $data['poids_max'], $data['tarif'], $data['date_debut'], $data['date_fin']
    ));
    pg_close($db);
}
// Modifie un tarif
function updateTarifer($data) {
    $db = connexion();
    pg_prepare($db, "updTarif",
        "UPDATE G02_Tarifer SET id_destination=$1, id_type_envoi=$2, poids_min=$3,
         poids_max=$4, tarif=$5, date_debut=$6, date_fin=$7 WHERE id_tarif=$8");
    pg_execute($db, "updTarif", array(
        $data['id_destination'], $data['id_type_envoi'], $data['poids_min'],
        $data['poids_max'], $data['tarif'], $data['date_debut'], $data['date_fin'],
        $data['id_tarif']
    ));
    pg_close($db);
}
// Supprime un tarif
function deleteTarifer($id) {
    $db = connexion();
    pg_prepare($db, "delTarif", "DELETE FROM G02_Tarifer WHERE id_tarif=$1");
    pg_execute($db, "delTarif", array($id));
    pg_close($db);
}
?>
