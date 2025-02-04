<?php

use yii\helpers\Url;

$items = [
    ['title' => 'First Name', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->first_name : 'Null'],
    ['title' => 'Last Name', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->last_name : 'Null'],
    ['title' => 'Username', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Null'],
    ['title' => 'Gender', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->gender : 'Null'],
    ['title' => 'Email', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->email : 'Null'],
    ['title' => 'Phone Number', 'data' => Yii::$app->user->identity ? Yii::$app->user->identity->phone : 'Null'],
]

?>
<div class="col-12 col-sm-12 col-lg-12">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">

                <?= $this->render('components/_user-sidebar') ?>

                <div class="col-12 col-md-12 col-lg-9">
                    <div class="card">
                        <div class="padding-20">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                     
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab2" href="<?= Url::to('/site/user-settings') ?>" role="tab" aria-selected="false">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab2" href="<?= Url::to('/site/change-password') ?>" role="tab" aria-selected="false">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content tab-bordered" id="myTab3Content">
                                <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>