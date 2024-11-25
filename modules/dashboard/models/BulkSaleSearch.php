<?php

namespace app\modules\dashboard\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BulkSale;

/**
 * BulkSaleSearch represents the model behind the search form of `app\models\BulkSale`.
 */
class BulkSaleSearch extends BulkSale
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'created_at', 'updated_at'], 'integer'],
            [['reference_no','sale_date', 'created_by', 'updated_by'], 'safe'],
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
        $query = BulkSale::find()->orderBy(['sale_date' => SORT_DESC]);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'sale_date' => $this->sale_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
