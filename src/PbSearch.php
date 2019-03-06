<?php
/**
 * Created by PhpStorm.
 * User: Arshad
 * Date: 05-Mar-19
 * Time: 11:31 AM
 */

namespace Arshad\PbSearch;

use Arshad\PbSearch\Engines;
use Arshad\PbSearch\Models\SearchResults;


class PbSearch
{

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