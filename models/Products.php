<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "products".
 *
 * @property int $product_id
 * @property int $category_id
 * @property string|null $product_name
 * @property string|null $product_number
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 * @property Inventory[] $inventories
 */
class Products extends \yii\db\ActiveRecord
{
    // Invalidate the cache after saving or deleting a product
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Report::invalidateCache();
        $this->invalidateLowStockCache();

    }

    public function afterDelete()
    {
        parent::afterDelete();
        Report::invalidateCache();
        $this->invalidateLowStockCache();

    }


    // Invalidate the low stock cache after saving or deleting a product
    private function invalidateLowStockCache()
    {
        Yii::$app->cache->delete("low_stock_products");
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'selling_price','product_name'], 'required'],
            [['selling_price'], 'number'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['product_name', 'product_number', 'description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category Name',
            'product_name' => 'Product Name',
            'product_number' => 'Product Number',
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
     * Gets query for [[Inventories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['product_id' => 'product_id']);
    }

    public static function createProduct($request)
    {
        $model = new Products();

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                // Create the inventory record
                $inventory = new Inventory();
                $inventory->product_id = $model->product_id;
                $inventory->quantity = 0; 
                $inventory->updated_at = $model->created_at; // Default quantity
                $inventory->save(); // Save the inventory record

                return true;
            }
        } else {
            $model->loadDefaultValues();
        }

    }

    public function getPurchases()
    {
        return $this->hasMany(Purchases::class, ['product_id' => 'product_id']);
    }

    public function getSales()
    {
        return $this->hasMany(Sales::class, ['product_id' => 'product_id']);
    }

}
