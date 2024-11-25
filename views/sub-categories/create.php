<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SubCategories $model */

$this->title = 'Create Sub Categories';
$this->params['breadcrumbs'][] = ['label' => 'Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
