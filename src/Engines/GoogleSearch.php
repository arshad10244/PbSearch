<?php
/**
 * Google Search Scrapping Class
 */

namespace Arshad\PbSearch\Engines;

use Arshad\PbSearch\Interfaces\PbSearchInterface as SearchInterface;
use GuzzleHttp\Client as HttpClient;


class GoogleSearch implements SearchInterface
{
    /**
     * Http client
     * @var HttpClient
     */
    protected $client;

    /**
     * Results Model
     * @var \Arshad\PbSearch\Models\SearchResults
     */
    private $results;

    /**
     * @var string parent container xpath
     */
    private $xpath_parent_container = "//div[@class='g']";

    /**
     * @var string xpath for extracting link
     */
    private $xpath_link_container = ".//h3/a";

    /**
     * @var string xpath for extracting result title
     */
    private $xpath_title_container = ".//h3/a";

    /**
     * @var string engine url
     */
    private $url = "http://google.com/search?q=";

    /**
     * @var keywords to search
     */
    private $keywords;


    public function __construct(\Arshad\PbSearch\Models\SearchResults $results)
    {
        $this->results = $results;
        $this->client = new HttpClient();
    }


    /**
     * Override default xpaths
     *
     * @param string $parentContainer
     * @param string $linkContainer
     * @param string $titleContainer
     * @return void
     */
    public function setXpaths($parentContainer = "",$linkContainer = "",$titleContainer = "")
    {
        if($parentContainer != "") {

            $this->xpath_parent_container = $parentContainer;

        } if($linkContainer != "") {

            $this->xpath_link_container = $linkContainer;

        } if($titleContainer != "") {

             $this->xpath_title_container = $titleContainer;

        }
    }


    /**
     * Main function for calling the class
     *
     * @param array $keywords array of keywords to search
     */
    public function find($keywords=array())
    {
        // create new object in results model for google
        $this->results->google = array();

        // set the keywords
        $this->keywords = $keywords;

        // start making requests
        $this->makeRequest();

    }


    /**
     * Loops through keywords and makes http calls
     *
     * @uses GoogleSearch::addData() to extract data and add to results model
     * @return void
     */
    private function makeRequest()
    {
        foreach($this->keywords as $keyword)  {

            try {

                $response = $this->client->request("GET", $this->url . $keyword);
                $data =  $response->getBody()->getContents();

            } catch (\Exception $exception) {

                echo $exception->getMessage();
            }

            // extract data and add to results model
            $this->addData($data);
        }


    }

    /**
     * Accepts html page and extracts required data and adds to results model
     * @param $dataBody string html data from the request
     */
    private function addData($dataBody)
    {
        $document = new \DOMDocument();

        @$document->loadHTML($dataBody);

        $xpath = new \DOMXPath($document);

        // navigate to parent div
        $items = $xpath->query($this->xpath_parent_container);


        // iterate through all parent divs holding results
        foreach($items as $item)
        {
                // navigate to the tag holding link
                $url = @$xpath->query($this->xpath_link_container, $item);



                // if its a valid node, get the href value
                if($url->item(0) !== null){

                    $url = $url->item(0)->getAttribute('href');
                }
                else{

                    $url = "";
                }


                // extract the title
                $title = @$xpath->query($this->xpath_title_container, $item)->item(0)->nodeValue;


                //remove google tracking parameters from url
                preg_match_all('/(?:\/url\?q=)(.*)(?:&sa=)/',$url,$output);

                // create new std object to add results
                $data = new \stdClass();

                // if url was extracted successfully, add to the result
                if(isset($output[1][0])) {

                    $data->url = $output[1][0];

                } else {

                    $data->url = "";

                }

                $data->title = $title;
                $this->results->url = $data;

                // add the new object to results model
                $this->results->data->google[] = $data;
        }



    }


}