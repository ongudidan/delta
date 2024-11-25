<?php

use app\models\PaymentMethods;
use app\models\Sales;
use app\models\User;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\SalesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-index">



    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- /////////// -->
                    <?= $this->render('components/_sales-header') ?>
                    <!-- ////////////// -->

                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                <div class="card-header">
                                    <h4>
                                        <p>
                                            <?php //Html::button('Sell Product', ['value' => Url::to('/sales/create/'), 'class' => 'btn btn-success', 'id' => 'modalButton']) 
                                            ?>
                                        </p>
                                        <?php
                                        Modal::begin([
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
                                                <input type="text" name="SalesSearch[globalSearch]" class="form-control" placeholder="Search by product name" value="<?= Html::encode($searchModel->globalSearch) ?>">
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
                                                    <th class="text-center">#</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Buying Price</th>
                                                    <th>Selling Price</th>
                                                    <th>Total amount</th>
                                                    <th>Profit</th>
                                                    <th>Payment Method</th>
                                                    <th>Sold By</th>
                                                    <th>Date sold at</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="ui-sortable">
                                                <?php foreach ($dataProvider->getModels() as $index => $sale) {
                                                    $data = PaymentMethods::findOne(['id' => $sale->payment_method_id]);
                                                    $user = User::findOne($sale->created_by);

                                                    // Check if the current user is the creator or has the admin role
                                                    $canManage = Yii::$app->user->id == $sale->created_by || Yii::$app->user->can('Sales Manage');
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                        </td>
                                                        <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            <?= Html::encode($sale->product->product_name) ?>
                                                        </td>
                                                        <td><?= Html::encode($sale->quantity) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asCurrency($sale->calculatedBuyingPrice, 'KES')) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asCurrency($sale->sell_price, 'KES')) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asCurrency($sale->total_amount, 'KES')) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asCurrency($sale->calculatedProfit, 'KES')) ?></td>
                                                        <td><?= Html::encode($data['name']) ?></td>
                                                        <td><?= $user ? Html::encode($user->username) : 'Admin' ?></td>
                                                        <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            <?= Html::encode(Yii::$app->formatter->asDatetime($sale->sale_date)) ?>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['sales/view', 'id' => $sale->id]) ?>"><i class="far fa-heart"></i> View</a>

                                                                    <?php if ($canManage): ?>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['sales/update', 'id' => $sale->id]) ?>"><i class="far fa-file"></i> Update</a>
                                                                        <a class="dropdown-item has-icon delete-btn" href="<?= Url::to(['sales/delete', 'id' => $sale->id]) ?>" data-url="<?= Url::to(['sales/delete', 'id' => $sale->id]) ?>">
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