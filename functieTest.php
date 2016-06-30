<?php
/**
 * Created by PhpStorm.
 * User: thientinlamngoc
 * Date: 27-06-16
 * Time: 23:28
 */

namespace home;

use Elasticsearch\ClientBuilder;

function print_pre ($object){ //weghalen
    echo '<pre>';
    print_r($object);
    echo '</pre>';
}

require 'vendor/autoload.php';
require_once 'SKOS_Transformer.php';


class FunctieTest extends \PHPUnit_Framework_TestCase {


    public function testPushAndPop()
    {
        $client = ClientBuilder::create()->build();


        $params = [
            'index' => 'hzbwnature',
            'type' => 'attachment',
            ];

// Get doc at /my_index/my_type/my_id
        $documents = $client->mtermvectors($params)['docs'];


        $skostermen = array_map('str_getcsv', file('skos.csv'));

        $functie = new Functie();

        $resultaat = $functie->documentloop();

        print_pre($resultaat);

        $this->assertArrayHasKey($resultaat,'dijk,boot');

        $this->assertArrayHasKey($resultaat,'dijk,boot');


    }




}
