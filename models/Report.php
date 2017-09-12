<?php

namespace app\models;

use app\modules\main\models\EnergyLog;
use Yii;
use yii\base\Model;
use app\controllers\HelpController;
//use yii\db\Query;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Writer_Excel2007;
use PHPExcel_IOFactory;
use PHPExcel_Style_NumberFormat;

class Report extends Model {

    public static function EnergyReport($save=FALSE){
    //    $query = new Query();
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                ),
            )
        );

        $styleCell = array(
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
            ->setCellValue('A5', '№ п\п')
            ->setCellValue('B5', 'Территория')
            ->setCellValue('C5', 'За кем закреплен')
            ->setCellValue('D5', 'Арендатор')
            ->setCellValue('E5', 'К оплате, руб');

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
                where l.year=$y and l.month=$period[1] GROUP BY rname ORDER BY pname, owner, rname";
        // подключение к базе данных
        $connection = \Yii::$app->db;
        // Составляем SQL запрос
        $model = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $rows = $model->queryAll();
    //    return print_r($rows);

        $k = 6;
        $num = 1;
        $pay = 0;
        foreach($rows as $row){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, $num)
                ->setCellValue('B'.$k, $row['pname'])
                ->setCellValue('C'.$k, $row['owner'])
                ->setCellValue('D'.$k, $row['rname'])
                ->setCellValue('E'.$k, $row['price']);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k)->applyFromArray($styleCell);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$k)->applyFromArray($styleCell);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$k)->applyFromArray($styleCell);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$k)->applyFromArray($styleCell);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->applyFromArray($styleCell);
            $k++;
            $num++;
            $pay+=$row['price'];
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$k, 'ИТОГО:')
            ->setCellValue('E'.$k, $pay);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$k.':E'.$k)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$k.':E'.$k)->applyFromArray($styleCell);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($styleArray);

        if($save){
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save("./download/billing.xlsx");
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

    public static function sendToMail($subj,$msg,$to,$file){
        $mail = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($to)
            ->setSubject($subj)
            //->setTextBody('Plain text content')
            ->setHtmlBody($msg)
            ->attach($file);
        if($mail->send())
            return 1;
        else
            return 0;
    }

}