<?php

class ExcelReport extends Report {
	
	// string $name, collection $data
	public static function createSheet($name, $data) {
		// create  headers
		if(sizeof($data) > 0) {            
            $firstResultArray = (array) json_decode(json_encode($data[0]), true);
            $headersArray = array_keys($firstResultArray);
            $headers = $headersArray;
        } else {
            $headers = array();
        }

		$sheet = array(
			'name' => $name,
			'headers' => $headers,
			'data' => $data
		);
		return $sheet;
	}

	public function make($data)
    {      	
    	$this->sheets = $this->getData($data);   	

        require_once base_path().'/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';        
        $excel = new PHPExcel();
        
        $excel->getProperties()
            ->setCreator('')
            ->setTitle('('.date('d-m-Y').')')
            ->setDescription('');
        
        $i=0;  

        foreach ($this->sheets as $sheetData) {
            $row = 1;
            $col = 0;   
            // Create a sheet with name from sheetData.
            $sheet = $excel->createSheet($i);            

           
            $sheet->setTitle(str_replace('_', ' ', $sheetData['name']));                         
            
            // create headers.
            for ($j=0; $j < sizeof($sheetData['headers']); $j++) {
                $header = str_replace('_', ' ', $sheetData['headers'][$j]);                
                $sheet->setCellValueByColumnAndRow($col++, $row, $header);
            }           
            //return $sheetData['data'][45]['id'];
            // Add data.
            $row = 2;
            foreach ($sheetData['data'] as $registro) {
                //return $sheetData['headers'];
                //return $registro['id'];                                
                
                //$registro = (array) $registro;
                # foreach headers
                for ($j=0; $j < sizeof($sheetData['headers']); $j++) {
                    # search for header-data coincidences.
                    $sheet->setCellValueByColumnAndRow($j, $row, $registro[$sheetData['headers'][$j]], 'integer');                    
                }
                $row++;                
            } 
            /* Head Style
            $sheet->getStyle('A1:'.$sheet->getHighestDataColumn().'1')->applyFromArray(array('font' => array('bold' => true))); 
            $sheet->getStyle('A1:'.$sheet->getHighestDataColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
            foreach (range('A1', $sheet->getHighestDataColumn().'1') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);                              
            }  
            
            // Estilo Body.
            foreach (range('A2', $sheet->getHighestDataColumn().'2') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
                $sheet->getStyle($columnID)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if(preg_match('/Facturación/i', $sheet->getCell($columnID.'1')->getValue())) {
                    $sheet->getStyle($columnID)->getNumberFormat()->setFormatCode('"$"#,##0_-');
                } 
            }*/
            
            $i++;
        }

        // Drop default Sheet.
        $excel->removeSheetByIndex($excel->getSheetCount() - 1);
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');       
        //$objWriter->save('app/storage/reports/excels/ex.xlsx');
        $objWriter->save('php://output');
        die;
    }
}
