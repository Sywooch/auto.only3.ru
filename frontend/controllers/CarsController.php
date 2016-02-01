<?php
namespace frontend\controllers;

use frontend\controllers\Only3Controller;
use frontend\models\UsersData;
use frontend\models\YandexForm;

use frontend\modules\account\models\Account;
use frontend\modules\account\models\ResetPasswordForm;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use frontend\models\System;
use frontend\modules\profile\models\SystemAutoSearch;
use frontend\modules\profile\models\Rentact;

use frontend\models\AutoSearch;

use common\models\Prepare;


use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

use yii\helpers\Json;

use yii\helpers\BaseArrayHelper;
use frontend\modules\account\models\PasswordForm;
/**
 * Site controller
 */
class CarsController extends Only3Controller
{
    /**
     * @inheritdoc
     */

    public $layout="main";
    public $title;

    const TEXT_RENT_SUCCESS = 'Бронирование было успешно выполнено, ожидайте с Вами свяжется менеджер салона';

    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(), [

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                    ],
                    [
                        'actions' => [['reserve-a-car-step3']],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'SelectCityFromIp' => [ 'class' => 'frontend\components\SelectCityFromIp\SelectCityFromIp',],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     *
     * TODO добавить ajax обработку
     */
    public function actionReserveACar($slug_url_system){

        $systemModel = System::findOne(['slug_url' => $slug_url_system]);

        $stepForm = 1;

        if(empty($systemModel)){
            $this->setNotFoundHttpException();
        }

        $rentModel = new Rentact();
        $rentModels = [];
        $this->title = $systemModel->name.' - автопрокат '.$systemModel->account->username;

        $newRentId = false;
        if(Yii::$app->request->post()) {
            $rentRes = $rentModel->createNewReserve(Yii::$app->request->post(), $systemModel);
            if($rentRes['is_ok']) {
                $newRentId = $rentModel->id;
                if(!Yii::$app->request->isAjax) {
                    //если салон подтвердил данные пользователя, то перебрасываем пользователя дальше
                    return $this->redirect(['/cars/reserve-a-car-step2', 'id' => $newRentId]);
                    /*
                    if(Yii::$app->user->identity->userData->isConfirmed()){
                        if($rentModel->system->isCanPay){
                            return $this->redirect(['/cars/reserve-a-car-step3', 'id' => $rentModel->id]);
                        } else {
                            Yii::$app->session->setFlash('info', self::TEXT_RENT_SUCCESS);
                        }
                    } else {
                        return $this->redirect(['/cars/reserve-a-car-step2', 'id' => $newRentId]);
                    }
                    */
                }
            }
        }

        if(Yii::$app->request->isAjax){
            $rentModels = $systemModel->rentact;
            $rentModels['newRentactId'] = $newRentId;
            $result = Json::encode($rentModels);
            echo $result;
        } else {
            $params = [
                'systemModel' => $systemModel,
                'rentModel' => $rentModel,
                'stepForm' => 1
            ];

            $template = 'reserveCar';
            if(Yii::$app->request->get('part') == '1'){
                $template = '_reserve-step1';
                $params['part'] = 1;
                $this->layout = 'main2';
            }

            return $this->render($template, $params);
        }
    }

    //TODO перенести сохранение в модель
    public function actionReserveACarStep2($id){

        if(Yii::$app->user->isGuest){
            return $this->redirect(['/cars/reserve-a-car-step-login','id' => $id]);
        }

        $clientUserData = Yii::$app->user->identity->userData;

        if(empty($clientUserData)){
            $this->setForbiddenHttpException('Произошла ошибка'); //данные по клиенту должны были быть занесены на первом шаге
        }

        $rentModel = $this->findRentModel($id);
        $systemModel = $rentModel->system;

        $prepareModel = UsersData::findOne($rentModel->user_data_id);

        $passwordFormModel = new ResetPasswordForm(Yii::$app->user->identity);
        $passwordFormModel->setScenario('change');

        //$prepareModel->setScenario('client-validate');
        if(Yii::$app->request->post()){

            if($passwordFormModel->load(Yii::$app->request->post())){
                if($passwordFormModel->validate()){
                    $passwordFormModel->resetPassword();
                    Yii::$app->session->setFlash('success', 'Смена пароля была произведена успешно');
                }
            }

            if($prepareModel->load(Yii::$app->request->post())){

                $changedAttributes = $prepareModel->loadImages();
                $changedAttributes = array_merge($prepareModel->getDirtyAttributes(), $changedAttributes);

                if($prepareModel->save()) {
                    if (!empty($changedAttributes)) {
                        $clientUserData->setAttributes($changedAttributes);
                        $clientUserData->save();
                    }

                    if($rentModel->system->isCanPay){
                        return $this->redirect(['/cars/reserve-a-car-step3', 'id' => $rentModel->id]);
                    } else {
                        Yii::$app->session->setFlash('info', self::TEXT_RENT_SUCCESS);
                        return $this->redirect($rentModel->system->PageReserve);
                    }

                }

            }

        } else {
            if (!empty($clientUserData)) {
                $prepareModel->setAttributes($clientUserData->getAttributes());
            } else {
                $prepareModel->loadDefaultValues();
            }
        }

        return $this->render('reserveCar', [
            'systemModel' => $systemModel,
            'rentModel' => $rentModel,
            'stepForm'  => 2,
            'prepareModel'  => $prepareModel,
            'passwordFormModel' => $passwordFormModel
        ]);

    }

    public function actionReserveACarStepLogin($id){

        $loginModel = new \frontend\modules\account\models\LoginForm();

        if (!Yii::$app->user->isGuest) {//after login get default userdata
            $rentModel = $this->findRentModel($id);

            $currentUserdata = $rentModel->userData;
            $userData = UsersData::getUserDataForReserve(Yii::$app->user->id, $rentModel->system->account_id);
            $currentUserdata->setAttributes($userData->getAttributes());
            $currentUserdata->save();

            return $this->redirect(['/cars/reserve-a-car-step2','id' => $id]);
        } else {
            $rentModel = $this->findRentModelNotAuth($id);
            $systemModel = $rentModel->system;
            $loginModel->phone = $rentModel->phone;
        }

        return $this->render('reserveCar', [
            'systemModel' => $systemModel,
            'rentModel' => $rentModel,
            'stepForm'  => 'auth',
            'loginModel'  => $loginModel
        ]);

    }

    public function actionReserveACarStep3($id){

        $rentModel = $this->findRentModel($id);
        if($rentModel->is_payed){
            Yii::$app->session->setFlash('info', self::TEXT_RENT_SUCCESS);
            $slug_url = $rentModel->system->PageReserve;
            return $this->redirect($slug_url);
        } else {

            $systemModel = $rentModel->system;
            $yandexForm = new YandexForm();

            return $this->render('reserveCar', [
                'systemModel' => $systemModel,
                'rentModel' => $rentModel,
                'yandexForm' => $yandexForm,
                'stepForm' => 3
            ]);
        }
    }

    public function actionCancelReserve($id){

        $rentModel = $this->findRentModel($id);

        if($rentModel) {
            $userData = $rentModel->userData;
            $systemModel = $rentModel->system;

            if ($userData->account_id === Yii::$app->user->id) {

                $rentModel->setCancel()->save();
                Yii::$app->session->setFlash('warning', 'Бронирование было успешно отменено');
                return $this->redirect($rentModel->system->PageReserve);

            } else {
                return $this->setForbiddenHttpException();
            }

            return $this->render('reserveCar', [
                'systemModel' => $systemModel,
                'rentModel' => $rentModel,
                'stepForm' => 1,
            ]);

        } else {
            $this->setNotFoundHttpException();
        }

    }

    public function actionForSite($slug_url_system){

        $_GET['part'] = 1; //дикий костыль
        return $this->actionReserveACar($slug_url_system);
    }

   public function actionGetxy()
   {
         $city = $_POST['add'];
         $c = City::find()->where(['name' => $city])->one();
         $xy = $c->xy; 
 
         return Json::encode($xy);
   }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionOurCars($slug_url){

        $accountModel = Account::findOne(['slug_url' => $slug_url]);

        if(empty($accountModel)){
            $this->setNotFoundHttpException();
        }

        $params['AutoSearch']['account_id'] = $accountModel->id;

        $this->title = "Автопрокат - ".$accountModel->username;

        $searchModel = new AutoSearch();
        $searchModel->setScenario('salon-cars');
        $dataProvider = $searchModel->search($params);

        return $this->render('ourCars', [
            'dataProvider' => $dataProvider,
            'accountModel' => $accountModel,
        ]);

    }

    protected function findModel($id)
    {
        if (($model = System::findOne($id)) !== null) {
            return $model;
        } else {
            $this->setNotFoundHttpException();
        }
    }

    protected function findRentModelNotAuth($id)
    {
        if (($model = Rentact::findOne($id)) !== null) {
            return $model;
        } else {
            $this->setNotFoundHttpException();
        }

    }

    protected function findRentModel($id)
    {
        if (($model = Rentact::findOne($id)) !== null) {
            if($model->userData->account_id !== Yii::$app->user->id){
                $this->setForbiddenHttpException();
            } else {
                return $model;
            }
        } else {
            $this->setNotFoundHttpException();
        }
    }
}
