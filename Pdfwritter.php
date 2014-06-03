<?php

namespace library;

/**
 * 
 * @author narendra.singh
 *
 */

require_once 'tcpdf/tcpdf.php';
class Pdfwriter
{
	function __construct()
	{
		error_reporting(E_ALL ^E_WARNING ^ E_NOTICE);
	}
	
	/**
	 * 
	 * @param string $remoteUrl
	 * @return string $content
	 */
	public function getRemoteContent($remoteUrl)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $remoteUrl);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	public function setHeader($headerContent)
	{
		return $headerContent;
	}
	
	public function generatePdf($htmlContent)
	{
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('times', 'N', 12);
		$pdf->AddPage('P', 'A4');
		$pdf->writeHtml($htmlContent, true, false, true, false, '');
		$pdf->Output('example_028.pdf', 'I');
		
	}
}

$remoteUrl = "http://localhost/backbone/library/html-to-pdf.html";
$pdfWriter = new Pdfwriter();

$content = $pdfWriter->getRemoteContent($remoteUrl);
/* $content = str_replace('href="', 'href="', $content);
$content = str_replace('src="', 'src="', $content); */

$pdfWriter->generatePdf($content);
// file_put_contents("jeevanrekhaayurved.html", $content);

echo $content;
