<?php

use app\models\Purchases;
use app\models\Sales;
use kartik\daterange\DateRangePicker;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

// $context = $this->context;
// $labels = $context->menus();
$meniItems = [
    ['id' => 'home-tab4',]
];

// Get today's date to set as default range
$today = date('d-M-y');
$defaultRange = "$today to $today";
?>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
$this->registerJsFile('@web/js/script.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<div class="products-index">

    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>

            </div>
            <div class="card-body">
                <div class="row ">

                    <!-- ////////////////// -->
                    <?= $this->render('@app/views/sales/components/_sales-header') ?>
                    <!-- ///////////////////////// -->


                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                <div class="card-header">
                                    <h4>
                                        <?php if (Yii::$app->user->can('purchase-create')) { ?>


                                            <p>
                                                <?= Html::button('Create New Product', ['value' => Url::to('/products/create/'), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
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
                                                <input type="text" name="ProductsSearch[product_name]" class="form-control" placeholder="Search by product name" value="<?= Html::encode($searchModel->product_name) ?>">
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
                                                    <th>Product Name</th>
                                                    <!-- <th>Product Number</th> -->
                                                    <th>Availabe</th>
                                                    <th>Category</th>
                                                    <th>Selling price</th>
                                                    <th>Created At</th>
                                                    <th>Sell</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody class="ui-sortable">
                                                <?php foreach ($dataProvider->getModels() as $index => $product) {
                                                    // Calculate the total quantity for the current product
                                                    // $totalQuantity = Inventory::find()->where(['product_id' => $product->product_id])->sum('quantity');
                                                    $totalSalesQuantity = Sales::find()->where(['product_id' => $product->product_id])->sum('quantity');
                                                    $totalPurchasesQuantity = Purchases::find()->where(['product_id' => $product->product_id])->sum('quantity');
                                                    $totalQuantity = $totalPurchasesQuantity - $totalSalesQuantity;

                                                    // Check if the current user is the creator or has the admin role
                                                    $canManage = Yii::$app->user->can('Sales Manage');
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                        </td>
                                                        <td><?= $product->product_name ?></td>
                                                        <td><?= $totalQuantity !== null ? $totalQuantity : 0 ?></td>
                                                        <td><?= $product->category->category_name ?></td>
                                                        <td>
                                                            <?= $product->selling_price !== null
                                                                ? Yii::$app->formatter->asCurrency($product->selling_price, 'KES')
                                                                : '<span class="not-set">(not set)</span>'
                                                            ?>
                                                        </td>
                                                        <td><?= date('d/m/Y', $product->created_at) ?></td>
                                                        <td>
                                                            <?php if ($totalQuantity > 0): ?>
                                                                <?= Html::a('SELL', Url::to(['products/create-sale', 'product_id' => $product->product_id]), [
                                                                    'class' => 'btn btn-sm btn-danger sell-btn',
                                                                    'data-quantity' => $totalQuantity
                                                                ]) ?>
                                                            <?php else: ?>
                                                                <span class="btn btn-sm btn-secondary">SOLD</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu" style="position: fixed; z-index: 1050;">
                                                                    <?php if (Yii::$app->user->can('purchase-create')) { ?>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['purchases/create', 'product_id' => $product->product_id]) ?>"><i class="fas fa-cart-arrow-down"></i> Add Stock</a>
                                                                    <?php } ?>
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['products/view', 'product_id' => $product->product_id]) ?>"><i class="far fa-heart"></i> View</a>

                                                                    <?php if ($canManage): ?>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['products/update', 'product_id' => $product->product_id]) ?>"><i class="far fa-file"></i> Update</a>
                                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['products/delete', 'product_id' => $product->product_id]) ?>"><i class="far fa-clock"></i> Delete</a>
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