<?php

use app\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Categories $model */
/** @var yii\widgets\ActiveForm $form */
$model = new Categories();

?>

<div class="categories-form">

    <!-- <div class="card col-md-12"> -->
        <div class="card-header">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    <!-- </div> -->

</div>