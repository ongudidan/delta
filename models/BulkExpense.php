<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bulk_expense".
 *
 * @property int $id
 * @property string|null $reference_no
 * @property int|null $expense_date
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @property Expenses[] $expenses
 */
class BulkExpense extends \yii\db\ActiveRecord
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
        $week = (int)date('W', strtotime($this->expense_date));
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
        return 'bulk_expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expense_date', 'created_at', 'updated_at'], 'integer'],
            [['reference_no', 'date', 'created_by', 'updated_by'], 'string', 'max' => 255],
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
            'expense_date' => 'Expense Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

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
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['bulk_expense_id' => 'id']);
    }
}
