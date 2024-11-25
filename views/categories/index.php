<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\CategoriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                <div class="card-header">
                                    <h4>
                                  
                                        <p>
                                            <?= Html::button('Create New Product Category', ['value' => Url::to('/categories/create/'), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
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
                                    </h4>
                                    <div class="card-header-action">
                                        <form method="get" action="<?= Url::to(['index']) ?>">
                                            <div class="input-group">
                                                <input type="text" name="CategoriesSearch[category_name]" class="form-control" placeholder="Search by category name" value="<?= Html::encode($searchModel->category_name) ?>">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm" id="sortable-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        #
                                                    </th>
                                                    <th>Category Name</th>
                                                    <th>Description</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="ui-sortable">
                                                <?php foreach ($dataProvider->getModels() as $index => $category) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                        </td>
                                                        <td><?= Html::encode($category->category_name) ?></td>
                                                        <td><?= Html::encode($category->description) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asDatetime($category->created_at)) ?></td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['categories/view', 'category_id' => $category->category_id]) ?>"><i class="far fa-heart"></i> View</a>
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['categories/update', 'category_id' => $category->category_id]) ?>"><i class="far fa-file"></i> Update</a>
                                                                    <a class="dropdown-item has-icon delete-btn" href="<?= Url::to(['categories/delete', 'category_id' => $category->category_id]) ?>" data-url="<?= Url::to(['categories/delete', 'category_id' => $category->category_id]) ?>">
                                                                        <i class="far fa-clock"></i> Delete
                                                                    </a>
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
                            <div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
                                Sed sed metus vel lacus hendrerit tempus. Sed efficitur velit tortor, ac efficitur est
                                lobortis quis. Nullam lacinia metus erat, sed fermentum justo rutrum ultrices. Proin quis
                                iaculis tellus. Etiam ac vehicula eros, pharetra consectetur dui. Aliquam convallis neque
                                eget tellus efficitur, eget maximus massa imperdiet. Morbi a mattis velit. Donec hendrerit
                                venenatis justo, eget scelerisque tellus pharetra a.
                            </div>
                            <div class="tab-pane fade" id="contact4" role="tabpanel" aria-labelledby="contact-tab4">
                                <!-- ////////////START////////////////////// -->
                                <?= $this->render('_form') ?>
                                <!-- ////////////////END////////////////////// -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>