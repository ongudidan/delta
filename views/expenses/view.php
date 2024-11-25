<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */

$this->title = $model->expense_id;
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to('/') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to('/expenses/index') ?>">Expenses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $model->expenseCategory->category_name ?></li>
            </ol>
        </nav>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'expense_id',
                'expenseCategory.category_name',
                'amount',
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