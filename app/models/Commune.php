<?php


class Commune extends Eloquent {

	public $timestamps = false;
	
	protected $table = 'commune';

	public static function santiagoFromKml(){
		$json = json_decode(Kml::Communes);	
		foreach ($json->kml->Document->Folder[0]->Placemark as $commune_data) {					
			$path = $commune_data->Polygon->outerBoundaryIs->LinearRing->coordinates;
			$path = explode(' ', $path);
	        $finalpath = array();
	        $commune = new Commune();
	        $commune->name = $commune_data->name;
	        $commune->save(); 
	        foreach ($path as $p) {
	            $points = explode(',', $p);  
	            $communePath = new CommunePath();          
	            $communePath->lat = (float) $points[1];
	            $communePath->lng = (float) $points[0];   
	            $communePath->commune_id = $commune->id;
	            $communePath->save();                  
	        }
	        $commune->center_lat = $communePath->lat;
	        $commune->center_lng = $communePath->lng;
	        $commune->save();
		}
	}	

}
