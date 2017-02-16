<?php


class Polygon extends Eloquent {

	public $timestamps = false;
	
	protected $table = 'polygon';

	
	public static function santiagoFromKml(){
		$json = json_decode(Kml::Polygons);	
		foreach ($json->kml->Document->Folder[0]->Placemark as $polygon_data) {					
			$path = $polygon_data->Polygon->outerBoundaryIs->LinearRing->coordinates;
			$path = explode(' ', $path);
	        $finalpath = array();
	        $polygon = new Polygon();
	        $polygon->name = $polygon_data->name;
	        $polygon->save(); 
	        foreach ($path as $p) {
	            $points = explode(',', $p);  
	            $polygonPath = new PolygonPath();          
	            $polygonPath->lat = (float) $points[1];
	            $polygonPath->lng = (float) $points[0];   
	            $polygonPath->polygon_id = $polygon->id;
	            $polygonPath->save();                  
	        }
	        $polygon->center_lat = $polygonPath->lat;
	        $polygon->center_lng = $polygonPath->lng;
	        $polygon->save();
		}
	}	

	public function pointInPolygon($point){
		// polygon format
		$path = PolygonPath::where('polygon_id', $this->id)->get();
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

	public function updateMeasurement($cellMeasurement) {
		
		// if measurement already are in database just update, else save first measurement.
 		if($measurement = DB::table('polygon_measurement')->where('date', $cellMeasurement->date)->where('polygon_id', $this->id)->first()) {
			// calculates average.
 			$newValue = ($measurement->quantity*$measurement->value + $cellMeasurement->value)/($measurement->quantity +1);			
			$measurement->quantity = $measurement->quantity+1;			
			DB::table('polygon_measurement')->where('date', $cellMeasurement->date)->where('polygon_id', $this->id)->update(
				array(
					'polygon_id' => $this->id,					
					'value' => $newValue,
					'quantity' => $measurement->quantity
				)
			);			
		} else {
			DB::table('polygon_measurement')->insert(
				array(
					'polygon_id' => $this->id,
					'date' => $cellMeasurement->date,
					'value' => $cellMeasurement->value,
					'quantity' => 1
				)
			);			
		}		
	}

	// return all mesasurement
	public function measurements($params) {
		$cell_measurement = PolygonMeasurement::where('date', '>=', $params['min_date'])->where('date', '<=', $params['max_date'])->where('polygon_id', $this->id)->select('date', 'value', 'quantity');
		$mesaurements = $cell_measurement->get();
		return $mesaurements;
	}

	// return AVG measurement.
	public function summary($params) {
		$polygon_measurement = PolygonMeasurement::where('date', '>=', $params['min_date'])->where('date', '<=', $params['max_date'])->where('polygon_id', $this->id)->select('date', 'value', 'quantity');
		$avg = $polygon_measurement->avg('value');
		return $avg;
	}

	// return AVG measurement.
	public function quantity($params) {
		$polygon_measurement = PolygonMeasurement::where('date', '>=', $params['min_date'])->where('date', '<=', $params['max_date'])->where('polygon_id', $this->id)->select('date', 'value', 'quantity');
		$avg = $polygon_measurement->sum('quantity');
		return $avg;
	}

}
