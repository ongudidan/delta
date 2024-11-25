<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "categories".
 *
 * @property int $category_id
 * @property string $category_name
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Products $products
 * @property SubCategories $subCategories
 */
class Categories extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['category_name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[SubCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategories()
    {
        return $this->hasOne(SubCategories::class, ['category_id' => 'category_id']);
    }

    public static function createCategory($request)
    {
        $model = new Categories();

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                return true;
            }
        } 
    }
}
