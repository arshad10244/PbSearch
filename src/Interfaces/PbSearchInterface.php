<?php
/**
 * Created by PhpStorm.
 * User: Arshad
 * Date: 05-Mar-19
 * Time: 11:35 AM
 */

namespace Arshad\PbSearch\Interfaces;



interface PbSearchInterface
{
    public function find($keywords=array());
    public function setXpaths($parentContainer = "",$linkContainer = "",$titleContainer = "");
}