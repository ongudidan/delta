<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_methods".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class PaymentMethods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_methods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    // public function getPurchases()
    // {
    //     return $this->hasMany(Purchases::class, ['id' => 'id']);
    // }

    // public function getSales()
    // {
    //     return $this->hasMany(Sales::class, ['id' => 'id']);
    // }
}
