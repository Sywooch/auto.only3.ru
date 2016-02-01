<?php

namespace common\models\payments;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\payments\PaymentsTransaction;

/**
 * PaymentsTransactionSearch represents the model behind the search form about `common\models\payments\PaymentsTransaction`.
 */
class PaymentsTransactionSearch extends PaymentsTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rentact_id'], 'integer'],
            [['operation_id', 'datetime', 'time', 'sender', 'pay_out', 'created_at', 'updated_at', 'data_log'], 'safe'],
            [['amount', 'amount_all'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['moderate'] = ['pay_out'];
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

        if($this->scenario === 'moderate'){
            $query = PaymentsTransaction::find();
        } else {
            $query = PaymentsTransaction::find()->asSalon();
        }

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
            'rentact_id' => $this->rentact_id,
            'amount' => $this->amount,
            'amount_all' => $this->amount_all,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_id', $this->operation_id])
            ->andFilterWhere(['like', 'datetime', $this->datetime])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'sender', $this->sender])
            ->andFilterWhere(['like', 'pay_out', $this->pay_out])
            ->andFilterWhere(['like', 'data_log', $this->data_log]);

        return $dataProvider;
    }
}
