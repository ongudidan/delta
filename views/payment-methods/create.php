<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PaymentMethods $model */

$this->title = 'Create Payment Methods';
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-methods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
