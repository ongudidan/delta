<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inventory;

/**
 * InventorySearch represents the model behind the search form of `app\models\Inventory`.
 */
class InventorySearch extends Inventory
{
    public $globalSearch;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity'], 'integer'],
            [['updated_at', 'globalSearch'], 'safe'],
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
        $query = Inventory::find()->orderBy(['updated_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100, // Set the number of items per page
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
        //     'quantity' => $this->globalSearch,
        //     'updated_at' => $this->updated_at,
        // ]);

        $query->orFilterWhere(['like', 'products.product_name', $this->globalSearch]);


        return $dataProvider;
    }
}
