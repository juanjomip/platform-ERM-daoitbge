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
	
	public static function make($params) {
		if($params['polygon_id'] == null){
			$data = Query::communes($params);
			return array('status' => 'success', 'communes' => $data);
		} elseif ($params['polygon_id'] != null && $params['cell_id'] == null) {
			$data = Query::commune($params);
			return array('status' => 'success', 'commune' => $data);
		}

		
	}

	public static function communes($params) {
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
		return $polygons;		
	}

	public static function commune($params) {
		$polygon = Polygon::find($params['polygon_id']);
		$polygon->center_lat = (float) $polygon->center_lat;
		$polygon->center_lng = (float) $polygon->center_lng;
		$polygon->path = PolygonPath::where('polygon_id', $polygon->id)->get();
		$polygon->summary = $polygon->summary($params);
		$polygon->summary = ($polygon->summary != null) ? (float) $polygon->summary : null;
		$polygon->quantity = (float) $polygon->quantity($params);
		$polygon->measurements = $polygon->measurements($params);
		foreach ($polygon->path as $point) {
			$point->lat = (float) $point->lat;
			$point->lng = (float) $point->lng;
		}

		// get cells
		$cells = Cell::join('cell_polygon', 'cell_polygon.cell_id', '=', 'cell.id')->where('cell_polygon.polygon_id', $polygon->id)->select('cell.id', 'center_lat', 'center_lng')->get();
		foreach ($cells as $cell) {
			$cell->center_lat = (double) $cell->center_lat;
			$cell->center_lng = (double) $cell->center_lng;
			$cell->path = CellPath::where('cell_id', $cell->id)->select('lat', 'lng')->get();
			foreach ($cell->path as $point) {
				$point->lat = (double) $point->lat;
				$point->lng = (double) $point->lng;
			}
			$cell->summary = $cell->summary($params);
			$cell->summary = ($cell->summary != null) ? (float) $cell->summary : null;
			$cell->quantity = (float) $cell->quantity($params);
		}

		$polygon->cells = $cells;
		return $polygon;		
	}
}
