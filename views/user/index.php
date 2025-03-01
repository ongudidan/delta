<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    $this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
    $this->registerJsFile('@web/js/script.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    ?>
    <div class="users-index">

        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4><?= $this->title ?></h4>
                </div>
                <div class="card-body">
                    <div class="row ">

                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="tab-content no-padding" id="myTab2Content">
                                <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                    <!-- <div class="card"> -->
                                    <div class="card-header">
                                        <h4>
                                            <?php if (Yii::$app->user->can('purchase-create')) { ?>

                                              
                                                <p>
                                                    <?= Html::button('Create New user', ['value' => Url::to('/user/create/'), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
                                                </p>
                                                <?php
                                                Modal::begin([
                                                    // 'header' => '<h4>Companies</h4>',
                                                    'id' => 'mod',
                                                    'size' => 'modal-lg'
                                                ]);

                                                echo "<div id='modalContent'></div>";

                                                Modal::end();
                                                ?>
                                            <?php } ?>
                                        </h4>
                                        <div class="card-header-action">
                                            <form method="get" action="<?= Url::to(['index']) ?>">
                                                <div class="input-group">
                                                    <input type="text" name="UserSearch[username]" class="form-control" placeholder="Search by user name" value="<?= Html::encode($searchModel->username) ?>">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-md" id="sortable-table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            #
                                                        </th>
                                                        <th>Username</th>
                                                        <th>First name</th>
                                                        <th>Last Name</th>
                                                        <th>Phone </th>
                                                        <th>Email</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="ui-sortable">
                                                    <?php foreach ($dataProvider->getModels() as $index => $user) {
                                                        if ($user->username === 'dan') {
                                                            continue;
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                            </td>
                                                            <td><?= $user->username ?></td>
                                                            <td><?= $user->first_name ?></td>
                                                            <td><?= $user->last_name ?></td>
                                                            <td><?= $user->phone ?></td>
                                                            <td><?= $user->email ?></td>
                                                            <td>
                                                                <?php if ($user->status == 9): ?>
                                                                    <div class="badge badge-danger">Inactive</div>
                                                                <?php elseif ($user->status == 10): ?>
                                                                    <div class="badge badge-success">Active</div>
                                                                <?php else: ?>
                                                                    <div class="badge badge-secondary">Unknown</div> <!-- Optional: Handle other statuses -->
                                                                <?php endif; ?>
                                                            </td>

                                                            <td><?= date('d/m/Y', $user->created_at) ?></td>
                                                            <td>
                                                                <div class="dropdown d-inline">
                                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Action
                                                                    </button>
                                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['user/view', 'id' => $user->id]) ?>"><i class="far fa-heart"></i> View</a>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['user/update', 'id' => $user->id]) ?>"><i class="far fa-file"></i> Update</a>

                                                                        <?php if ($user->id != Yii::$app->user->id): ?> <!-- Hide options for logged-in user -->
                                                                            <a class="dropdown-item has-icon toggle-status-btn" href="<?= Url::to(['user/toggle-status', 'id' => $user->id]) ?>">
                                                                                <i class="far fa-clock"></i>
                                                                                <?= $user->status == 10 ? 'Deactivate' : 'Activate' ?>
                                                                            </a>
                                                                            <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['user/delete', 'id' => $user->id]) ?>">
                                                                                <i class="far fa-clock"></i> Delete
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <nav class="d-inline-block">
                                            <?= LinkPager::widget([
                                                'pagination' => $dataProvider->pagination,
                                                'options' => ['class' => 'pagination mb-0'],
                                                'linkOptions' => ['class' => 'page-link'],
                                                'pageCssClass' => 'page-item',
                                                'prevPageCssClass' => 'page-item',
                                                'nextPageCssClass' => 'page-item',
                                                'activePageCssClass' => 'active',
                                            ]) ?>
                                        </nav>
                                    </div>
                                    <!-- </div> -->

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>