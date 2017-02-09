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
				

				

				

				/*$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$ps = $p->toLatLng();	

				$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$ps = $p->toLatLng();

				$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$ps = $p->toLatLng();

				$p = new PHPCoord\UTMRef($x, $y, 0, 'H', 19);
				$ps = $p->toLatLng();

				$punto = array(
					"lat" => $ps->getLat(),
					"lng" => $ps->getLng()						
				);*/

				$x = $x+self::SIDE_SIZE;			

				//array_push($markers, $punto);
			}
			$y = $y+self::SIDE_SIZE;
		}
    }


	/*$comments = array(
    new Comment(array('message' => 'A new comment.')),
    new Comment(array('message' => 'Another comment.')),
    new Comment(array('message' => 'The latest comment.'))
	);

	$post = Post::find(1);

	$post->comments()->saveMany($comments);*/

	
}
