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
		/*$samples = UnprocessedSample::all();
		foreach ($samples as $sample) {
			$sample->value = (int) $sample->value;
			$sample->lat = (float) $sample->lat;
			$sample->lng = (float) $sample->lng;
		}*/

		/*$cells = [];
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
	*/
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

		//$bandas = ['X', 'W', 'V', 'U', 'T', 'S', 'R', 'Q', 'P', 'N'];
		//$husos = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30];

		/*$markers = [];
		//foreach ($bandas as $banda) {
			//foreach ($husos as $huso) {				
				$c = new  PHPCoord\UTMRef(500000, 0, 0,  'S', 30);
				$l = $c->toLatLng();
				$p = array(
					"lat" => $l->getLat(),
					"lng" => $l->getLng()				
					);
				//array_push($markers, $p);	


				$c = new  PHPCoord\UTMRef(343025, 6298336, 0,  'H', 19);
				$l = $c->toLatLng();
				$p = array(
					"lat" => $l->getLat(),
					"lng" => $l->getLng()				
					);
				array_push($markers, $p);
*/
							
		//	}
		//}



		$markers = array();

		$p = new PHPCoord\LatLng(-33.4082648,-70.5507676, 0, PHPCoord\RefEll::wgs84());

		//$p = new PHPCoord\Cartesian(0,0,0, PHPCoord\RefEll::wgs84());
		$l =  $p->toUTMRef();
		$ps = $l->toLatLng();
		$punto = array(
			"lats" => $p->getLat(),
			"lngs" => $p->getLng(),
			"lat" => $ps->getLat(),
			"lng" => $ps->getLng(),
			"x" => $l->getX(),
			"y" => $l->getY(),
			"latZone" => $l->getLatZone(),
			"lngZone" => $l->getLngZone()				
		);

		//array_push($markers, $punto);


		//355797

		//6302377
		
		$y = 6250000;

		for ($i=0; $i < 10; $i++) { 
			$x = 300000;
			for ($j=0; $j < 10; $j++) { 

				$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$ps = $p->toLatLng();

				$punto = array(
					"lat" => $ps->getLat(),
					"lng" => $ps->getLng()						
				);

				$x = $x+10000;			

				array_push($markers, $punto);
			}
			$y = $y+10000;
		}
		
		$markers = [];
		$markers = UTMCell::all();
		foreach ($markers as $marker) {
			$marker->lat = $marker->center_lat;
			$marker->lng = $marker->center_lng;
		}

		$samples = [];
		$cells = [];
		$communes = [];


		$cells = Cell::select('id', 'center_lat', 'center_lng')->get();
		foreach ($cells as $cell) {
			$cell->center_lat = (double) $cell->center_lat;
			$cell->center_lng = (double) $cell->center_lng;
			$cell->path = CellPath::where('cell_id', $cell->id)->select('lat', 'lng')->get();
			foreach ($cell->path as $point) {
				$point->lat = (double) $point->lat;
				$point->lng = (double) $point->lng;
			}
		}


		return array(
			//'markers' => $markers,
			'samples' => UnprocessedSample::all(),
			'cells' => $cells,

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
			'polygon_id' => $polygon_id,
			'cell_id' => $cell_id
		);

		
		return Query::start($params);
		

		

	}

}
