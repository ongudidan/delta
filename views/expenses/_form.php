<?php

use app\models\ExpenseCategories;
use app\models\PaymentMethods;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="expenses-form">

    <!-- <div class="card"> -->
    <div class="card-header">
        <h4><?= Html::encode($this->title) ?></h4>
    </div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'expense_category_id')->dropDownList(
                    ArrayHelper::map(ExpenseCategories::find()->all(), 'expense_category_id', 'category_name'),
                    [
                        'prompt' => 'Select Expense category',
                    ]
                ) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'payment_method_id')->dropDownList(
                    ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                    [
                        'prompt' => 'Select Payment Method',
                        'options' => ['2' => ['Selected' => true]]
                    ]
                ) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'amount')->textInput(
                    [
                        'maxlength' => true,
                        'placeholder' => 'Enter amount',
                    ]
                ) ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <!-- </div> -->

</div>