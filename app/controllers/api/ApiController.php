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
		return array('samples' => $samples);
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

}
