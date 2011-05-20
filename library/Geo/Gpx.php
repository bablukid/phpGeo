<?php

class Geo_Gpx{

	protected $_rawData;
	protected $_dom;

	public $name;
	public $desc;
	public $waypoints = array();

	function __construct($filePath){
		//echo "loading $filePath \n";
		$fileContent = utf8_decode(file_get_contents($filePath));
		$this->_rawData = $fileContent;
	}

	public function getWayPoints(){

		$list = new SimpleXmlElement($this->_rawData);

		foreach ($list as $r) {

			switch ($r->getName()){
				//waypoint
				case 'wpt':
					$att = $r->attributes();
					$lat = floatval($att['lat']);
					$lng = floatval($att['lon']);

					$wp = new Geo_Waypoint($lat, $lng);
					$wp->desc = (string)$r->desc;
					$wp->name = (string)$r->name;
					$wp->url = (string)$r->url;
					$wp->urlname = (string)$r->urlname;
					$wp->type = (string)$r->type;

					$this->waypoints[] = $wp;
					break;
				case 'name':
					$this->name = $r;
					break;
				case 'desc':
					$this->desc = $r;
					break;


			}
		}
		return $this->waypoints;

	}



}
