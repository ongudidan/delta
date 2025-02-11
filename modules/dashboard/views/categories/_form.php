<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Categories $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['categories/update', 'category_id' => $model->category_id]
    : ['categories/create']; // Use 'create' action if it's not update
?>

<?php $form = ActiveForm::begin([
    'id' => 'main-form',
    'enableAjaxValidation' => false, // Disable if you're not using AJAX
    'action' => $formAction, // Set action based on create or update
    'method' => 'post',
]); ?>


<div class="row">

    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="col-12 col-sm-12">
        <div class="form-group local-forms">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group d-flex justify-content-center">
            <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>