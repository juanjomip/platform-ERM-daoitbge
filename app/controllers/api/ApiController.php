<?php
use Carbon;

class ApiController extends BaseController {	

	public function __construct() {  
		Config::set('auth.model', 'User');        
        $this->beforeFilter('auth.basic', array('only' => array('getAdminauth', 'getReport')));
        //$this->user = Auth::user();       
    }

    public function getAdminauth() {
    	return array('status' => 'success');
    }

	public function getIndex()
	{
		$a = 9;
		$b = 90;
		$c = 10;
		$newValue = (($a*$b) + $c ) / $a + 1;
		return $newValue;
		
	}

	public function getReport($type = null, $minDate = null, $maxDate = null, $polygon_id = null, $cell_id = null) {		
		$data = array('type' => $type, 'minDate' => $minDate, 'maxDate' => $maxDate, 'polygonId' => $polygon_id, 'cellId' => $cell_id);
		$report = new ExcelReport();		
		return $report->make($data);
	}	

	// Main Querys *******************************************************************************************************//
	/*
	ok
	return 
	polygons array.
		name, value, quantity
		path
	*/
	public function getPolygons($minDate, $maxDate) {
		return Polygon::getCommunes($minDate, $maxDate);
	}

	/*
	ok
	return 
	polygon data
		array measurements
			date, value, quantity
		path
	*/
	public function getPolygon($minDate, $maxDate, $id) {
		$polygon = Polygon::find($id);
		return $polygon->getData($minDate, $maxDate);
	}

	/*
	ok
	return 
	cells array
		id, value, quantity
	*/
	public function getCells($minDate, $maxDate, $id) {
		$polygon = Polygon::find($id);
		return $polygon->getCells($minDate, $maxDate);
	}

	/*
	ok
	cell data
		array measurements
			date, vale, quantity
		path
	*/
	public function getCell($minDate, $maxDate, $id) {
		$cell = Cell::find($id);
		return $cell->getData($minDate, $maxDate);
	}

	/*
	ok
	samples array
		date, value
	*/
	public function getSamples($minDate, $maxDate, $id) {
		$cell = Cell::find($id);
		return $cell->getSamples($minDate, $maxDate);
	}

	// nonok
	public function getSensitiveareas($minDate, $maxDate, $id) {
		$polygon = Polygon::find($id);
		return $polygon->getSensitiveareas($minDate, $maxDate);
	}

	// nonok
	public function getSensitivearea($minDate, $maxDate, $id) {
		$sensitivearea = SensitiveArea::find($id);
		return $sensitivearea->getData($minDate, $maxDate);
	}

	// End Main Querys *****************************************************************************************//

	public function getCommunes($minDate, $maxDate) {
		$params = array('min_date' => $minDate, 'max_date' => $maxDate);
		$polygons = Polygon::all();
		foreach ($polygons as $polygon) {
			$polygon->summary = $polygon->summary($params);
			$polygon->quantity = (float) $polygon->quantity($params);
			$polygon->summary = ($polygon->summary != null) ? (float) $polygon->summary : null;
			$polygon->center_lat = (float) $polygon->center_lat;
			$polygon->center_lng = (float) $polygon->center_lng;
			$polygon->path = PolygonPath::where('polygon_id', $polygon->id)->get();
			foreach ($polygon->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}
		}
		return array(
			'communes' => $polygons
		);
	}

	

	public function getPolygoncells($id) {
		$polygon = Polygon::find($id);

		$cells = DB::table('cell_polygon')->join('cell', 'cell_polygon.cell_id', '=', 'cell.id')->select('cell.id', 'center_lat', 'center_lng')->where('cell_polygon.polygon_id', $id)->get();
		foreach ($cells as $cell) {
			$cell->center_lat = (double) $cell->center_lat;
			$cell->center_lng = (double) $cell->center_lng;
			$cell->path = CellPath::where('cell_id', $cell->id)->select('lat', 'lng')->get();
			foreach ($cell->path as $point) {
				$point->lat = (double) $point->lat;
				$point->lng = (double) $point->lng;
			}
		}
		return array('cells' => $cells);
	}

	public function postSamples() {
		$inputs = Input::all();
		foreach ($inputs['samples'] as $sample) {
			$sample = UnprocessedSample::create($sample);
		}
		return array('status' => 'success');
	}

	public function getSavesamples($samples) {
		$samples_string = $samples;
		$samples = explode("||", $samples_string);

		foreach ($samples as $sample) {
			$params = explode("|", $sample);

			$sample_obj = new UnprocessedSample();
			$sample_obj->lat = $params[0];
			$sample_obj->lng = $params[1];
			
			$timestamp = $params[2];
			$carbon = Carbon::createFromTimestamp($timestamp);
			$datetime = $carbon->toDateString();
			$sample_obj->date = $datetime;			

			$sample_obj->value = $params[3];
			$sample_obj->assignCell();
		}
		return 'ok';
		//http://104.236.92.253/api/savesamples/-33.4888092|-70.6666941|2017-07-19|16
	}

	public function postSavesamples() {
		$input = Input::all();
		$samples = $input['samples'];
		foreach ($samples as $sample) {
			$sample_obj = new UnprocessedSample();
			$sample_obj->lat = $sample['lat'];
			$sample_obj->lng = $sample['lng'];
			$sample_obj->date = $sample['date'];
			$sample_obj->value = $sample['value'];
			$sample_obj->assignCell();
		}
		return 'ok';
	}

	public function getSample($lat, $lng, $date, $value){
		$sample = new UnprocessedSample();
		$sample->lat = $lat;
		$sample->lng = $lng;
		$sample->date = $date;
		$sample->value = $value;
		$sample->assignCell();
	}

	public function getResetdb(){
		UnprocessedSample::delete();
	}

	public function getTestcell($lat_index, $lng_index) {
		$cell = Cell::createAndConfig($lat_index, $lng_index);
		return $cell;
	}

	public function getPolygonsdelete() {
		DB::table('polygon_path')->delete();
		DB::table('polygon')->delete();
	}

	/*public function getpolygons() {
	    polygon::santiagoFromKml();
	}*/

	/*public function getCells() {	
		$paths = polygonPath::all();
		foreach ($paths as $path) {
			$sample = new UnprocessedSample();
			$sample->lat = $path->lat;
			$sample->lng = $path->lng;
			$sample->save();
			$sample->assignCell();
		}
	}*/

	/*public function getPolygon($id) {
		//cell 119061
		$cell = Cell::find($id);		
		$cell->assignpolygons();
		$polygons = DB::table('cell_polygon')->where('cell_id', $cell->id)->join('polygon', 'polygon.id', '=', 'cell_polygon.polygon_id')->select('polygon.name')->get();
		return array('response' => $polygons);
	}*/

	/*public function getPolygons(){
		$cells = Cell::all();
		foreach ($cells as $cell) {
			$cell->assignpolygons();
		}
	}*/

	public function getSantiagocells() {
		//33.3633808,-70.5640598 top right
		
		// bottom left -33.498147,-70.7384529,12.17

		//						culador-18571  -39277
		// -18646  -39374 

		for ($i=-18646; $i < -18571; $i++) { 
			Cell::createAndConfig($i, -39277);
		}
	}

	public function getQuery($minDate, $maxDate, $polygon_id = null, $cell_id = null) {
		$params = array(
			'min_date' => $minDate,
			'max_date' => $maxDate,
			'polygon_id' => ($polygon_id == 'undefined') ? null : $polygon_id,
			'cell_id' => ($cell_id == 'undefined') ? null : $cell_id
		);		
		return Query::make($params);
	}
}
