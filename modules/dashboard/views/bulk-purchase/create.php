<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BulkPurchase $model */

$this->title = 'Create Bulk Purchase';
$this->params['breadcrumbs'][] = ['label' => 'Bulk Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bulk-purchase-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelsPurchases' => $modelsPurchases,

    ]) ?>

</div>
