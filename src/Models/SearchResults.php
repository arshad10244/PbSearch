<?php
/**
 * Model Class for holding and formatting results of searches from different engines
 */
namespace Arshad\PbSearch\Models;


class SearchResults
{
    /**
     * Set all results to this std class
     * @var \stdClass
     */
    public $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }


    /**
     * Performs cleaning and returns array of results
     *
     * @return array cleaned array of results from all engines
     * @uses SearchResults::cleanData() to perform cleanup
     */
    public function getResults()
    {
        return $this->cleanData();

    }


    /**
     * uses SearchResults::$data to access the results
     * @return array formatted data
     */
    private function cleanData()
    {
        $cleanedData = array();

        foreach ($this->data as $engineName=>$engineResults) {

            foreach ($engineResults as $result) {

                if($result->url != "" && $result->title != "") {

                    //normalize the url
                    $domain  = preg_replace(['(https?:\/\/)','(www\.)','(\/$)'],["","",""],$result->url);
                    $data = array();
                    $data['url'] = $result->url;
                    $data['title'] = $result->title;

                    // check if md5 of domain exists, if it does add to the source
                    if(key_exists(md5($domain),$cleanedData)){

                       array_push($cleanedData[md5($domain)]['source'],$engineName);
                    }

                    // else add as new result
                    else {

                        $data['source'] = [$engineName];
                        $cleanedData[md5($domain)] = $data;
                    }
                }

            }


        }

        return $cleanedData;

    }



}