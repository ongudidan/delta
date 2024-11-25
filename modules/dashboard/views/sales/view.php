<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sales $model */

$this->title = $model->product->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-view">

    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">

                <div class="row align-items-center">
                    <div class="col-auto text-end float-end ms-auto download-grp">
                        <p>
                            <a href="<?= Url::to(['/dashboard/sales/update', 'id' => $model->id]) ?>" class="btn btn-sm bg-danger-light">
                                <i class="feather-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/sales/delete', 'id' => $model->id]) ?>">
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
                        'sell_price',
                        'total_amount',
                        // 'profit',
                        // 'sale_date',
                        // 'created_by',
                        [
                            'attribute' => 'created_by',
                            'value' => function ($model) {
                                return User::findOne($model->created_by)->username ?? '{not set}';
                            },
                        ],
                        // 'updated_by',
                        [
                            'attribute' => 'updated_by',
                            'value' => function ($model) {
                                return User::findOne($model->updated_by)->username ?? '{not set}';
                            },
                        ],
                        'paymentMethod.name',
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
    </div>

</div>