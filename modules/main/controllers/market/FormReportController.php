<?php

namespace app\modules\main\controllers\market;

use app\modules\main\models\FormQty;
use Yii;
use app\modules\main\models\Form;
use app\modules\main\models\FormReport;
use app\models\Report;

class FormReportController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new FormReport();

        if (Yii::$app->request->post('report')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $form = Form::findOne($model->form_id);
                $qty = $this->getOuantity($model->start,$model->finish);
                return $this->render('view', [
                    'model' => $model,
                    'form' => $form['name'],
                    'form_id' => $form['id'],
                    'qty' => $qty,
                ]);
            }
            return print_r($model->errors);
        }
        elseif (Yii::$app->request->post('export')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Report::GetStatistics($model->form_id,$model->version,$model->start,$model->finish);
            }
            $this->redirect('index');
        }
        else{
            $model->start = date('Y-m').'-01';
            $model->finish = date('Y-m-d');
            $forms = Form::find()->where(['=','is_active',1])->all();
            $verselect = ['new'=>'Новый вариант (версия 2)','old'=>'Старый вариант (версия 1)'];
            foreach($forms as $form) {
                $formselect[$form['id']] = $form['name']; //массив для заполнения данных в select формы
            }
            return $this->render('index',[
                'model' => $model,
                'formselect' => $formselect,
                'verselect' => $verselect,
            ]);
        }
    }

    private function getOuantity($from,$to){
        $qty = FormQty::find()->where(['between', 'date', $from, $to])->sum('qty');
        return $qty;
    }
}
