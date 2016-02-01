<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

use \frontend\modules\profile\models\SystemAutoSearch;
use \frontend\modules\profile\models\SystemAuto;
use common\models\payments\PaymentsTransaction;
use common\models\payments\PaymentsTransactionSearch;


class PaymentsController extends Controller
{

    public function beforeAction($action)
    {
        if ($this->action->id == 'pay-out') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'update', 'delete', 'view','pay-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'pay-out' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {

        $searchModel = new PaymentsTransactionSearch();
        $searchModel->setScenario('moderate');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('show-transactions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionPayOut($id){
        $paymentT = PaymentsTransaction::findOne($id);
        if($paymentT){
            $paymentT->setAttribute('pay_out', '2');
            $paymentT->save();
        }
        $this->goBack();
    }
    /**
     * Displays a single SystemAuto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing SystemAuto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('moderate');

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Запись была изменена успешно');
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SystemAuto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = PaymentsTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}