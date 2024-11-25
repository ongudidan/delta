<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $last_updated
 *
 * @property Products $product
 */
class Inventory extends \yii\db\ActiveRecord
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
        return 'inventory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['updated_at','created_at'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'updated_at' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['product_id' => 'product_id']);
    }

    ////////////////////////////////////////////////////////////////



    /**
     * Get total product counts within a date range.
     */
    public static function getProductCounts($startDate, $endDate)
    {
        return self::find()
            ->select(['product_id', 'SUM(quantity) AS total_quantity'])
            ->where(['between', 'created_at', strtotime($startDate), strtotime($endDate)])
            ->groupBy('product_id')
            ->asArray()
            ->all();
    }
}
