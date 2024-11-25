<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;

/**
 * SalesSearch represents the model behind the search form of `app\models\Sales`.
 */
class SalesSearch extends Sales
{
    public $globalSearch;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity'], 'integer'],
            [['sell_price', 'total_amount'], 'number'],
            [['sale_date', 'globalSearch'], 'safe'],
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
        $query = Sales::find()->orderBy(['sale_date' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50, // Set the number of items per page
            ],
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
        //     'sell_price' => $this->sell_price,
        //     'total_amount' => $this->total_amount,
        //     'sale_date' => $this->sale_date,
        // ]);

        $query->orFilterWhere(['like', 'products.product_name', $this->globalSearch]);


        return $dataProvider;
    }
}
