<?php

class ApiController extends BaseController {	

	public function getIndex()
	{
		return 'ApiController works';
	}

	public function postSample() {
		$inputs = Input::all();
		$rules = array(
			'lat' => 'required',
			'lng' => 'required',
			'value' => 'requierd'
		);
		$validador = Validator::make($inputs, $rules);
        if ($validador->passes()) {
        	$sample = UnprocessedSample::create(Input::all());
        	return array('status' => 'success', 'message' => 'created.');
        }
        else 
        	return array('status' => 'fail', 'messages' => $validador->messages()->all());	
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

}
