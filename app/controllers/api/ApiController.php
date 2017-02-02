<?php

class ApiController extends BaseController {	

	public function getIndex()
	{
		return 'ApiController works';
	}	

	public function getSamples(){
		$samples = UnprocessedSample::all();
		foreach ($samples as $sample) {
			$sample->value = (int) $sample->value;
			$sample->lat = (float) $sample->lat;
			$sample->lng = (float) $sample->lng;
		}

		$cells = [];
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

		$communes = Commune::all();
		foreach ($communes as $commune) {
			$commune->center_lat = (float) $commune->center_lat;
			$commune->center_lng = (float) $commune->center_lng;
			$commune->path = CommunePath::where('commune_id', $commune->id)->get();
			foreach ($commune->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}
		}
		return array(
			'samples' => $samples,
			'cells' => $cells,
			'communes' => $communes
		);
	}

	public function getCommunecells($id) {
		$commune = Commune::find($id);

		$cells = Cell::join('cell_commune', 'cell.id', '=', 'cell_commune.cell_id')->where('cell_commune.commune_id', $commune->id)->get();
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

	public function getSample($lat, $lng){
		$sample = new UnprocessedSample();
		$sample->lat = $lat;
		$sample->lng = $lng;
		$sample->assignCell();
	}

	public function getResetdb(){
		UnprocessedSample::delete();
	}

	public function getTestcell($lat_index, $lng_index) {
		$cell = Cell::createAndConfig($lat_index, $lng_index);
		return $cell;
	}

	public function getCommunesdelete() {
		DB::table('commune_path')->delete();
		DB::table('commune')->delete();
	}

	/*public function getCommunes() {
	    Commune::santiagoFromKml();
	}*/

	/*public function getCells() {	
		$paths = CommunePath::all();
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
		$cell->assignCommunes();
		$communes = DB::table('cell_commune')->where('cell_id', $cell->id)->join('commune', 'commune.id', '=', 'cell_commune.commune_id')->select('commune.name')->get();
		return array('response' => $communes);
	}

	public function getPolygons(){
		$cells = Cell::all();
		foreach ($cells as $cell) {
			$cell->assignCommunes();
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

}
