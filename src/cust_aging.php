<?php
$xml = simplexml_load_file("../Data/CustAging/custAging.xml");
$json = json_encode($xml);
$array = json_decode($json,TRUE);

echo '<pre>'.print_r($array,true);
