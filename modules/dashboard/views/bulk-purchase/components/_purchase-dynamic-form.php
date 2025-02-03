<?php

use app\models\PaymentMethods;
use app\models\Products;
use kartik\select2\Select2;
use Yii2\Extensions\DynamicForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\JobCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
// Use the already fetched payment methods
$paymentMethodList = $paymentMethodList ?? [];

// Find the ID for the 'Cash' payment method, if it exists
$cashPaymentMethodId = null;
foreach ($paymentMethodList as $id => $name) {
    if (strtolower($name) === 'cash') {
        $cashPaymentMethodId = $id;
        break;
    }
}
?>

<div class="row">
    <div class="card-header text-white">
        <h5 class="mb-0">
            <i class="fas fa-list-alt"></i> Items
        </h5>
    </div>
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
                                    'options' => ['placeholder' => 'Select product ...', 'class' => 'form-select product-field'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 2,
                                        'ajax' => [
                                            'url' => Url::to(['/dashboard/bulk-purchase/search']),
                                            'dataType' => 'json',
                                            'delay' => 250,
                                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                            'processResults' => new JsExpression('function(data) { return {results: data}; }'),
                                        ],
                                    ],
                                    'initValueText' => $modelPurchases->product_id
                                        ? Products::findOne($modelPurchases->product_id)->product_name
                                        : '',
                                ]); ?>
                        </td>
                        <td>
                            <?= $form->field($modelPurchases, "[{$i}]quantity", ['template' => "{input}\n{error}"])
                                ->textInput(['min' => 1, 'class' => 'form-control quantity-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelPurchases, "[{$i}]buying_price", ['template' => "{input}\n{error}"])
                                ->textInput(['step' => '0.01', 'class' => 'form-control buying-price-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelPurchases, "[{$i}]total_cost", ['template' => "{input}\n{error}"])
                                ->textInput(['readonly' => true, 'class' => 'form-control total-cost-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelPurchases, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
                                ->dropDownList(
                                    $paymentMethodList, // Use the pre-fetched payment methods list
                                    [
                                        'prompt' => 'Select payment method...',
                                        'class' => 'form-select',
                                        'options' => [
                                            // Set 'Cash' as the default selected option
                                            $cashPaymentMethodId => ['Selected' => !$modelPurchases->isNewRecord ? false : true]
                                        ]
                                    ]
                                ); ?>
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
            <i class="fas fa-plus"></i> Add
        </button>
    </div>

    <?php DynamicFormWidget::end(); ?>
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