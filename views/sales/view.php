<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sales $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to('/') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to('/sales/index') ?>">Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $model->product->product_name ?></li>
            </ol>
        </nav>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
                'product.product_name',
                'quantity',
                'sell_price',
                'total_amount',
                [
                    'attribute' => 'sale_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->sale_date);
                    },
                ],
            ],
        ]) ?>


    </div>
</div>