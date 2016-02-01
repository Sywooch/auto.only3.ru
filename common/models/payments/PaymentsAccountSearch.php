<?php

namespace common\models\payments;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\payments\PaymentsAccount;

/**
 * PaymentsAccountSearch represents the model behind the search form about `common\models\payments\PaymentsAccount`.
 */
class PaymentsAccountSearch extends PaymentsAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'pay_summ'], 'integer'],
            [['is_active', 'card_number', 'r_sch', 'yandex_money', 'out_type', 'pay_type', 'is_client_pay_com', 'is_moderated', 'moderated_text', 'created_at', 'updated_at', 'rs_bik', 'rs_fio', 'rs_ident'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = PaymentsAccount::find();

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
            'account_id' => $this->account_id,
            'pay_summ' => $this->pay_summ,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'card_number', $this->card_number])
            ->andFilterWhere(['like', 'r_sch', $this->r_sch])
            ->andFilterWhere(['like', 'yandex_money', $this->yandex_money])
            ->andFilterWhere(['like', 'out_type', $this->out_type])
            ->andFilterWhere(['like', 'pay_type', $this->pay_type])
            ->andFilterWhere(['like', 'is_client_pay_com', $this->is_client_pay_com])
            ->andFilterWhere(['like', 'is_moderated', $this->is_moderated])
            ->andFilterWhere(['like', 'moderated_text', $this->moderated_text])
            ->andFilterWhere(['like', 'rs_bik', $this->rs_bik])
            ->andFilterWhere(['like', 'rs_fio', $this->rs_fio])
            ->andFilterWhere(['like', 'rs_ident', $this->rs_ident]);

        return $dataProvider;
    }
}
