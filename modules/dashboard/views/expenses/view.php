<?php

use app\models\User;
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
<div class="expenses-view">

    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">


                <div class="row align-items-center">
                    <div class="col-auto text-end float-end ms-auto download-grp">
                        <p>
                            <a href="<?= Url::to(['/dashboard/expenses/update', 'expense_id' => $model->expense_id]) ?>" class="btn btn-sm bg-danger-light">
                                <i class="feather-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/expenses/delete', 'expense_id' => $model->expense_id]) ?>">
                                <i class="feather-trash"></i>
                            </a>
                        </p>
                    </div>
                </div>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'expenseCategory.category_name',
                        'amount',
                        // 'created_at',
                        // 'updated_at',
                        // 'created_by',
                        // 'updated_by',
                        'paymentMethod.name',
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
                    ],
                ]) ?>

            </div>
        </div>
    </div>

</div>