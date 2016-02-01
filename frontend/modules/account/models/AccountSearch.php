<?php

namespace frontend\modules\account\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\account\models\Account;

/**
 * AccountSearch represents the model behind the search form about `frontend\modules\account\models\Account`.
 */
class AccountSearch extends Account
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'status', 'balance'], 'integer'],
            [['phone', 'username', 'password', 'password_reset_token', 'authKey', 'email', 'lastLoginAt', 'createdAt', 'updatedAt', 'url', 'xy', 'address', 'thumb', 'city_name', 'place_delivery', 'other', 'slug_url', 'contract', 'is_moderated', 'is_salon'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        $scenarios = parent::scenarios();
        $scenarios['moderate'] = ['phone', 'username','email','is_moderated'];

        return $scenarios;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Account::find()->salons();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'city_id' => $this->city_id,
            'lastLoginAt' => $this->lastLoginAt,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'status' => $this->status,
            'balance' => $this->balance,
        ]);

        if($this->getScenario() !== 'moderate'){
            $query->andFilterWhere(['like', 'slug_url', $this->slug_url]);
        }

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'xy', $this->xy])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'thumb', $this->thumb])
            ->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'place_delivery', $this->place_delivery])
            ->andFilterWhere(['like', 'other', $this->other])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'is_moderated', $this->is_moderated])
            ->andFilterWhere(['like', 'is_salon', $this->is_salon]);

        // Create a command. You can get the actual SQL using $command->sql
        $command = $query->createCommand();

        return $dataProvider;
    }
}
