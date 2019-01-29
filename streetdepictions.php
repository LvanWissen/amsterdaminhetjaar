<?php

$sparqlquery = '
PREFIX void: <http://rdfs.org/ns/void#>
PREFIX sem: <http://semanticweb.cs.vu.nl/2009/11/sem/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX hg: <http://rdf.histograph.io/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
SELECT DISTINCT ?werk (SAMPLE(?titel) AS ?titel) (SAMPLE(?img) AS ?img) ?set WHERE {
  ?werk sem:hasBeginTimeStamp ?begin .
  ?werk void:inDataset ?set .
  ?werk dct:spatial ?street .
  ?street a hg:Street .
  BIND(IF(COALESCE(xsd:datetime(str(?begin)), "!") != "!",
     year(xsd:dateTime(str(?begin))),"3"^^xsd:integer) AS ?jaar ) .
  ?werk dc:title ?titel .
  ?werk foaf:depiction ?img .
  FILTER(?jaar=' . $_GET['year'] . ')
}
GROUP BY ?werk ?set
LIMIT 5';

//echo $sparqlquery;


$url = "https://api.data.adamlink.nl/datasets/AdamNet/all/services/endpoint/sparql?default-graph-uri=&query=" . urlencode($sparqlquery) . "&format=application%2Fsparql-results%2Bjson&timeout=120000&debug=on";


$json = file_get_contents($url);

$data = json_decode($json,true);

$i = 0;


$names = array();
$checknames = array();
$checkimgs = array();
$imgs = array();

foreach ($data['results']['bindings'] as $row) { 
	?>

	<a target="_blank" href="<?= $row['werk']['value'] ?>"><img src="<?= $row['img']['value'] ?>"></a>
	<strong><?= $row['titel']['value'] ?></strong>
	</div>

	<?php 
} 

?>

