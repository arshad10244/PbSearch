<?php
/**
 * Class for handling execution
 */

namespace Arshad\PbSearch;

use Arshad\PbSearch\Engines;
use Arshad\PbSearch\Models\SearchResults;


class PbSearch
{

    /**
     * Accepts keywords array and performs search on each engine implementation.
     *
     * @param array $keywords array of keywords to search
     * @return mixed array of results
     */
    public function search($keywords=array())
    {
        $results = new SearchResults();

        $google = new Engines\GoogleSearch($results);
        $google->find($keywords);

        $yahoo = new Engines\YahooSearch($results);
        $yahoo->find($keywords);

        return $results->getResults();


    }
}