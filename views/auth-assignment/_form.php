<?php

use app\models\AuthItem;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthAssignment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Checkbox List for Auth Items -->
    <?= $form->field($model, 'item_name')->checkboxList(
        ArrayHelper::map(AuthItem::find()->all(), 'name', 'name'),
        ['separator' => '<br>']
    ) ?>

    <!-- Dropdown List for Users -->
    <?= $form->field($model, 'user_id')->dropDownList(
          ArrayHelper::map(User::find()->all(), 'id', 'username'),
          ['prompt' => 'Select User']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
