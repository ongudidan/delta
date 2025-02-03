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

<div class="card-body">

    <div class="row align-items-center">
        <div class="col-auto text-end float-end ms-auto download-grp">
            <p>
                <a href="<?= Url::to(['/dashboard/products/update', 'product_id' => $model->product_id]) ?>" class="btn btn-sm bg-danger-light">
                    <i class="feather-edit"></i>
                </a>
                <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/products/delete', 'product_id' => $model->product_id]) ?>">
                    <i class="feather-trash"></i>
                </a>
            </p>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'category.category_name',
            'product_name',
            'selling_price',
            'product_number',
            'description',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
            ],
            // 'updated_at',
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                },
            ],

        ],
    ]) ?>

</div>