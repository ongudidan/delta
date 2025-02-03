<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkSale $model */

$this->title = 'Update Bulk Sale: ' . $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Bulk Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reference_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bulk-sale-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsSales' => $modelsSales,
        'paymentMethodList' => $paymentMethodList,

    ]) ?>

</div>
