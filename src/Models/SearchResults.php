<?php
/**
 * Created by PhpStorm.
 * User: Arshad
 * Date: 05-Mar-19
 * Time: 1:51 PM
 */

namespace Arshad\PbSearch\Models;


class SearchResults
{

    public $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }

    public function getResults()
    {
        return $this->cleanData();

    }

    private function cleanData()
    {
        $cleanedData = array();

        foreach ($this->data as $engineName=>$engineResults) {

            foreach ($engineResults as $result) {

                if($result->url != "" && $result->title != "") {

                    $domain  = preg_replace(['(https?:\/\/)','(www\.)','(\/$)'],["","",""],$result->url);
                    $data = array();
                    $data['url'] = $result->url;
                    $data['title'] = $result->title;

                    if(key_exists(md5($domain),$cleanedData)){

                       array_push($cleanedData[md5($domain)]['source'],$engineName);
                    }

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