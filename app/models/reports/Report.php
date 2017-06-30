<?php

class Report{
	public function getData($data)
    {        
    	/*$sheets = array(
            array(
                'name' => 'opa',
                'headers' => array('cata', 'maite'),
                'data' => array(
                    array('cata' => 'amor', 'maite' => 'mas amor'), array('cata' => 'amor', 'maite' => 'mas amor'))
                ));*/

        switch ($data['type']) {
            case 'polygons':
                $sheets = Polygon::polygonsReport($data);
                break;
            case 'cells':
                $sheets = Polygon::polygonReport($data);
                //$data = Polygon::polygonReport($data);
                break;
            case 'samples':
                $sheets = Cell::cellReport($data);
                //$data = Cell::cellReport($data);
                break;
            default:
                # code...
                break;
        }


        return $sheets;
    }
}
