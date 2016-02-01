<?


namespace console\controllers;

use webroot\frontend\models\System;

use yii\console\Controller;

class CityBaseController extends \yii\console\Controller
{
    // Команда "yii example/create test" вызовет "actionCreate('test')"
    public function actionCreateSlug() {

        echo dirname(dirname(__DIR__));

        $systems = System::findAll();

        foreach($systems as $model){
            $model->save(false);
        }

        return 1;

    }

    public function Translit(){

    }


    // Команда "yii example/index city" вызовет "actionIndex('city', 'name')"
    // Команда "yii example/index city id" вызовет "actionIndex('city', 'id')"
    public function actionIndex($category, $order = 'name') {


    }

    // Команда "yii example/add test" вызовет "actionAdd(['test'])"
    // Команда "yii example/add test1,test2" вызовет "actionAdd(['test1', 'test2'])"
    public function actionAdd(array $name) {

    }
}