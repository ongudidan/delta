<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */

$this->title = 'Create Expenses';
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
