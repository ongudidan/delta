<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */

$this->title = $model->product->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchases-view">


    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">

                <div class="row align-items-center">
                    <div class="col-auto text-end float-end ms-auto download-grp">
                        <p>
                            <a href="<?= Url::to(['/dashboard/purchases/update', 'id' => $model->id]) ?>" class="btn btn-sm bg-danger-light">
                                <i class="feather-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/purchases/delete', 'id' => $model->id]) ?>">
                                <i class="feather-trash"></i>
                            </a>
                        </p>
                    </div>
                </div>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [

                        'product.product_name',
                        'quantity',
                        'buying_price',
                        'total_cost',
                        'paymentMethod.name',
                        [
                            'attribute' => 'purchase_date',
                            'value' => function ($model) {
                                return Yii::$app->formatter->asDatetime($model->purchase_date);
                            },
                        ],


                    ],
                ]) ?>

            </div>
        </div>
    </div>

</div>