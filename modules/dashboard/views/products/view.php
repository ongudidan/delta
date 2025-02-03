<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = $model->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<h1><?= Html::encode($this->title) ?></h1>

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