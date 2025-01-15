<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "expenses".
 *
 * @property int $expense_id
 * @property int $expense_category_id
 * @property float $amount
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ExpenseCategories $expenseCategory
 */
class Expenses extends \yii\db\ActiveRecord
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Report::invalidateCache();
        $this->invalidateWeeklyCache();

    }

    public function afterDelete()
    {
        parent::afterDelete();
        Report::invalidateCache();
        $this->invalidateWeeklyCache();

    }

    // Invalidate the weekly cache after saving or deleting an expense
    private function invalidateWeeklyCache()
    {
        $year = date('Y');
        $week = (int)date('W', strtotime($this->updated_at));
        $cacheKey = "weekly_report_{$year}_week_{$week}";
        Yii::$app->cache->delete($cacheKey);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'updated_at',

            ],
            [

                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expense_category_id', 'payment_method_id', 'amount'], 'required'],
            [['expense_category_id',  'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['created_by','created_at', 'updated_by'], 'safe'],
            [['expense_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategories::class, 'targetAttribute' => ['expense_category_id' => 'expense_category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'expense_id' => 'Expense ID',
            'expense_category_id' => 'Expense Category Name',
            'amount' => 'Amount',
            'payment_method_id' => 'Payment Method',
            'paymentMethod.name' => 'Payment Method',

            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ExpenseCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseCategory()
    {
        return $this->hasOne(ExpenseCategories::class, ['expense_category_id' => 'expense_category_id']);
    }

    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethods::class, ['id' => 'payment_method_id']);
    }

    public static function createExpense($request)
    {
        $model = new Expenses();

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                return true;
            }
        } else {
            $model->loadDefaultValues();
        }
    }

    /////////////////////////////////////////////////////////////////
    public static function getExpensesForSpecificDay()
    {
        $timestamp = strtotime('today');
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['DAY(FROM_UNIXTIME(updated_at))' => $day])
            ->andWhere(['MONTH(FROM_UNIXTIME(updated_at))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(updated_at))' => $year])
            ->sum('amount') ?: 0;
    }

    public static function getExpensesForSpecificWeek()
    {
        $timestamp = strtotime('today');
        $week = date('W', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['WEEK(FROM_UNIXTIME(updated_at), 3)' => $week])
            ->andWhere(['YEAR(FROM_UNIXTIME(updated_at))' => $year])
            ->sum('amount') ?: 0;
    }

    public static function getExpensesForSpecificMonth()
    {
        $timestamp = strtotime('today');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['MONTH(FROM_UNIXTIME(updated_at))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(updated_at))' => $year])
            ->sum('amount') ?: 0;
    }

    public static function getExpensesForSpecificYear()
    {
        $timestamp = strtotime('today');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        return self::find()
            ->where(['YEAR(FROM_UNIXTIME(updated_at))' => $year])
            ->sum('amount') ?: 0;
    }
    ////////////////////////////////////////////
}
