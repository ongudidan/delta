<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to('/') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to('/products/index') ?>">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $model->product_name ?></li>
            </ol>
        </nav>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'product_id',
                'category.category_name',
                // 'sub_category_id',
                'product_name',
                // 'buying_price',
                'selling_price',
                'product_number',
                'description',
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->created_at);
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->updated_at);
                    },
                ],
            ],
        ]) ?>


    </div>
</div>