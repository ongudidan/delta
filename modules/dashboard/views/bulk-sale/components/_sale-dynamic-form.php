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
        'model' => $modelsSales[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'product_id',
            'quantity',
            'sell_price',
            'total_amount',
            'sale_date',
            'created_by',
            'updated_by',
            'payment_method_id',
        ],
    ]); ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Sell Price</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="container-items">
                <?php foreach ($modelsSales as $i => $modelSales): ?>
                    <tr class="item">
                        <?php if (!$modelSales->isNewRecord): ?>
                            <?= Html::activeHiddenInput($modelSales, "[{$i}]id"); ?>
                        <?php endif; ?>

                        <td>
                            <?= $form->field($modelSales, "[{$i}]product_id", ['template' => "{input}\n{error}"])
                                ->widget(Select2::classname(), [
                                    'options' => ['placeholder' => 'Select product ...', 'class' => 'form-select product-field'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 2,
                                        'ajax' => [
                                            'url' => Url::to(['/dashboard/bulk-sale/search']),
                                            'dataType' => 'json',
                                            'delay' => 250,
                                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                            'processResults' => new JsExpression('function(data) { return {results: data}; }'),
                                        ],
                                    ],
                                    'initValueText' => $modelSales->product_id
                                        ? Products::findOne($modelSales->product_id)->product_name
                                        : '',
                                ]); ?>
                        </td>
                        <td>
                            <?= $form->field($modelSales, "[{$i}]quantity", ['template' => "{input}\n{error}"])
                                ->textInput(['min' => 1, 'class' => 'form-control quantity-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelSales, "[{$i}]sell_price", ['template' => "{input}\n{error}"])
                                ->textInput(['step' => '0.01', 'class' => 'form-control sell-price-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelSales, "[{$i}]total_amount", ['template' => "{input}\n{error}"])
                                ->textInput(['readonly' => true, 'class' => 'form-control total-amount-field']); ?>
                        </td>
                        <td>
                            <?= $form->field($modelSales, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
                                ->dropDownList(
                                    $paymentMethodList, // Use the pre-fetched payment methods list
                                    [
                                        'prompt' => 'Select payment method...',
                                        'class' => 'form-select',
                                        'options' => [
                                            // Set 'Cash' as the default selected option
                                            $cashPaymentMethodId => ['Selected' => !$modelSales->isNewRecord ? false : true]
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
// Function to update total amount based on quantity and sell price
function updateTotalAmount(row) {
    var quantity = parseFloat(row.find('.quantity-field').val()) || 1;  // Default to 1 if quantity is empty
    var sellPrice = parseFloat(row.find('.sell-price-field').val()) || 0;
    var totalAmount = quantity * sellPrice;
    row.find('.total-amount-field').val(totalAmount.toFixed(2)); 
}

// Event listener for product selection change
$(document).on('change', '.dynamicform_wrapper .product-field', function () {
    var row = $(this).closest('.item');
    var productId = $(this).val();

    // Set default quantity to 1 when a product is selected
    if (!row.find('.quantity-field').val()) {
        row.find('.quantity-field').val(1);
    }

    if (productId) {
        $.ajax({
            url: '/dashboard/sales/get-product-details',
            type: 'GET',
            data: { id: productId },
            success: function (response) {
                var data = JSON.parse(response);
                if (data && data.price) {
                    row.find('.sell-price-field').val(data.price); 
                    updateTotalAmount(row);
                }
            },
            error: function () {
                alert('Unable to fetch product details.');
            }
        });
    } else {
        row.find('.sell-price-field').val('');
        row.find('.total-amount-field').val('');
    }
});

// Event listener for quantity input change
$(document).on('input', '.dynamicform_wrapper .quantity-field', function () {
    var row = $(this).closest('.item');
    updateTotalAmount(row);
});

// Event listener for sell price input change
$(document).on('input', '.dynamicform_wrapper .sell-price-field', function () {
    var row = $(this).closest('.item');
    updateTotalAmount(row);
});

JS);
?>