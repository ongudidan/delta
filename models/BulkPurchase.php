<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bulk_purchase".
 *
 * @property int $id
 * @property string|null $reference_no
 * @property int|null $purchase_date
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @property Purchases[] $purchases
 */
class BulkPurchase extends \yii\db\ActiveRecord
{
    public $date;

    // Invalidate the cache after saving or deleting a sale
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Report::invalidateCache();
        $this->invalidateWeeklyCache();
    }

    // Invalidate the cache after saving or deleting a sale
    public function afterDelete()
    {
        parent::afterDelete();
        Report::invalidateCache();
        $this->invalidateWeeklyCache();
    }

    // Invalidate the weekly report cache after saving a sale
    private function invalidateWeeklyCache()
    {
        $year = date('Y');
        $week = (int)date('W', strtotime($this->purchase_date));
        $cacheKey = "weekly_report_{$year}_week_{$week}";
        Yii::$app->cache->delete($cacheKey);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',

            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bulk_purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_date', 'created_at', 'updated_at'], 'integer'],
            [['reference_no','date', 'created_by', 'updated_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reference_no' => 'Reference No',
            'purchase_date' => 'Purchase Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }


    // public static function generateReferenceNo()
    // {
    //     $year = date('Y');
    //     $prefix = '#';
    //     $yearPrefix = substr($year, -2);

    //     // Get the maximum card number from the database
    //     $lastRecord = self::find()
    //         ->select(['reference_no'])
    //         ->orderBy(['reference_no' => SORT_DESC])
    //         ->limit(1)
    //         ->one();

    //     // Extract the last number from the highest card number
    //     if ($lastRecord && preg_match('/(\d{5})' . $yearPrefix . '$/', $lastRecord->reference_no, $matches)) {
    //         $lastNumber = intval($matches[1]);
    //     } else {
    //         $lastNumber = 0;  // Default to 0 if no records found
    //     }

    //     // Increment the last number to create a new number
    //     $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

    //     return $prefix . $newNumber . $yearPrefix;
    // }

    public static function generateReferenceNo()
    {
        // Current year and month
        $datePrefix = date('Ym'); // e.g., 202501 for January 2025

        // Generate a unique identifier
        $uniqueId = uniqid(); // Generates a unique ID based on the current timestamp

        // Use a hash to shorten the unique identifier
        $hash = substr(md5($uniqueId), 0, 6); // Take the first 6 characters of the hash

        // Combine components into the reference number
        $referenceNo = strtoupper("REF-{$datePrefix}-{$hash}");

        return $referenceNo;
    }


    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchases::class, ['bulk_purchase_id' => 'id']);
    }
}
