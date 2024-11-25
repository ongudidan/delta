<?php

namespace app\modules\dashboard\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BulkPurchase;

/**
 * BulkPurchaseSearch represents the model behind the search form of `app\models\BulkPurchase`.
 */
class BulkPurchaseSearch extends BulkPurchase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'purchase_date', 'created_at', 'updated_at'], 'integer'],
            [['reference_no', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = BulkPurchase::find()->orderBy(['purchase_date' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'purchase_date' => $this->purchase_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
