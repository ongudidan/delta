<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkExpense $model */

$this->title = 'Create Bulk Expense';
$this->params['breadcrumbs'][] = ['label' => 'Bulk Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bulk-expense-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsExpenses' => $modelsExpenses,
        'paymentMethodList' => $paymentMethodList,

    ]) ?>

</div>
