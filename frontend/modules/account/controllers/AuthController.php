<?php
namespace frontend\modules\account\controllers;

use frontend\modules\account\controllers\AccountController;
use frontend\modules\account\Module;
use Yii;

use frontend\modules\account\models\SignupForm;

use frontend\modules\account\models\Account;

use frontend\modules\account\models\LoginForm;
use frontend\modules\account\models\PasswordResetRequestForm;
use frontend\modules\account\models\ResetPasswordForm;
use frontend\modules\account\models\PasswordForm;

use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AuthController extends AccountController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(),[
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
//                'minLength' => 4,
//                'maxLength' => 4,
                'fixedVerifyCode' => (YII_ENV_TEST ? 'testme' : null)
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        $this->title = "Авторзация";

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
                if(Yii::$app->user->can('salon')){
                    return $this->redirect([Module::URL_ROUTE_PROFILE]);
                } else {
                    return $this->redirect(Yii::$app->getUser()->getReturnUrl($this->goHome()));
                }
            //return $this->goBack();

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect([Module::URL_AFTER_REGISTRATION]);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignupClient()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup-client', [
            'model' => $model,
        ]);
    }

    /**f
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {

        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($userId = $model->sendSms()) {
                Yii::$app->session->setFlash('success', 'Отправка sms с кодом для сброса пароля произведена.');
                return $this->redirect(['/account/auth/reset-password', 'id'=>$userId]);
            } else {
                Yii::$app->session->setFlash('error', 'Извините произошла ошибка, мы не можем отправить Вам сообщение');
            }
        } else {

        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($id)
    {
        $user = Account::findOne($id);
        $model = new ResetPasswordForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if(!$user->findByPasswordResetToken($model->password_reset_token)){
                $model->addError('password_reset_token');
            } else {
                $model->resetPassword();
                Yii::$app->session->setFlash('success', 'Смена пароля была произведена успешно');
                Yii::$app->user->login($user);
                return $this->redirect(Yii::$app->getUser()->getReturnUrl($this->goHome()));
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);

        /*
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
        */
    }

    public function actionChangePassword(){

        $passwordFormModel = new PasswordForm();
        $passwordFormModel->setScenario('change');

        if(Yii::$app->request->post() && $passwordFormModel->load(Yii::$app->request->post())){
            if($passwordFormModel->validate()){
                Yii::$app->session->set('registered_password', $passwordFormModel->password);
                $passwordFormModel->changePassword();
            }
        }

        Yii::$app->session->setFlash('success', 'Смена пароля была произведена успешно');
        return $this->redirect(Yii::$app->getUser()->getReturnUrl($this->goHome()));
    }
}