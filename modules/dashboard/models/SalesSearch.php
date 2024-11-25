<?php

namespace app\modules\dashboard\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;

/**
 * SalesSearch represents the model behind the search form of `app\models\Sales`.
 */
class SalesSearch extends Sales
{
    public $productName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity', 'created_by', 'updated_by', 'payment_method_id'], 'integer'],
            [['sell_price', 'total_amount', 'profit'], 'number'],
            [['productName', 'sale_date'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50, // Set the number of items per page
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('product');

        // Convert sale_date from 'dd/mm/yyyy' to Unix timestamp range
        if (!empty($this->sale_date)) {
            $dateParts = explode('/', $this->sale_date);
            if (count($dateParts) === 3) {
                $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

                // Start of the day
                $startOfDay = strtotime($formattedDate . ' 00:00:00');

                // End of the day
                $endOfDay = strtotime($formattedDate . ' 23:59:59');

                // Add the range condition for sale_date
                $query->andFilterWhere(['between', 'sale_date', $startOfDay, $endOfDay]);
            }
        }

        // Additional grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'sell_price' => $this->sell_price,
            'total_amount' => $this->total_amount,
            'profit' => $this->profit,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'payment_method_id' => $this->payment_method_id,
        ])
        ->andFilterWhere(['like', 'products.product_name', $this->productName]);

        return $dataProvider;
    }



}
