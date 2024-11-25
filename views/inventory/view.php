<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Inventory $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inventories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">

        <p>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
                'product.product_name',
                'quantity',
                [
                    'attribute' => 'last_updated',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->created_at);
                    },
                ],

            ],
        ]) ?>

    </div>
</div>