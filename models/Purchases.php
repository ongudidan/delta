<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property float $buying_price
 * @property float $total_cost
 * @property string $purchase_date
 *
 * @property Products $product
 */
class Purchases extends \yii\db\ActiveRecord
{
    // Invalidate the cache after saving or deleting a product
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Report::invalidateCache();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Report::invalidateCache();
    }

    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'purchase_date',
                'updatedAtAttribute' => false,
            ],
            // [
            //     'class' => BlameableBehavior::class,
            //     'createdByAttribute' => 'created_by',
            //     'updatedByAttribute' => 'updated_by',
            // ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'buying_price', 'payment_method_id'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['buying_price', 'total_cost'], 'number'],
            [['purchase_date'], 'safe'],
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
            'product_id' => 'Product Name',
            'quantity' => 'Quantity',
            'buying_price' => 'Buying Price',
            'total_cost' => 'Total Cost',
            'purchase_date' => 'Purchase Date',
            'payment_method_id'=> 'Payment Method',
            'paymentMethod.name' => 'Payment Method',

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

    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethods::class, ['id' => 'payment_method_id']);
    }

    public function getTotalAmount()
    {
        return $this->quantity * $this->buying_price;
    }

    public static function createPurchase($request)
    {
        $model = new Purchases();

        if ($model->load($request->post())) {
            // Calculate and set total_amount
            $model->total_cost = $model->getTotalAmount();

            if ($model->save()) {
                // Update inventory
                $model->updateInventory($model->product_id, $model->quantity);

                return true;
            }
        }
    }

    /**
     * Updates the inventory for a given product.
     * @param int $product_id The ID of the product
     * @param int $new_quantity The quantity to add or subtract (negative value for subtraction)
     * @param int|null $old_quantity The old quantity before update (optional, for update operations)
     */
    protected function updateInventory($product_id, $new_quantity, $old_quantity = null)
    {
        $inventory = Inventory::findOne(['product_id' => $product_id]);

        if ($inventory === null) {
            // Create a new inventory record if none exists
            $inventory = new Inventory();
            $inventory->product_id = $product_id;
            $inventory->quantity = $new_quantity;
            $inventory->created_at = time();
            $inventory->updated_at = time();
            $inventory->save();
        } else {
            // Update existing inventory record
            if ($old_quantity !== null) {
                $inventory->quantity += $new_quantity - $old_quantity;
            } else {
                $inventory->quantity += $new_quantity;
            }

            // Ensure quantity does not go negative
            if ($inventory->quantity < 0) {
                $inventory->quantity = 0;
            }

            $inventory->updated_at = time();
            $inventory->save();
        }
    }

    ////////////////////////////////////////////////////////////////
    public static function getPurchasesForSpecificDay()
    {
        $timestamp = strtotime('today');
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['DAY(FROM_UNIXTIME(purchase_date))' => $day])
            ->andWhere(['MONTH(FROM_UNIXTIME(purchase_date))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(purchase_date))' => $year])
            ->sum('total_cost') ?: 0;
    }

    public static function getPurchasesForSpecificWeek()
    {
        $timestamp = strtotime('today');
        $week = date('W', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['WEEK(FROM_UNIXTIME(purchase_date), 3)' => $week])
            ->andWhere(['YEAR(FROM_UNIXTIME(purchase_date))' => $year])
            ->sum('total_cost') ?: 0;
    }

    public static function getPurchasesForSpecificMonth()
    {
        $timestamp = strtotime('today');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['MONTH(FROM_UNIXTIME(purchase_date))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(purchase_date))' => $year])
            ->sum('total_cost') ?: 0;
    }

    public static function getPurchasesForSpecificYear()
    {
        $timestamp = strtotime('today');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['YEAR(FROM_UNIXTIME(purchase_date))' => $year])
            ->sum('total_cost') ?: 0;
    }
    ////////////////////////////////////////////////////////
}
