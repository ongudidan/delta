<?php

use app\models\PaymentMethods;
use app\models\Products;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Sales $model */
/** @var yii\widgets\ActiveForm $form */

$productId = Yii::$app->request->get('product_id');
$product = Products::findOne(['product_id' => $productId]);

?>

<div class="col-12 col-sm-12 col-lg-12">

    <div class="card">
        <div class="card-header">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <?= $form->field($model, 'product_id')->dropDownList(
                        ArrayHelper::map(Products::find()->where(['product_id' => $productId])->all(), 'product_id', 'product_name'),
                        [
                            'prompt' => 'Select Product',
                            'options' => [$productId => ['Selected' => true]]
                        ]
                    ) ?>
                </div>

                <div class="form-group col-md-6">
                    <?= $form->field($model, 'sell_price')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter selling price',
                        'value' => $product->selling_price,
                    ]) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <?= $form->field($model, 'quantity')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter quantity',
                        'value' => '1',
                    ]) ?>
                </div>
                <div class="form-group col-md-6">
                    <?= $form->field($model, 'payment_method_id')->dropDownList(
                        ArrayHelper::map(PaymentMethods::find()->all(), 'id', 'name'),
                        [
                            'prompt' => 'Select Payment Method',
                            'options' => [
                                1 => ['Selected' => true]  // Set the payment method with ID 1 as the default selected option
                            ],
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
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$this->registerJs("
    $('#submit-button').on('click touchstart', function() {
        $(this).prop('disabled', true);
        $(this).closest('form').submit(); // Submit the form
    });
");
?>