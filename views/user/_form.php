<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">


    <div class="col-12 col-sm-12 col-lg-12">


        <!-- <div class="card"> -->
        <div class="card-header">
            <h4><?= $this->title ?></h4>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="form-group col-6">
                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group col-6">
                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group col-6">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group col-6">
                    <?= $form->field($model, 'status')->dropDownList([
                        '10' => 'Active',
                        '9' => 'Inactive'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <?= $form->field($model, 'gender')->dropDownList([
                        'Male' => 'Male',
                        'Female' => 'Female'
                    ]) ?>
                </div>

            </div>
            <div class="form-group align-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <!-- </div> -->
    </div>

</div>