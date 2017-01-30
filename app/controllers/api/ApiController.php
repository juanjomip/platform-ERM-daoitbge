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
			$cell->bottom_left_lat = (float) $cell->bottom_left_lat;
			$cell->bottom_left_lng	= (float) $cell->bottom_left_lng;			
		}

		return array(
			'samples' => $samples,
			'cells' => $cells
		);
	}

	public function postSamples() {
		$inputs = Input::all();
		foreach ($inputs['samples'] as $sample) {
			$sample = UnprocessedSample::create($sample);
		}
		return array('status' => 'success');
	}

	public function getResetdb(){
		UnprocessedSample::delete();
	}

	public function getTestcell($lat_index, $lng_index) {
		$cell = Cell::createAndConfig($lat_index, $lng_index);
		return $cell;
	}

}
