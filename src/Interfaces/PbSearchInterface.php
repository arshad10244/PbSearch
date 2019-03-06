<?php
/**
 * Interface for implementing different search engine classes
 */

namespace Arshad\PbSearch\Interfaces;



interface PbSearchInterface
{
    /**
     * @param array $keywords keywords array to search
     * @return void
     */
    public function find($keywords=array());

    /**
     * Update Xpath expressions in-case of changes
     *
     * @param string $parentContainer
     * @param string $linkContainer
     * @param string $titleContainer
     * @return mixed
     */
    public function setXpaths($parentContainer = "",$linkContainer = "",$titleContainer = "");
}