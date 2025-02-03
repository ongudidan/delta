<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Categories $model */

$this->title = 'Update Categories: ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_name, 'url' => ['view', 'category_id' => $model->category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>