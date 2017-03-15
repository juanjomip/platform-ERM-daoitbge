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
                //$data = Polygon::polygonReport($data);
                break;
            case 'samples':
                //$data = Cell::cellReport($data);
                break;
            default:
                # code...
                break;
        }


        return $sheets;
    }
}
