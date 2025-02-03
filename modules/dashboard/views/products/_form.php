<?php

use app\models\Categories;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['products/update', 'product_id' => $model->product_id]
    : ['products/create']; // Use 'create' action if it's not update
?>

<?php $form = ActiveForm::begin([
    'id' => 'main-form',
    'enableAjaxValidation' => false, // Disable if you're not using AJAX
    'action' => $formAction, // Set action based on create or update
    'method' => 'post',
]); ?>

<div class="row">
    <!-- <div class="panel-heading pb-3">
                        <h4><i class="glyphicon glyphicon-envelope"></i> field-officer Details</h4>
                    </div> -->


    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Categories::find()->all(), 'category_id', 'category_name'),
                'language' => 'en',
                'options' => ['placeholder' => 'Select category ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'product_number')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'selling_price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
        </div>
    </div>




    <div class="col-12">
        <div class="student-submit d-flex justify-content-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'form' => 'main-form']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>