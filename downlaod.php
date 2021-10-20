<?php
require 'vendor/autoload.php';
require './include/dbvar.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
$spreadsheet = new Spreadsheet();
$writer = new Xlsx($spreadsheet);

if (!isset($_SESSION["user"])) {
    header("location:login.html");
}
if (isset($_GET['city'])) {
    $city = $_GET['city'];
    DB::useDB('lcn_auth_db');
    $city_d = DB::queryFirstRow("SELECT * FROM city_tb WHERE city_name=%s", $city);
    $city_db = $city_d['db_name'];
} elseif (isset($_SESSION["city"])) {
    $city = $_SESSION["city"];
    DB::useDB('lcn_auth_db');
    $city_d = DB::queryFirstRow("SELECT * FROM city_tb WHERE city_name=%s", $city);
    $city_db = $city_d['db_name'];
}
DB::useDB($city_db);
$result = DB::query("SELECT *,RANK() OVER(PARTITION BY lcn_tb.genre ORDER BY lcn_tb.lcn ASC) AS 'rank' FROM channel_tb,lcn_tb,sid_tb WHERE channel_tb.lcn=lcn_tb.lcn AND channel_tb.sid=sid_tb.sid ORDER BY lcn_tb.lcn");

$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getProperties()->setCreator("Meghbela Digital")
							 ->setLastModifiedBy("Meghbela")
							 ->setTitle("MeghbelaLCN")
							 ->setSubject("Meghbela LCN")
							 ->setDescription("Meghbela LCN")
							 ->setKeywords("LCN Excel")
							 ->setCategory("LCN");
// Add Heading
$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'GENRE')
            ->setCellValue('D1', 'LCN')
			->setCellValue('B1', 'CHANNEL NAME')
			->setCellValue('C1', 'BROADCASTER')
			->setCellValue('E1', 'RANK');

$spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
);

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$rowcount=2;
foreach ($result as $row) {
    $sheet->SetCellValue('A'.$rowcount, $row['genre']);
    $sheet->SetCellValue('D'.$rowcount, $row['lcn']);
    $sheet->SetCellValue('B'.$rowcount, $row['channel']);
    $sheet->SetCellValue('C'.$rowcount, $row['broadcaster']);
    $sheet->SetCellValue('E'.$rowcount, $row['rank']);
    $rowcount++;

}

$file_name=$city."_LCN_".Date('Y-m-d').".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_name.'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>