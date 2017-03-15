<?php


class Polygon extends Eloquent {

	public $timestamps = false;
	
	protected $table = 'polygon';

	public static function polygonsReport($data) {
		$minDate = $data['minDate'];
		$maxDate = $data['maxDate'];
		$polygons = Polygon::all();
		foreach ($polygons as $polygon) {
			$polygon->id = (int) $polygon->id;
			$polygon->value = (int) $polygon->summary($minDate, $maxDate);
			$polygon->quantity = $polygon->quantity($minDate, $maxDate);	
		}		
		$sheet = ExcelReport::createSheet('Comunas', $polygons);
		return array($sheet);
	}

	// Query methods ********************************************************************************************************************** //
	// ok
	public static function getCommunes($minDate, $maxDate) {
		$polygons = Polygon::all();
		foreach ($polygons as $polygon) {
			$polygon->id = (int) $polygon->id;
			$polygon->value = (int) $polygon->summary($minDate, $maxDate);
			$polygon->quantity = $polygon->quantity($minDate, $maxDate);			
			$polygon->path = PolygonPath::where('polygon_id', $polygon->id)->get();
			foreach ($polygon->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}
		}
		return array('data' => $polygons);
	}

	//ok
	public function getData($minDate, $maxDate) {
		$data = array();
		$data['id'] = (int) $this->id;
		$data['name'] = $this->name;
		$data['measurements'] = $this->measurements($minDate, $maxDate);
		$data['value'] = $this->summary($minDate, $maxDate);
		$data['quantity'] = $this->quantity($minDate, $maxDate);			
		$data['path'] = PolygonPath::where('polygon_id', $this->id)->get();
		foreach ($data['path'] as $point) {
			$point->lat = (float) $point->lat;
			$point->lng = (float) $point->lng;
		}

		return array('data' => $data);
	}

	//ok
	public function getCells($minDate, $maxDate) {
		$cells = Cell::join('cell_polygon', 'cell_polygon.cell_id', '=', 'cell.id')->where('cell_polygon.polygon_id', $this->id)->get();
		foreach ($cells as $cell) {
			$cell->value = $cell->summary($minDate, $maxDate);
			$cell->quantity = $cell->quantity($minDate, $maxDate);
			$cell->path = CellPath::where('cell_id', $cell->id)->get();
			foreach ($cell->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}			
		}
		return array('data' => $cells);
	}
	// End Query methods *********************************************************************************************************************************** //
	
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

	public function updateMeasurement($cellMeasurement, $value) {
		
		// if measurement already are in database just update, else save first measurement.
 		if($measurement = DB::table('polygon_measurement')->where('date', $cellMeasurement->date)->where('polygon_id', $this->id)->first()) {
			// calculates average.
 			$newValue = ($measurement->quantity*$measurement->value + $value)/($measurement->quantity +1);
 			
 			/*dd(print_r(
 				array(
 					$measurement->quantity,
 					$measurement->value,
 					$cellMeasurement->value,
 					$measurement->quantity,
 					$newValue
 				)
 			));*/

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
	public function measurements($minDate, $maxDate) {
		$cell_measurement = PolygonMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('polygon_id', $this->id)->select('date', 'value', 'quantity')->orderBy('date', 'ASC');
		$mesaurements = $cell_measurement->get();
		return $mesaurements;
	}

	// return AVG measurement.
	public function summary($minDate, $maxDate) {
		$polygon_measurement = PolygonMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('polygon_id', $this->id)->select('date', 'value', 'quantity');
		$avg = $polygon_measurement->avg('value');
		return $avg;
	}

	// return AVG measurement.
	public function quantity($minDate, $maxDate) {
		$polygon_measurement = PolygonMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('polygon_id', $this->id)->select('date', 'value', 'quantity');
		$avg = $polygon_measurement->sum('quantity');
		return $avg;
	}

}
