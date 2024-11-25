<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */

$this->title = 'Update Expense Categories: ' . $model->expense_category_id;
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->expense_category_id, 'url' => ['view', 'expense_category_id' => $model->expense_category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expense-categories-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
