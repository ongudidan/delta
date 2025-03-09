<?php

use yii\helpers\Url;

$items = [
    ['title' => 'First Name', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->first_name : 'Null'],
    ['title' => 'Last Name', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->last_name : 'Null'],
    ['title' => 'Username', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Null'],
    ['title' => 'Phone', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->phone : 'Null'],
]

?>

<div class="col-12 col-md-12 col-lg-3">
    <div class="card author-box">
        <div class="card-body">
            <div class="author-box-center">
                <img alt="image" src="/web/otika/assets/img/user.jpg" class="rounded-circle author-box-picture">
                <div class="clearfix"></div>
                <div class="author-box-name">
                    <a href="#"><?= Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Guest' ?></a>
                </div>
            </div>
            <div class="card-body">
                <div class="py-4">
                    <?php foreach ($items as $item) { ?>
                        <p class="clearfix">
                            <span class="float-left">
                                <?= $item['title'] ?>
                            </span>
                            <span class="float-right text-muted">
                                <?= $item['data'] ?>
                            </span>
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>