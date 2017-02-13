<?php

class ApiController extends BaseController {	

	public function getIndex()
	{
		$a = 9;
		$b = 90;
		$c = 10;
		$newValue = (($a*$b) + $c ) / $a + 1;
		return $newValue;
		
	}	

	public function getSamples(){
		$samples = UnprocessedSample::all();
		foreach ($samples as $sample) {
			$sample->value = (int) $sample->value;
			$sample->lat = (float) $sample->lat;
			$sample->lng = (float) $sample->lng;
		}

		$cells = Cell::all();
		foreach ($cells as $cell) {
			$cell->lat_index = (float) $cell->lat_index;
			$cell->lng_index = (float) $cell->lng_index;
			$cell->bottom_left_lat = (float) $cell->bottom_left_lat;
			$cell->bottom_left_lng = (float) $cell->bottom_left_lng;
			$cell->top_left_lat = (float) $cell->top_left_lat;
			$cell->top_left_lng = (float) $cell->top_left_lng;
			$cell->top_right_lat = (float) $cell->top_right_lat;
			$cell->top_right_lng = (float) $cell->top_right_lng;
			$cell->bottom_right_lat = (float) $cell->bottom_right_lat;
			$cell->bottom_right_lng	= (float) $cell->bottom_right_lng;			
		}        

		$polygons = Polygon::all();
		foreach ($polygons as $polygon) {
			$polygon->center_lat = (float) $polygon->center_lat;
			$polygon->center_lng = (float) $polygon->center_lng;
			$polygon->path = PolygonPath::where('polygon_id', $polygon->id)->get();
			foreach ($polygon->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}
		}
		return array(
			'samples' => $samples,
			'cells' => $cells,
			'communes' => $polygons
		);
	}

	public function getPolygoncells($id) {
		$polygon = Polygon::find($id);

		$cells = Cell::join('cell_polygon', 'cell.id', '=', 'cell_polygon.cell_id')->where('cell_polygon.polygon_id', $polygon->id)->get();
		foreach ($cells as $cell) {
			$cell->lat_index = (float) $cell->lat_index;
			$cell->lng_index = (float) $cell->lng_index;
			$cell->bottom_left_lat = (float) $cell->bottom_left_lat;
			$cell->bottom_left_lng = (float) $cell->bottom_left_lng;
			$cell->top_left_lat = (float) $cell->top_left_lat;
			$cell->top_left_lng = (float) $cell->top_left_lng;
			$cell->top_right_lat = (float) $cell->top_right_lat;
			$cell->top_right_lng = (float) $cell->top_right_lng;
			$cell->bottom_right_lat = (float) $cell->bottom_right_lat;
			$cell->bottom_right_lng	= (float) $cell->bottom_right_lng;			
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

	public function getPolygon($id) {
		//cell 119061
		$cell = Cell::find($id);		
		$cell->assignpolygons();
		$polygons = DB::table('cell_polygon')->where('cell_id', $cell->id)->join('polygon', 'polygon.id', '=', 'cell_polygon.polygon_id')->select('polygon.name')->get();
		return array('response' => $polygons);
	}

	public function getPolygons(){
		$cells = Cell::all();
		foreach ($cells as $cell) {
			$cell->assignpolygons();
		}
	}

}
