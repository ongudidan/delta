<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SubCategories $model */

$this->title = 'Update Sub Categories: ' . $model->sub_category_id;
$this->params['breadcrumbs'][] = ['label' => 'Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sub_category_id, 'url' => ['view', 'sub_category_id' => $model->sub_category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
