<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Inet;
use app\modules\main\models\InetSearch;
use app\modules\main\models\Renter;
use yii\web\Controller;
use Yii;
use app\models\BaseModel;
use \yii\web\HttpException;

class InetController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(Yii::$app->user->can('admin')) {
            $searchModel = new InetSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    public function actionReport(){
        $content = '<table class="table table-bordered table-hover">'.PHP_EOL;
        $content .= '<tr>
                        <th>Площадка</th>
                        <th>Арендатор</th>
                        <th>Участок</th>
                        <th>Дата подключения</th>
                        <th>Дата отключения</th>
                        <th>Тип подключения</th>
                        <th>Примечание</th>
                    </tr>';
        //$rows = Inet::find()->all();
        //выборка по арендаторам
        $query="select r.title, p.name, r.area, i.connect, i.disconnect, i.ip, i.comment from inet i
                join renter r on r.id = i.renter_id
                join place p on p.id = r.place_id
                order by p.name, CAST(r.area AS UNSIGNED)";
        // подключение к базе данных
        $connection = \Yii::$app->db;
        // Составляем SQL запрос
        $model = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $rows = $model->queryAll();
        foreach ($rows as $row){
            if($row['ip']=='static')
                $type = 'Выделенный IP';
            if($row['ip']=='dynamic')
                $type = 'Динамический IP';
            if(empty($row['disconnect'])){
                $content .= '<tr>';
            }
            else{
                $content .= '<tr class="danger">';
            }
            $content .= '<td>'. $row['name'] .'</td>
                                <td>'. $row['title'] .'</td>
                                <td>'. $row['area'] .'</td>
                                <td>'. $row['connect'] .'</td>
                                <td>'. $row['disconnect'] .'</td>
                                <td>'. $type .'</td>
                                <td>'. $row['comment'] .'</td>
                             </tr>'.PHP_EOL;
        }
        $content .= '</table>'.PHP_EOL;
        $itog = Inet::find()->where('connect is not null')->count();
        return $this->render('report', [
            'content' => $content,
            'itog' => $itog,
        ]);
    }

    public function actionCreate()
    {
        if(Yii::$app->user->can('admin')) {
            $model = new Inet();
            $model->created_at = date('Y-m-d H:i:s');
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong> добавлена запись о новом подключении интернет <strong>' . $model->renter->title . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['index']);
            } else {
                //return print_r($model->errors);
                $statsel = array('dynamic' => 'Динамический IP','static' => 'Выделенный IP');
                $renters = Renter::find()->select(['id', 'title', 'area'])->where(['status'=>1])->asArray()->all();
                $rentsel = array();
                foreach ($renters as $renter) {
                    $rentsel[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
                }
                return $this->render('create', [
                    'model' => $model,
                    'rentsel' => $rentsel,
                    'statsel' => $statsel,
                ]);
            }
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    /**
     * Updates an existing Inet model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $msg = 'Данные интернет-подключения арендатора <strong>' . $model->renter->title . '</strong> были обновлены пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
                BaseModel::AddEventLog('info', $msg);
                return $this->redirect(['index']);
            } else {
                //return print_r($model->errors);
                $statsel = array('dynamic' => 'Динамический IP','static' => 'Выделенный IP');
                $renters = Renter::find()->select(['id', 'title', 'area'])->where(['status'=>1])->asArray()->all();
                $rentsel = array();
                foreach ($renters as $renter) {
                    $rentsel[$renter['id']] = $renter['title'].' ('.$renter['area'].')'; //массив для заполнения данных в select формы
                }
                return $this->render('update', [
                    'model' => $model,
                    'rentsel' => $rentsel,
                    'statsel' => $statsel,
                ]);
            }
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    /**
     * Deletes an existing Inet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            //$this->findModel($id)->delete();
            $row = Inet::findOne($id);
            $row->delete();
            $msg = 'Данные интернет-подключения арендатора <strong>' . $row->renter->title . '</strong> были удалены пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong>.';
            BaseModel::AddEventLog('info', $msg);

            return $this->redirect(['index']);
        }
        else{
            throw new HttpException(500 ,'Доступ запрещен');
        }
    }

    /**
     * Finds the Inet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \app\modules\main\models\Renter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
