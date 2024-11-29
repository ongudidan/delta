<?php

use app\models\PaymentMethods;
use app\models\Products;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */
/** @var yii\widgets\ActiveForm $form */

// Set the action based on whether it's a create or update
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['purchases/update', 'id' => $model->id]
    : ['purchases/create']; // Use 'create' action if it's not update

// Get the current date in dd/mm/yyyy format
$currentDate = date('d/m/Y');

// Convert the Unix timestamp to dd/mm/yyyy format for update
$purchaseDate = Yii::$app->controller->action->id === 'update' && $model->purchase_date
    ? date('d/m/Y', $model->purchase_date) // Convert Unix timestamp to date format
    : $currentDate; // Default to current date if creating a new record

// Check if a product_id is passed as a URL parameter
$productIdFromUrl = Yii::$app->request->get('product_id');

// If a product_id is passed, find the product and set it in the model
if ($productIdFromUrl) {
    $product = Products::findOne($productIdFromUrl);
    if ($product) {
        $model->product_id = $product->product_id; // Set the product ID in the model
    }
}
?>

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
                            <?= $form->field($model, 'quantity')->textInput(['maxlength' => true, 'value' => $model->quantity ?? 1 ]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'buying_price')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'total_cost')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'purchase_date')->widget(DatePicker::classname(), [
                                'options' => [
                                    'placeholder' => 'Enter date of purchase ...',
                                    'value' => $purchaseDate,
                                ],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',
                                    'orientation' => 'bottom',
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
                        <div class="student-submit d-flex justify-content-center">
                            <!-- Removed the submit button disabling feature -->
                            <?= Html::button('Save', ['class' => 'btn btn-success', 'id' => 'submit-btn']) ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    // JavaScript for handling quantity, total cost, and validation
    document.addEventListener("DOMContentLoaded", function() {
        var quantityField = document.getElementById('purchases-quantity');
        var buyingPriceField = document.getElementById('purchases-buying_price');
        var totalCostField = document.getElementById('purchases-total_cost');
        var form = document.getElementById('main-form');
        var submitButton = document.getElementById('submit-btn'); // Use the button with id 'submit-btn'

        // Calculate total cost and set it as readonly
        function calculateTotalCost() {
            var quantity = parseFloat(quantityField.value) || 1; // Default to 1 if quantity is invalid
            var price = parseFloat(buyingPriceField.value) || 0; // Default to 0 if price is invalid

            // Calculate total cost
            var totalCost = quantity * price;
            totalCostField.value = totalCost.toFixed(2); // Format the result to 2 decimal places
        }

        // Set the initial value of total cost on page load
        calculateTotalCost();

        // Listen for changes in the quantity or buying price fields and update total cost
        quantityField.addEventListener('input', calculateTotalCost);
        buyingPriceField.addEventListener('input', calculateTotalCost);

        // Check for quantity less than 1 and show SweetAlert
        quantityField.addEventListener('input', function() {
            if (parseInt(quantityField.value) < 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Quantity',
                    text: 'Quantity must be at least 1!',
                }).then(() => {
                    // Reset quantity to 1
                    quantityField.value = 1;
                    calculateTotalCost(); // Recalculate the total cost with the reset value
                });
            }
        });

        // Disable the submit button once clicked
        submitButton.addEventListener('click', function(e) {
            // Prevent default action if form is not valid
            e.preventDefault();

            // Disable the button immediately after it is clicked
            submitButton.disabled = true;

            // Check if the form is valid before submitting
            if (form.checkValidity()) {
                // Submit the form after 0.5 seconds to ensure button stays disabled
                setTimeout(function() {
                    form.submit(); // Submit the form if it's valid
                }, 500);
            } else {
                // Show SweetAlert for form validation errors
                Swal.fire({
                    icon: 'error',
                    title: 'Form Invalid',
                    text: 'Please ensure all fields are correctly filled!',
                }).then(() => {
                    // Re-enable the submit button if form is invalid
                    submitButton.disabled = false;
                });
            }
        });
    });
</script>