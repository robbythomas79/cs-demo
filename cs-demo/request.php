<?php


$q = $_GET["q"]; 
$returnfields = $_GET["return-fields"];

echo file_get_contents("http://search-imdb-test-sv2v6frwqh3uaszf22gnlbsrfq.us-east-1.cloudsearch.amazonaws.com/2011-02-01/search?&q=".$q."&return-fields=".$returnfields);
?>
