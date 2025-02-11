<?php

use app\models\PaymentMethods;
use app\models\Products;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Sales $model */
/** @var yii\widgets\ActiveForm $form */

$formAction = Yii::$app->controller->action->id === 'update'
    ? ['sales/update', 'id' => $model->id]
    : ['sales/create'];

// Get the current date in dd/mm/yyyy format
$currentDate = date('d/m/Y');

// Convert the Unix timestamp to dd/mm/yyyy format for update
$saleDate = Yii::$app->controller->action->id === 'update' && $model->sale_date
    ? date('d/m/Y', $model->sale_date) // Convert Unix timestamp to date format
    : $currentDate; // Default to current date if creating a new record
?>

<div class="sales-form">
    <?php $form = ActiveForm::begin([
        'id' => 'main-form',
        'enableAjaxValidation' => false,
        'action' => $formAction,
        'method' => 'post',
    ]); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(Products::find()->all(), 'product_id', 'product_name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select product ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'quantity')->textInput(['maxlength' => true, 'value' => $model->quantity ?? 1]) ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'sell_price')->textInput(['maxlength' => true, 'readonly' => false]) ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'sale_date')->widget(DatePicker::classname(), [
                                    'options' => [
                                        'placeholder' => 'Enter date of sale ...',
                                        'value' => $saleDate,
                                    ],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd/mm/yyyy',
                                        'orientation' => 'bottom'
                                    ]
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?php
                                // Check if it's an update or create scenario
                                if ($model->isNewRecord) {
                                    // On create, set the default value to the first payment method
                                    $defaultPaymentMethod = PaymentMethods::find()->one();
                                    $defaultValue = $defaultPaymentMethod ? $defaultPaymentMethod->id : null;
                                } else {
                                    // On update, use the value from the database
                                    $defaultValue = $model->payment_method_id;
                                }
                                ?>
                                <?= $form->field($model, 'payment_method_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                                    'language' => 'en',
                                    'options' => [
                                        'placeholder' => 'Select payment method ...',
                                        'value' => $defaultValue, // Set default or current value
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
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

<?php
$script = <<<JS
$(document).ready(function() {
    let quantityField = $('#sales-quantity');
    let productIdField = $('#sales-product_id');
    let form = $('#main-form'); // Reference to the form
    let submitButton = form.find('button[type="submit"]'); // Reference to the submit button

    // Set default quantity to 1 if it's empty
    if (!quantityField.val()) {
        quantityField.val(1);
    }

    // Fetch product price when the product selection changes
    productIdField.on('change', function() {
        let productId = $(this).val();
        if (productId) {
            $.ajax({
                url: '/dashboard/sales/get-product-details',
                type: 'GET',
                data: { id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response && response.price) {
                        $('#sales-sell_price').val(response.price);
                        calculateTotalAmount();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching product details:", error);
                }
            });
        }
    });

    // Listen for quantity changes and check stock availability immediately
    quantityField.on('input', function() {
        let quantity = parseInt($(this).val()) || 1;
        let productId = productIdField.val();

        if (productId && quantity > 0) {
            $.ajax({
                url: '/dashboard/sales/check-stock',
                type: 'GET',
                data: { id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response && quantity > response.available_stock) {
                        Swal.fire({
                            title: 'Insufficient Stock!',
                            text: 'Only ' + response.available_stock + ' units available.',
                            icon: 'warning',
                        }).then(() => {
                            // Reset quantity to 1 after showing the warning
                            quantityField.val(1);
                            calculateTotalAmount();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error checking stock:", error);
                }
            });
        }
    });

    // Listen for sell price changes and calculate total amount
    $('#sales-sell_price').on('input', function() {
        calculateTotalAmount();
    });

    // Prevent form submission if stock is insufficient and handle multiple submissions
    form.on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission immediately

        // Disable the submit button to prevent multiple submissions
        submitButton.prop('disabled', true);
        
        let quantity = parseInt(quantityField.val()) || 1;
        let productId = productIdField.val();
        let isStockChecked = false;

        if (productId && quantity > 0) {
            $.ajax({
                url: '/dashboard/sales/check-stock',
                type: 'GET',
                data: { id: productId },
                dataType: 'json',
                async: false, // Wait for this request to complete
                success: function(response) {
                    isStockChecked = true;
                    if (response && quantity > response.available_stock) {
                        Swal.fire({
                            title: 'Insufficient Stock!',
                            text: 'Only ' + response.available_stock + ' units available.',
                            icon: 'warning',
                        }).then(() => {
                            // Reset quantity to 1 after showing the warning
                            quantityField.val(1);
                            calculateTotalAmount();

                            // Re-enable the submit button after the warning
                            submitButton.prop('disabled', false);
                        });
                    } else {
                        // If stock is sufficient, proceed with form submission
                        form.unbind('submit').submit();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error checking stock:", error);
                    // Re-enable the submit button in case of error
                    submitButton.prop('disabled', false);
                }
            });
        }

        // If stock check is not done yet, prevent form submission and re-enable the button
        if (!isStockChecked) {
            submitButton.prop('disabled', false);
        }
    });

    // Disable the submit button after one click
    // $('#main-form button[type="submit"]').on('click', function() {
    //     $(this).prop('disabled', true);
    // });

       $('#main-form button[type="submit"]').on('click touchstart', function() {
        $(this).prop('disabled', true);
        $(this).closest('form').submit(); // Submit the form
    });

    

    function calculateTotalAmount() {
        let price = parseFloat($('#sales-sell_price').val()) || 0;
        let quantity = parseInt(quantityField.val()) || 1;
        $('#sales-total_amount').val((price * quantity).toFixed(2));
    }

    calculateTotalAmount();
});
JS;
$this->registerJs($script);
?>