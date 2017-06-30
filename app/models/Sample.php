<?php

class Sample {
//class Sample extends Eloquent {
	
	/*protected $table = 'sample';

	public $timestamps = false;
	
	protected $fillable = array('lat', 'lng', 'value', 'datetime');

	public function assignUTMCell(){
		// convert to UTM.
		$latLng = new PHPCoord\LatLng($this->lat, $this->lng, 0, PHPCoord\RefEll::wgs84());
		$utm = $latLng->toUTMRef(); 

		// get UTM index.
		$index_x = floor($utm->getX()/UTMCell::SIDE_SIZE);
		$index_y = floor($utm->getY()/UTMCell::SIDE_SIZE);

		$lat_zone = $utm->getLatZone();
		$lng_zone = $utm->getLngZone();
			
		if($cell = UTMCell::where('index_x', $index_x)->where('index_y', $index_y)->where('utm_lat_zone', $lat_zone)->where('utm_lng_zone', $lng_zone)->first()) {
			$this->cell_id = $cell->id;
			$this->save();
		} else {
			$cell = UTMCell::createFromUTMSample($utm);
			$this->cell_id = $cell->id;
			$this->save();
		}
	}*/
}
