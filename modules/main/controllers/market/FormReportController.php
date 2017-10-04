<?php

namespace app\modules\main\controllers\market;

use Yii;
use app\modules\main\models\Form;
use app\modules\main\models\FormReport;

class FormReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new FormReport();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $content='https://developers.google.com/chart/interactive/docs/gallery/piechart';
            $form = Form::findOne($model->form_id);
            return $this->render('view',[
                'model' => $model,
                'form' => $form['name'],
                'content' => $content,
            ]);
        }
        else{
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

}
