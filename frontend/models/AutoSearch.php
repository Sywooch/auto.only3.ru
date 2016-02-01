<?php

namespace frontend\models;

use frontend\modules\account\models\Account;
use frontend\modules\profile\models\Rentact;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\profile\models\SystemAutoSearch;
use frontend\modules\profile\models\SystemAuto;

/**
 * SystemAutoSearch represents the model behind the search form about `frontend\modules\profile\models\SystemAuto`.
 */
class AutoSearch extends System
{

    public $min_price;
    public $max_price;

    public $city_name;
    public $city_url;

    public $trans1;
    public $trans2;

    public $day;

    public function rules()
    {
        return [
            [['id', 'account_id', 'cost2', 'w_driver','trans','conditioner','trans1','trans2'], 'integer'],
            [['name', 'category', 'min_cost','min_price','max_price','trans', 'conditioner', 'pledge', 'info', 'contract', 'photo', 'photos', 'wheel', 'gear', 'number', 'year', 'power', 'fuel', 'account_id', 'w_driver', 'trans1', 'trans2', 'city_url', 'day'], 'safe'],
            [['cost1', 'cost8'], 'number'],
        ];
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['salon-cars'] = ['account_id', 'sort_order', 'cost'];

        return $scenarios;
    }

    public function search($params, $city_name = false)
    {

       $query = System::find();

        if($this->getScenario() === 'salon-cars'){
            $sort = [
                'defaultOrder' => ['sort_order' => SORT_ASC]
            ];
        } else {
            $sort = [
                'defaultOrder' => ['min_cost' => SORT_ASC]
            ];
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => false,
        ]);

        $this->load($params);

        if((!$this->trans1 and !$this->trans2) or ($this->trans1 and $this->trans2) ){

            $this->trans = null;
            $this->trans1 = 1;
            $this->trans2 = 1;

        } elseif($this->trans1){
            $this->trans = 1;
        } else {
            $this->trans = 2;
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category' => $this->category,
            //'account_id' => $this->account_id,
            //'min_cost' => $this->min_cost,
        ]);


        if(!empty($this->day)){
            $query->select(['system_auto.*', 'rentact.day']);
            $query->leftJoin('rentact', 'rentact.system_id = system_auto.id AND rentact.day = "'.date('d.m.Y', strtotime($this->day)).'" ' );
            $query->having(' `rentact`.`day` is NULL ');
            $query->groupBy('`system_auto`.`id`');
        }

//        $query->innerJoinWith(['account'])->where("account.balance > -1 and account.is_moderated = '2' and account.is_salon = '1' ");
        $query->innerJoinWith(['account'])->where("account.is_moderated = '" . Account::FLAG_MODERATE_COMPLET . "' AND account.is_salon = '1' ");


        if($this->getScenario() !== 'salon-cars') {
//            if (Yii::$app->user->isGuest) {
                $query->innerJoinWith(['account'])->where('account.balance > -1');

                $query->andWhere(
                    [
                        'system_auto.is_moderated' => '2',
                        'account.is_moderated' => '2',
                        'is_display' => '1'
                    ]);

//            } else {
//                $query->innerJoinWith(['account'])->where(['or', ['>', 'account.balance', -1], ['system_auto.account_id' => Yii::$app->user->id]]);
//                $query->andWhere(['or', ['system_auto.is_moderated' => '2'], ['system_auto.account_id' => Yii::$app->user->id]]);
//                $query->andWhere(['or', ['=', 'is_display', '1'], ['system_auto.account_id' => Yii::$app->user->id]]);
//            }
        }

        if($city_name) {
            $query->andWhere(['=','account.city_name', $city_name]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'category', $this->category])
            ->andFilterWhere(['between', 'min_cost', $this->min_price, $this->max_price])
            ->andFilterWhere(['=', 'account_id', $this->account_id])

            ->andFilterWhere(['=', 'trans', $this->trans])
            ->andFilterWhere(['=', 'conditioner', $this->conditioner])
            ->andFilterWhere(['=', 'w_driver', $this->w_driver])

            //->andFilterWhere(['=', 'account', $city_name])

            /*
            ->andFilterWhere(['like', 'pledge', $this->pledge])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'photos', $this->photos])
            ->andFilterWhere(['like', 'wheel', $this->wheel])
            ->andFilterWhere(['like', 'gear', $this->gear])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'power', $this->power])
            */
            ->andFilterWhere(['like', 'fuel', $this->fuel]);

        // Create a command. You can get the actual SQL using $command->sql
        //$command = $query->createCommand();

        return $dataProvider;
    }
}
