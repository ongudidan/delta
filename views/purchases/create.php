<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */

$this->title = 'Create Purchases';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
