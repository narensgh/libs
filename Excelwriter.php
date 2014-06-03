<?php

/**
 * 
 * @author narendra.singh
 * This Class creates .xlsx(2007 Format) file using PHPExcel Library
 */

class Excelwriter
{
	private $phpExcel;
	private $rowPointer;

	function __construct()
	{
		ini_set('maximum_execution_time', 2000);
		if(!$this->phpExcel){
			$this->phpExcel = new PHPExcel();
			$this->rowPointer = 1;
		}
	}
	
	/**
	 * 
	 * @param String $sheetTitle
	 */
	
	public function setDocumentProperty($sheetTitle = "")
	{
		$this->phpExcel->getActiveSheet()->setTitle($sheetTitle);
		$this->phpExcel->setActiveSheetIndex(0);
	}
	
	/**
	 * 
	 * @param array $headerData
	 */
	public function setColumnHeader($headerData = array())
	{
		if(!empty($headerData))
		{
			$col = 0;
			foreach ($headerData as $headerRow)
			{
				$this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $this->rowPointer, $headerRow);
				$this->setColumnWidth($col, 22);
				$col ++;
			}
			$this->setHeaderStyle($this->rowPointer);
			$this->rowPointer++;
			$this->freezePane($this->rowPointer);
		}
	}
	
	/**
	 * 
	 * @param array $reportRow
	 */
	public function setRow($reportRow = array())
	{
		if(!empty($reportRow))
		{
			$col = 0;
			$activeSheet = $this->phpExcel->setActiveSheetIndex(0);
			foreach($reportRow as $rowData)
			{
				$activeSheet->setCellValueByColumnAndRow($col, $this->rowPointer, $rowData);
				$col ++;
			}
			$this->rowPointer++;
		}
	}
	
	/**
	 * @param int $rowNo
	 * sets the header style, color, font-size
	 * 
	 */	
	private function setHeaderStyle($rowPointer)
	{
		$headerIndex ='A'.$rowPointer;
		$styleArray = array(
				'font'  => array(
						'bold'  => true,
					//'color' => array('rgb' => 'FFFFFF'),
						'size'  => 10,
						'name'  => 'Verdana'
				));
		$this->phpExcel->getActiveSheet(0)->getStyle($headerIndex)->applyFromArray($styleArray);
	}
	
	/**
	 * @param int $rowNo
	 * freeze the row 
	 *
	 */
	
	private function freezePane($rowPointer)
	{
		$this->phpExcel->getActiveSheet(0)->freezePane('A'.$rowPointer);
	}

	/**
	 * 
	 * @param int $col
	 * @param int $width
	 */
	private function setColumnWidth($col, $width = 10)
	{
		$this->phpExcel->getActiveSheet(0)->getColumnDimensionByColumn($col)->setWidth($width);
	}

	/**
	 * 
	 * @param String $filename
	 */
	public function downloadExcel($filename = "Report")
	{
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$filename.'.xlsx');
		header('Cache-Control: max-age=0');
	
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		exit;
	}
}

?>