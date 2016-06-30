<?php

namespace home;


/**
 * Created by PhpStorm.
 * User: thientinlamngoc
 * Date: 12-06-16
 * Time: 23:22
 */
class SKOS_Transformer
{

    /**
     * 
     * @param array $documents
     * @param array $skostermen
     * @return array
     */
    
    function documentloop(array $documents, array $skostermen)
    {
        $gevondenRegels = [];
        // loopen over alle documenten heen
        foreach ($documents as $document) {

        //loopen over de term vectoren de content en de termen
            $termenGevonden = $document['term_vectors']['content.content']['terms'];

            //print_pre($document);
            // loopt over de skos termen heen
            foreach ($skostermen as $skos_term) {
                // loopt vervolgens weer over de gevonden zoektermen en voegt ze toe in de array om in de csv te zetten
                $skostermlower = strtolower($skos_term[0]);
                //
                if (in_array($skostermlower, array_keys($termenGevonden))) {
                    // gevonden termen worden geloopt
                    foreach ($termenGevonden as $term => $waardes) {
                        // key aanmaken met skosterm en gevonden term
                        $key = $skostermlower . ';' . $term;
/*
                        [
                            ['skosterm;term'] => [opgetelde term freq, ['document_id'=>'term_freq', 'document_id2'=>'termfreq',..]

                        ]
*/
                        if (!isset($gevondenRegels[$key])) {
                            $gevondenRegels[$key] =
                                array($waardes['term_freq'],
                                    array($document['_id'] => $waardes['term_freq'])
                                );
                        } else {
                            $gevondenRegels[$key][0] += $waardes['term_freq']; // 0 = term_freq opgeteld

                            if (!isset($gevondenRegels[$key][1][$document['_id']])) {
                                $gevondenRegels[$key][1][$document['_id']] = $waardes['term_freq'];
                                // 1 array[document_id => term_freq];
                            } else {
                                $gevondenRegels[$key][1][$document['_id']] += $waardes['term_freq'];
                            }
                        }
                    }
                }
            }
        }
        return $gevondenRegels;
    }
}