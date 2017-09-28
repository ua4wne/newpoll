<?php

namespace app\modules\user\controllers;

use app\modules\user\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\user\models\PasswordResetForm;
use app\modules\user\models\PasswordResetRequestForm;
use app\models\BaseModel;
use app\modules\user\models\User;
use Yii;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','password-reset-request'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['password-reset-request'],
                        'allow' => true,
                        'roles' => ['?'],
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
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
                Yii::$app->getSession()->setFlash('success', 'Запрос на смену пароля отправлен!');
                $log = 'На email <strong>'. $model->email .'</strong> отправлен запрос на смену пароля пользователя <strong>'. $model->getUser()->username .'</strong>.';
                BaseModel::AddEventLog('info',$log);
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Возникла ошибка при попытке отправки запроса на смену пароля.');
                Yii::$app->getSession()->setFlash('success', 'Запрос на смену пароля отправлен!');
                $log = 'Возникла ошибка при попытке отправки запроса на смену пароля пользователя <strong>'. $model->getUser()->username .'</strong> на email <strong>'. $model->email .'</strong>.';
                BaseModel::AddEventLog('info',$log);
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
            Yii::$app->getSession()->setFlash('success', 'Новый пароль установлен!');
            $log = 'Новый пароль для пользователя <strong>'. $user['username'].'</strong> установлен.';
            BaseModel::AddEventLog('info',$log);
            return $this->goHome();
        }
        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }
}
