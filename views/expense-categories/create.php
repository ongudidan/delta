<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */

$this->title = 'Create Expense Categories';
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-categories-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
