<html>
<head>
	<link href="http://www.trezor.fr/css/mysterius.css" media="screen" rel="stylesheet" type="text/css" />
 	<title>phpGeo demo</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<h1>phpGeo v0.1 Demo page</h1>

<p>
	phpGeo is a set of PHP classes for common geolocalisation tasks.
	Everyone is welcome to contribute on GitHub !<br/>
	<a href="https://github.com/bablukid/phpGeo">https://github.com/bablukid/phpGeo</a>
	<br/>
	Features :
	<ul>
		<li>coordinates conversion ( decimal, degrees, GPS... )</li>
		<li>distance between 2 points in differents formats (kilometers, miles)</li>
		<li>TODO : GPX files import/export</li>
		<li>TODO : Google Maps stuff</li>
	</ul>

	Requirements : PHP 5.2 or more<br /> Class loading : These classes are
	named according to the PEAR naming convention, they are easy to use
	with an autoloader like the <a
		href="http://framework.zend.com/manual/en/zend.loader.html">Zend
		Framework one</a>.
	<br/>
	<span style='color:red;'>Please look at the source code of this page to discover how to use the classes.</span>
</p>

<h2>Geo_Point class</h2>

<h3>Coordinates conversion</h3>

<?php
include "library/Geo/Point.php";
include "library/Geo/Gpx.php";
include "library/Geo/Waypoint.php";


// Paris position from decimal coordinates
$point = Geo_Point::getFromDecimal(48.85666600,2.35098700);
$coord = $point->getCoordinates(Geo_Point::COORD_FORMAT_DECIMAL);

echo "Paris coordinates in decimal : {$coord['lat']} , longitude : {$coord['lng']}<br/>";

$coord = $point->getCoordinates(Geo_Point::COORD_FORMAT_GPS);

echo "Paris coordinates in GPS style :  {$coord['lat']} , {$coord['lng']}<br/>";
?>


<h3>point distances</h3>


<?php

$paris = Geo_Point::getFromDecimal(48.85666600,2.35098700);
$bordeaux = Geo_Point::getFromDecimal(44.8373682,-0.5761440);

echo "Paris is {$paris->getDistance($bordeaux)}km away from Bordeaux";


?>
<h3>GPX files parsing</h3>
<?php


/**
 * Parse GPX files
 */

$dir = dirname(__FILE__)."/gpx_demo_files/";
echo "Here are some sample parsed files in /gpx_demo_files/ :";
echo "<ul>";
if ($dh = opendir($dir)) {
	while (($file = readdir($dh)) !== false) {
		if (is_file($dir."/".$file)){
			$gpx = new Geo_Gpx($dir."/".$file);
			$waypoints = $gpx->getWayPoints();

			foreach ($waypoints as $waypoint){
				$pos = $waypoint->getCoordinates(Geo_Point::COORD_FORMAT_GPS);
				echo "<li><b>{$waypoint->name}</b> located at {$pos['lat']}, {$pos['lng']}</li>";
			}
		}
	}
	closedir($dh);
}
echo "</ul>";

?>

</html>
