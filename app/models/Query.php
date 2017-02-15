<?php


class Query{

	public static function start($params) {
		$polygons = Polygon::all();
		foreach ($polygons as $polygon) {
			// set query of all polygon measurements between dates.
			$polygon_measurement_query = PolygonMeasurement::where('date', '>=', $params['min_date'])->where('date', '<=', $params['max_date'])->where('polygon_id', $polygon->id)->select('date', 'value');
			// avg of query
			$polygon->value = $polygon_measurement_query->avg('value');
			// all measurements
			 
			$polygon->measurements = $polygon_measurement_query->get();

			$polygon->center_lat = (float) $polygon->center_lat;
			$polygon->center_lng = (float) $polygon->center_lng;
			$polygon->path = PolygonPath::where('polygon_id', $polygon->id)->get();
			foreach ($polygon->path as $point) {
				$point->lat = (float) $point->lat;
				$point->lng = (float) $point->lng;
			}

		}
		return array('data' => $polygons, 'query' => 'start');
				
	}
	
	public static function fromCell() {

	}

}
