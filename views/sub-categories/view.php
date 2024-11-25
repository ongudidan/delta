<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SubCategories $model */

$this->title = $model->sub_category_id;
$this->params['breadcrumbs'][] = ['label' => 'Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">


        <p>
            <?= Html::a('Update', ['update', 'sub_category_id' => $model->sub_category_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'sub_category_id' => $model->sub_category_id], [
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
                // 'sub_category_id',
                'category.category_name',
                'sub_category_name',
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