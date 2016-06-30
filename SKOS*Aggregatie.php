<?php
namespace home;

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
require_once 'SKOS_Transformer.php';

$client = ClientBuilder::create()->build();

echo date('Y-m-d H:m:s:u');

$params = [
    'index' => 'hzbwnature',
    'type' => 'attachment',
    'ids' => ['AVWdaiylKiyG0QdReM3P','AVWdahzyKiyG0QdReM3J','AVWdahlAKiyG0QdReM3H','AVWdagwtKiyG0QdReM3D']];

// Get doc at /my_index/my_type/my_id
$documents = $client->mtermvectors($params)['docs'];


$skostermen = array_map('str_getcsv', file('skos.csv'));
//klasse aanmaken
$SKOS_Transformer = new SKOS_Transformer();

/*
echo '<pre>';
print_r($documents);
echo '</pre>';
*/


// klasse aanroepen
$result = $SKOS_Transformer->documentloop($documents, $skostermen);
// output naar csv
$outputFile = fopen('output.csv', 'w');

/*
echo '<pre>';
print_r($result);
echo '</pre>';
*/
echo date('Y-m-d H:m:s:u');

foreach ($result as $key => $value) {

    $resultArray = array_merge(explode(';', $key), [$value[0]]); //explode maakt van de key string een array
    // arrays achter elkaar in de kollomen zetten
    foreach ($value[1] as $documentId => $termFreq) { // $value[1] is de lijst met documenten gekoppeld aan freq
        $resultArray[] = $documentId;
        $resultArray[] = $termFreq;
    }

    fputcsv($outputFile, $resultArray, ';');
}

fclose($outputFile);