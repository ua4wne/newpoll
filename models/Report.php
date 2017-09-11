<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\controllers\HelpController;
use yii\db\Query;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Writer_Excel2007;
use PHPExcel_IOFactory;

class Report extends Model {

    public static function EnergyReport($save=FALSE){
        $query = new Query();
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            )
        );

        $objPHPExcel = new PHPExcel();
        $year = date('Y');
        $month = date('m');
        $period = explode('-', date('Y-m', strtotime("$year-$month-01 -1 month"))); //определяем предыдущий период
        $y = $period[0];
        $m = HelpController::SetMonth($period[1]);
        /*$file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'billing' => [
                    'data' => $query->select(['renter_id','delta','price'])
                        ->from('energy_log')
                        ->where(['year'=>$y,'month'=>$period[1]])
                        ->all(),
            'titles' => ['ID', 'Потребление, кВт', 'К оплате, руб.'],
        ],
    ]
]);
        $phpExcel = $file->getWorkbook();
        $phpExcel->getSheet(0)->getStyle('B1')
            ->getFont()->getColor()->setARGB(\PHPExcel_Style_Color::COLOR_RED);
        $file->send('demo');*/
        //готовим файл для отправки отчета бухгалтеру
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Ведомость по оплате за электроэнергию')
            ->setCellValue('A2', 'Год')
            ->setCellValue('B2', $y)
            ->setCellValue('A3', 'Месяц')
            ->setCellValue('B3', $m)
            ->setCellValue('A4', 'Участок №')
            ->setCellValue('B4', 'Территория')
            ->setCellValue('C4', 'За кем закреплен')
            ->setCellValue('D4', 'Арендатор')
            ->setCellValue('E4', 'К оплате, руб');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //Название листа
        $objPHPExcel->getActiveSheet()->setTitle("billing");

        //выборка по арендаторам
        $query="select p.name as pname, d.name as owner, r.title as rname, round(sum(l.price),2) as price from energy_log as l
                join renter as r on r.id = l.renter_id
                join division as d on d.id = r.division_id
                join place as p on p.id = r.place_id
                where l.year=$y and l.month=$m GROUP BY rname ORDER BY pname, owner, rname";

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        if($save){
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save("./download/billing.xlsx");

            if(file_exists('./download/billing.xlsx')){
                header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $filename = "billing.xlsx";
                header('Content-Disposition: attachment;filename='.$filename .' ');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
            }
        }
        else{
            header('Content-Type: application/vnd.ms-excel');
            $filename = "billing.xls";
            header('Content-Disposition: attachment;filename='.$filename .' ');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }

    }

}