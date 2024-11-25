<?php

use app\models\Categories;
use app\models\Products;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var yii\widgets\ActiveForm $form */


?>

<div class="products-form">
    <!-- <div class="card"> -->
    <div class="card-header">
        <h4><?= Html::encode($this->title) ?></h4>
    </div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'product_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Enter product name',
                    'value' => $model->isNewRecord ? '' : $model->product_name,
                ]) ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'product_number')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Enter product number',
                    'value' => $model->isNewRecord ? '' : $model->product_number,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'description')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Enter description',
                    'value' => $model->isNewRecord ? '' : $model->description,
                ]) ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'category_id')->dropDownList(
                    ArrayHelper::map(Categories::find()->all(), 'category_id', 'category_name'),
                    ['prompt' => 'Select Category']
                ) ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'selling_price')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Enter selling price',
                    'value' => $model->isNewRecord ? '' : $model->selling_price,
                ]) ?>
            </div>
        </div>
        <!-- </div> -->
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$this->registerJs("
    $('#submit-button').on('click', function() {
        $(this).prop('disabled', true);
    });
");
?>