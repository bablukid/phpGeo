<?php



class Geo_Point
{
	//coordinates format
	const COORD_FORMAT_DECIMAL = 'decimal';
	const COORD_FORMAT_GEOCACHING = 'default';
	const COORD_FORMAT_GPS = 'default';//N 48° 53.061 E 002° 22.240 (degrees+minutes)

	//distance format
	const DISTANCE_KM = 'km';
	const DISTANCE_MILES = 'miles';

	public $lat;
	public $lng;

	/**
	 * instanciate with DECIMAL values
	 *
	 * @param int $lat
	 * @param int $lng
	 */
	public function __construct($lat,$lng){
		$this->lat= $lat;
		$this->lng = $lng;
	}

	/**
	 * get a Point instance from decimal coordinates
	 *
	 * @param Float $lat
	 * @param Float $lng
	 * @return Geo_Point $point
	 */
	public static function getFromDecimal($lat,$lng){
		return $point = new self($lat, $lng);

	}


	/**
	 * returns an array
	 *
	 * @return array<String>
	 */
	public function getCoordinates($format=self::COORD_FORMAT_DECIMAL){
		switch ($format){
			case self::COORD_FORMAT_GPS :
			case self::COORD_FORMAT_GEOCACHING :
				return $this->decimal2geocaching($this->lat, $this->lng);
				break;

			default:
				//return decimal values
				return array("lat"=>$this->lat,"lng"=>$this->lng);
		}
	}


	protected function decimal2geocaching($lat,$lng){

		//on chope ce qu'il y a apres la virgule et on le multiplie par 60
		if($lat>0){
			$min = (($lat)-floor($lat))*60;
			$lat2 = "N ".abs(floor($lat))."° ".round($min,3);
		}else{
			$min = (($lat)-ceil($lat))*60;
			$lat2 = "S ".abs(ceil($lat))."° ".abs(round($min,3));
		}


		if($lng>0){
			$min = (($lng)-floor($lng))*60;//compute minutes
			$deg = abs(floor($lng));//compute degrees
			$lng2 = "E {$deg}° ".round($min,3);
		}else{
			$min = (($lng)-ceil($lng))*60;
			$deg = abs(ceil($lng));
			$lng2 = "W {$deg}° ".abs(round($min,3));
		}


		return array("lat"=>$lat2,"lng"=>$lng2);

	}


	/**
	 * Get distance from this point to another
	 * @param Geo_Point $point
	 * @param String $format Optionnal, distance format
	 */
	public function getDistance(Geo_Point $point,$format=self::DISTANCE_KM){
		switch ($format) {
			case self::DISTANCE_KM:

				$lat1 = $this->lat *M_PI /180;
				$lon1 = $this->lng *M_PI/180;

				$c = $point->getCoordinates(self::COORD_FORMAT_DECIMAL);
				$lat = $c['lat'];
				$lng = $c['lng'];
				$lat2 = $lat * M_PI /180;
				$lon2 = $lng * M_PI /180;

				$R = 6371;//earth radius
				$d =  acos( sin($lat1)* sin($lat2) +
				cos($lat1)*cos($lat2) *
				cos($lon2-$lon1)) * $R;
				return round($d,2);
				break;

			case self::DISTANCE_MILES:
				throw new Exception("not implemented yet !");
				break;
			default:
				throw new Exception("unknown format");
				break;
		}

	}




}

