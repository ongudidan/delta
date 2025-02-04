<?php

namespace app\modules\dashboard\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\Products;
use app\models\Sales;
use app\models\Purchases;

class ProductsSearch extends Products
{
    public $available_quantity; // Virtual attribute for stock quantity

    public function rules()
    {
        return [
            [['product_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['product_name', 'product_number', 'description'], 'safe'],
            [['selling_price', 'available_quantity'], 'number'], // Allow searching by available_quantity
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        // Subquery to calculate total purchases
        $purchaseQuery = Purchases::find()
            ->select(['product_id', new Expression('COALESCE(SUM(quantity), 0) AS total_purchases')])
            ->groupBy('product_id');

        // Subquery to calculate total sales
        $salesQuery = Sales::find()
            ->select(['product_id', new Expression('COALESCE(SUM(quantity), 0) AS total_sales')])
            ->groupBy('product_id');

        // Main query with LEFT JOINs
        $query = Products::find()
            ->select([
                'products.*',
                new Expression('COALESCE(total_purchases, 0) - COALESCE(total_sales, 0) AS available_quantity')
            ])
            ->leftJoin(['p' => $purchaseQuery], 'p.product_id = products.product_id')
            ->leftJoin(['s' => $salesQuery], 's.product_id = products.product_id')
            ->with(['category']) // Keep eager loading for category
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'products.product_id' => $this->product_id,
            'products.category_id' => $this->category_id,
            'products.selling_price' => $this->selling_price,
            'products.created_at' => $this->created_at,
            'products.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'products.product_name', $this->product_name])
            ->andFilterWhere(['like', 'products.product_number', $this->product_number])
            ->andFilterWhere(['like', 'products.description', $this->description]);

        // ✅ Only apply `HAVING` condition if `available_quantity` is explicitly set
        if ($this->available_quantity !== null && $this->available_quantity !== '') {
            $query->having(['=', 'available_quantity', $this->available_quantity]);
        }

        return $dataProvider;
    }
}


