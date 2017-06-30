<?php

class UTMCell extends Cell {

	// Corresponds to 200 meters.
	const SIDE_SIZE = 200;	

	protected $table = 'cell';

	public $timestamps = false;

	protected $fillable = array(		
	);

	public function Path()
    {
        return $this->hasMany('CellPath');
    }

    public static function createFromUTMSample($utm) {
    	// to latLng.
		$l = $utm->toLatLng();

		$index_x = floor($utm->getX()/self::SIDE_SIZE);
		$index_y = floor($utm->getY()/self::SIDE_SIZE);
		$index_x = $index_x*self::SIDE_SIZE;
		$index_y = $index_y*self::SIDE_SIZE;

    	$p = new PHPCoord\UTMRef($index_x+(self::SIDE_SIZE/2), $index_y,+(self::SIDE_SIZE/2), $utm->getLatZone(),$utm->getLngZone());
		$l = $p->toLatLng();

		// center
		$cell = new UTMCell();
		$cell->center_lat = $l->getLat();
		$cell->center_lng = $l->getLng();
		$cell->index_x = $index_x;
		$cell->index_y = $index_y;
		$cell->utm_lat_zone = $utm->getLatZone();
		$cell->utm_lng_zone = $utm->getLngZOne();
		$cell->save();

		// path.
		$p = new PHPCoord\UTMRef($index_x, $index_y, 0, $utm->getLatZone(),$utm->getLngZone());
		$l = $p->toLatLng();

		$cellPath = new CellPath();
		$cellPath->cell_id = $cell->id;
		$cellPath->utm_x = $p->getX();
		$cellPath->utm_y = $p->getY();
		$cellPath->lat = $l->getLat();
		$cellPath->lng = $l->getLng();
		$cellPath->save();

		$p = new PHPCoord\UTMRef($index_x + (self::SIDE_SIZE-1), $index_y, 0, $utm->getLatZone(),$utm->getLngZone());
		$l = $p->toLatLng();

		$cellPath = new CellPath();
		$cellPath->cell_id = $cell->id;
		$cellPath->utm_x = $index_x+(self::SIDE_SIZE-1);
		$cellPath->utm_y = $index_y;
		$cellPath->lat = $l->getLat();
		$cellPath->lng = $l->getLng();
		$cellPath->save();

		$p = new PHPCoord\UTMRef($index_x+(self::SIDE_SIZE-1), $index_y+(self::SIDE_SIZE-1), 0, $utm->getLatZone(),$utm->getLngZone());
		$l = $p->toLatLng();

		$cellPath = new CellPath();
		$cellPath->cell_id = $cell->id;
		$cellPath->utm_x = $index_x+(self::SIDE_SIZE-1);
		$cellPath->utm_y = $index_y+(self::SIDE_SIZE-1);
		$cellPath->lat = $l->getLat();
		$cellPath->lng = $l->getLng();
		$cellPath->save();
				

		$p = new PHPCoord\UTMRef($index_x, $index_y+(self::SIDE_SIZE-1), 0, $utm->getLatZone(),$utm->getLngZone());
		$l = $p->toLatLng();

		$cellPath = new CellPath();
		$cellPath->cell_id = $cell->id;
		$cellPath->utm_x = $index_x;
		$cellPath->utm_y = $index_y+(self::SIDE_SIZE-1);
		$cellPath->lat = $l->getLat();
		$cellPath->lng = $l->getLng();
		$cellPath->save();

		return $cell;				

    }

    public static function makeDefaultCells() {
    	$y = 6250000;
		for ($i=0; $i < 10; $i++) { 
			$x = 300000;
			for ($j=0; $j < 10; $j++) {

				$index_x = floor($x/self::SIDE_SIZE);
				$index_y = floor($y/self::SIDE_SIZE);

				// center
				$p = new PHPCoord\UTMRef($x+(self::SIDE_SIZE/2), $y,+(self::SIDE_SIZE/2), 'H', 19);
				$l = $p->toLatLng();

				$cell = new UTMCell();
				$cell->center_lat = $l->getLat();
				$cell->center_lng = $l->getLng();
				$cell->index_x = $index_x;
				$cell->index_y = $index_y;
				$cell->utm_lat_zone = 'H';
				$cell->utm_lng_zone = 19;
				$cell->save();

				$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$l = $p->toLatLng();

				$cellPath = new CellPath();
				$cellPath->cell_id = $cell->id;
				$cellPath->utm_x = $x;
				$cellPath->utm_y = $y;
				$cellPath->lat = $l->getLat();
				$cellPath->lng = $l->getLng();
				$cellPath->save();

				$p = new PHPCoord\UTMRef($x+(self::SIDE_SIZE-1), $y, 0, 'H', 19);
				$l = $p->toLatLng();

				$cellPath = new CellPath();
				$cellPath->cell_id = $cell->id;
				$cellPath->utm_x = $x+(self::SIDE_SIZE-1);
				$cellPath->utm_y = $y;
				$cellPath->lat = $l->getLat();
				$cellPath->lng = $l->getLng();
				$cellPath->save();

				$p = new PHPCoord\UTMRef($x+(self::SIDE_SIZE-1), $y+(self::SIDE_SIZE-1), 0, 'H', 19);
				$l = $p->toLatLng();

				$cellPath = new CellPath();
				$cellPath->cell_id = $cell->id;
				$cellPath->utm_x = $x+(self::SIDE_SIZE-1);
				$cellPath->utm_y = $y+(self::SIDE_SIZE-1);
				$cellPath->lat = $l->getLat();
				$cellPath->lng = $l->getLng();
				$cellPath->save();
				

				$p = new PHPCoord\UTMRef($x, $y+(self::SIDE_SIZE-1), 0, 'H', 19);
				$l = $p->toLatLng();

				$cellPath = new CellPath();
				$cellPath->cell_id = $cell->id;
				$cellPath->utm_x = $x;
				$cellPath->utm_y = $y+(self::SIDE_SIZE-1);
				$cellPath->lat = $l->getLat();
				$cellPath->lng = $l->getLng();
				$cellPath->save();	

				$x = $x+self::SIDE_SIZE;				
			}
			$y = $y+self::SIDE_SIZE;
		}
    }
}
