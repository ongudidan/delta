<?php

use app\models\ExpenseCategories;
use app\models\PaymentMethods;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="expenses-form">
    <?php
    $formAction = Yii::$app->controller->action->id === 'update'
        ? ['expenses/update', 'expense_id' => $model->expense_id]
        : ['expenses/create']; // Use 'create' action if it's not update
    ?>

    <?php $form = ActiveForm::begin([
        'id' => 'main-form',
        'enableAjaxValidation' => false, // Disable if you're not using AJAX
        'action' => $formAction, // Set action based on create or update
        'method' => 'post',
    ]); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [
                                    'options' => [
                                        'placeholder' => 'Enter date...',
                                        'class' => 'form-control',  // Add the form-control class to the input
                                        'value' => isset($model->created_at) && $model->created_at > 0
                                            ? date('d-M-Y H:i', $model->created_at)
                                            : date('d-M-Y H:i'),
                                    ],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd-M-yyyy hh:ii', // Set the date format to 'dd-M-yyyy' and include time
                                        'todayHighlight' => true, // Highlight today's date
                                        'todayBtn' => true, // Add a button to quickly select today's date and time
                                        'minuteStep' => 1, // Optional: set minute interval for time picker
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'expense_category_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(ExpenseCategories::find()->all(), 'expense_category_id', 'category_name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Expense category ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'dropdownParent' => '#custom-modal',
                                    ],
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'payment_method_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select payment method ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group d-flex justify-content-center">
                                <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<!-- JavaScript to handle form submission and disable the button -->
<script>
    document.getElementById('submit-btn').addEventListener('click', function() {
        var submitButton = this;
        submitButton.disabled = true; // Disable the submit button
        document.getElementById('main-form').submit(); // Submit the form
    });
</script>