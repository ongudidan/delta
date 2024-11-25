<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */

$this->title = 'Update Expenses: ' . $model->expense_id;
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->expense_id, 'url' => ['view', 'expense_id' => $model->expense_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenses-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
