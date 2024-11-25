<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesSearch represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesSearch extends Purchases
{
    public $globalSearch;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity'], 'integer'],
            [['buying_price', 'total_cost'], 'number'],
            [['purchase_date', 'globalSearch'], 'safe'],
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
        $query = Purchases::find()->orderBy(['purchase_date' => SORT_DESC]);

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
        $query->joinWith('product');


        // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'product_id' => $this->product_id,
        //     'quantity' => $this->quantity,
        //     'buying_price' => $this->buying_price,
        //     'total_cost' => $this->total_cost,
        //     'purchase_date' => $this->purchase_date,
        // ]);

        $query->orFilterWhere(['like', 'products.product_name', $this->globalSearch]);

        return $dataProvider;
    }
}
