<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\UsersData;

/**
 * UsersDataSearch represents the model behind the search form about `frontend\models\UsersData`.
 */
class UsersDataSearch extends UsersData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['full_name', 'birth_date', 'city_id'],'required',
                'when' => function () {
                    if(strlen($this->full_name) < '4')
                        $this->addError('full_name', 'Минимальное число символов для поиска ровняется 4.');

                    if (!$this->full_name && !$this->birth_date && !$this->city_id) {
                        $this->addError('full_name', 'Необходимо указать город либо телефон либо email.');
                        $this->addError('birth_date', 'Необходимо указать город либо телефон либо email.');
                        $this->addError('city_id', 'Необходимо указать город либо телефон либо email.');
                    }
                }, 'on' => 'inBlack'],

            [['full_name', 'birth_date', 'city_id'],'required',
                'when' => function () {
                    if(strlen($this->full_name) < '4')
                        $this->addError('full_name', 'Минимальное число символов для поиска ровняется 4.');

                    if (!$this->full_name && !$this->birth_date && !$this->city_id) {
                        $this->addError('full_name', 'Необходимо указать город либо телефон либо email.');
                        $this->addError('birth_date', 'Необходимо указать город либо телефон либо email.');
                        $this->addError('city_id', 'Необходимо указать город либо телефон либо email.');
                    }
                }, 'on' => 'inBlack-notmoderate'],

            [['id', 'account_id', 'salon_account_id', 'document_num'], 'integer'],

            [['name', 'full_name', 'phone', 'address_reg', 'address_fact', 'image_passport_photo', 'image_passport_reg', 'image_drive_licence', 'okpo', 'bank', 'director', 'passport_give', 'is_black', 'black_text', 'opf'], 'filter', 'filter' => 'trim'],

            [['is_black','name', 'full_name', 'phone', 'is_organization', 'passport_number', 'passport_serion', 'birth_date', 'address_reg', 'address_fact', 'license_number', 'license_date', 'image_passport_photo', 'image_passport_reg', 'image_drive_licence', 'inn', 'kpp', 'ogrn', 'okpo', 'r_sch', 'bank', 'k_sch', 'bik', 'director', 'is_confirmed', 'images', 'created_at', 'updated_at', 'passport_give'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        $scenarios = parent::scenarios();
        $scenarios['forSalon'] = ['is_black','name','full_name', 'phone', 'is_organization', 'passport_number', 'passport_serion', 'birth_date', 'address_reg', 'address_fact', 'license_number', 'license_date', 'image_passport_photo', 'image_passport_reg', 'image_drive_licence', 'inn', 'kpp', 'ogrn', 'okpo', 'r_sch', 'bank', 'k_sch', 'bik', 'director', 'is_confirmed', 'images', 'created_at', 'updated_at', 'passport_give'];

        $scenarios['inBlack'] = ['is_black','name', 'full_name', 'birth_date', 'address_reg', 'address_fact', 'city_id'];
        $scenarios['inBlack-moderate'] = $scenarios['inBlack'];
        $scenarios['inBlack-notmoderate'] = $scenarios['inBlack'];

        return $scenarios;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $mode = false)
    {

        if($this->getScenario() === 'forSalon'){
            $query = UsersData::find()->forSalon();
        } elseif ($this->getScenario() === 'inBlack' or $this->getScenario() === 'inBlack-moderate') {
            $query = UsersData::find()->inBlack();
        } elseif($this->getScenario() === 'inBlack-notmoderate'){
            $query = UsersData::find()->inBlackNotModerated();
        } else {
            $query = UsersData::find();
        }

        if($this->getScenario() === 'inBlack-notmoderate'){

        }

        $dataProviderParam = [
            'query' => $query,
        ];

        if($this->getScenario() === 'inBlack'){
            $dataProviderParam['sort'] = false;
        }


        $dataProvider = new ActiveDataProvider($dataProviderParam);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            if($this->getScenario() === 'inBlack' or $this->getScenario() === 'inBlack-notmoderate'){
                $query->where('0=1');
            }

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'account_id' => $this->account_id,
            'salon_account_id' => $this->salon_account_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'document_num' => $this->document_num,
            'is_black' => $this->is_black,
            'city_id'  => $this->city_id,
        ]);


        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'is_organization', $this->is_organization])
            ->andFilterWhere(['like', 'passport_number', $this->passport_number])
            ->andFilterWhere(['like', 'passport_serion', $this->passport_serion])
            ->andFilterWhere(['like', 'birth_date', $this->birth_date])
            ->andFilterWhere(['like', 'address_reg', $this->address_reg])
            ->andFilterWhere(['like', 'address_fact', $this->address_fact])
            ->andFilterWhere(['like', 'license_number', $this->license_number])
            ->andFilterWhere(['like', 'license_date', $this->license_date])
            ->andFilterWhere(['like', 'image_passport_photo', $this->image_passport_photo])
            ->andFilterWhere(['like', 'image_passport_reg', $this->image_passport_reg])
            ->andFilterWhere(['like', 'image_drive_licence', $this->image_drive_licence])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'kpp', $this->kpp])
            ->andFilterWhere(['like', 'ogrn', $this->ogrn])
            ->andFilterWhere(['like', 'okpo', $this->okpo])
            ->andFilterWhere(['like', 'r_sch', $this->r_sch])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'k_sch', $this->k_sch])
            ->andFilterWhere(['like', 'bik', $this->bik])
            ->andFilterWhere(['like', 'director', $this->director])
            ->andFilterWhere(['like', 'is_confirmed', $this->is_confirmed])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'passport_give', $this->passport_give]);

        return $dataProvider;
    }
}
