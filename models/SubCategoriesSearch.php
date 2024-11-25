<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubCategories;

/**
 * SubCategoriesSearch represents the model behind the search form of `app\models\SubCategories`.
 */
class SubCategoriesSearch extends SubCategories
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_category_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['sub_category_name', 'description'], 'safe'],
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
        $query = SubCategories::find();

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
            'sub_category_id' => $this->sub_category_id,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'sub_category_name', $this->sub_category_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
