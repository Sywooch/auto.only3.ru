<?php
namespace frontend\controllers;

use common\models\payments\PaymentsTransaction;
use frontend\models\UsersData;
use frontend\modules\profile\models\Fake;
use Yii;
use Yii\helpers\Html;

use frontend\models\ContactForm;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\modules\account\models\Account;
use frontend\modules\profile\models\SystemAuto;
use frontend\modules\profile\models\Rentact;

use frontend\models\AutoSearch;
use frontend\models\Transaction;
use frontend\models\City;

use yii\helpers\ArrayHelper;

use yii\web\NotFoundHttpException;

use common\models\SystemLogs;

use yii\helpers\Json;

/**
 * Site controller
 */
class SiteController extends Only3Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    public $layout="main";

    public function beforeAction($action)
    {
        if ($this->action->id == 'yandex-pay') {
            SystemLogs::WriteAccessLog();
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'SelectCityFromIp' => [ 'class' => 'frontend\components\SelectCityFromIp\SelectCityFromIp',],
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
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        $searchModel = new AutoSearch();
        $searchModel->city_url = $this->city_url;

        $searchModel->day = ArrayHelper::getValue(Yii::$app->request->queryParams, 'AutoSearch.day');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->city);

        $this->title = "Бронирование авто в ".$this->city_padezh;

        $salonModels = Account::getSalonsListModels($this->city, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'salonModels' => $salonModels
        ]);
    }


    public function actionIndex2()
    {
        $searchModel = new AutoSearch();
        $searchModel->city_url = $this->city_url;

        $searchModel->day = ArrayHelper::getValue(Yii::$app->request->queryParams, 'AutoSearch.day');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->city);

        $this->title = "Бронирование авто в ".$this->city_padezh;

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContactus()
    {
        $this->title = "Обратная связь";
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            $name    = $model->name;
            $mail    = $model->email;
            $tel     = $model->subject;
            $content = $model->body;
            $subject = "Сообщение - auto.only3.ru";
            $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
            $message = "<strong>Имя:</strong> ".$name."<br><strong>Почта: </strong>".$mail."<br><strong>Телефон: </strong>".$tel."<br>"."<strong>Сообщение: </strong>".$content;
            $headers  = "Content-type: text/html; charset=utf-8 \r\nFrom:support@only3.ru";
            mail("polyakovkp@mail.ru", $subject, $message, $headers);
            mail("hejvkt@yandex.ru", $subject, $message, $headers);
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contactus', ['model' => $model]);

    }

    public function actionEmailok()
    {
        $this->title = "Спасибо за подписку";
        return $this->render('emailok');
    }


