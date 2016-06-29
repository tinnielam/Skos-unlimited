<?php
namespace home;

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
require_once 'functie.php';

$client = ClientBuilder::create()->build();


$params = [
    'index' => 'hzbwnature',
    'type' => 'attachment',
    "ids" => ["AVWTiv_r2INwjrJt81EA", "AVWTivqB2INwjrJt81D-", "AVWTit3t2INwjrJt81D6"],
];

// Get doc at /my_index/my_type/my_id
$documents = $client->mtermvectors($params)['docs'];
$skostermen = array_map('str_getcsv', file('skos.csv'));
//klasse aanmaken
$functie = new Functie();
// klasse aanroepen
$result = $functie->documentloop($documents, $skostermen);
// output naar csv
$outputFile = fopen('output.csv', 'w');

/*
echo '<pre>';
print_r($result);
echo '</pre>';
*/

foreach ($result as $part1 => $value) {

    $resultArray = array_merge(explode(';', $part1), [$value[0]]);
// arrays achter elkaar in de kollomen zetten
    foreach ($value[1] as $documentId => $termFreq) {
        $resultArray[] = $documentId;
        $resultArray[] = $termFreq;
    }

    fputcsv($outputFile, $resultArray, ';');
}

fclose($outputFile);