<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkExpense $model */

$this->title = 'Update Bulk Expense: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bulk Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bulk-expense-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsExpenses' => $modelsExpenses,

    ]) ?>

</div>