public function actionEmail()
    {
        $this->title = "Email-подписка";
        // Ваш ключ доступа к API (из Личного Кабинета)
        $api_key = "5ie6aquch4eex3zrgaf3bas9u6n9m3aae6r8b1na";
        // Данные о новом подписчике
        if($_POST['email'] != NULL && $_POST['f_4677794']!=NULL){

          $user_email = $_POST['email'];
          $user_name = $_POST['f_4677794'];
          $user_lists = "6068866";
          $user_tag = urlencode("Added using API");

          // Создаём POST-запрос
          $POST = array (
          'api_key' => $api_key,
          'list_ids' => $user_lists,
          'fields[email]' => $user_email,
          'fields[Name]' => $user_name,
          'tags' => $user_tag
          );

        // Устанавливаем соединение
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
          curl_setopt($ch, CURLOPT_TIMEOUT, 10);
          curl_setopt($ch, CURLOPT_URL,'http://api.unisender.com/ru/api/subscribe?format=json');
          $result = curl_exec($ch);

          if ($result) {
            // Раскодируем ответ API-сервера
            $jsonObj = json_decode($result);

          if(null===$jsonObj) {
            // Ошибка в полученном ответе
           // echo "Invalid JSON";
          }
          elseif(!empty($jsonObj->error)) {
            // Ошибка добавления пользователя
            //  echo "An error occured: " . $jsonObj->error . "(code: " . $jsonObj->code . ")";

          } else {
            // Новый пользователь успешно добавлен
            // echo "Added. ID is " . $jsonObj->result->person_id;
          }
        } else {
          // Ошибка соединения с API-сервером
          //  echo "API access error";
        }

      }
        return $this->render('email');
  
 }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionReserveACar($id){

        $systemModel = $this->findModel($id);
        $rentModel = new Rentact();
        $this->title = 'Бронирование авто';
        return $this->render('reserveCar', [
            'model' => $systemModel,
            'rentModel' => $rentModel
        ]);

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


    public function actionChangeCity($city){

        $fCity = City::findOne($city);
        if($fCity){
            $this->city = \yii\helpers\Html::encode($fCity->name);
            Yii::$app->session->set('city', $this->city);
            Yii::$app->session->set('xy', $fCity->xy);

            Yii::$app->params['city_url'] = $fCity->trans;

        }

        return $this->goBack();
    }

    public function actionResult()
    {
     $mrh_pass2 = "96542888p2";

     $out_summ = $_GET["OutSum"];
     $inv_id = $_GET["InvId"];
     $crc = $_GET["SignatureValue"];
     $user = $_GET["Shpuser"];
     $crc = strtoupper($crc); 

     $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shpuser=$user"));

     if (strtoupper($my_crc) != strtoupper($crc))
     {
       echo "bad sign\n";
       exit();
     }

     $t = new Transaction;
     $t->user = $user;
     $t->price = $out_summ;
     $t->status = strval($inv_id);
     $t->time = strval(time());
     if($t->save()){
       $ac = Account::findOne(['id' => $user]);
       $ac->balance = $ac->balance+$out_summ;
       $ac->save();
     }

     echo "OK$inv_id\n";
    }



    public function actionMinusbalance()
    {
      $accs = Account::find()->all();  

      foreach ($accs as $key => $value) {
       if($value->is_moderated == 2){
   
  
           $myCars = SystemAuto::find()->where(['account_id' => $value->id])->all();
           $valCars = count($myCars);
           $mycarpay = 1;
           /*
            if($valCars>=0 && $valCars<2){ $mycarpay = 10; }
            elseif($valCars>=2 && $valCars<4) { $mycarpay = 9; }
            elseif($valCars>=4 && $valCars<6) { $mycarpay = 8; }
            else { $mycarpay = 7; } */

           $everyDayPay = $mycarpay*$valCars;
           $everyDayPay = round($everyDayPay,0);

           $value->balance = $value->balance - $everyDayPay;
           if($value->balance<0){
               $value->balance = -1;
           }
           $value->save();
        

       }     
      }
    }


    public function actionUpSlug(){

        $systems = SystemAuto::find()->all();
        foreach($systems as $model){
            $model->save();
        }

        $systems = Account::find()->all();
        foreach($systems as $model){
            $model->save();
        }

    }

    public function actionGetAutos($name){

        /*
        $searchModel = new AutoSearch();
        $searchModel->name = $name;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->city)
            ->getModels();
        */

        $data = SystemAuto::find()
            ->select(['name as value', 'name as  label', 'id as id'])
            ->asArray()
            ->andFilterWhere(['like', 'name', $name])
            ->distinct(true)
            ->all();

        if(Yii::$app->request->isAjax){
            $result = Json::encode($data);
            echo $result;
        } else {

        }
    }

    public function actionYandexPay(){

        //https://sp-money.yandex.ru/myservices/online.xml
        /*
        $_POST = array (
            'notification_type' => 'p2p-incoming',
            'amount' => '10.50',
            'withdraw_amount' => '10.50',
            'datetime' => '2015-11-03T14:50:54Z',
            'codepro' => 'false',
            'sender' => '41001000040',
            'sha1_hash' => 'd65c3ae9fd476450697552a7cbb842f660eba555',
            'test_notification' => 'true',
            'operation_label' => '',
            'operation_id' => 'test-notification',
            'currency' => '643',
            'label' => '',
        );

        $_POST['test_notification'] = true;
        */

        $secret_key = Yii::$app->params['yandexSecretKey'];

        $sha1 = '';
        if(!empty($_POST)){

            $sha1 = sha1( $_POST['notification_type'] . '&'. $_POST['operation_id']. '&' . $_POST['amount'] . '&643&' . $_POST['datetime'] . '&'. $_POST['sender'] . '&' . $_POST['codepro'] . '&' . $secret_key. '&' . $_POST['label'] );
            $rentactId = intval($_POST['label']);  // Записываем номер брони Rentact

            if(isset($_POST['test_notification'])){
                $_POST['currency'] = '643';
                $_POST['sha1_hash'] = $sha1;
                $rentactId = 119;
            }

            if($_POST['currency'] !=='643')
                exit();

            if ($sha1 !== $_POST['sha1_hash'] or !$rentactId){
                exit();
            } else {

                $Transaction = PaymentsTransaction::findOne(['rentact_id'=>$rentactId]);

                if(!empty($Transaction)){
                      exit();//нашли оплату выходим
                }
                    // тут код на случай, если проверка прошла успешно
                    $Transaction = new PaymentsTransaction();  // Модель транзакции оплаты
                    $Transaction->rentact_id = $rentactId;  // Записываем номер брони Rentact

                    $Transaction->operation_id = $_POST['operation_id'];  // Номер операции в системе Яндекса (varchar)
                    $Transaction->amount = $_POST['amount']; // Сумма за вычетом комиссии (float)
//                    $Transaction->amount_all = $_POST['withdraw_amount']; // Полный размер оплаты (float)
                    $Transaction->amount_all = $_POST['withdraw_amount']; // Полный размер оплаты (float)

                    $Transaction->sender = $_POST['sender']; // Полный размер оплаты (float)
                    $Transaction->datetime = $_POST['datetime']; // дата транзакции в формате 2015-10-28T17:49:52Z (varchar)
                    $Transaction->pay_out = '1'; // Статус выплат владельцу 1 - не выплачено. 2 - выплачено

                    $errors = $Transaction->createPaymentTransaction();

                    if(!empty($errors)){
                        $errors = var_export($errors, true);
                        SystemLogs::WriteAccessLog($errors);
                    }
                }
                exit();
            }

    }

    public function actionGetProtectedImage($userdataid, $file, $w = '1200', $h = '1200')
    {
        $resImage = false;

        if(!Yii::$app->user->isGuest) {

            $userData = UsersData::findOne($userdataid);
            if($userData)
                $resImage = $userData->getThumbSecretPhoto($userdataid, $file, $w, $h);

            if($resImage)
                $resImage->show('jpg', ['quality' => 80]);
        }

        Yii::$app->end();
    }



   public function actionCheckis()  // Проверка оплаты баланса
   {
      $secret_key = "jcJJmKzO5tJnZRTRLEj/N3kM";
      $sha1 = sha1( $_POST['notification_type'] . '&'. $_POST['operation_id']. '&' . $_POST['amount'] . '&643&' . $_POST['datetime'] . '&'. $_POST['sender'] . '&' . $_POST['codepro'] . '&' . $secret_key. '&' . $_POST['label'] );
      if ($sha1 != $_POST['sha1_hash'] ) {
        exit();
      }
       if($_POST['label'] != NULL){
        $ac_id = intval($_POST['label']);
        $ac = Account::findOne(['id' => $ac_id]);
        $ac->balance = $ac->balance + intval($_POST['withdraw_amount']);
        $ac->save();

            $subject = "Оплата баланса"; 
            $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
            $headers  = "From: auto.only3.ru <support@only3.ru>\r\nContent-type: text/html; charset=utf-8 \r\n"; 
            $mes = "Выполнено пополнение баланса автопроката - '".$ac->username."' на сумму <b>".$_POST['withdraw_amount']." рублей</b>.<br> Текущий баланс автопроката: ".$ac->balance." рублей";
            mail('ankaniti@mail.ru', $subject, $mes, $headers);
           mail('hejvkt@yandex.ru', $subject, $mes, $headers);

           exit();
       }  
    }

    public function actionCarRentals($city_url){

        $city = $this->city;

        $salonModels = Account::getSalonsListModels($this->city);

        return $this->render('car-rentals', [
            'salonModels' => $salonModels,
        ]);
    }

    public function actionSetBlackFake(){
        $fake = new Fake();
        $fake->setFakeValue();
        Yii::$app->end();
    }

    protected function findModel($id)
    {
        if (($model = SystemAuto::findOne($id)) !== null) {
            return $model;
        } else {
            $this->setNotFoundHttpException();
        }
    }

}
