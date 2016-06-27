<?php
/**
 * Created by PhpStorm.
 * User: nucmed
 * Date: 27/06/2016
 * Time: 1:01 PM
 */

$xml=simplexml_load_file("dwl.xml") or die("Error: Cannot create object");
print_r($xml);
