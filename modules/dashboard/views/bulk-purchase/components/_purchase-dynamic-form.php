<?php

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

<div class="mt-4">
    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h5 class="mb-0">
                <i class="fas fa-list-alt"></i> Items
            </h5>
        </div>
        <div class="card-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.item',
                'limit' => 50000,
                'min' => 1,
                'insertButton' => '.add-item',
                'deleteButton' => '.remove-item',
                'model' => $modelsPurchases[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'product_id',
                    'quantity',
                    'buying_price',
                    'total_cost',
                    'payment_method_id',
                ],
            ]); ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Buying Price (Per Unit)</th>
                            <th>Total Cost</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="container-items">
                        <?php foreach ($modelsPurchases as $i => $modelPurchases): ?>
                            <tr class="item">
                                <?php if (!$modelPurchases->isNewRecord): ?>
                                    <?= Html::activeHiddenInput($modelPurchases, "[{$i}]id"); ?>
                                <?php endif; ?>

                                <td>
                                    <?= $form->field($modelPurchases, "[{$i}]product_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(Products::find()->all(), 'product_id', 'product_name'),
                                            'options' => ['placeholder' => 'Select product ...', 'class' => 'form-select'],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelPurchases, "[{$i}]quantity", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 1, 'class' => 'form-control quantity-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelPurchases, "[{$i}]buying_price", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control buying-price-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelPurchases, "[{$i}]total_cost", ['template' => "{input}\n{error}"])
                                        ->textInput(['readonly' => true, 'class' => 'form-control total-cost-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelPurchases, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                                            'options' => ['placeholder' => 'Select payment method ...', 'class' => 'form-select'],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]); ?>
                                </td>
                                <td>
                                    <button type="button" class="remove-item btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="add-item btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="button" id="submit-form" class="btn btn-primary">Save</button>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Calculate total cost on quantity or buying price change
$(document).on('input', '.dynamicform_wrapper .quantity-field, .dynamicform_wrapper .buying-price-field', function () {
    var row = $(this).closest('.item');
    var quantity = parseFloat(row.find('.quantity-field').val()) || 0;
    var buyingPrice = parseFloat(row.find('.buying-price-field').val()) || 0;
    var totalCost = quantity * buyingPrice;
    row.find('.total-cost-field').val(totalCost.toFixed(2));
});

// Submit form when the save button is clicked
$('#submit-form').on('click', function () {
    $('#dynamic-form').submit(); // Submit the form directly
});
JS);
?>