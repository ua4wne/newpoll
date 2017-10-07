<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\user\models\PasswordResetForm;
use app\modules\user\models\PasswordResetRequestForm;
use app\models\BaseModel;
use app\modules\user\models\User;
use app\models\Events;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    //private $attempt;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','password-reset-request','password-reset'],
                'rules' => [
                    [
                        'actions' => ['password-reset-request','password-reset'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'basic';
        $session = Yii::$app->session;
        $attempt = $session->get('num');
        if(!isset($attempt))
            $session->set('num', 0);
        if($attempt>5)
            return $this->render('ban');

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //запись в лог
            $msg = 'Пользователь <strong>'. $model->username .'</strong> вошел в систему '.date('d-m-Y H:i:s');
            BaseModel::AddEventLog('access',$msg);
            return $this->goBack();
        }
        else{
            //запись в лог
            /*$msg = 'Пользователь <strong>'. $model->username .'</strong> пытался войти в систему '.date('d-m-Y H:i:s');
            $log = new Events();
            $log->user_id = 1;
            $log->user_ip = $_SERVER['REMOTE_ADDR'];
            $log->type = 'access';
            $log->is_read = 0;
            $log->msg = $msg;
            $log->save();*/
            $attempt = $session->get('num');
            if($attempt<5)
                $attempt++;
            $session->set('num', $attempt);
            echo 'Число попыток='.$session->get('num');
            if($attempt==Yii::$app->params['max_attempts']){
                //число попыток входа исчерпано, блокируем пользователя
                $user = User::findByUsername($model->username);
                $user->status = 0;
                $user->save();
                //запись в лог
                $msg = 'Пользователь <strong>'. $model->username .'</strong> исчерпал все свои попытки входа в систему и поэтому был заблокирован '.date('d-m-Y H:i:s');
                $log = new Events();
                $log->user_id = 1;
                $log->user_ip = $_SERVER['REMOTE_ADDR'];
                $log->type = 'access';
                $log->is_read = 0;
                $log->msg = $msg;
                $log->save();
            }
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        //запись в лог
        $msg = 'Пользователь <strong>'. Yii::$app->user->identity->username .'</strong> вышел из системы '.date('d-m-Y H:i:s');
        $log = new Events();
        $log->user_id = 1;
        $log->user_ip = $_SERVER['REMOTE_ADDR'];
        $log->type = 'access';
        $log->is_read = 0;
        $log->msg = $msg;
        $log->save();
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //Yii::$app->user->id; ID залогиненного пользователя
     /*   if (!\Yii::$app->user->can('viewAdminPage')) {
            throw new ForbiddenHttpException('Access denied');
        } */
        $this->view->title = 'Информационная панель';
        return $this->render('index');
    }

    public function actionPasswordResetRequest()
    {
        $this->layout = 'basic';
        $model = new PasswordResetRequestForm($this->module->passwordResetTokenExpire);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $log = new Events();
                $log->user_id = 1;
                $log->user_ip = $_SERVER['REMOTE_ADDR'];
                $log->type = 'info';
                $log->is_read = 0;
                $log->msg = 'На email <strong>'. $model->email .'</strong> отправлен запрос на смену пароля пользователя <strong>'. $model->getUser()->username .'</strong>.';
                $log->save();
                return $this->goHome();
            } else {
                $log = new Events();
                $log->user_id = 1;
                $log->user_ip = $_SERVER['REMOTE_ADDR'];
                $log->type = 'error';
                $log->is_read = 0;
                $log->msg = 'Возникла ошибка при попытке отправки запроса на смену пароля пользователя <strong>'. $model->getUser()->username .'</strong> на email <strong>'. $model->email .'</strong>.';
                $log->save();
            }
        }
        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }

    public function actionPasswordReset($token)
    {
        $user = User::findByPasswordResetToken($token);
        $this->layout = 'basic';
        try {
            $model = new PasswordResetForm($token, $this->module->passwordResetTokenExpire);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $log = new Events();
            $log->user_id = 1;
            $log->user_ip = $_SERVER['REMOTE_ADDR'];
            $log->type = 'info';
            $log->is_read = 0;
            $log->msg = 'Установлен новый пароль для пользователя <strong>'. $user['username'].'</strong>.';
            $log->save();
            return $this->goHome();
        }
        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }
}
