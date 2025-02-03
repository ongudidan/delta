<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkSale $model */

$this->title = 'Create Bulk Sale';
$this->params['breadcrumbs'][] = ['label' => 'Bulk Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bulk-sale-create">


    <?= $this->render('_form', [
        'model' => $model,

        'modelsSales' => $modelsSales,
        'paymentMethodList' => $paymentMethodList,

    ]) ?>

</div>
