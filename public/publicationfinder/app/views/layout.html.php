<?php 
/**
 * Layout view
 * LOCATION: http://widgets.ebscohost.com/prod/ftf-atoz/app/views/layout.html.php
 * APP NAME: Publication Finder API Sample
 **/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AtoZ Browse App</title>
        <!--<link rel="stylesheet" href="web/styles.css" type="text/css" media="screen" /> -->
        <link rel="stylesheet" href="https://vf22.wpunj.edu/themes/bootprint3/css/compiled.css" type="text/css" media="screen" />
		<link rel="shortcut icon" href="web/favicon.ico" />   
		<script type="text/javascript" src="web/jquery.js" ></script>  
    </head>

    <body>
    
 <style>
 body {background-color: white;}
</style>

            <?php echo $content; ?>
<?php 
$xml ="Config.xml";
$dom = new DOMDocument();
$dom->load($xml);  
$version = $dom ->getElementsByTagName('Version')->item(0)->nodeValue;
?>
    </body>
</html>