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
use PHPExcel_Style_Color;
use PHPExcel_RichText;
use PHPExcel_Style_Fill;
use app\modules\main\models\Renter;
use app\modules\main\models\RentLog;
use app\modules\main\models\Visit;

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

    public static function RenterReport($renters,$start,$finish){
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
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //готовим файл excel
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Время работы домов');
        $k=1;
        if(count($renters)==1){ //выбран один арендатор
            foreach($renters as $renter){
                $model_renter = Renter::findOne($renter);
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, 'Учет времени присутствия на выставке за период с '.$start.' по '.$finish.'');
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':L'.$k);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k++;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, 'Компания "'.$model_renter->title.'"  участок '.$model_renter->area);
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':L'.$k);
            //$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k++;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, 'Дата\Период');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$k, '10-11');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$k, '11-12');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$k, '12-13');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$k, '13-14');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$k, '14-15');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('G'.$k, '15-16');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H'.$k, '16-17');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('I'.$k, '17-18');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('J'.$k, '18-19');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K'.$k, '19-20');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('L'.$k, '20-21');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->applyFromArray($styleArray);
            $k++;
            //цикл по датам и периодам
            $logs = RentLog::find()->select(['data', 'period1', 'period2', 'period3', 'period4', 'period5', 'period6', 'period7', 'period8', 'period9', 'period10', 'period11'])
                ->where(['=', 'renter_id', $renter])->andWhere(['between', 'data', $start, $finish])->orderBy(['data' => SORT_ASC])->all();
            foreach($logs as $log){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$k, $log->data);
                for($j=1; $j<12; $j++)
                {
                    $period = 'period'.$j;
                    if($log->$period==1)
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $k, 'Да');
                    else
                    {
                        $objRichText = new PHPExcel_RichText();
                        $objRichText->createText('');
                        $objNo = $objRichText->createTextRun('Нет');
                        $objNo->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j, $k, $objRichText);
                    }
                }//for
                $k++;
            }
        }
        if(count($renters) > 1){ //выбрано более одного арендатора
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, 'Учет времени присутствия на выставке за период с '.$start.' по '.$finish.'');
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':E'.$k);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':E'.$k)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':E'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k++;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, '№ участка');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$k, 'Название компании');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$k, 'Кол-во часов');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$k, 'Кол-во дней');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$k, 'В среднем часов в день');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':E'.$k)->applyFromArray($styleArray);
            $k++;
            // подключение к базе данных
            $connection = \Yii::$app->db;
            foreach($renters as $renter) {
                //группировка по датам и периодам
                $query = "SELECT renter.title, renter.area, Sum(period1)+Sum(period2)+Sum(period3)+Sum(period4)+Sum(period5)+Sum(period6)+Sum(period7)+Sum(period8)+Sum(period9)+Sum(period10)+Sum(period11) AS alltime,";
                $query .= "count(rent_log.data) AS alldata FROM rent_log INNER JOIN renter ON renter.id = rent_log.renter_id";
                $query .= " WHERE renter_id=" . $renter . " AND rent_log.`data` BETWEEN '" . $start . "' AND '" . $finish . "'";
                $query .= " GROUP BY renter.title, renter.area ORDER BY renter.area+0";
                $result = $connection->createCommand($query)->queryAll();
                if(count($result)==0)
                    continue;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$k, $result[0]['area']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$k, $result[0]['title']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$k, $result[0]['alltime']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$k, $result[0]['alldata']);
                if($result[0]['alldata'] > 0)
                    $avg=round($result[0]['alltime']/$result[0]['alldata'],2);
                else
                    $avg = 0;
                $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getNumberFormat()
                    ->setFormatCode('[Black][>=9]#,##0.00;[Red][<9]#,##0.00');
                $objPHPExcel->getActiveSheet()->getCell('E'.$k)->setValue($avg);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$k)->getNumberFormat();
                $k++;
            }
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "presence.xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public static function VisitReport($start,$finish){

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
        //готовим файл excel
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Посетители выставки');
        $k=1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$k, 'Учет количества посетителей выставки за период с '.$start.' по '.$finish.'');
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':L'.$k);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $k=5;
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //    $k++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$k, 'Дата\Период');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$k, '10-11');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$k, '11-12');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$k, '12-13');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$k, '13-14');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$k, '14-15');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$k, '15-16');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H'.$k, '16-17');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$k, '17-18');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$k, '18-19');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$k, '19-20');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('L'.$k, '20-21');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->applyFromArray($styleArray);
        $k++;
        $itog = 0; //общее кол-во посетителей
        $dates = Visit::find()->select(['data'])->distinct()->where(['between', 'data', $start, $finish])->orderBy(['data' => SORT_ASC])->all();
        $cell = ['10'=>'B','11'=>'C','12'=>'D','13'=>'E','14'=>'F','15'=>'G','16'=>'H','17'=>'I','18'=>'J','19'=>'K','20'=>'L'];
        foreach($dates as $date){
            $logs=Visit::find()->select(['hours','ucount'])->where(['=','data',$date->data])->orderBy(['hours' => SORT_ASC])->all();
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, $date->data);
            //сначала заполняем все нулями
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('G'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('I'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('J'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('L'.$k, 0);
            foreach($logs as $log){
                $itog = $itog+$log->ucount;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($cell[$log->hours].$k, $log->ucount);
            }
            $k++;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Всего посетителей выставки - '.$itog);
        $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "attendance.xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public static function VisitTemplate(){
        $currd = date('Y-m');
        $start = $currd.'-01';
        $finish = date('Y-m-t');
        $max = date('t');

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
        //готовим файл excel
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Посетители выставки');
        $k=1;
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //    $k++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$k, 'Дата\Период');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$k, '10-11');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$k, '11-12');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$k, '12-13');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$k, '13-14');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$k, '14-15');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$k, '15-16');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H'.$k, '16-17');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$k, '17-18');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$k, '18-19');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$k, '19-20');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('L'.$k, '20-21');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->applyFromArray($styleArray);
        $k++;
        for($i=1;$i<=$max;$i++)
        {
            $d = $i;
            if(strlen($d)==1)
                $d = '0'.$d;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, $currd.'-'.$d);
            $k++;
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "template.xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public static function WorkTemplate($date){
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
        //готовим файл excel
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Присутствие на выставке');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $date);
        $k=2;
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //    $k++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$k, 'ID');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$k, 'Арендатор\Период');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$k, '10-11');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$k, '11-12');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$k, '12-13');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$k, '13-14');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$k, '14-15');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H'.$k, '15-16');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$k, '16-17');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$k, '17-18');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$k, '18-19');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('L'.$k, '19-20');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M'.$k, '20-21');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':M'.$k)->applyFromArray($styleArray);
        $k++;
        $in = Yii::$app->params['place_mc'];
        $rents = Renter::find()->select(['id','title','area'])->where(['status'=>1])->andWhere(['in','place_id',$in])->orderBy(['cast(area as unsigned)'=>SORT_ASC])->all();
        foreach ($rents as $rent){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$k, $rent->id);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$k, $rent->title . '    участок № ' . $rent->area);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':A'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('G'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('I'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('J'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('L'.$k, 0);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M'.$k, 0);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$k.':M'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k++;
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //$objWriter->save("./download/template.xlsx");
        header('Content-Type: application/vnd.ms-excel');
        $filename = "template.xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public static function CalculateToExcel($year,$renters){

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
        //готовим файл excel
        $p=0;
        foreach($renters as $renter){
            $logs = EnergyLog::find()->select(['month','encount','delta','price'])->where(['=','renter_id',$renter])->andWhere(['=','year',$year])->orderBy('month', SORT_ASC)->all();
            $title = Renter::findOne($renter);
            $objPHPExcel->setActiveSheetIndex($p);
            $objPHPExcel->getActiveSheet()->setTitle($title->title.' ('.$title->area.')');
            $k=1;
            $objPHPExcel->setActiveSheetIndex($p)
                ->setCellValue('A'.$k, 'Расчет энергопотребления за '.$year.' год');
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':D'.$k);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':D'.$k)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':D'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k=3;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':L'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex($p)
                ->setCellValue('A'.$k, 'Месяц');
            $objPHPExcel->setActiveSheetIndex($p)
                ->setCellValue('B'.$k, 'Показания счетчика, кВт');
            $objPHPExcel->setActiveSheetIndex($p)
                ->setCellValue('C'.$k, 'Потребление, кВт');
            $objPHPExcel->setActiveSheetIndex($p)
                ->setCellValue('D'.$k, 'Сумма, руб');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$k.':D'.$k)->applyFromArray($styleArray);
            $k++;
            foreach ($logs as $log){
                $month = HelpController::SetMonth($log->month);
                $objPHPExcel->setActiveSheetIndex($p)
                    ->setCellValue('A'.$k, $month);
                //сначала заполняем все нулями
                $objPHPExcel->setActiveSheetIndex($p)
                    ->setCellValue('B'.$k, $log->encount);
                $objPHPExcel->setActiveSheetIndex($p)
                    ->setCellValue('C'.$k, $log->delta);
                $objPHPExcel->setActiveSheetIndex($p)
                    ->setCellValue('D'.$k, $log->price);
                $k++;
            }
            $p++;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->createSheet();
        }
        header('Content-Type: application/vnd.ms-excel');
        $filename = "calculate.xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public static function VisitTable($start,$finish){
        $content = '<table class="table table-hover table-striped"><tr>
                        <th>Дата\Период</th><th>10-11</th><th>11-12</th><th>12-13</th><th>13-14</th><th>14-15</th>
                        <th>15-16</th><th>16-17</th><th>17-18</th><th>18-19</th><th>19-20</th><th>20-21</th><th>Итого за день</th></tr>';
        $dates = Visit::find()->select(['data'])->distinct()->where(['between', 'data', $start, $finish])->orderBy(['data' => SORT_ASC])->all();
        $sum = 0;
        foreach($dates as $date){
            $itog = 0;
            $data = ['10'=>'0','11'=>'0','12'=>'0','13'=>'0','14'=>'0','15'=>'0','16'=>'0','17'=>'0','18'=>'0','19'=>'0','20'=>'0'];
            $logs=Visit::find()->select(['data','hours','ucount'])->where(['=','data',$date->data])->orderBy(['hours' => SORT_ASC])->all();
            foreach($logs as $log){
                $data[$log->hours] = $log->ucount;
                $itog+=$log->ucount;
            }
            $date=explode("-", $log-data);
            $numday = date("w", mktime(0, 0, 0, $date[1], $date[2], $date[0]));
            if($numday==0 || $numday==6)
                $content.='<tr class="warning"><td>'.$log->data.'</td>'; //это выходные
            else
                $content.='<tr><td>'.$log->data.'</td>';
            foreach($data as $val){
                $content.='<td>'.$val.'</td>';
            }
            $content.='<td>'.$itog.'</td></tr>';
            $sum+=$itog;
        }

        $content .= '</table>';
        $header = "<p>Всего посетителей: <strong>$sum</strong></p>";
        return $header.$content;
    }

    public static function GetStatistics($form_id,$ver,$start,$finish){
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

        $styleRow = array(
            'font' => array(
                'bold' => false,
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
        if($ver == 'new'){
            $fill = array('FFFFEFD5','FFFFDAB9','FFFFF8DC','FFFFE4B5','FFFFE4E1','FFBEBEBE','FF6A5ACD','FF4169E1','FF5F9EA0','FF2E8B57','FFADFF2F','FFDAA520','FFD2B48C','FFFFA500','FFDDA0DD','FFFFDAB9','FFFFF8DC','FFFFE4B5','FFFFE4E1','FFBEBEBE');
            //определяем вопросы анкеты
            $query = "select distinct `q`.`id` AS `qid`,`q`.`name` AS `q_name` from (`questions` as q join `logger` as l on((`q`.`id` = `l`.`question_id`))) where `q`.`form_id`=$form_id and data between '$start' and '$finish'";
            // подключение к базе данных
            $connection = \Yii::$app->db;
            // Составляем SQL запрос
            $model = $connection->createCommand($query);
            //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
            $rows = $model->queryAll();
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //готовим файл excel
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Статистика');
            $objPHPExcel->getActiveSheet()->setCellValue('A1','Номер');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
            $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFill()->getStartColor()->setARGB($fill[5]);
            $col=1;
            $row=1;
            $step=1; //смещение по второй строке
            $cell_one = array(); //массив первых ячеек ответов каждого вопроса
            $qstart =$rows[0][qid]; //первый вопрос анкеты
            $f = 0;
            foreach($rows as $arr){
                //$arr_qst[]=$arr[qid];
                //выбираем ответы на вопрос
                $query="SELECT id,`name` FROM answers WHERE question_id=$arr[qid]";
                // Составляем SQL запрос
                $model = $connection->createCommand($query);
                //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                $answers = $model->queryAll();
                // получаем доступ к ячейке по номеру строки
                // (нумерация с единицы) и столбца (нумерация с нуля)
                $cell_start = $objPHPExcel->setActiveSheetIndex(0)->getCellByColumnAndRow($col, $row)->getColumn();
                $cell_one["$arr[qid]"]=$cell_start;
                for($j=0;$j<count($answers);$j++){ //заполняем заголовки ответов
                    $ans = $answers[$j];
                    $curr_cell=$objPHPExcel->setActiveSheetIndex(0)->getCellByColumnAndRow($step, 2)->getColumn();
                    $objPHPExcel->getActiveSheet()->setCellValue($curr_cell.'2', $ans['name']);
                    $objPHPExcel->getActiveSheet()->getStyle($curr_cell . '2:'.$curr_cell . '2')->applyFromArray($styleRow);
                    $step++;
                }//цикл по ответам
                $col = $col + count($answers)-1;
                $cell_end = $objPHPExcel->setActiveSheetIndex(0)->getCellByColumnAndRow($col, $row)->getColumn();
                $col++;

                $objPHPExcel->getActiveSheet()->setCellValue($cell_start.$row, $arr['q_name'].'?');
                $objPHPExcel->getActiveSheet()->mergeCells($cell_start.$row.':'.$cell_end.$row);
                $objPHPExcel->getActiveSheet()->getStyle($cell_start.$row.':'.$cell_end.$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle($cell_start.$row.':'.$cell_end.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cell_start.$row.':'.$cell_end.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle($cell_start.$row.':'.$cell_end.$row)->getFill()->getStartColor()->setARGB($fill[$f]);
                $f++;
            }

            $month_cell=$objPHPExcel->setActiveSheetIndex(0)->getCellByColumnAndRow($step, 2)->getColumn();
            $objPHPExcel->getActiveSheet()->setCellValue($month_cell.$row, 'Месяц опроса');
            $objPHPExcel->getActiveSheet()->mergeCells($month_cell.'1:'.$month_cell.'2');
            $objPHPExcel->getActiveSheet()->getStyle($month_cell.'1:'.$month_cell.'2')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle($month_cell.'1:'.$month_cell.'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($month_cell.'1:'.$month_cell.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle($month_cell.'1:'.$month_cell.'2')->getFill()->getStartColor()->setARGB($fill[$f]);

            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(140);

            //заполняем ответами на вопросы по анкете
            $query="SELECT id,`data`,question_id,answer_id,answer FROM logger WHERE form_id=$form_id AND `data` BETWEEN '$start' AND '$finish'";
            // Составляем SQL запрос
            $model = $connection->createCommand($query);
            //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
            $dat = $model->queryAll();
            $data_row=4;
            $num = 1; //порядковый номер анкеты
            $k=0;
            foreach($dat as $val){
                $idq = $val['question_id'];
                $cell_start = $cell_one["$idq"];
                $curr_cell = $cell_start;
                //выбираем ответы на текущий вопрос
                $query="SELECT id,name FROM answers WHERE question_id=$idq";
                // Составляем SQL запрос
                $model = $connection->createCommand($query);
                //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                $answ = $model->queryAll();
                $month = substr($val['data'],5,2); //выделяем месяц
                $arr_ans = array(); //массив ответов на вопрос
                for($i=0;$i<count($answ);$i++){
                    $tmp = $answ[$i];
                    //$arr_ans[$i] = $tmp[tid];
                    $arr_ans["$tmp[id]"] = $tmp['name'];
                }
                //return print_r($answ);
                foreach ($arr_ans as $key=>$value) {
                    //$val = $objPHPExcel->getActiveSheet()->getCell($curr_cell . $data_row)->getCalculatedValue();
                    if($key==$val['answer_id']) {
                        $pos = strpos($value, 'укажите');
                        if ($pos === false)
                            $objPHPExcel->getActiveSheet()->setCellValue($curr_cell . $data_row, 1);
                        else
                            $objPHPExcel->getActiveSheet()->setCellValue($curr_cell . $data_row, $val['name']);
                        $objPHPExcel->getActiveSheet()->setCellValue($month_cell . $data_row, $month);
                        $objPHPExcel->getActiveSheet()->getStyle($month_cell . $data_row.':'.$month_cell . $data_row)->applyFromArray($styleRow);
                    }
                    //if($val!=1)
                    //	$objPHPExcel->getActiveSheet()->setCellValue($curr_cell . $data_row, 0);
                    $curr_cell++;
                }
                $curr_cell = $cell_start;
                for($i=0;$i<count($arr_ans);$i++){
                    $val = $objPHPExcel->getActiveSheet()->getCell($curr_cell . $data_row)->getCalculatedValue();
                    if(strlen($val)==0)
                        $objPHPExcel->getActiveSheet()->setCellValue($curr_cell . $data_row, 0);
                    $objPHPExcel->getActiveSheet()->getStyle($curr_cell . $data_row.':'.$curr_cell . $data_row)->applyFromArray($styleRow);
                    $curr_cell++;
                }

                //$objPHPExcel->getActiveSheet()->setCellValue($curr_cell.$data_row, 1);
                if($idq==$qstart&&$k>3){
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$data_row, $num);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $data_row.':A' . $data_row)->applyFromArray($styleRow);
                    $data_row++;
                    $num++;
                }
                $k++;
            }

            header('Content-Type: application/vnd.ms-excel');
            $filename = "statistics.xls";
            header('Content-Disposition: attachment;filename='.$filename .' ');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
        else{
            Yii::$app->session->setFlash('error', 'Выбранная версия экспорта не поддерживается! Обратитесь к администратору.');
        }
    }

}