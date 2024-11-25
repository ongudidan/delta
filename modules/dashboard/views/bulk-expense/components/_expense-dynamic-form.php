<?php

use app\models\ExpenseCategories;
use app\models\PaymentMethods;
use app\models\Products;
use kartik\select2\Select2;
use Yii2\Extensions\DynamicForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\JobCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><i class="glyphicon glyphicon-envelope"></i> Items</h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items1',
                'widgetItem' => '.item1',
                'limit' => 50,
                'min' => 1,
                'insertButton' => '.add-item1',
                'deleteButton' => '.remove-item1',
                'model' => $modelsExpenses[0],
                'formId' => 'dynamic-form1',
                'formFields' => [
                    'expense_category_id',
                    'amount',
                    'bulk_expense_id',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'payment_method_id',
                  
                ],
            ]); ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Expense category</th>
                            <th>Amount</th>
                            <th> Payment Method</th>
                         
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="container-items1">
                        <?php foreach ($modelsExpenses as $i => $modelExpenses): ?>
                            <tr class="item1">
                                <?php
                                if (!$modelExpenses->isNewRecord) {
                                    echo Html::activeHiddenInput($modelExpenses, "[{$i}]expense_id");
                                }
                                ?>
                                <td>
                                    <?= $form->field($modelExpenses, "[{$i}]expense_category_id", [
                                        'template' => "{input}\n{error}"
                                    ])->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(ExpenseCategories::find()->all(), 'expense_category_id', 'category_name'),
                                        'language' => 'en',
                                        'options' => ['placeholder' => 'Select expense category ...', 'class' => 'form-control product-field'],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]); ?>
                                </td>
                   
                                <td>
                                    <?= $form->field($modelExpenses, "[{$i}]amount", [
                                        'template' => "{input}\n{error}"
                                    ])->textInput([
                                        'maxlength' => true,
                                        // 'readonly' => true,
                                        'class' => 'form-control sell-price-field'
                                    ]) ?>
                                </td>
               
                                <td>
                                    <?php
                                    // Check if it's an update or create scenario
                                    if ($modelExpenses->isNewRecord) {
                                        // On create, set the default value to the first payment method
                                        $defaultPaymentMethod = PaymentMethods::find()->one();
                                        $defaultValue = $defaultPaymentMethod ? $defaultPaymentMethod->id : null;
                                    } else {
                                        // On update, use the value from the database
                                        $defaultValue = $modelExpenses->payment_method_id;
                                    }
                                    ?>
                                    <?= $form->field($modelExpenses, "[{$i}]payment_method_id", [
                                        'template' => "{input}\n{error}"
                                    ])->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                                        'language' => 'en',
                                        'options' => [
                                            'placeholder' => 'Select payment method ...',
                                            // 'value' => $defaultValue, // Set default or current value
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                </td>
                                <td>
                                    <button type="button" class="remove-item1 btn btn-link text-danger p-0 pt-1">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                <div class="d-flex justify-content-end">
                    <button type="button" class="add-item1 btn btn-warning btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="col-12">
        <div class="student-submit d-flex justify-content-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
