<?php

use app\models\ExpenseCategories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */
/** @var yii\widgets\ActiveForm $form */
$model = new ExpenseCategories();

?>

<div class="expense-categories-form">

    <div class="card">
        <div class="card-header">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <?= $form->field($model, 'category_name')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Enter expense category name',
                        ]
                    ) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <?= $form->field($model, 'description')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Enter description',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>