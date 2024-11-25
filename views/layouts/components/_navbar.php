<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                    <i data-feather="align-justify"></i>
                </a>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <div class="d-flex align-items-center">
            <!-- <p class="pe-2 mb-0">
                <?= Html::a('Download Database Backup', ['site/export'], [
                    'class' => 'btn btn-primary',
                    'data-method' => 'post',
                ]); ?>
            </p> -->

            <p class="mb-0">
                <?= Html::a('SELL', Url::to(['/products/index']), ['class' => 'btn btn-sm btn-danger']) ?>
            </p>
        </div>

        <div class="dropdown d-inline">
            <button class="btn .btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Hello <?= Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Guest' ?>
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                </a> <a href="<?= Url::to('/site/user-profile') ?>" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                    Profile
                </a>
                <a href="<?= Url::to('/site/logout') ?>" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </ul>
</nav>