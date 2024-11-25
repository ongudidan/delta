<?php

use app\models\Inventory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\InventorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Inventories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-index">



    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title?></h4>
            </div>
            <div class="card-body">
                <div class="row">
            
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                    <div class="card-header">
                                        <h4>
                                        </h4>
                                        <div class="card-header-action">
                                            <form method="get" action="<?= Url::to(['index']) ?>">
                                                <div class="input-group">
                                                    <input type="text" name="InventorySearch[globalSearch]" class="form-control" placeholder="Search by product name" value="<?= Html::encode($searchModel->globalSearch) ?>">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="sortable-table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            #
                                                        </th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Created At</th>
                                                        <th>Updated at</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ui-sortable">
                                                    <?php foreach ($dataProvider->getModels() as $index => $inventory) { ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                            </td>
                                                            <td><?= Html::encode($inventory->product->product_name) ?></td>
                                                            <td><?= Html::encode($inventory->quantity) ?></td>
                                                            <td><?= Html::encode(Yii::$app->formatter->asDatetime($inventory->created_at)) ?></td>
                                                            <td><?= Html::encode(Yii::$app->formatter->asDatetime($inventory->updated_at)) ?></td>
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
                                    Vestibulum imperdiet odio sed neque ultricies, ut dapibus mi maximus. Proin ligula massa,
                                    gravida in lacinia efficitur, hendrerit eget mauris. Pellentesque fermentum, sem interdum
                                    molestie finibus, nulla diam varius leo, nec varius lectus elit id dolor. Nam malesuada orci
                                    non ornare vulputate. Ut ut sollicitudin magna. Vestibulum eget ligula ut ipsum venenatis
                                    ultrices. Proin bibendum bibendum augue ut luctus.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>