<?php

use app\models\AuthItemChild;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\AuthItemChildSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Auth Item Children';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-child-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Auth Item Child', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'parent',
            'child',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AuthItemChild $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'parent' => $model->parent, 'child' => $model->child]);
                 }
            ],
        ],
    ]); ?>


<div class="authItemChild-index">

    <?php
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    $this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
    $this->registerJsFile('@web/js/script.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    ?>
    <div class="authItemChilds-index">

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
                                            <?php if (Yii::$app->user->can('auth-item-child-create')) { ?>

                                                <p>
                                                    <?= Html::a('Create New authItemChild', ['create'], ['class' => 'btn btn-success']) ?>
                                                </p>
                                            <?php } ?>
                                        </h4>
                                        <div class="card-header-action">
                                            <form method="get" action="<?= Url::to(['index']) ?>">
                                                <div class="input-group">
                                                    <input type="text" name="AuthItemChildsSearch[parent]" class="form-control" placeholder="Search by authItemChild name" value="<?= Html::encode($searchModel->parent) ?>">
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
                                                        <th>Parent</th>
                                                        <th>Child</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="ui-sortable">
                                                    <?php foreach ($dataProvider->getModels() as $index => $authItemChild) {
                                                        // if($authItemChild->authItemChildname === 'dan'){
                                                        //     continue;
                                                        // }
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                            </td>
                                                            <td><?= $authItemChild->parent ?></td>
                                                            <td><?= $authItemChild->child ?></td>
                                                      
                                                       

                                                            <td>
                                                                <div class="dropdown d-inline">
                                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Action
                                                                    </button>
                                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['authItemChild/view', 'parent' => $authItemChild->parent, 'child' => $authItemChild->child]) ?>"><i class="far fa-heart"></i> View</a>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['authItemChild/update', 'parent' => $authItemChild->parent, 'child' => $authItemChild->child]) ?>"><i class="far fa-file"></i> Update</a>
                                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['authItemChild/delete', 'parent' => $authItemChild->parent, 'child' => $authItemChild->child]) ?>"><i class="far fa-clock"></i> Delete</a>
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

</div>
