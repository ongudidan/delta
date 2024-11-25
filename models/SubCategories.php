<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_categories".
 *
 * @property int $sub_category_id
 * @property int $category_id
 * @property string $sub_category_name
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 * @property Products $products
 */
class SubCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'sub_category_name'], 'required'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['sub_category_name', 'description'], 'string', 'max' => 255],
            [['category_id'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sub_category_id' => 'Sub Category ID',
            'category_id' => 'Category ID',
            'sub_category_name' => 'Sub Category Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['sub_category_id' => 'sub_category_id']);
    }
}
