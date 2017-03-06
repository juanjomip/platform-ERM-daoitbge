<?php

class Cell extends Eloquent {

	// Corresponds to 200 meters in degrees.
	const SIDE_SIZE = 0.0017966;

	// For testing.
	//const SIDE_SIZE = 10;

	protected $table = 'cell';

	public $timestamps = false;

	protected $fillable = array(
		'lat_index',
		'lng_index',
		'bottom_left_lat',
		'bottom_left_lng',
		'top_left_lat',
		'top_left_lng',
		'top_right_lat',
		'top_right_lng',
		'bottom_right_lat',
		'bottom_right_lng'
	);

	// Query methods ********************************************************************************************************************** //
	// ok
	public function getData($minDate, $maxDate) {
		$data = array();
		$data['measurements'] = $this->measurements($minDate, $maxDate);
		$data['value'] = $this->summary($minDate, $maxDate);
		$data['quantity'] = $this->quantity($minDate, $maxDate);			
		$data['path'] = CellPath::where('cell_id', $this->id)->get();
		foreach ($data['path'] as $point) {
			$point->lat = (float) $point->lat;
			$point->lng = (float) $point->lng;
		}

		return array('data' => $data);
	}

	// ok
	public function getSamples($minDate, $maxDate) {
		$samples = UnprocessedSample::where('cell_id', $this->id)
			->where('date', '>=', $minDate)
			->where('date', '<=', $maxDate)
			->get();
		return array('data' => $samples);
	}

	// End Query methods *********************************************************************************************************************************** //

	private function setBottomLeft() {
		$this->bottom_left_lat = $this->lat_index*self::SIDE_SIZE;
		$this->bottom_left_lng = $this->lng_index*self::SIDE_SIZE;
		$path = new CellPath();
		$path->lat = $this->bottom_left_lat;
		$path->lng = $this->bottom_left_lng;
		$path->cell_id = $this->id;
		$path->save();
	}

	private function setTopLeft() {
		$this->top_left_lat = $this->bottom_left_lat;
		$this->top_left_lng = $this->bottom_left_lng + ( self::SIDE_SIZE - 0.0000001 );
		$path = new CellPath();
		$path->lat = $this->top_left_lat;
		$path->lng = $this->top_left_lng;
		$path->cell_id = $this->id;
		$path->save();
	}

	private function setTopRight() {
		$this->top_right_lat = $this->top_left_lat + ( self::SIDE_SIZE - 0.0000001 );
		$this->top_right_lng = $this->top_left_lng;
		$path = new CellPath();
		$path->lat = $this->top_right_lat;
		$path->lng = $this->top_left_lng;
		$path->cell_id = $this->id;
		$path->save();
	}

	private function setBootmRight() {
		$this->bottom_right_lat = $this->top_right_lat;
		$this->bottom_right_lng = $this->bottom_left_lng;
		$path = new CellPath();
		$path->lat = $this->bottom_right_lat;
		$path->lng = $this->bottom_left_lng;
		$path->cell_id = $this->id;
		$path->save();
	}

	private function setVertices() {
		$this->setBottomLeft();
		$this->setTopLeft();
		$this->setTopRight();
		$this->setBootmRight();
	}

	private function setCenter() {
		$this->center_lat = $this->top_left_lat + ( self::SIDE_SIZE / 2 );
		$this->center_lng = $this->bottom_left_lng + ( self::SIDE_SIZE / 2 );		
	}

	// Creates a new cell with lower left vertex the point corresponding to
	// the multiplication of the constant SIDE_SIZE and the value of LAT_INDEX
	// and LNG_INDEX respectively.
	// The values LAT_INDEX AND LNG_INDEX correspond to the integer part of the result
	// of lat / SIDE_SIZE and lng / SIDE_SIZE.
	public static function createAndConfig($lat_index, $lng_index) {		
		$cell = new Cell();
		$cell->lat_index = $lat_index;
		$cell->lng_index = $lng_index;
		$cell->save();
		$cell->setVertices();
		$cell->setCenter();
		$cell->save();
		$cell->assignPolygons();
		return $cell;
	}

	public function assignPolygons() {
		$communes = Polygon::all();
		foreach ($communes as $commune) {
			if($commune->cellIsInside($this)) {
				DB::table('cell_polygon')->insert(array('polygon_id' => $commune->id, 'cell_id' => $this->id));
			}
		}
		return true;
	}


	public function updateMeasurement($sample) {
		// if measurement already are in database just update, else save first measurement.
 		if($cellMeasurement = CellMeasurement::where('date', $sample->date)->where('cell_id', $this->id)->first()) {
			// calculates average.
 			$newValue = ($cellMeasurement->quantity*$cellMeasurement->value + $sample->value)/($cellMeasurement->quantity +1);			
			$cellMeasurement->quantity = $cellMeasurement->quantity+1;			
			DB::table('cell_measurement')->where('date', $sample->date)->where('cell_id', $this->id)->update(
				array(
					'cell_id' => $this->id,					
					'value' => $newValue,
					'quantity' => $cellMeasurement->quantity
				)
			);			
		} else {
			$cellMeasurement = new CellMeasurement();
			$cellMeasurement->cell_id = $this->id;
			$cellMeasurement->date = $sample->date;
			$cellMeasurement->value = $sample->value;
			$cellMeasurement->quantity = 1;
			$cellMeasurement->save();						
		}
		$this->updatePolygons($cellMeasurement);
	}

	public function updatePolygons($cellMeasurement) {
		$polygons = Polygon::select('polygon.id')->join('cell_polygon', 'cell_polygon.polygon_id', '=', 'polygon.id')->where('cell_polygon.cell_id', $this->id)->get();
		foreach ($polygons as $polygon) {
			$polyg = Polygon::find($polygon->id);
			$polyg->updateMeasurement($cellMeasurement);
		}
	}

	public static function merc_x($lon)
	{
		$r_major = 6378137.000;
		return $r_major * deg2rad($lon);
	}

	public static function merc_y($lat)
	{
		if ($lat > 89.5) $lat = 89.5;
		if ($lat < -89.5) $lat = -89.5;
		$r_major = 6378137.000;
	    $r_minor = 6356752.3142;
	    $temp = $r_minor / $r_major;
		$es = 1.0 - ($temp * $temp);
	    $eccent = sqrt($es);
	    $phi = deg2rad($lat);
	    $sinphi = sin($phi);
	    $con = $eccent * $sinphi;
	    $com = 0.5 * $eccent;
		$con = pow((1.0-$con)/(1.0+$con), $com);
		$ts = tan(0.5 * ((M_PI*0.5) - $phi))/$con;
	    $y = - $r_major * log($ts);
	    return $y;
	}

	public static function merc($x,$y) {
	    return array('x'=>Cell::merc_x($x),'y'=>Cell::merc_y($y));

	}

	// return all mesasurement
	public function measurements($minDate, $maxDate) {
		$cell_measurement = CellMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('cell_id', $this->id)->select('date', 'value');
		$mesaurements = $cell_measurement->get();
		return $mesaurements;
	}

	// return AVG measurement.
	public function summary($minDate, $maxDate) {
		$cell_measurement = CellMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('cell_id', $this->id)->select('date', 'value');
		$avg = $cell_measurement->avg('value');
		return $avg;
	}

	public function quantity($minDate, $maxDate) {
		$cell_measurement = CellMeasurement::where('date', '>=', $minDate)->where('date', '<=', $maxDate)->where('cell_id', $this->id)->select('date', 'value', 'quantity');
		$avg = $cell_measurement->sum('quantity');
		return $avg;
	}
}
