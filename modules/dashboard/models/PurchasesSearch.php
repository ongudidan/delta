<?php

namespace app\modules\dashboard\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesSearch represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesSearch extends Purchases
{
    public $productName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity', 'payment_method_id'], 'integer'],
            [['buying_price', 'total_cost'], 'number'],
            [['productName', 'purchase_date'], 'safe'],

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

        // Convert purchase_date from 'dd/mm/yyyy' to Unix timestamp range
        if (!empty($this->purchase_date)) {
            $dateParts = explode('/', $this->purchase_date);
            if (count($dateParts) === 3) {
                $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

                // Start of the day
                $startOfDay = strtotime($formattedDate . ' 00:00:00');

                // End of the day
                $endOfDay = strtotime($formattedDate . ' 23:59:59');

                // Add the range condition for purchase_date
                $query->andFilterWhere(['between', 'purchase_date', $startOfDay, $endOfDay]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            // 'products.product_name' => $this->productName,

            'quantity' => $this->quantity,
            'buying_price' => $this->buying_price,
            'total_cost' => $this->total_cost,
            // 'purchase_date' => $this->purchase_date,
            'payment_method_id' => $this->payment_method_id,
        ])
        ->andFilterWhere(['like', 'products.product_name', $this->productName]);

        return $dataProvider;
    }
}
