<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "expense_categories".
 *
 * @property int $expense_category_id
 * @property string $category_name
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Expenses[] $expenses
 */
class ExpenseCategories extends \yii\db\ActiveRecord
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
        return 'expense_categories';
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
            'expense_category_id' => 'Expense Category ID',
            'category_name' => 'Category Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['expense_category_id' => 'expense_category_id']);
    }

    public static function createExpenseCategory($request)
    {

        $model = new ExpenseCategories();
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                return true;
            }
        } 
    }
}
