<?php

parse_str($_SERVER['QUERY_STRING']);

$q=preg_replace("/ /","+",$q);

$xmlData = file_get_contents('https://server15701.contentdm.oclc.org/dmwebservices/index.php?q=dmQuery/all/CISOSEARCHALL^'.$q.'^all^or/title!subjec!descri/title/3/1/0/0/0/0/xml');

// Create the document object

$xml = simplexml_load_string($xmlData);

$pager = array();

// How many hits did the search yield

foreach ($xml->xpath('//pager') as $hits) {
	$pager[] = array(
		'start' => (string) $hits->start,
		'total' => (string) $hits->total
	);
}

$result = array();

// Get the nodes and loop them

foreach ($xml->xpath('//record') as $record) {
	$result[] = array(
		'collection' => (string) $record->collection,
		'title' => (string) $record->title,
		'subject' => (string) $record->subjec,
		'descri' => (string) $record->descri,
		'thumb' => (string) $record->pointer
	);
}

$numberOfHits = $pager[0]["total"];
$startingAt = $pager[0]["start"];

$resultCount = count($result) - 1;

?>
<html>
        <head>
                <title>ContentDM API</title>
               <link rel="stylesheet" href="https://vf22.wpunj.edu/themes/bootprint3/css/compiled.css" type="text/css" media="screen" />
        </head>
        <body>
<style>
a:link {
    text-decoration: none;
}
a:visited {
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
a:active {
    text-decoration: underline;
}
body {
    background-color: white;
}
</style>


		<div id="spinner" class="spinner" style="display:none;">
<i class="fa fa-spinner" aria-hidden="true"></i>
		</div>

                        <?php
   if ($pager[0]["total"]=='0') { 
          echo "<div class=\"pull-left help-block\">
                <h3>No Results!</h3>
              <p class=\"alert alert-danger\">
          Your search - <strong> $q </strong> - did not match any resources.      </p>
            </div>";
} else { 

for ($i=0;$i<=$resultCount;$i++) {
        $title = $result[$i]["title"];
        $subject = $result[$i]["subject"];
        $description = $result[$i]["descri"];
        $thumb = $result[$i]["thumb"];
	$collection = $result[$i]["collection"];
	$collection = str_ireplace("/", "", "$collection");
	$dmCP = file_get_contents("https://server15701.contentdm.oclc.org/dmwebservices/index.php?q=dmGetCollectionParameters/$collection/xml");
	$collectionXML = simplexml_load_string($dmCP);
	$collectionName = $collectionXML[0]->{'name'};
	$imgStr = "http://cdm15701.contentdm.oclc.org/utils/getthumbnail/collection/$collection/id/" . $thumb;
	$n=$i+1;
	echo "            <div class=\"row result clearfix\">
	               <label class=\"pull-left\">
                    $n
                </label>                     
<div class=\"col-sm-2 col-xs-1 left\">
<a target=\"_blank\" href='http://cdm15701.contentdm.oclc.org/cdm/singleitem/collection/$collection/id/$thumb'><img src=\"$imgStr\"></a>
</div>

<div class=\"col-sm-8 col-xs-6 \">
 <a target=\"_blank\" class=\"title _record_link\" href='http://cdm15701.contentdm.oclc.org/cdm/singleitem/collection/$collection/id/$thumb'>$title</a><em>$description</em><br />\n$subject<br />\n<em>From: <a target=\"_blank\" class=\"custom-link\" href='http://cdm15701.contentdm.oclc.org/cdm/landingpage/collection/$collection'>$collectionName</a></em>\n</div>\n</div>";
}
}
?>
        </body>
</html>
