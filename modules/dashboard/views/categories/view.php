<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Categories $model */

$this->title = $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'category_name',
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