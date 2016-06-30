<?php
require 'vendor/autoload.php';
//$client = elasticsearch\Clientbuilder::create()->build();
$client = \Elasticsearch\ClientBuilder::create()->build();

//index aanmaken,type declareren en id
$params = [
    'index' => 'hzbwnature',
    'type' => 'attachment',
    "ids" => ["avwtbhmtdo33cilcv6ek"],
];

// multi termvectoren opvragen
$documents = $client->mtermvectors($params)['docs'];
//zet csv file om in array
$skostermen = array_map('str_getcsv', file('skos.csv'));

$result = [];

foreach ($documents as $document) {
//loopt over alle documenten heen en zoekt op termen op basis van resultaten in json
    $termen_gevonden = $document['term_vectors']['content.content']['terms'];

    // loopt over de skos termen heen
    foreach ($skostermen as $skos_term) {
        //gevonden termen tegenover de skos termen zetten
        if (in_array(strtolower($skos_term[0]), array_keys($termen_gevonden)))
            // loopt vervolgens weer over de gevonden zoektermen en voegt ze toe in de array om in de csv te zetten
            foreach ($termen_gevonden as $term => $values) {
                $result[] = [$skos_term[0], $term, $values['term_freq'], $document['_id']];
            }
        }

}

$file = fopen('file.csv', 'w');


foreach ($result as $fields) {
    fputcsv($file, $fields, ';');
}

fclose($file);

?>

