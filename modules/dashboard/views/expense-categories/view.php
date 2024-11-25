<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */

$this->title = $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="expense-categories-view">


    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">

                <div class="row align-items-center">
                    <div class="col-auto text-end float-end ms-auto download-grp">
                        <p>
                            <a href="<?= Url::to(['/dashboard/expense-categories/update', 'expense_category_id' => $model->expense_category_id]) ?>" class="btn btn-sm bg-danger-light">
                                <i class="feather-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/expense-categories/delete', 'expense_category_id' => $model->expense_category_id]) ?>">
                                <i class="feather-trash"></i>
                            </a>
                        </p>
                    </div>
                </div>

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

            </div>
        </div>
    </div>

</div>