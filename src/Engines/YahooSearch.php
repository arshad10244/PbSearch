<?php
/**
 * Created by PhpStorm.
 * User: Arshad
 * Date: 05-Mar-19
 * Time: 11:43 AM
 */

namespace Arshad\PbSearch\Engines;

use Arshad\PbSearch\Interfaces\PbSearchInterface as SearchInterface;
use GuzzleHttp\Client as HttpClient;


class YahooSearch implements SearchInterface
{

    protected $client;
    private $results;
    private $xpath_parent_container = "//ol[contains(concat(' ',normalize-space(@class),' '),' searchCenterMiddle ')]/li";
    private $xpath_link_container = ".//h3/a";
    private $xpath_title_container = ".//h3/a";
    private $url = "http://search.yahoo.com/search?p=";
    private $keywords;


    public function __construct(\Arshad\PbSearch\Models\SearchResults $results)
    {
        $this->results = $results;
        $this->client = new HttpClient(['verify'=>false]);
    }

    public function setXpaths($parentContainer = "", $linkContainer = "", $titleContainer = "")
    {
        if ($parentContainer != "") {

            $this->xpath_parent_container = $parentContainer;

        }
        if ($linkContainer != "") {

            $this->xpath_link_container = $linkContainer;

        }
        if ($titleContainer != "") {

            $this->xpath_title_container = $titleContainer;

        }
    }

    public function find($keywords = array())
    {
        $this->results->google = array();
        $this->keywords = $keywords;
        $this->makeRequest();

    }


    private function makeRequest()
    {
        foreach ($this->keywords as $keyword) {

            try {

                $response = $this->client->request("GET", $this->url . $keyword);
                $data = $response->getBody()->getContents();

            } catch (\Exception $exception) {

                echo $exception->getMessage();
            }

            $this->addData($data);
        }


    }

    private function addData($dataBody)
    {
        $document = new \DOMDocument();

        @$document->loadHTML($dataBody);

        $xpath = new \DOMXPath($document);

        $items = $xpath->query($this->xpath_parent_container);


        foreach ($items as $item) {

            $url = @$xpath->query($this->xpath_link_container, $item);

            if($url->item(0) !== null){

                $url = $url->item(0)->getAttribute('href');
            }
            else{

                $url = "";
            }

            $title = @$xpath->query($this->xpath_title_container, $item)->item(0)->nodeValue;

            $data = new \stdClass();
            $data->url = $url;
            $data->title = $title;
            $this->results->url = $data;
            $this->results->data->yahoo[] = $data;


        }


    }
}