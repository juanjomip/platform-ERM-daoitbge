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

	private function setBottomLeft() {
		$this->bottom_left_lat = $this->lat_index*self::SIDE_SIZE;
		$this->bottom_left_lng = $this->lng_index*self::SIDE_SIZE;
	}

	private function setTopLeft() {
		$this->top_left_lat = $this->bottom_left_lat;
		$this->top_left_lng = $this->bottom_left_lng + ( self::SIDE_SIZE - 0.0000001 );
	}

	private function setTopRight() {
		$this->top_right_lat = $this->top_left_lat + ( self::SIDE_SIZE - 0.0000001 );
		$this->top_right_lng = $this->top_left_lng;
	}

	private function setBootmRight() {
		$this->bottom_right_lat = $this->top_right_lat;
		$this->bottom_right_lng = $this->bottom_left_lng;
	}

	private function setVertices() {
		$this->setBottomLeft();
		$this->setTopLeft();
		$this->setTopRight();
		$this->setBootmRight();
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
		$cell->setVertices();
		$cell->save();
		return $cell;
	}

	public function assignCommunes() {
		$communes = Commune::all();
		foreach ($communes as $commune) {
			if($commune->cellIsInside($this)) {
				DB::table('cell_commune')->insert(array('commune_id' => $commune->id, 'cell_id' => $this->id));
			}
		}
		return true;
	}
}
