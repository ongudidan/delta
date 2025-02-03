<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkPurchase $model */

$this->title = 'Update Bulk Purchase: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bulk Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bulk-purchase-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsPurchases' => $modelsPurchases,
        'paymentMethodList' => $paymentMethodList,

    ]) ?>

</div>
