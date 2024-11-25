<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to('/') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to('/user/index') ?>">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $model->username ?></li>
            </ol>
        </nav>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'id',
                'username',
                'first_name',
                'last_name',
                'phone',
                'gender',
                'verification_token',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'email:email',
                'status',
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>