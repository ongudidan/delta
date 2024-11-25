<?php

use app\models\PaymentMethods;
use app\models\Products;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\Sales $model */
/** @var yii\widgets\ActiveForm $form */

$productId = Yii::$app->request->get('id');
$product = Products::findOne(['product_id' => $productId]);

?>

<div class="sales-form">

    <!-- <div class="card"> -->
    <div class="card-header">
        <h4><?= Html::encode($this->title) ?></h4>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Products::find()->all(), 'product_id', 'product_name'),
                    'language' => 'en',
                    'options' => [
                        'placeholder' => 'Search a product ...',
                        'class' => 'form-control',
                        'id' => 'product-select', // Add your ID here

                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '100%' // Force the widget to take full width
                    ],
                ]);
                ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'sell_price')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'payment_method_id')->dropDownList(
                    ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                    [
                        'prompt' => 'Select Payment Method',

                    ]
                ) ?>
            </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'sale_date')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Select date', 'value' => date('d-M-Y')],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy',
                    ]
                ])->label('Date Picker') ?>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- </div> -->
</div>


<?php
$script = <<< JS
// here we write all your javascript
$('#product-select').change(function() {

    var productId = $(this).val();
    $.get('/sales/get-selling-price', { productId: productId })
    .done(function(data) {
        // Assuming data is already JSON and contains a selling_price field
        console.log(data);

        // Set the value of the sell_price field
        $('#sales-sell_price').attr('value',data.selling_price);

    })
    .fail(function() {
        alert('Error occurred while fetching data.');
    });
});
JS;
$this->registerJs($script);
?>

<?php
$this->registerJs("
    $('#submit-button').on('click', function() {
        $(this).prop('disabled', true);
    });
");


?>