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

	public function pointInPolygon($point){
		// polygon format
		$path = CommunePath::where('commune_id', $this->id)->get();
		$polygon = array();

		foreach ($path as $path_point) {			
			$polygon_point = [];
			array_push($polygon_point, $path_point['lat']);
			array_push($polygon_point, $path_point['lng']);
			array_push($polygon, $polygon_point);
		}	

		$return = false;
     	foreach ($polygon as $k=>$p){
        if(!$k)
        	$k_prev = count($polygon)-1;
        else $k_prev = $k-1;

	    if(($p[1]< $point[1] && $polygon[$k_prev][1]>=$point[1] || $polygon[$k_prev][1]< $point[1] && $p[1]>=$point[1]) && ($p[0]<=$point[0] || $polygon[$k_prev][0]<=$point[0])){
	         if($p[0]+($point[1]-$p[1])/($polygon[$k_prev][1]-$p[1])*($polygon[$k_prev][0]-$p[0])<$point[0]){
	            $return = !$return;
	            }
	        }
	    }
	    return $return;	    
	}

	public function cellIsInside($cell) {
		$bottom_left = array($cell->bottom_left_lat, $cell->bottom_left_lng);
		$top_left = array($cell->top_left_lat, $cell->top_left_lng);
		$top_right = array($cell->top_right_lat, $cell->top_right_lng);
		$bottom_right = array($cell->bottom_right_lat, $cell->bottom_right_lng);

		if($this->pointInPolygon($bottom_right) || $this->pointInPolygon($top_right) || $this->pointInPolygon($top_left) || $this->pointInPolygon($bottom_left)) {
			return true;
		}
		else 
			return false;
	}

}
