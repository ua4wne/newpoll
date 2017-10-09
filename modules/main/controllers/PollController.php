<?php

namespace app\modules\main\controllers;
use app\modules\main\models\Form;
use app\modules\main\controllers\market\FormController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\HttpException;
use Yii;

class PollController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/poll.php';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
        //$model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            //$request = Yii::$app->request->post();
            $date = date('Y-m-d'); //текущая дата
            FormController::SavePoll($id, $date);
        }
        return $this->render('index', [
                'model' => $this->findModel($id),
                'content' => FormController::ViewForm($id),
        ]);
    }

    public function actionAddVisitor(){
        if (\Yii::$app->request->isAjax) {
            //$model = new Visit();
            $data = date('Y-m-d');
            $hours = date('H');
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // подключение к базе данных
            $conn = \Yii::$app->db;
            //обновляем старые записи, если есть
            $query = "select ucount from visit where data='$data' and hours='$hours'";
            $row = $conn->createCommand($query)->queryAll();
            //return print_r($row);
            if (empty($row)) {
                //добавляем новую запись
                $count = 1;
                $query = "insert into visit(data,hours,ucount,created_at,updated_at) values('$data','$hours',$count,'$created_at','$updated_at')";
                $conn->createCommand($query)->execute();
            } else {
                //обновляем старую запись
                $count = $row[0]['ucount'] + 1;
                $row[0]['ucount'] = $count;
                $query = "update visit set ucount=$count where data='$data' and hours='$hours'";
                $conn->createCommand($query)->execute();
            }
            $query = "select sum(ucount) as kolvo from visit where data = '$data'";
            $row = $conn->createCommand($query)->queryAll();
            return 'Учтено посетителей: ' . $row[0]['kolvo'];
        }
    }

    protected function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
